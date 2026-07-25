<?php
/**
 * SUBO4 Classic Theme functions and definitions
 *
 * @package SUBO4_Classic_Theme
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Load theme configuration
 */
require_once get_template_directory() . '/includes/theme-config.php';

/**
 * Load ACF PRO
 */
if ( file_exists( SUBO4_ACF_PATH . 'acf.php' ) ) {
    include_once( SUBO4_ACF_PATH . 'acf.php' );

    add_filter( 'acf/settings/url', function( $url ) {
        return SUBO4_ACF_URL;
    });
}

/**
 * Load ZSUPER Framework - Remove head clutter
 */
if ( file_exists( SUBO4_ZS_PATH . 'remove-head.php' ) ) {
    include_once( SUBO4_ZS_PATH . 'remove-head.php' );
}

/**
 * Load ACF availability check
 */
require_once SUBO4_THEME_DIR . '/includes/acf-check.php';

/**
 * Load theme modules
 */
require_once SUBO4_THEME_DIR . '/includes/functions/theme-setup.php';
require_once SUBO4_THEME_DIR . '/includes/functions/acf-integration.php';
require_once SUBO4_THEME_DIR . '/includes/functions/footnotes.php';

/**
 * Load template functions
 */
require_once SUBO4_THEME_DIR . '/includes/template-tags.php';
require_once SUBO4_THEME_DIR . '/includes/customizer.php';

/**
 * Load ACF field definitions
 */
require_once SUBO4_THEME_DIR . '/acf-fields/theme-settings.php';
