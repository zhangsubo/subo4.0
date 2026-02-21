<?php
/**
 * ACF 可用性检查
 * 检查 ACF 插件是否正确加载
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 检查 ACF 是否可用
 * @return bool
 */
function subo2026_is_acf_available() {
    return function_exists('get_field');
}

/**
 * 安全地获取 ACF 字段值
 * @param string $selector 字段名称
 * @param mixed $post_id 可选的文章ID或'option'
 * @param mixed $default 默认值（当ACF不可用时返回）
 * @return mixed
 */
function subo2026_get_field($selector, $post_id = false, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($selector, $post_id);
        return ($value !== null && $value !== false) ? $value : $default;
    }
    return $default;
}

/**
 * 在管理后台显示 ACF 警告
 */
add_action('admin_notices', function() {
    // 只在主题设置页面显示
    $screen = get_current_screen();
    if (!$screen || !isset($screen->id) || $screen->id !== 'toplevel_page_theme-settings') {
        return;
    }
    
    if (!function_exists('get_field')) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p><strong>主题错误：</strong>ACF PRO 插件未正确加载。</p>';
        echo '<p>请确保将 ACF PRO 文件放置在：<br>';
        echo '<code>' . esc_html(get_template_directory() . '/includes/acf/') . '</code></p>';
        echo '<p>需要包含的文件：<code>acf.php</code></p>';
        echo '</div>';
    }
});
