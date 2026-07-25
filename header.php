<?php
/**
 * Header template
 *
 * @package SUBO4_Classic_Theme
 */

// Get Logo
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

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid" style="max-width: 800px;">
            <!-- Logo Area -->
            <?php if ( $logo ) : ?>
            <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="<?php echo esc_url( $logo ); ?>"
                     alt="<?php bloginfo( 'name' ); ?>"
                     class="navbar-logo">
            </a>
            <?php endif; ?>

            <!-- Mobile Menu Button -->
            <button class="navbar-toggler ms-auto"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e( 'Toggle navigation', 'subo4-classic-theme' ); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location'  => 'primary',
                        'container'       => false,
                        'menu_class'      => 'navbar-nav mb-2 mb-lg-0',
                        'fallback_cb'     => false,
                        'depth'           => 1,
                    ) );
                } else {
                    // Show placeholder only to users who can edit theme options
                    if ( current_user_can( 'edit_theme_options' ) ) {
                        printf(
                            '<p class="menu-placeholder">%s</p>',
                            esc_html__( 'Please set up navigation menu in admin panel', 'subo4-classic-theme' )
                        );
                    }
                }
                ?>
            </div>
        </div>
    </nav>
