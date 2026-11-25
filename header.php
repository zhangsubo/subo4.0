<?php
/**
 * Header template
 *
 * @package SUBO4_Classic_Theme
 */

$logo = subo4_get_logo();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <nav class="navbar-custom" aria-label="<?php esc_attr_e( 'Primary Menu', 'subo4-classic-theme' ); ?>">
        <div class="navbar-container">
            <?php if ( $logo ) : ?>
                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <img src="<?php echo esc_url( $logo ); ?>"
                         alt="<?php bloginfo( 'name' ); ?>"
                         class="navbar-logo">
                </a>
            <?php endif; ?>

            <button class="navbar-toggler"
                    type="button"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle navigation', 'subo4-classic-theme' ); ?>"
                    data-nav-toggle>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <line x1="4" y1="7" x2="20" y2="7"></line>
                    <line x1="4" y1="12" x2="20" y2="12"></line>
                    <line x1="4" y1="17" x2="20" y2="17"></line>
                </svg>
            </button>

            <div class="navbar-collapse" id="navbarNav">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'navbar-nav',
                        'fallback_cb'    => false,
                        'depth'          => 1,
                    ) );
                } elseif ( current_user_can( 'edit_theme_options' ) ) {
                    printf(
                        '<p class="menu-placeholder">%s</p>',
                        esc_html__( 'Please set up navigation menu in admin panel', 'subo4-classic-theme' )
                    );
                }
                ?>
            </div>
        </div>
    </nav>
