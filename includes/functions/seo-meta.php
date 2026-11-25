<?php
/**
 * Social & SEO Meta Tags (Open Graph & Twitter Card)
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Resolve current page canonical URL for social meta tags
 *
 * @return string
 */
function subo4_get_current_canonical_url() {
    if ( is_front_page() || is_home() ) {
        return home_url( '/' );
    }
    if ( is_singular() ) {
        return (string) get_permalink();
    }
    if ( is_search() ) {
        return get_search_link();
    }

    global $wp;
    if ( isset( $wp->request ) && '' !== $wp->request ) {
        return user_trailingslashit( home_url( $wp->request ) );
    }

    return home_url( '/' );
}

/**
 * Output Open Graph and Twitter Card tags in wp_head
 */
function subo4_output_social_meta() {
    if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) ) {
        return;
    }

    $site_name = get_bloginfo( 'name', 'display' );
    $is_single = is_singular();
    $title     = ( is_front_page() || is_home() ) ? $site_name : wp_get_document_title();
    $type      = ( is_singular( 'post' ) || is_singular( 'subo4_project' ) ) ? 'article' : 'website';
    $url       = subo4_get_current_canonical_url();

    $description = '';
    $image       = '';
    $has_feature = false;

    if ( $is_single ) {
        $post_id = get_the_ID();
        $post    = get_post( $post_id );

        if ( is_singular( 'subo4_project' ) && function_exists( 'subo4_get_project_data' ) ) {
            $project = subo4_get_project_data( $post_id );
            if ( ! empty( $project['summary'] ) ) {
                $description = $project['summary'];
            }
            if ( ! empty( $project['screenshot_url'] ) ) {
                $image       = $project['screenshot_url'];
                $has_feature = true;
            }
        }

        if ( '' === $description && $post ) {
            if ( has_excerpt( $post_id ) ) {
                $description = $post->post_excerpt;
            } elseif ( ! empty( $post->post_content ) ) {
                $description = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 80, '...' );
            }
        }

        if ( '' === $image ) {
            $thumb_url = get_the_post_thumbnail_url( $post_id, 'large' );
            if ( $thumb_url ) {
                $image       = $thumb_url;
                $has_feature = true;
            }
        }
    } elseif ( is_archive() ) {
        $description = get_the_archive_description();
    }

    if ( '' === trim( (string) $description ) ) {
        $description = get_bloginfo( 'description', 'display' );
    }
    $description = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $description ) ) );

    if ( '' === $image && function_exists( 'subo4_get_avatar' ) ) {
        $image = (string) subo4_get_avatar();
    }

    $twitter_card = ( $is_single && $has_feature ) ? 'summary_large_image' : 'summary';

    if ( '' !== $site_name ) {
        echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
    }
    echo '<meta property="og:type" content="' . esc_attr( $type ) . '">' . "\n";
    if ( '' !== $title ) {
        echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    }
    if ( '' !== $description ) {
        echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    }
    if ( '' !== $url ) {
        echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
    }
    if ( '' !== $image ) {
        echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ) . '">' . "\n";

    // Avoid duplicate meta description when WPJAM Basic is active.
    if ( ! defined( 'WPJAM_BASIC_PLUGIN_FILE' ) && ! class_exists( 'WPJAM_Basic' ) && '' !== $description ) {
        echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'subo4_output_social_meta', 5 );
