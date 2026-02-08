<?php
/**
 * 标签页面模板
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

    <!-- 标签云 -->
    <div class="content-card">
        <h1 class="text-center mb-4" style="font-size: 24px;">标签云</h1>
        
        <div class="tags-cloud text-center">
            <?php
            $tags = get_tags(array('orderby' => 'count', 'order' => 'DESC'));
            if ($tags) :
                foreach ($tags as $tag) :
                    $count = $tag->count;
                    $font_size = min(16, max(12, 12 + $count));
                ?>
                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" 
                       class="tag-item" 
                       style="font-size: <?php echo $font_size; ?>px;">
                        <?php echo esc_html($tag->name); ?> (<?php echo $count; ?>)
                    </a>
                <?php endforeach;
            else : ?>
                <p class="text-muted">暂无标签</p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>