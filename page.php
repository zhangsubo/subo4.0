<?php
/**
 * 页面模板
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

while ( have_posts() ) :
    the_post();
    ?>

<main class="main-wrapper">
    <?php get_template_part( 'template-parts/profile-card' ); ?>

    <div class="content-card">
        <h1 class="text-center post-title-main"><?php the_title(); ?></h1>
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
