<?php
/**
 * Header template
 *
 * @package SUBO4_Classic_Theme
 */

// 获取Logo
$logo = get_field('SUBO4_logo', 'option');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid" style="max-width: 800px;">
            <!-- Logo区域 -->
            <?php if ($logo) : ?>
            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>" style="max-height: 40px; width: auto;">
            </a>
            <?php endif; ?>
            
            <!-- 移动端菜单按钮 - 放在右侧 -->
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="切换导航">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- 导航菜单 -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array(
                        'theme_location'  => 'primary',
                        'container'       => false,
                        'menu_class'      => 'navbar-nav mb-2 mb-lg-0',
                        'fallback_cb'     => false,
                        'depth'           => 1,
                    ) );
                } else {
                    echo '<p style="color: #999; font-size: 12px; margin: 0;">请在后台设置导航菜单</p>';
                }
                ?>
            </div>
        </div>
    </nav>