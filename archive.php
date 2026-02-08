<?php
/**
 * 归档页面模板
 *
 * @package SUBO4_Block_Theme
 */

get_header();

// 获取ACF字段值
$avatar = get_field('SUBO4_avatar', 'option') ?: get_template_directory_uri() . '/assets/images/avatar.jpg';
$author_name = get_field('SUBO4_author_name', 'option') ?: 'SUBO4';
$bio = get_field('SUBO4_bio', 'option') ?: 'All good things come to those who wait.';
?>

<main class="main-wrapper">
    <!-- 个人资料卡片 -->
    <div class="content-card">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <a href="<?php echo esc_url(home_url('/about/')); ?>">
                    <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($author_name); ?>" class="rounded-circle" style="width: 80px; height: 80px; border: 1px solid #ddd;">
                </a>
            </div>
            <div class="col-md-9 text-center text-md-start">
                <h2 style="font-size: 24px;"><?php echo esc_html($author_name); ?></h2>
                <p class="text-muted" style="font-size: 14px;"><?php echo esc_html($bio); ?></p>
            </div>
        </div>
    </div>

    <!-- 归档内容 -->
    <div class="content-card">
        <h1 class="text-center mb-4" style="font-size: 24px;">文章归档</h1>
        
        <?php
        // 获取所有文章，按年份分组
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );
        $posts = get_posts($args);
        
        if ($posts) :
            $current_year = '';
            foreach ($posts as $post) :
                setup_postdata($post);
                $year = get_the_date('Y');
                
                if ($year !== $current_year) :
                    if ($current_year !== '') echo '</div>';
                    $current_year = $year;
                    echo '<div class="archive-year-section mb-4">';
                    echo '<h3 class="archive-year">' . esc_html($year) . '</h3>';
                endif;
                ?>
                <div class="archive-item">
                    <span class="archive-time"><?php echo get_the_date('M j'); ?></span>
                    <span class="archive-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></span>
                </div>
            <?php
            endforeach;
            echo '</div>';
            wp_reset_postdata();
        else :
            ?>
            <p class="text-center text-muted">暂无文章</p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>