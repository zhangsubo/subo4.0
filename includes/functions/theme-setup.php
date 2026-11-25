<?php
/**
 * Theme Setup, Asset Loading, Head Cleanup, and Shared Helpers
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get asset version based on filemtime with theme version fallback
 *
 * @param string $relative_path Path relative to theme root.
 * @return string
 */
function subo4_get_asset_version( $relative_path ) {
    $file_path = SUBO4_THEME_DIR . '/' . ltrim( $relative_path, '/' );
    return file_exists( $file_path ) ? (string) filemtime( $file_path ) : SUBO4_THEME_VERSION;
}

/**
 * Resolve image field value to URL (supports URL string, attachment ID, or legacy array)
 *
 * @param mixed  $value Field value.
 * @param string $size  Image size when value is attachment ID.
 * @return string
 */
function subo4_resolve_image_url( $value, $size = 'full' ) {
    if ( empty( $value ) ) {
        return '';
    }
    if ( is_numeric( $value ) ) {
        $url = wp_get_attachment_image_url( (int) $value, $size );
        return $url ? $url : '';
    }
    if ( is_string( $value ) ) {
        return trim( $value );
    }
    if ( is_array( $value ) && ! empty( $value['url'] ) ) {
        return trim( (string) $value['url'] );
    }
    return '';
}

/**
 * Render unified pagination markup for main queries and custom WP_Query loops
 *
 * @param WP_Query|null $custom_query Optional custom query instance.
 * @param int           $paged        Current page number for custom queries.
 */
function subo4_render_pagination( $custom_query = null, $paged = 1 ) {
    if ( $custom_query instanceof WP_Query ) {
        $links = paginate_links( array(
            'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, (int) $paged ),
            'total'     => (int) $custom_query->max_num_pages,
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
            'mid_size'  => 2,
            'end_size'  => 1,
        ) );
        if ( $links ) {
            echo '<nav class="navigation pagination" aria-label="' . esc_attr__( 'Posts navigation', 'subo4-classic-theme' ) . '">';
            echo '<div class="nav-links">' . $links . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo '</nav>';
        }
        return;
    }

    the_posts_pagination( array(
        'prev_text'          => '&larr;',
        'next_text'          => '&rarr;',
        'before_page_number' => '',
        'after_page_number'  => '',
        'mid_size'           => 2,
        'end_size'           => 1,
        'screen_reader_text' => esc_html__( 'Posts navigation', 'subo4-classic-theme' ),
    ) );
}

/**
 * Theme setup
 */
function subo4_theme_setup() {
    load_theme_textdomain( 'subo4-classic-theme', SUBO4_THEME_DIR . '/languages' );

    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );

    set_post_thumbnail_size( 1200, 9999 );

    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'subo4-classic-theme' ),
    ) );

    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'subo4_theme_setup' );

/**
 * Remove unnecessary wp_head discovery links and disable frontend admin bar
 */
function subo4_cleanup_head() {
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
    remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}
add_action( 'after_setup_theme', 'subo4_cleanup_head' );

add_filter( 'show_admin_bar', '__return_false' );

/**
 * Disable WordPress emojis
 */
function subo4_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'subo4_disable_emojis_tinymce' );
}
add_action( 'init', 'subo4_disable_emojis' );

/**
 * Remove TinyMCE emoji plugin
 *
 * @param array $plugins TinyMCE plugins.
 * @return array
 */
function subo4_disable_emojis_tinymce( $plugins ) {
    return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
}

/**
 * Enqueue frontend styles and scripts
 */
