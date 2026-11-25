<?php
/**
 * Theme Setup and Configuration
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
 * Theme setup
 */
function subo4_theme_setup() {
    // Load text domain for translations
    load_theme_textdomain( 'subo4-classic-theme', SUBO4_THEME_DIR . '/languages' );

    // Add theme support
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );

    // Featured image sizes
    set_post_thumbnail_size( 1200, 9999 );
    add_image_size( 'subo4-medium', 800, 600, false );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'subo4-classic-theme' ),
        'footer'  => esc_html__( 'Footer Menu', 'subo4-classic-theme' ),
    ) );

    // Add editor style
    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'subo4_theme_setup' );

/**
 * Enqueue frontend styles and scripts
 */
function subo4_enqueue_scripts() {
    // Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-5',
        SUBO4_BOOTSTRAP_CSS_CDN,
        array(),
        null
    );

    // Theme styles
    wp_enqueue_style(
        'subo4-theme-style',
        SUBO4_THEME_URI . '/assets/css/theme.css',
        array( 'bootstrap-5' ),
        subo4_get_asset_version( 'assets/css/theme.css' )
    );

    // Bootstrap JS
    wp_enqueue_script(
        'bootstrap-5-js',
        SUBO4_BOOTSTRAP_JS_CDN,
        array(),
        null,
        true
    );

    // Theme JS
    wp_enqueue_script(
        'subo4-theme-js',
        SUBO4_THEME_URI . '/assets/js/theme.js',
        array( 'bootstrap-5-js' ),
        subo4_get_asset_version( 'assets/js/theme.js' ),
        true
    );

    // Localize script for AJAX and translations
    wp_localize_script( 'subo4-theme-js', 'subo4_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'subo4_nonce' ),
    ) );

    // Vibe Coding 作品展示页与作品详情页专用样式及脚本
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

    // 文章/页面/作品详情页按需加载阅读增强（TOC、代码块高亮与复制、图片灯箱）
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
 * Enqueue admin styles
 */
function subo4_admin_scripts() {
    wp_enqueue_style(
        'subo4-admin-style',
        SUBO4_THEME_URI . '/assets/css/admin.css',
        array(),
        subo4_get_asset_version( 'assets/css/admin.css' )
    );
}
add_action( 'admin_enqueue_scripts', 'subo4_admin_scripts' );

/**
 * Add Bootstrap classes to navigation menu
 */
function subo4_nav_menu_css_class( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
        $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter( 'nav_menu_css_class', 'subo4_nav_menu_css_class', 10, 4 );

function subo4_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
        $atts['class'] = 'nav-link';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'subo4_nav_menu_link_attributes', 10, 4 );

/**
 * Customize excerpt length
 */
function subo4_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'subo4_excerpt_length', 999 );

/**
 * Customize excerpt more string
 */
function subo4_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'subo4_excerpt_more' );

/**
 * Add preconnect resource hint for jsDelivr CDN
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type Relation type the URLs are printed for.
 * @return array
 */
function subo4_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
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
        'bootstrap-5'      => defined( 'SUBO4_BOOTSTRAP_CSS_SRI' ) ? SUBO4_BOOTSTRAP_CSS_SRI : '',
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
        'bootstrap-5-js' => defined( 'SUBO4_BOOTSTRAP_JS_SRI' ) ? SUBO4_BOOTSTRAP_JS_SRI : '',
        'subo4-hljs-js'  => defined( 'SUBO4_HLJS_JS_SRI' ) ? SUBO4_HLJS_JS_SRI : '',
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
