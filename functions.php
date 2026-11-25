<?php
/**
 * SUBO4 Classic Theme functions and definitions
 *
 * @package SUBO4_Classic_Theme
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once get_template_directory() . '/includes/theme-config.php';
require_once SUBO4_THEME_DIR . '/includes/functions/theme-setup.php';
require_once SUBO4_THEME_DIR . '/includes/functions/theme-settings.php';
require_once SUBO4_THEME_DIR . '/includes/functions/footnotes.php';
require_once SUBO4_THEME_DIR . '/includes/functions/projects-cpt.php';
require_once SUBO4_THEME_DIR . '/includes/functions/seo-meta.php';

if ( is_admin() ) {
    require_once SUBO4_THEME_DIR . '/includes/functions/project-metaboxes.php';
}
