<?php
/**
 * Footnotes Functionality
 * Converts inline links to academic-style footnotes
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Convert content links to footnotes using DOMDocument
 * Fixes XSS vulnerabilities and improves link parsing
 *
 * @param string $content Post content
 * @return string Modified content with footnotes
 */
function subo4_convert_links_to_footnotes( $content ) {
    // Check if footnotes are enabled (with fallback)
    if ( ( ! defined( 'SUBO4_ENABLE_FOOTNOTES' ) || ! SUBO4_ENABLE_FOOTNOTES ) || empty( $content ) ) {
        return $content;
    }

    // Check cache
    $post_id = get_the_ID();
    $cache_enabled = defined( 'SUBO4_FOOTNOTES_CACHE' ) ? SUBO4_FOOTNOTES_CACHE : true;
    if ( $post_id && $cache_enabled ) {
        $cache_key = 'subo4_footnotes_' . md5( $content );
        $cached = get_transient( $cache_key );
        if ( false !== $cached ) {
            return $cached;
        }
    }

    // Use DOMDocument for proper HTML parsing
    $dom = new DOMDocument( '1.0', 'UTF-8' );

    // Suppress warnings for malformed HTML
    libxml_use_internal_errors( true );

    // Add UTF-8 meta tag to handle encoding properly
    $dom->loadHTML( '<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

    // Clear errors
    libxml_clear_errors();

    $xpath = new DOMXPath( $dom );
    $links = $xpath->query( '//a[@href]' );

    if ( $links->length === 0 ) {
        return $content;
    }

    $footnotes = array();
    $index = 1;

    foreach ( $links as $link ) {
        $href = $link->getAttribute( 'href' );
        $link_text = $link->textContent;

        // Skip empty links or anchors
        if ( empty( $href ) || strpos( $href, '#' ) === 0 ) {
            continue;
        }

        // Create footnote reference
        $sup = $dom->createElement( 'sup' );
        $sup->setAttribute( 'class', 'footnote-ref' );
        $sup_link = $dom->createElement( 'a', $index );
        $sup_link->setAttribute( 'href', '#fn-' . $index );
        $sup_link->setAttribute( 'id', 'fnref-' . $index );
        $sup->appendChild( $sup_link );

        // Replace link with text + superscript
        $text_node = $dom->createTextNode( $link_text );
        $link->parentNode->insertBefore( $text_node, $link );
        $link->parentNode->insertBefore( $sup, $link );
        $link->parentNode->removeChild( $link );

        // Store footnote data
        $footnotes[] = array(
            'index'     => $index,
            'text'      => $link_text,
            'url'       => $href,
        );

        $index++;
    }

    // Get modified content
    $modified_content = $dom->saveHTML();

    // Remove XML declaration and wrapper
    $modified_content = preg_replace( '/^<\?xml[^>]+>/', '', $modified_content );

    // Add footnotes section
    if ( ! empty( $footnotes ) ) {
        $modified_content .= subo4_build_footnotes_html( $footnotes );
    }

    // Cache the result
    if ( $post_id && $cache_enabled ) {
        set_transient( $cache_key, $modified_content, WEEK_IN_SECONDS );
    }

    return $modified_content;
}

/**
 * Build footnotes HTML section
 *
 * @param array $footnotes Array of footnote data
 * @return string HTML for footnotes section
 */
function subo4_build_footnotes_html( $footnotes ) {
    if ( empty( $footnotes ) ) {
        return '';
    }

    $html = '<div class="footnotes-section">';
    $html .= '<h2>' . esc_html__( 'References', 'subo4-classic-theme' ) . '</h2>';
    $html .= '<ol class="footnotes-list">';

    foreach ( $footnotes as $note ) {
        $html .= '<li id="fn-' . absint( $note['index'] ) . '">';
        $html .= esc_html( $note['text'] ) . ': ';
        $html .= '<a href="' . esc_url( $note['url'] ) . '" rel="nofollow noopener" target="_blank">';
        $html .= esc_html( $note['url'] );
        $html .= '</a>';
        $html .= ' <sup><a href="#fnref-' . absint( $note['index'] ) . '" class="reversefootnote" aria-label="' . esc_attr__( 'Return to content', 'subo4-classic-theme' ) . '">&#8617;</a></sup>';
        $html .= '</li>';
    }

    $html .= '</ol>';
    $html .= '</div>';

    return $html;
}

/**
 * Clear footnotes cache when post is updated
 *
 * @param int $post_id Post ID
 */
function subo4_clear_footnotes_cache( $post_id ) {
    $cache_enabled = defined( 'SUBO4_FOOTNOTES_CACHE' ) ? SUBO4_FOOTNOTES_CACHE : true;
    if ( ! $cache_enabled ) {
        return;
    }

    // Clear all footnote transients for this post
    global $wpdb;
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            $wpdb->esc_like( '_transient_subo4_footnotes_' ) . '%'
        )
    );
}
add_action( 'save_post', 'subo4_clear_footnotes_cache' );

// Apply footnotes conversion to content
add_filter( 'the_content', 'subo4_convert_links_to_footnotes', 20 );
