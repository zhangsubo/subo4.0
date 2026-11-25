<?php
/**
 * 归档页面模板
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

$archive_title       = get_the_archive_title();
$archive_description = get_the_archive_description();
?>

<main class="main-wrapper">
    <?php get_template_part( 'template-parts/profile-card' ); ?>

    <div class="content-card">
        <h1 class="text-center post-title-main">
            <?php echo $archive_title ? wp_kses_post( $archive_title ) : esc_html__( 'Archives', 'subo4-classic-theme' ); ?>
        </h1>

        <?php if ( $archive_description ) : ?>
            <div class="archive-description">
                <?php echo wp_kses_post( $archive_description ); ?>
            </div>
        <?php endif; ?>

        <?php if ( have_posts() ) : ?>
            <?php
            $current_year = '';
            while ( have_posts() ) :
                the_post();
                $year = get_the_date( 'Y' );

                if ( $year !== $current_year ) {
                    if ( '' !== $current_year ) {
                        echo '</div>';
                    }
                    $current_year = $year;
                    echo '<div class="archive-year-section">';
                    echo '<h3 class="archive-year">' . esc_html( $year ) . '</h3>';
                }
                ?>
                <div class="archive-item">
                    <span class="archive-time"><?php echo esc_html( get_the_date( 'M j' ) ); ?></span>
                    <span class="archive-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                </div>
                <?php
            endwhile;
            if ( '' !== $current_year ) {
                echo '</div>';
            }
            ?>
        <?php else : ?>
            <p class="empty-state"><?php esc_html_e( 'No posts found.', 'subo4-classic-theme' ); ?></p>
        <?php endif; ?>

        <div class="pagination-wrapper">
            <?php subo4_render_pagination(); ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
