<?php
/**
 * ACF Integration and Options Page
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load bundled ACF PRO
 */
if ( file_exists( SUBO4_ACF_PATH . 'acf.php' ) ) {
    include_once( SUBO4_ACF_PATH . 'acf.php' );

    // Set ACF URL
    add_filter( 'acf/settings/url', function( $url ) {
        return SUBO4_ACF_URL;
    });

    // Hide ACF menu in production
    add_filter( 'acf/settings/show_admin', function( $show ) {
        return defined( 'WP_DEBUG' ) && WP_DEBUG;
    });
}

/**
 * Register ACF Options Page
 */
function subo4_acf_init() {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    acf_add_options_page( array(
        'page_title' => esc_html__( 'Theme Settings', 'subo4-classic-theme' ),
        'menu_title' => esc_html__( 'Theme Settings', 'subo4-classic-theme' ),
        'menu_slug'  => 'subo4-theme-settings',
        'capability' => 'edit_theme_options',
        'redirect'   => false,
        'position'   => 30,
        'icon_url'   => 'dashicons-admin-generic',
    ) );
}
add_action( 'acf/init', 'subo4_acf_init' );

/**
 * Get ACF field with fallback
 *
 * @param string $field_name ACF field name
 * @param mixed  $default    Default value
 * @return mixed Field value or default
 */
function subo4_get_option( $field_name, $default = '' ) {
    if ( ! function_exists( 'get_field' ) ) {
        return $default;
    }

    $value = get_field( $field_name, 'option' );
    return $value ? $value : $default;
}

/**
 * Get theme avatar
 */
function subo4_get_avatar() {
    return subo4_get_option(
        'SUBO4_avatar',
        SUBO4_THEME_URI . '/assets/images/avatar.jpg'
    );
}

/**
 * Get theme author name
 */
function subo4_get_author_name() {
    return subo4_get_option(
        'SUBO4_author_name',
        SUBO4_DEFAULT_AUTHOR_NAME
    );
}

/**
 * Get theme bio
 */
function subo4_get_bio() {
    return subo4_get_option(
        'SUBO4_bio',
        SUBO4_DEFAULT_BIO
    );
}

/**
 * Get about me content
 */
function subo4_get_aboutme_content() {
    return subo4_get_option(
        'SUBO4_aboutme_content',
        SUBO4_DEFAULT_ABOUTME
    );
}

/**
 * Get logo URL
 */
function subo4_get_logo() {
    return subo4_get_option( 'SUBO4_logo', '' );
}
