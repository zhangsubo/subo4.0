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

    <!-- 文章列表 -->
    <div class="content-card">
        <?php
        if ( $home_query->have_posts() ) :
            while ( $home_query->have_posts() ) :
                $home_query->the_post();
        ?>
            <article class="row post-item">
                <div class="col-md-2 text-center text-md-start mb-2 mb-md-0">
                    <div class="post-date-month">
                        <?php echo esc_html( get_the_date( 'M' ) ); ?>
                    </div>
                    <div class="post-date-year">
                        <?php echo esc_html( get_the_date( 'Y' ) ); ?>
                    </div>
                </div>
                <div class="col-md-10">
                    <h3 class="h5 post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="post-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?></p>
                    <div class="post-tags">
                        <?php
                        $tags = get_the_tags();
                        if ( $tags ) {
                            foreach ( $tags as $tag ) {
                                printf(
                                    '<a href="%s" class="badge bg-light text-secondary text-decoration-none">#%s</a> ',
                                    esc_url( get_tag_link( $tag->term_id ) ),
                                    esc_html( $tag->name )
                                );
                            }
                        }
                        ?>
                    </div>
                </div>
            </article>
        <?php
            endwhile;
        else :
        ?>
            <p class="empty-state"><?php esc_html_e( 'No posts found.', 'subo4-classic-theme' ); ?></p>
        <?php endif; ?>

        <!-- 分页 -->
        <div class="pagination-wrapper">
            <?php
            if ( $is_custom_page_home ) {
                echo paginate_links( array(
                    'base'               => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                    'format'             => '?paged=%#%',
                    'current'            => $paged,
                    'total'              => $home_query->max_num_pages,
                    'prev_text'          => '&larr;',
                    'next_text'          => '&rarr;',
                    'before_page_number' => '',
                    'after_page_number'  => '',
                    'mid_size'           => 2,
                    'end_size'           => 1,
                ) );
            } else {
                the_posts_pagination( array(
                    'prev_text'          => '&larr;',
                    'next_text'          => '&rarr;',
                    'before_page_number' => '',
                    'after_page_number'  => '',
                    'mid_size'           => 2,
                    'end_size'           => 1,
                    'screen_reader_text' => esc_html__( 'Posts navigation', 'subo4-classic-theme' ),
                ) );
            }
            ?>
        </div>

        <?php
        if ( $is_custom_page_home ) {
            wp_reset_postdata();
        }
        ?>
    </div>

    <!-- 社交链接 -->
    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
