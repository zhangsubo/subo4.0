<?php
/**
 * 页面模板
 *
 * @package SUBO4_Block_Theme
 */

get_header();

// 获取ACF字段值
$avatar = get_field('SUBO4_avatar', 'option') ?: get_template_directory_uri() . '/assets/images/avatar.jpg';
$author_name = get_field('SUBO4_author_name', 'option') ?: 'SUBO4';
$bio = get_field('SUBO4_bio', 'option') ?: 'All good things come to those who wait.';

while (have_posts()) : the_post();
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

    <!-- 页面内容 -->
    <div class="content-card">
        <h1 class="text-center mb-4" style="font-size: 24px;"><?php the_title(); ?></h1>
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    </div>

    <!-- 社交链接 -->
    <?php get_template_part('template-parts/social-links'); ?>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
