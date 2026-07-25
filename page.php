<?php
/**
 * 页面模板
 *
 * @package SUBO4_Block_Theme
 */

get_header();

// 获取ACF字段值（使用辅助函数，带降级处理）
$avatar = subo4_get_avatar();
$author_name = subo4_get_author_name();
$bio = subo4_get_bio();

while (have_posts()) : the_post();
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

    <!-- 页面内容 -->
    <div class="content-card">
        <h1 class="text-center mb-4 post-title-main"><?php the_title(); ?></h1>
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    </div>

    <!-- 社交链接 -->
    <?php get_template_part('template-parts/social-links'); ?>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
