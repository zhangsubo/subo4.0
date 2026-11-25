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
 * Fixes XSS vulnerabilities, scopes to single posts, and preserves linked media
 *
 * @param string $content Post content.
 * @return string Modified content with footnotes.
 */
function subo4_convert_links_to_footnotes( $content ) {
    if (
        ( ! defined( 'SUBO4_ENABLE_FOOTNOTES' ) || ! SUBO4_ENABLE_FOOTNOTES )
        || empty( $content )
        || is_admin()
        || is_feed()
        || ( defined( 'REST_REQUEST' ) && REST_REQUEST )
        || ! is_singular( 'post' )
        || ! in_the_loop()
        || ! is_main_query()
    ) {
        return $content;
    }

    $post_id       = get_the_ID();
    $cache_enabled = defined( 'SUBO4_FOOTNOTES_CACHE' ) ? SUBO4_FOOTNOTES_CACHE : true;
    $content_hash  = md5( $content );
    $cache_key     = $post_id ? 'subo4_fn_' . (int) $post_id : '';

    if ( $post_id && $cache_enabled ) {
        $cached = get_transient( $cache_key );
        if ( is_array( $cached ) && isset( $cached['hash'], $cached['html'] ) && hash_equals( $cached['hash'], $content_hash ) ) {
            return $cached['html'];
        }
    }

    $dom = new DOMDocument( '1.0', 'UTF-8' );
    libxml_use_internal_errors( true );

    // Wrap content in a single root element so multi-node HTML fragments do not break structure.
    $wrapped_html = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body><div id="subo4-fn-root">' . $content . '</div></body></html>';
    $loaded       = $dom->loadHTML( $wrapped_html );

    libxml_clear_errors();

    if ( ! $loaded ) {
        return $content;
    }

    $root = $dom->getElementById( 'subo4-fn-root' );
    if ( ! $root ) {
        return $content;
    }

    $xpath = new DOMXPath( $dom );
    $links = $xpath->query( './/a[@href]', $root );

    if ( false === $links || 0 === $links->length ) {
        return $content;
    }

    $footnotes = array();
    $index     = 1;

    foreach ( iterator_to_array( $links ) as $link ) {
        if ( ! $link instanceof DOMElement || ! $link->parentNode ) {
            continue;
        }

        $href      = trim( $link->getAttribute( 'href' ) );
        $link_text = $link->textContent;

        // Skip empty links, anchors, and non-web action schemes.
        if (
            '' === $href
            || 0 === strpos( $href, '#' )
            || preg_match( '/^(mailto:|tel:|javascript:)/i', $href )
        ) {
            continue;
        }

        // Skip existing footnote links or links inside a footnotes block.
        $class_attr = ' ' . trim( $link->getAttribute( 'class' ) ) . ' ';
        if (
            false !== strpos( $class_attr, ' reversefootnote ' )
            || false !== strpos( $class_attr, ' footnote-ref ' )
            || $xpath->query( 'ancestor::*[contains(concat(" ", normalize-space(@class), " "), " footnotes-section ")]', $link )->length > 0
        ) {
            continue;
        }

        // Preserve empty-text links or links wrapping images/media.
        if (
            '' === trim( $link_text )
            || $xpath->query( './/img | .//picture | .//svg | .//iframe | .//video', $link )->length > 0
        ) {
            continue;
        }

        $sup = $dom->createElement( 'sup' );
        $sup->setAttribute( 'class', 'footnote-ref' );
        $sup_link = $dom->createElement( 'a', (string) $index );
        $sup_link->setAttribute( 'href', '#fn-' . $index );
        $sup_link->setAttribute( 'id', 'fnref-' . $index );
        $sup->appendChild( $sup_link );

        $text_node = $dom->createTextNode( $link_text );
        $link->parentNode->insertBefore( $text_node, $link );
        $link->parentNode->insertBefore( $sup, $link );
        $link->parentNode->removeChild( $link );

        $footnotes[] = array(
            'index' => $index,
            'text'  => $link_text,
            'url'   => $href,
        );

        $index++;
    }

    if ( empty( $footnotes ) ) {
        return $content;
    }

    $modified_content = '';
    foreach ( $root->childNodes as $child ) {
        $modified_content .= $dom->saveHTML( $child );
    }

    $modified_content .= subo4_build_footnotes_html( $footnotes );

    if ( $post_id && $cache_enabled ) {
        set_transient(
            $cache_key,
            array(
                'hash' => $content_hash,
                'html' => $modified_content,
            ),
            WEEK_IN_SECONDS
        );
    }

    return $modified_content;
}

/**
 * Build footnotes HTML section
 *
 * @param array $footnotes Array of footnote data.
 * @return string HTML for footnotes section.
 */
function subo4_build_footnotes_html( $footnotes ) {
    if ( empty( $footnotes ) ) {
        return '';
    }

    $html  = '<div class="footnotes-section">';
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
 * Clear footnotes cache for a single post when it is updated
 *
 * @param int $post_id Post ID.
 */
function subo4_clear_footnotes_cache( $post_id ) {
    $cache_enabled = defined( 'SUBO4_FOOTNOTES_CACHE' ) ? SUBO4_FOOTNOTES_CACHE : true;
    if ( ! $cache_enabled || wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
        return;
    }

    delete_transient( 'subo4_fn_' . (int) $post_id );
}
add_action( 'save_post_post', 'subo4_clear_footnotes_cache' );

// Apply footnotes conversion to content
add_filter( 'the_content', 'subo4_convert_links_to_footnotes', 20 );