function subo4_enqueue_scripts() {
    wp_enqueue_style(
        'subo4-theme-style',
        SUBO4_THEME_URI . '/assets/css/theme.css',
        array(),
        subo4_get_asset_version( 'assets/css/theme.css' )
    );

    wp_enqueue_script(
        'subo4-theme-js',
        SUBO4_THEME_URI . '/assets/js/theme.js',
        array(),
        subo4_get_asset_version( 'assets/js/theme.js' ),
        true
    );

    if ( is_page_template( 'page-vibe-showcase.php' ) || is_singular( 'subo4_project' ) ) {
        wp_enqueue_style(
            'subo4-showcase-style',
            SUBO4_THEME_URI . '/assets/css/showcase.css',
            array( 'subo4-theme-style' ),
            subo4_get_asset_version( 'assets/css/showcase.css' )
        );
    }

    if ( is_page_template( 'page-vibe-showcase.php' ) ) {
        wp_enqueue_script(
            'subo4-showcase-js',
            SUBO4_THEME_URI . '/assets/js/showcase.js',
            array(),
            subo4_get_asset_version( 'assets/js/showcase.js' ),
            true
        );
    }

    if ( is_singular( array( 'post', 'page', 'subo4_project' ) ) ) {
        $queried  = get_queried_object();
        $has_code = ( $queried instanceof WP_Post ) && ( false !== stripos( $queried->post_content, '<pre' ) );

        $reading_style_deps = array( 'subo4-theme-style' );
        $reading_js_deps    = array();

        if ( $has_code ) {
            wp_enqueue_style(
                'subo4-hljs-style',
                SUBO4_HLJS_CSS_CDN,
                array(),
                null
            );
            wp_enqueue_script(
                'subo4-hljs-js',
                SUBO4_HLJS_JS_CDN,
                array(),
                null,
                true
            );
            $reading_style_deps[] = 'subo4-hljs-style';
            $reading_js_deps[]    = 'subo4-hljs-js';
        }

        wp_enqueue_style(
            'subo4-reading-style',
            SUBO4_THEME_URI . '/assets/css/reading-enhancements.css',
            $reading_style_deps,
            subo4_get_asset_version( 'assets/css/reading-enhancements.css' )
        );

        wp_enqueue_script(
            'subo4-reading-js',
            SUBO4_THEME_URI . '/assets/js/reading-enhancements.js',
            $reading_js_deps,
            subo4_get_asset_version( 'assets/js/reading-enhancements.js' ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'subo4_enqueue_scripts' );

/**
 * Enqueue admin styles and media uploader scripts on demand
 *
 * @param string $hook Current admin page hook suffix.
 */
function subo4_admin_scripts( $hook ) {
    $is_settings_page = ( 'toplevel_page_subo4-theme-settings' === $hook );
    $is_target_editor = false;

    if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        $screen = get_current_screen();
        if ( $screen && in_array( $screen->post_type, array( 'subo4_project', 'page' ), true ) ) {
            $is_target_editor = true;
        }
    }

    if ( ! $is_settings_page && ! $is_target_editor ) {
        return;
    }

    wp_enqueue_media();

    wp_enqueue_style(
        'subo4-admin-style',
        SUBO4_THEME_URI . '/assets/css/admin.css',
        array(),
        subo4_get_asset_version( 'assets/css/admin.css' )
    );

    wp_enqueue_script(
        'subo4-admin-js',
        SUBO4_THEME_URI . '/assets/js/admin.js',
        array(),
        subo4_get_asset_version( 'assets/js/admin.js' ),
        true
    );
}
add_action( 'admin_enqueue_scripts', 'subo4_admin_scripts' );

/**
 * Add nav-item class to primary navigation items
 */
function subo4_nav_menu_css_class( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'subo4_nav_menu_css_class', 10, 4 );

/**
 * Add nav-link class to primary navigation links
 */
function subo4_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
        $atts['class'] = 'nav-link';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'subo4_nav_menu_link_attributes', 10, 4 );

/**
 * Customize excerpt length and suffix
 */
function subo4_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'subo4_excerpt_length', 999 );

function subo4_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'subo4_excerpt_more' );

/**
 * Add preconnect resource hint for jsDelivr CDN only when Highlight.js is enqueued
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type Relation type the URLs are printed for.
 * @return array
 */
function subo4_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type && wp_style_is( 'subo4-hljs-style', 'enqueued' ) ) {
        $urls[] = array(
            'href'        => 'https://cdn.jsdelivr.net',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'subo4_resource_hints', 10, 2 );

/**
 * Add Subresource Integrity attributes to CDN stylesheet tags
 *
 * @param string $html   The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @return string
 */
function subo4_add_style_sri( $html, $handle ) {
    $sri_map = array(
        'subo4-hljs-style' => defined( 'SUBO4_HLJS_CSS_SRI' ) ? SUBO4_HLJS_CSS_SRI : '',
    );

    if ( ! empty( $sri_map[ $handle ] ) && false === strpos( $html, 'integrity=' ) ) {
        $sri  = ' integrity="' . esc_attr( $sri_map[ $handle ] ) . '" crossorigin="anonymous"';
        $html = preg_replace( '/\s*\/?>\s*$/', $sri . ' />' . "\n", $html, 1 );
    }
    return $html;
}
add_filter( 'style_loader_tag', 'subo4_add_style_sri', 10, 2 );

/**
 * Add Subresource Integrity attributes to CDN script tags
 *
 * @param string $tag    The script tag for the enqueued script.
 * @param string $handle The script's registered handle.
 * @return string
 */
function subo4_add_script_sri( $tag, $handle ) {
    $sri_map = array(
        'subo4-hljs-js' => defined( 'SUBO4_HLJS_JS_SRI' ) ? SUBO4_HLJS_JS_SRI : '',
    );

    if ( ! empty( $sri_map[ $handle ] ) && false === strpos( $tag, 'integrity=' ) ) {
        $sri = ' integrity="' . esc_attr( $sri_map[ $handle ] ) . '" crossorigin="anonymous" ';
        $tag = str_replace( '<script ', '<script' . $sri, $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'subo4_add_script_sri', 10, 2 );

/**
 * Estimate reading time in minutes for Chinese + English content
 *
 * @param int $post_id Optional post ID.
 * @return int Estimated minutes (minimum 1).
 */
function subo4_get_reading_time( $post_id = 0 ) {
    $post = get_post( $post_id ? $post_id : get_the_ID() );
    if ( ! $post ) {
        return 1;
    }

    $text        = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
    $cjk_count   = preg_match_all( '/[\x{4e00}-\x{9fff}]/u', $text );
    $non_cjk     = trim( (string) preg_replace( '/[\x{4e00}-\x{9fff}]+/u', ' ', $text ) );
    $latin_words = '' !== $non_cjk ? str_word_count( $non_cjk ) : 0;
    $total_units = (int) $cjk_count + (int) $latin_words;

    return max( 1, (int) ceil( $total_units / 350 ) );
}
