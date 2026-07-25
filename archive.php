<?php
/**
 * 归档页面模板
 *
 * @package SUBO4_Block_Theme
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
                         class="profile-avatar-img">
                </a>
            </div>
            <div class="col-md-9 text-center text-md-start">
                <h2 class="profile-name"><?php echo esc_html( $author_name ); ?></h2>
                <p class="profile-bio"><?php echo esc_html( $bio ); ?></p>
            </div>
        </div>
    </div>

    <!-- 归档内容 -->
    <div class="content-card">
        <h1 class="text-center mb-4 post-title-main"><?php esc_html_e( 'Archives', 'subo4-classic-theme' ); ?></h1>

        <?php
        // 获取所有文章，按年份分组
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $posts = get_posts( $args );

        if ( $posts ) :
            $current_year = '';
            foreach ( $posts as $post ) :
                setup_postdata( $post );
                $year = get_the_date( 'Y' );

                if ( $year !== $current_year ) :
                    if ( $current_year !== '' ) echo '</div>';
                    $current_year = $year;
                    echo '<div class="archive-year-section mb-4">';
                    echo '<h3 class="archive-year">' . esc_html( $year ) . '</h3>';
                endif;
                ?>
                <div class="archive-item">
                    <span class="archive-time"><?php echo get_the_date( 'M j' ); ?></span>
                    <span class="archive-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                </div>
            <?php
            endforeach;
            echo '</div>';
            wp_reset_postdata();
        else :
            ?>
            <p class="empty-state"><?php esc_html_e( 'No posts found.', 'subo4-classic-theme' ); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>