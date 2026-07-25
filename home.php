<?php
/**
 * Template Name: 首页
 * Template Post Type: page
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

// 获取ACF字段值（使用辅助函数，带降级处理）
$avatar = subo4_get_avatar();
$author_name = subo4_get_author_name();
$bio = subo4_get_bio();
?>

<main class="main-wrapper">
    <!-- 个人资料卡片 -->
    <div class="content-card">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="profile-avatar-link">
                    <img src="<?php echo esc_url( $avatar ); ?>"
                         alt="<?php echo esc_attr( $author_name ); ?>"
                         class="profile-avatar-img"
                         loading="eager">
                </a>
            </div>
            <div class="col-md-9 text-center text-md-start">
                <h2 class="profile-name"><?php echo esc_html( $author_name ); ?></h2>
                <p class="profile-bio"><?php echo esc_html( $bio ); ?></p>
            </div>
        </div>
    </div>

    <!-- 文章列表 -->
    <div class="content-card">
        <?php
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => 6,
            'paged'          => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
        );
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) :
            while ( $query->have_posts() ) : $query->the_post();
        ?>
            <article class="row post-item">
                <div class="col-md-2 text-center text-md-start mb-2 mb-md-0">
                    <div class="post-date-month">
                        <?php echo get_the_date( 'M' ); ?>
                    </div>
                    <div class="post-date-year">
                        <?php echo get_the_date( 'Y' ); ?>
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
            echo paginate_links( array(
                'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                'format'    => '?paged=%#%',
                'current'   => max( 1, get_query_var( 'paged' ) ),
                'total'     => $query->max_num_pages,
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'before_page_number' => '',
                'after_page_number'  => '',
                'mid_size'  => 2,
                'end_size'  => 1,
            ) );
            ?>
        </div>

        <?php wp_reset_postdata(); ?>
    </div>

    <!-- 社交链接 -->
    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
