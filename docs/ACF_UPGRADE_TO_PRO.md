# 从 ACF 免费版升级到 Pro 版

## 当前状态

**检测到的问题**：
- ✅ ACF 已安装：版本 6.8.6
- ❌ 缺少 Pro 功能：`acf_add_options_page` 不可用
- ⚠️ 主题部分功能受限

## 为什么需要升级？

### Pro 版专属功能（主题正在使用）

1. **Options Page** - 主题选项页面
   - 全局设置（如站点 Logo、社交媒体链接等）
   - 当前因为缺少此功能，相关设置无法使用

2. **Repeater Field** - 重复字段
   - 用于图片画廊、项目列表等
   
3. **Flexible Content** - 灵活内容
   - 用于页面构建器功能

4. **Clone Field** - 字段克隆
   - 提高字段组复用效率

### 免费版 vs Pro 版对比

| 功能 | 免费版 | Pro 版 |
|------|--------|--------|
| 基础字段类型 | ✅ | ✅ |
| 字段组 | ✅ | ✅ |
| Options Page | ❌ | ✅ |
| Repeater | ❌ | ✅ |
| Flexible Content | ❌ | ✅ |
| Gallery | ❌ | ✅ |
| Clone | ❌ | ✅ |

## 升级步骤

### 方法 1：通过 SSH 替换（推荐）

```bash
# 1. 连接到服务器
ssh user@server

# 2. 备份现有版本
cd /www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/
mv acf acf-free-backup-$(date +%Y%m%d)

# 3. 上传 ACF Pro（使用 SFTP 或 scp）
# 从本地上传：
scp -r /path/to/advanced-custom-fields-pro user@server:/www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/acf

# 4. 设置权限
chmod -R 755 acf/
chown -R www-data:www-data acf/  # 根据服务器配置调整用户名

# 5. 验证安装
php -r "require '/www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/acf/acf.php'; echo defined('ACF_PRO') ? 'ACF Pro installed' : 'Still free version';"
```

### 方法 2：通过 FTP 替换

1. **下载 ACF Pro**
   - 登录 https://www.advancedcustomfields.com/my-account/
   - 下载最新的 Pro 版本 ZIP
   - 解压到本地

2. **备份现有文件**
   - FTP 连接到服务器
   - 重命名：`acf` → `acf-free-backup`

3. **上传 Pro 版本**
   - 上传解压后的文件夹为 `acf`
   - 路径：`/www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/acf/`

4. **验证文件结构**
   ```
   acf/
   ├── acf.php                 # 主文件
   ├── pro/                    # ← Pro 版独有目录
   │   ├── acf-pro.php
   │   └── ...
   ├── includes/
   └── assets/
   ```

### 方法 3：使用 WP-CLI（如果服务器支持）

```bash
# 1. 删除免费版
wp plugin delete advanced-custom-fields

# 2. 上传 Pro 版 ZIP 到服务器
scp acf-pro.zip user@server:/tmp/

# 3. 安装并激活
wp plugin install /tmp/acf-pro.zip --activate

# 4. 移动到主题目录
mv /www/sites/.../wp-content/plugins/advanced-custom-fields-pro \
   /www/sites/.../wp-content/themes/subo2026/includes/acf
```

## 验证升级

升级后，访问主题的调试页面或使用以下脚本：

```php
<?php
// 创建临时文件：check-acf-pro.php
require_once('wp-load.php');

echo "<h2>ACF Pro 验证</h2>";

// 1. 检查 Pro 常量
echo "1. ACF_PRO 常量: ";
echo defined('ACF_PRO') ? '✅ YES' : '❌ NO';
echo "<br>";

// 2. 检查 Pro 函数
$pro_functions = [
    'acf_add_options_page',
    'acf_register_block_type',
    'acf_get_loop',
];

foreach ($pro_functions as $func) {
    echo "function_exists('$func'): ";
    echo function_exists($func) ? '✅ YES' : '❌ NO';
    echo "<br>";
}

// 3. 检查 Pro 目录
$pro_path = get_template_directory() . '/includes/acf/pro/acf-pro.php';
echo "Pro 文件存在: ";
echo file_exists($pro_path) ? '✅ YES' : '❌ NO';
echo "<br>";

// 4. 显示版本
if (defined('ACF_VERSION')) {
    echo "ACF 版本: " . ACF_VERSION;
    echo defined('ACF_PRO') ? ' (Pro)' : ' (Free)';
}
?>
```

## 预期结果

升级成功后，应该看到：

```
✅ defined('ACF_PRO'): YES
✅ function_exists('acf_add_options_page'): YES
✅ function_exists('acf_register_block_type'): YES
✅ Pro 文件存在: YES
✅ ACF 版本: 6.8.6 (Pro)
```

## 如果仍然无法使用 Pro 功能

### 检查许可证

ACF Pro 需要有效的许可证密钥：

```php
// 在 functions.php 中添加（如果需要）
define('ACF_PRO_LICENSE', 'your-license-key-here');
```

### 检查加载顺序

确保 `functions.php` 中的加载顺序正确：

```php
// 1. 先定义路径
define( 'SUBO4_ACF_PATH', get_template_directory() . '/includes/acf/' );

// 2. 再加载 ACF
if ( file_exists( SUBO4_ACF_PATH . 'acf.php' ) ) {
    require_once SUBO4_ACF_PATH . 'acf.php';
}

// 3. 最后加载集成文件
require_once get_template_directory() . '/includes/functions/acf-integration.php';
```

### 清除缓存

```bash
# WordPress 对象缓存
wp cache flush

# Opcache（如果启用）
# 在 PHP 文件中执行：
<?php opcache_reset(); ?>
```

## 获取 ACF Pro

如果还没有购买：

- **官网**：https://www.advancedcustomfields.com/pro/
- **个人许可**：$49 USD/年（1 个网站）
- **无限许可**：$249 USD/年（无限网站）

## 常见问题

### Q: 升级会丢失数据吗？

A: 不会。ACF 的字段配置和数据都存储在数据库中，升级不会影响现有数据。

### Q: 可以先使用免费版吗？

A: 可以，但主题的以下功能会不可用：
- 主题选项页面（Options Page）
- 某些高级字段类型
- 部分页面构建功能

### Q: 升级后需要重新配置吗？

A: 不需要。Pro 版完全兼容免费版的所有配置。

## 支持

- **ACF 官方文档**：https://www.advancedcustomfields.com/resources/
- **主题文档**：`docs/ACF_INSTALLATION.md`

---

**创建时间**: 2026-07-25  
**相关文档**: ACF_INSTALLATION.md
