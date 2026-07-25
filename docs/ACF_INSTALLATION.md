# ACF Pro 手动安装指南

## 概述

本主题需要 **Advanced Custom Fields (ACF) Pro** 插件才能正常工作。由于 ACF Pro 是付费插件且不能公开分发，需要手动安装。

## 为什么需要 ACF Pro？

主题使用 ACF Pro 管理以下自定义字段：

- **文章元数据**：发布日期、更新日期、阅读时长等
- **SEO 设置**：自定义描述、关键词等
- **页面布局**：灵活内容区域、组件配置
- **媒体增强**：图片说明、版权信息等

## 安装方法

### 方法 1：通过 FTP/SFTP 上传（推荐）

1. **获取 ACF Pro 插件**
   - 访问 [ACF Pro 官网](https://www.advancedcustomfields.com/pro/)
   - 登录你的账户并下载最新版本
   - 解压下载的 ZIP 文件

2. **上传到服务器**
   ```
   本地路径：advanced-custom-fields-pro/
   服务器路径：/www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/acf/
   ```

3. **验证文件结构**
   ```
   includes/acf/
   ├── acf.php                    # 主文件
   ├── includes/                  # 核心文件
   ├── assets/                    # 资源文件
   ├── lang/                      # 语言文件
   └── pro/                       # Pro 功能
   ```

### 方法 2：使用 rsync 同步

如果你有 SSH 访问权限：

```bash
# 从本地同步到服务器
rsync -avz --progress advanced-custom-fields-pro/ \
  user@server:/www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/acf/
```

### 方法 3：在服务器上直接下载

```bash
# SSH 到服务器
ssh user@server

# 进入主题目录
cd /www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/

# 下载并解压（需要你的 ACF Pro 下载链接）
wget "你的ACF_Pro下载链接" -O acf-pro.zip
unzip acf-pro.zip -d acf
rm acf-pro.zip

# 设置正确的权限
chmod -R 755 acf/
```

## 验证安装

安装完成后，检查以下内容：

### 1. 文件权限

```bash
# SSH 到服务器执行
ls -la /www/sites/zhangsubo.cn/index/wp-content/themes/subo2026/includes/acf/acf.php
```

应该显示文件存在且可读。

### 2. WordPress 后台

访问 WordPress 后台，你应该能看到：

- ✅ 左侧菜单出现 "自定义字段" 或 "ACF"
- ✅ 无错误提示
- ✅ 可以正常创建和编辑字段组

### 3. 主题功能

在文章编辑页面，你应该能看到：

- ✅ 文章元数据字段组
- ✅ 自定义字段正常显示和保存
- ✅ 前端页面正确显示字段内容

## 常见问题

### Q: 如何获取 ACF Pro？

A: ACF Pro 是付费插件，需要从官网购买：
- 个人许可：$49 USD/年（单站点）
- 无限许可：$249 USD/年（无限站点）

### Q: 可以使用免费版 ACF 吗？

A: 不建议。主题使用了 Pro 版的高级功能（如 Repeater、Flexible Content、Options Page 等）。免费版会导致部分功能不可用。

### Q: 安装后看不到 ACF 菜单？

A: 检查 `functions.php` 中的设置：

```php
// 确保这行存在且返回 true
add_filter('acf/settings/show_admin', '__return_true');
```

### Q: 前端显示字段为空？

A: 主题已实现安全机制，当 ACF 未安装时会使用默认值。安装 ACF 后需要：
1. 编辑文章
2. 填写自定义字段
3. 保存后刷新前端

### Q: 更新主题时会丢失 ACF 吗？

A: 不会。ACF 位于 `includes/acf/` 目录，该目录已添加到 `.gitignore`，不会被版本控制覆盖。

### Q: 如何备份 ACF 配置？

A: ACF 字段组配置存储在数据库中，建议：
1. 使用 ACF 的导出功能导出为 JSON
2. 将 JSON 文件保存到 `acf-json/` 目录（已包含在主题中）
3. 定期备份数据库

## 技术说明

### 为什么 ACF 不包含在主题中？

1. **许可限制**：ACF Pro 是付费插件，不能公开分发
2. **更新灵活性**：独立管理方便及时更新安全补丁
3. **代码精简**：减少仓库体积（ACF 约 10MB+）

### 主题如何处理 ACF 缺失？

主题实现了完整的兼容层：

```php
// 安全的字段获取函数
function subo_get_field($field_name, $post_id = null, $default = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        return $value !== false ? $value : $default;
    }
    return $default;
}
```

所有模板文件都使用辅助函数，确保：
- ✅ ACF 未安装时不会报错
- ✅ 显示合理的默认值
- ✅ 不影响其他主题功能

## 支持

如有问题，请检查：

1. **官方文档**：https://www.advancedcustomfields.com/resources/
2. **主题文档**：本仓库 `docs/` 目录
3. **错误日志**：`/wp-content/debug.log`（需开启 WP_DEBUG）

## 版本要求

- **ACF Pro**: >= 6.0
- **WordPress**: >= 6.0
- **PHP**: >= 7.4

---

**最后更新**: 2026-07-25
