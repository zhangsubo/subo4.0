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
    add_editor_style( 'assets/css/editor-style-min.css' );
}
add_action( 'after_setup_theme', 'subo4_theme_setup' );

/**
 * Enqueue frontend styles and scripts
 */
function subo4_enqueue_scripts() {
    // Bootstrap CSS (with fallback)
    wp_enqueue_style(
        'bootstrap-5',
        SUBO4_BOOTSTRAP_CSS_CDN,
        array(),
        SUBO4_BOOTSTRAP_VERSION
    );

    // Theme styles
    wp_enqueue_style(
        'subo4-theme-style',
        SUBO4_THEME_URI . '/assets/css/theme-min.css',
        array( 'bootstrap-5' ),
        SUBO4_THEME_VERSION
    );

    // Bootstrap JS
    wp_enqueue_script(
        'bootstrap-5-js',
        SUBO4_BOOTSTRAP_JS_CDN,
        array(),
        SUBO4_BOOTSTRAP_VERSION,
        true
    );

    // Theme JS
    wp_enqueue_script(
        'subo4-theme-js',
        SUBO4_THEME_URI . '/assets/js/theme-min.js',
        array( 'bootstrap-5-js' ),
        SUBO4_THEME_VERSION,
        true
    );

    // Localize script for AJAX and translations
    wp_localize_script( 'subo4-theme-js', 'subo4_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'subo4_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'subo4_enqueue_scripts' );

/**
 * Enqueue admin styles
 */
function subo4_admin_scripts() {
    wp_enqueue_style(
        'subo4-admin-style',
        SUBO4_THEME_URI . '/assets/css/admin-min.css',
        array(),
        SUBO4_THEME_VERSION
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
 * Add lazy loading to post images
 */
function subo4_add_lazy_loading( $attr, $attachment ) {
    $attr['loading'] = 'lazy';
    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'subo4_add_lazy_loading', 10, 2 );
