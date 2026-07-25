# 快速参考 - 代码审查修复

## 🚀 快速开始

### 1. 检查文件
```bash
git status
# 应看到修改的和新增的文件
```

### 2. 测试主题
访问你的 WordPress 网站：
- ✓ 首页正常显示
- ✓ 文章页面脚注功能工作
- ✓ 社交链接二维码弹出
- ✓ 移动端响应式正常

### 3. 清除缓存
在 WordPress 后台：
- 清除对象缓存
- 清除页面缓存（如使用缓存插件）
- 浏览器强制刷新 (Ctrl/Cmd + Shift + R)

---

## 📁 重要文件说明

### 新增的核心文件

```
includes/theme-config.php
```
- 主题配置常量
- 默认值定义
- 功能开关（如脚注缓存）

```
includes/functions/theme-setup.php
```
- 主题初始化
- 资源加载（CSS/JS）
- 导航菜单配置

```
includes/functions/acf-integration.php
```
- ACF 辅助函数
- 字段获取封装
- 降级处理

```
includes/functions/footnotes.php
```
- 安全的脚注功能
- DOMDocument 解析
- 缓存机制

---

## 🔧 修改的关键函数

### 旧函数 → 新函数

| 旧函数 | 新函数 | 说明 |
|-------|-------|------|
| `convert_links_to_footnotes()` | `subo4_convert_links_to_footnotes()` | 使用 DOMDocument |
| - | `subo4_get_avatar()` | 获取头像（带 fallback）|
| - | `subo4_get_author_name()` | 获取作者名 |
| - | `subo4_get_bio()` | 获取简介 |
| - | `subo4_get_aboutme_content()` | 获取关于内容 |

---

## ⚠️ 可能需要的调整

### 如果子主题继承了本主题

检查子主题是否调用了旧函数：
```bash
grep -r "convert_links_to_footnotes" your-child-theme/
```

如果有，更新为 `subo4_convert_links_to_footnotes()`

### 如果需要禁用脚注功能

编辑 `includes/theme-config.php`:
```php
define( 'SUBO4_ENABLE_FOOTNOTES', false );
```

### 如果需要禁用脚注缓存

编辑 `includes/theme-config.php`:
```php
define( 'SUBO4_FOOTNOTES_CACHE', false );
```

---

## 🐛 故障排除

### 脚注不显示
1. 检查文章是否包含链接
2. 清除缓存（Transient）
3. 检查 PHP 错误日志

### 样式错乱
1. 清除浏览器缓存
2. 检查 CSS 文件是否正确加载
3. 查看浏览器控制台错误

### ACF 字段不显示
1. 确认 ACF Pro 已激活
2. 检查主题设置页面是否能访问
3. 查看 PHP 错误日志

---

## 📊 性能监控

### 检查缓存是否工作
```sql
-- 在 phpMyAdmin 或数据库工具中运行
SELECT * FROM wp_options 
WHERE option_name LIKE '%transient_subo4_footnotes%' 
LIMIT 10;
```

应该看到缓存的脚注数据。

### 监控页面加载时间
使用浏览器开发者工具 → Network 标签：
- HTML 加载时间
- CSS/JS 加载时间
- 图片懒加载是否生效

---

## ✅ 验证清单

在部署到生产环境前：

- [ ] 所有页面正常显示
- [ ] 脚注功能正常工作
- [ ] 社交链接正常
- [ ] 导航菜单响应式正常
- [ ] 移动端测试通过
- [ ] 浏览器控制台无错误
- [ ] PHP 错误日志无警告
- [ ] 性能测试通过（GTmetrix/PageSpeed）
- [ ] 备份已完成

---

## 📚 相关文档

- `CODE_REVIEW_FIXES.md` - 详细修复报告（9KB）
- `FIXES_SUMMARY.md` - 简短总结（2.5KB）
- `CODE_REVIEW_VALIDATION.md` - 验证报告（详细）
- `QUICK_REFERENCE.md` - 本文件

---

## 💡 提示

### Git 提交建议
```bash
git add .
git commit -m "fix: 修复代码审查发现的12个问题

- 修复XSS安全漏洞（使用DOMDocument）
- 添加国际化支持（中文翻译）
- 模块化重构functions.php
- 移除内联样式
- 添加缓存和懒加载
- 提升可访问性（ARIA标签）
- 统一代码规范

详见 CODE_REVIEW_FIXES.md"
```

### 版本号更新
如果要发布新版本，更新以下文件：
- `style.css` (主题头部)
- `includes/theme-config.php` (SUBO4_THEME_VERSION)
- `README.md`

---

**最后更新**: 2026-07-25  
**主题版本**: 1.1.0
