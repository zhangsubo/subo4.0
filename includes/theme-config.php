<?php
/**
 * Theme Configuration Constants
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Default theme values
 */
define( 'SUBO4_DEFAULT_AUTHOR_NAME', 'SUBO4' );
define( 'SUBO4_DEFAULT_BIO', 'All good things come to those who wait.' );
define( 'SUBO4_DEFAULT_ABOUTME', __( 'Welcome to my blog. I write about technology, products, and life.', 'subo4-classic-theme' ) );

/**
 * Theme paths and URIs
 */
define( 'SUBO4_THEME_VERSION', '1.0.0' );
define( 'SUBO4_THEME_DIR', get_template_directory() );
define( 'SUBO4_THEME_URI', get_template_directory_uri() );

/**
 * ACF PRO Integration
 */
define( 'SUBO4_ACF_PATH', SUBO4_THEME_DIR . '/includes/acf/' );
define( 'SUBO4_ACF_URL', SUBO4_THEME_URI . '/includes/acf/' );

/**
 * ZSUPER Framework
 */
define( 'SUBO4_ZS_PATH', SUBO4_THEME_DIR . '/includes/zsuper-framework/' );
define( 'SUBO4_ZS_URL', SUBO4_THEME_URI . '/includes/zsuper-framework/' );

/**
 * Bootstrap CDN versions
 */
define( 'SUBO4_BOOTSTRAP_VERSION', '5.3.2' );
define( 'SUBO4_BOOTSTRAP_CSS_CDN', 'https://cdn.jsdelivr.net/npm/bootstrap@' . SUBO4_BOOTSTRAP_VERSION . '/dist/css/bootstrap.min.css' );
define( 'SUBO4_BOOTSTRAP_JS_CDN', 'https://cdn.jsdelivr.net/npm/bootstrap@' . SUBO4_BOOTSTRAP_VERSION . '/dist/js/bootstrap.bundle.min.js' );

/**
 * Feature flags
 */
define( 'SUBO4_ENABLE_FOOTNOTES', true );
define( 'SUBO4_FOOTNOTES_CACHE', true );
