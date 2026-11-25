<?php
/**
 * Main index template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();
?>

<main class="main-wrapper">
    <?php get_template_part( 'template-parts/profile-card', null, array( 'eager_avatar' => true ) ); ?>

    <div class="content-card">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/post-item' );
            endwhile;
        else :
            ?>
            <p class="empty-state"><?php esc_html_e( 'No posts found.', 'subo4-classic-theme' ); ?></p>
        <?php endif; ?>

        <div class="pagination-wrapper">
            <?php subo4_render_pagination(); ?>
        </div>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
