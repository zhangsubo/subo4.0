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
    $default_aboutme = ( defined( 'SUBO4_DEFAULT_ABOUTME' ) && 'Welcome to my blog. I write about technology, products, and life.' !== SUBO4_DEFAULT_ABOUTME )
        ? SUBO4_DEFAULT_ABOUTME
        : __( 'Welcome to my blog. I write about technology, products, and life.', 'subo4-classic-theme' );

    return subo4_get_option(
        'SUBO4_aboutme_content',
        $default_aboutme
    );
}

/**
 * Get logo URL
 */
function subo4_get_logo() {
    return subo4_get_option( 'SUBO4_logo', '' );
}
