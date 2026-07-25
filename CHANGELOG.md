# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-07-25

### Added
- **国际化支持**: 全面支持多语言，提供中文翻译
  - 新增 `languages/subo4-classic-theme.pot` 翻译模板
  - 新增 `languages/zh_CN.po` 简体中文翻译
- **缓存机制**: 脚注功能添加 Transient API 缓存（7天）
- **图片懒加载**: 自动为文章图片添加 `loading="lazy"` 属性
- **可访问性**: 所有交互元素添加 ARIA 标签和语义化 HTML
- **辅助函数**: 新增 ACF 字段获取封装函数
  - `subo4_get_avatar()`
  - `subo4_get_author_name()`
  - `subo4_get_bio()`
  - `subo4_get_aboutme_content()`
  - `subo4_get_logo()`
- **配置管理**: 集中管理主题常量和默认值
  - `includes/theme-config.php`

### Changed
- **模块化重构**: `functions.php` 从 191 行减少到 57 行
  - 拆分为独立功能模块
  - `includes/functions/theme-setup.php` - 主题初始化
  - `includes/functions/acf-integration.php` - ACF 集成
  - `includes/functions/footnotes.php` - 脚注功能
- **移除内联样式**: 所有模板文件移除内联 `style` 属性
  - 新增对应 CSS 类到 `assets/css/theme.css`
  - 提升浏览器缓存效率

### Fixed
- **XSS 安全漏洞**: 脚注功能使用 DOMDocument 安全解析 HTML
  - 替换不安全的正则表达式和 `htmlspecialchars()`
  - 所有输出使用 WordPress 标准转义函数
  - 链接添加 `rel="nofollow noopener"` 属性
- **代码质量**: 遵循 WordPress Coding Standards
  - 统一使用英文注释
  - 添加 PHPDoc 文档注释
  - 函数命名规范化（`subo4_` 前缀）
- **错误处理**: 所有 ACF 函数调用添加存在性检查
  - `function_exists()` 检查
  - 提供降级方案和默认值
- **硬编码问题**: 移除模板中的硬编码默认值
  - 集中到配置文件
  - 支持国际化

### Security
- 所有用户输入和输出使用安全转义函数
- XSS 防护：DOMDocument 解析替代正则表达式
- CSRF 准备：添加 nonce 传递机制
- 权限检查：敏感信息显示前验证用户权限

### Performance
- 脚注功能缓存：使用 Transient API（可配置）
- 图片懒加载：自动优化页面加载速度
- CSS 优化：移除内联样式，提升缓存命中率
- 资源加载：正确管理依赖关系和版本号

### Documentation
- 新增 `CODE_REVIEW_FIXES.md` - 详细修复报告
- 新增 `FIXES_SUMMARY.md` - 快速总结
- 新增 `CODE_REVIEW_VALIDATION.md` - 验证报告
- 新增 `QUICK_REFERENCE.md` - 快速参考
- 新增 `.gitignore` - Git 忽略文件配置

## [1.0.0] - 2024-02-08

### Added
- 初始版本发布
- 基于 Bootstrap 5 的响应式设计
- ACF Pro 集成
- 社交链接支持
- 文章列表和详情页
- 导航菜单
- 页脚设置

[1.1.0]: https://github.com/zhangsubo/subo4.0/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/zhangsubo/subo4.0/releases/tag/v1.0.0
