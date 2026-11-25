<?php
/**
 * Template Name: 首页
 * Template Post Type: page
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

$is_custom_page_home = is_page();
$paged               = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

if ( $is_custom_page_home ) {
    $home_query = new WP_Query( array(
        'post_type'      => 'post',
        'posts_per_page' => 6,
        'paged'          => $paged,
    ) );
} else {
    global $wp_query;
    $home_query = $wp_query;
}
?>

<main class="main-wrapper">
    <?php get_template_part( 'template-parts/profile-card', null, array( 'eager_avatar' => true ) ); ?>

    <div class="content-card">
        <?php
        if ( $home_query->have_posts() ) :
            while ( $home_query->have_posts() ) :
                $home_query->the_post();
                get_template_part( 'template-parts/post-item' );
            endwhile;
        else :
            ?>
            <p class="empty-state"><?php esc_html_e( 'No posts found.', 'subo4-classic-theme' ); ?></p>
        <?php endif; ?>

        <div class="pagination-wrapper">
            <?php subo4_render_pagination( $is_custom_page_home ? $home_query : null, $paged ); ?>
        </div>

        <?php
        if ( $is_custom_page_home ) {
            wp_reset_postdata();
        }
        ?>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
