<?php
/**
 * 主索引模板
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

    <!-- 文章列表 -->
    <div class="content-card">
        <?php
        if (have_posts()) :
            while (have_posts()) : the_post();
        ?>
            <article class="row post-item">
                <div class="col-md-2 text-center text-md-start mb-2 mb-md-0">
                    <div class="text-muted" style="font-size: 13px;">
                        <div><?php echo get_the_date('M'); ?></div>
                        <div><?php echo get_the_date('Y'); ?></div>
                    </div>
                </div>
                <div class="col-md-10">
                    <h3 class="h5 post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="post-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?></p>
                    <div class="post-tags">
                        <?php
                        $tags = get_the_tags();
                        if ($tags) {
                            foreach ($tags as $tag) {
                                echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge bg-light text-secondary text-decoration-none">#' . esc_html($tag->name) . '</a> ';
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
            <p class="text-center text-muted">暂无文章</p>
        <?php endif; ?>

        <!-- 分页 -->
        <div class="pagination-wrapper">
            <?php
            the_posts_pagination(array(
                'prev_text' => '←',
                'next_text' => '→',
                'before_page_number' => '',
                'after_page_number'  => '',
                'mid_size'  => 2,
                'end_size'  => 1,
            ));
            ?>
        </div>
    </div>

    <!-- 社交链接 -->
    <?php get_template_part('template-parts/social-links'); ?>

</main>

<?php get_footer(); ?>
