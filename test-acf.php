<?php
/**
 * ACF Debug Test
 * 访问: https://zhangsubo.cn/wp-content/themes/subo2026/test-acf.php
 */

// Load WordPress
require_once('../../../../../wp-load.php');

echo '<h1>ACF 加载状态检测</h1>';
echo '<pre>';

// 1. 检查 ACF 函数是否存在
echo "=== 1. ACF 函数检查 ===\n";
echo "function_exists('acf'): " . (function_exists('acf') ? '✅ YES' : '❌ NO') . "\n";
echo "function_exists('get_field'): " . (function_exists('get_field') ? '✅ YES' : '❌ NO') . "\n";
echo "function_exists('acf_add_options_page'): " . (function_exists('acf_add_options_page') ? '✅ YES' : '❌ NO') . "\n\n";

// 2. 检查 ACF 常量
echo "=== 2. ACF 常量检查 ===\n";
echo "ACF: " . (defined('ACF') ? '✅ YES - ' . ACF : '❌ NO') . "\n";
echo "ACF_PATH: " . (defined('ACF_PATH') ? '✅ YES - ' . ACF_PATH : '❌ NO') . "\n";
echo "ACF_VERSION: " . (defined('ACF_VERSION') ? '✅ YES - ' . ACF_VERSION : '❌ NO') . "\n\n";

// 3. 检查主题常量
echo "=== 3. 主题常量检查 ===\n";
echo "SUBO4_ACF_PATH: " . (defined('SUBO4_ACF_PATH') ? '✅ YES - ' . SUBO4_ACF_PATH : '❌ NO') . "\n";
echo "SUBO4_ACF_URL: " . (defined('SUBO4_ACF_URL') ? '✅ YES - ' . SUBO4_ACF_URL : '❌ NO') . "\n\n";

// 4. 检查 ACF 文件
echo "=== 4. ACF 文件检查 ===\n";
$acf_file = get_template_directory() . '/includes/acf/acf.php';
echo "ACF 文件路径: $acf_file\n";
echo "文件是否存在: " . (file_exists($acf_file) ? '✅ YES' : '❌ NO') . "\n";
echo "文件是否可读: " . (is_readable($acf_file) ? '✅ YES' : '❌ NO') . "\n\n";

// 5. 检查已加载的文件
echo "=== 5. 已加载的 ACF 文件 ===\n";
$loaded_files = get_included_files();
$acf_loaded = array_filter($loaded_files, function($file) {
    return strpos($file, 'acf') !== false && strpos($file, 'includes/acf') !== false;
});
if (count($acf_loaded) > 0) {
    echo "✅ 找到 " . count($acf_loaded) . " 个 ACF 文件已加载\n";
    foreach (array_slice($acf_loaded, 0, 5) as $file) {
        echo "  - " . basename($file) . "\n";
    }
} else {
    echo "❌ 没有找到 ACF 文件已加载\n";
}
echo "\n";

// 6. 测试 get_field 函数
echo "=== 6. 测试 get_field 函数 ===\n";
if (function_exists('get_field')) {
    $test_value = get_field('SUBO4_avatar', 'option');
    echo "get_field('SUBO4_avatar', 'option'): " . ($test_value ? '✅ 有值 - ' . substr($test_value, 0, 50) . '...' : '❌ 无值') . "\n";
} else {
    echo "❌ get_field 函数不存在\n";
}
echo "\n";

// 7. 检查辅助函数
echo "=== 7. 主题辅助函数检查 ===\n";
echo "function_exists('subo4_get_avatar'): " . (function_exists('subo4_get_avatar') ? '✅ YES' : '❌ NO') . "\n";
echo "function_exists('subo4_get_author_name'): " . (function_exists('subo4_get_author_name') ? '✅ YES' : '❌ NO') . "\n";
echo "function_exists('subo4_get_option'): " . (function_exists('subo4_get_option') ? '✅ YES' : '❌ NO') . "\n\n";

// 8. 测试辅助函数
echo "=== 8. 测试主题辅助函数 ===\n";
if (function_exists('subo4_get_avatar')) {
    echo "subo4_get_avatar(): " . subo4_get_avatar() . "\n";
}
if (function_exists('subo4_get_author_name')) {
    echo "subo4_get_author_name(): " . subo4_get_author_name() . "\n";
}

echo '</pre>';

// 删除此测试文件的提示
echo '<p style="color: red; font-weight: bold;">⚠️ 测试完成后请删除此文件！</p>';
?>
