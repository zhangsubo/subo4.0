<?php
/**
 * Footer template
 *
 * @package SUBO4_Classic_Theme
 */

// Get theme settings
$icp_number = subo4_get_option( 'SUBO4_icp_number', '' );
$complaints_email = subo4_get_option( 'SUBO4_complaints_email', '' );
?>

    <footer class="text-center py-4" style="color: #666; font-size: 13px;">
        <div class="container" style="max-width: 800px;">
            <p class="footer-copyright">
                &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
                <?php
                printf(
                    /* translators: %s: theme link */
                    esc_html__( ' · Theme: %s', 'subo4-classic-theme' ),
                    '<a href="https://github.com/zhangsubo/subo4.0" target="_blank" rel="noopener noreferrer" class="footer-link">SUBO4.0</a>'
                );
                ?>
            </p>
            <?php if ( $icp_number || $complaints_email ) : ?>
            <p class="footer-info">
                <?php if ( $icp_number ) : ?>
                <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer" class="footer-link">
                    <?php echo esc_html( $icp_number ); ?>
                </a>
                <?php endif; ?>
                <?php if ( $icp_number && $complaints_email ) : ?> · <?php endif; ?>
                <?php if ( $complaints_email ) : ?>
                <?php
                printf(
                    /* translators: %s: email address */
                    esc_html__( 'Report illegal content: %s', 'subo4-classic-theme' ),
                    '<a href="mailto:' . esc_attr( $complaints_email ) . '" class="footer-link">' . esc_html( $complaints_email ) . '</a>'
                );
                ?>
                <?php endif; ?>
            </p>
            <?php endif; ?>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
