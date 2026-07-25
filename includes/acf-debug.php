<?php
/**
 * ACF Debug Widget
 * 在 WordPress 后台显示 ACF 加载状态
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 添加 ACF 调试信息到 WordPress 仪表板
 */
function subo4_acf_debug_dashboard_widget() {
    echo '<div style="font-family: monospace; font-size: 12px;">';

    // 1. ACF 函数检查
    echo '<h4>1. ACF 函数检查</h4>';
    echo 'function_exists(\'get_field\'): ' . (function_exists('get_field') ? '✅ YES' : '❌ NO') . '<br>';
    echo 'function_exists(\'acf_add_options_page\'): ' . (function_exists('acf_add_options_page') ? '✅ YES' : '❌ NO') . '<br>';

    // 2. ACF 常量检查
    echo '<h4>2. ACF 常量检查</h4>';
    echo 'defined(\'ACF\'): ' . (defined('ACF') ? '✅ YES' : '❌ NO') . '<br>';
    echo 'ACF_VERSION: ' . (defined('ACF_VERSION') ? '✅ ' . ACF_VERSION : '❌ NO') . '<br>';

    // 3. 主题常量检查
    echo '<h4>3. 主题常量检查</h4>';
    echo 'SUBO4_ACF_PATH: ' . (defined('SUBO4_ACF_PATH') ? '✅ ' . SUBO4_ACF_PATH : '❌ NO') . '<br>';

    // 4. 文件检查
    echo '<h4>4. ACF 文件检查</h4>';
    $acf_file = get_template_directory() . '/includes/acf/acf.php';
    echo 'File exists: ' . (file_exists($acf_file) ? '✅ YES' : '❌ NO') . '<br>';
    echo 'File readable: ' . (is_readable($acf_file) ? '✅ YES' : '❌ NO') . '<br>';

    // 5. 辅助函数检查
    echo '<h4>5. 主题辅助函数检查</h4>';
    echo 'subo4_get_avatar: ' . (function_exists('subo4_get_avatar') ? '✅ YES' : '❌ NO') . '<br>';
    echo 'subo4_get_author_name: ' . (function_exists('subo4_get_author_name') ? '✅ YES' : '❌ NO') . '<br>';

    // 6. 已加载的 ACF 文件数量
    echo '<h4>6. 已加载的文件</h4>';
    $loaded_files = get_included_files();
    $acf_loaded = array_filter($loaded_files, function($file) {
        return strpos($file, '/includes/acf/') !== false;
    });
    echo 'ACF files loaded: ' . (count($acf_loaded) > 0 ? '✅ ' . count($acf_loaded) : '❌ 0') . '<br>';

    echo '</div>';
}

/**
 * 注册仪表板小工具
 */
function subo4_add_acf_debug_widget() {
    wp_add_dashboard_widget(
        'subo4_acf_debug',
        'ACF 加载状态检测',
        'subo4_acf_debug_dashboard_widget'
    );
}
add_action( 'wp_dashboard_setup', 'subo4_add_acf_debug_widget' );

/**
 * 在管理员栏显示 ACF 状态
 */
function subo4_admin_bar_acf_status( $wp_admin_bar ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $status = function_exists( 'get_field' ) ? '✅' : '❌';
    $title = $status . ' ACF';

    $wp_admin_bar->add_node( array(
        'id'    => 'acf-status',
        'title' => $title,
        'href'  => admin_url( 'index.php' ),
    ) );
}
add_action( 'admin_bar_menu', 'subo4_admin_bar_acf_status', 100 );
