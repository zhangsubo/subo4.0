<?php
/**
 * SUBO4 Classic Theme functions and definitions
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Theme version
 */
define( 'SUBO4_THEME_VERSION', '1.0.0' );
define( 'SUBO4_THEME_DIR', get_template_directory() );
define( 'SUBO4_THEME_URI', get_template_directory_uri() );

/**
 * ACF PRO Integration
 */
define( 'MY_THEME_ACF_PATH', SUBO4_THEME_DIR . '/includes/acf/' );
define( 'MY_THEME_ACF_URL', SUBO4_THEME_URI . '/includes/acf/' );

/**
 * ZSUPER Framework
 */
define('ZS_PATH', SUBO4_THEME_DIR . '/includes/zsuper-framework/');
define('ZS_URL', SUBO4_THEME_URI . '/includes/zsuper-framework/');




// 加载ACF PRO
if ( file_exists( MY_THEME_ACF_PATH . 'acf.php' ) ) {
    include_once( MY_THEME_ACF_PATH . 'acf.php' );
    
    add_filter( 'acf/settings/url', function( $url ) {
        return MY_THEME_ACF_URL;
    });
}

// 移除头部多余代码
include_once (ZS_PATH . 'remove-head.php');

// 加载 ACF 可用性检查
include_once( SUBO4_THEME_DIR . '/includes/acf-check.php' );


/**
 * Theme setup
 */
add_action( 'after_setup_theme', 'SUBO4_theme_setup' );
function SUBO4_theme_setup() {
    // 添加主题支持
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    
    // 特色图片尺寸
    set_post_thumbnail_size( 1200, 9999 );
    
    // 注册导航菜单
    register_nav_menus( array(
        'primary' => '主导航菜单',
        'footer'  => '页脚菜单',
    ) );
    
    // 加载编辑器样式
    add_editor_style( 'assets/css/editor-style-min.css' );
}

/**
 * 加载CSS和JS
 */
add_action( 'wp_enqueue_scripts', 'SUBO4_enqueue_scripts' );
function SUBO4_enqueue_scripts() {
    // Bootstrap 5 CSS
    wp_enqueue_style( 'bootstrap-5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );
    
    // 主题样式
    wp_enqueue_style( 'SUBO4-theme-style', SUBO4_THEME_URI . '/assets/css/theme-min.css', array( 'bootstrap-5' ), SUBO4_THEME_VERSION );
    
    // Bootstrap 5 JS
    wp_enqueue_script( 'bootstrap-5-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array(), '5.3.2', true );
    
    // 主题JS
    wp_enqueue_script( 'SUBO4-theme-js', SUBO4_THEME_URI . '/assets/js/theme-min.js', array( 'bootstrap-5-js' ), SUBO4_THEME_VERSION, true );
}

/**
 * 后台样式
 */
add_action( 'admin_enqueue_scripts', 'SUBO4_admin_scripts' );
function SUBO4_admin_scripts() {
    wp_enqueue_style( 'SUBO4-admin-style', SUBO4_THEME_URI . '/assets/css/admin-min.css', array(), SUBO4_THEME_VERSION );
}

/**
 * ACF主题选项页面
 */
add_action( 'acf/init', 'SUBO4_acf_init' );
function SUBO4_acf_init() {
    if ( function_exists( 'acf_add_options_page' ) ) {
        acf_add_options_page( array(
            'page_title' => '主题设置',
            'menu_title' => '主题设置',
            'menu_slug'  => 'SUBO4-theme-settings',
            'capability' => 'edit_posts',
            'redirect'   => false,
            'position'   => 30,
            'icon_url'   => 'dashicons-admin-generic',
        ) );
    }
}

/**
 * 摘要长度
 */
add_filter( 'excerpt_length', function() { return 30; }, 999 );

/**
 * 摘要后缀
 */
add_filter( 'excerpt_more', function() { return '...'; } );

/**
 * 为导航菜单项添加 Bootstrap 类
 */
add_filter( 'nav_menu_css_class', function( $classes, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
        $classes[] = 'nav-item';
    }
    return $classes;
}, 10, 4 );

add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args, $depth ) {
    if ( isset( $args->theme_location ) && $args->theme_location === 'primary' ) {
        $atts['class'] = 'nav-link';
    }
    return $atts;
}, 10, 4 );

/**
 * 包含其他文件
 */
require_once SUBO4_THEME_DIR . '/includes/template-tags.php';
require_once SUBO4_THEME_DIR . '/includes/customizer.php';
require_once SUBO4_THEME_DIR . '/acf-fields/theme-settings.php';

/**
 * 将文中的链接全部转换成脚注
 */
function convert_links_to_footnotes($content) {
    // 匹配文章中的链接
    preg_match_all('/<a href="([^"]+)">([^<]+)<\/a>/i', $content, $matches);

    // 初始化脚注数组
    $footnotes = array();

    // 替换链接为脚注编号
    foreach ($matches[0] as $key => $match) {
        // 获取链接和链接文本
        $url = $matches[1][$key];
        $link_text = $matches[2][$key];

        // 将链接替换为脚注编号（使用上标样式）
        $content = str_replace($match, $link_text . '<sup class="footnote-ref">' . ($key + 1) . '</sup>', $content);

        // 添加脚注到数组
        $footnotes[] = htmlspecialchars($link_text) . ': ' . htmlspecialchars($url);
    }

    // 添加脚注列表到文章末尾
    if (!empty($footnotes)) {
        $content .= '<h2>References</h2><ol>';
        foreach ($footnotes as $key => $footnote) {
            $content .= '<li id="fn-' . ($key + 1) . '">' . $footnote . ' <sup><a href="#fnref-' . ($key + 1) . '" class="reversefootnote">&#8617;</a></sup></li>';
        }
        $content .= '</ol>';
    }

    return $content;
}

// 将函数添加到WordPress的the_content过滤器
add_filter('the_content', 'convert_links_to_footnotes');

