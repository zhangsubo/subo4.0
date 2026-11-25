# SUBO4.0 Theme

时隔九年，在大模型的加持下制作了一个新的 blog 网站。主要使用 [Opencode](https://opencode.ai/zh) + [kimi K2.5](https://www.kimi.com/code) 完成，并在 v2.0 升级中彻底移除 ACF PRO 与 Bootstrap 5 依赖，精简为纯原生 WordPress 经典主题。

灵感来自 hugo-zenHO 主题。简洁、优雅、响应式。

## 特性

- ✅ **Classic Theme** - 纯原生 WordPress 经典 PHP 模板主题，零第三方插件/框架依赖
- ✅ **原生后台设置** - 基于 WordPress Settings API 与原生媒体库的「主题设置」页，支持分 Tab 配置
- ✅ **原生作品集模块** - 内置 Vibe Coding 作品 CPT (`subo4_project`)、原生 Meta Box 与前端实时筛选/搜索
- ✅ **纯原生 CSS / JS** - 无外部前端框架依赖，轻量响应式排版与原生二维码弹窗
- ✅ **阅读增强** - 文章自动目录（TOC）、按需加载代码块高亮与复制、图片灯箱与正文外链脚注转换
- ✅ **SEO 与中文优化** - 语义化 HTML、Open Graph / Twitter Card 元标签与中文阅读时长/排版优化

## 文件结构

```
subo2026/
├── style.css                      # 主题信息（必需）
├── functions.php                  # 主题入口
├── index.php                      # 默认列表模板
├── home.php                       # 首页模板
├── single.php                     # 文章详情页
├── page.php                       # 页面模板
├── archive.php                    # 归档页
├── tags.php                       # 标签云页面模板
├── search.php                     # 站内搜索结果页
├── 404.php                        # 404 错误页
├── page-vibe-showcase.php         # Vibe Coding 作品展示页模板
├── single-subo4_project.php       # 作品详情页模板
├── header.php                     # 页头模板
├── footer.php                     # 页脚模板
├── screenshot.png                 # 主题截图
├── README.md                      # 说明文档
├── assets/
│   ├── css/
│   │   ├── theme.css                # 主题主样式（纯原生 CSS）
│   │   ├── showcase.css             # 作品展示样式
│   │   ├── reading-enhancements.css # 阅读增强样式（TOC/代码块/灯箱）
│   │   ├── admin.css                # 后台设置与 Meta Box 样式
│   │   └── editor-style.css         # 编辑器样式
│   ├── js/
│   │   ├── theme.js                 # 主题脚本（纯原生 JS）
│   │   ├── showcase.js              # 作品筛选与搜索脚本
│   │   ├── reading-enhancements.js  # 阅读增强脚本（TOC/代码块/灯箱）
│   │   └── admin.js                 # 后台 Tab、媒体库选择器与 Meta Box 联动脚本
│   └── images/
│       └── avatar.jpg               # 默认头像
├── includes/
│   ├── theme-config.php             # 主题常量与按需 CDN/SRI 配置
│   └── functions/
│       ├── theme-setup.php          # 主题初始化、Head 精简、资源入队、分页与阅读时长
│       ├── theme-settings.php       # 原生「主题设置」选项页与兼容读取辅助函数
│       ├── footnotes.php            # 正文外链脚注转换
│       ├── projects-cpt.php         # 作品 CPT、分类法与数据组装
│       ├── project-metaboxes.php    # 作品与展示页原生 Meta Box（仅后台加载）
│       └── seo-meta.php             # Open Graph / Twitter Card 元标签
└── template-parts/
    ├── post-item.php                # 文章列表卡片部件
    ├── profile-card.php             # 个人资料卡片部件
    ├── project-card.php             # 作品卡片部件
    └── social-links.php             # 社交链接与二维码弹窗模板
```

## 安装与配置

### 1. 上传并启用主题

1. 将主题文件夹上传到 `/wp-content/themes/`
2. 在 WordPress 后台 **外观 → 主题** 中激活主题，无需安装额外插件

### 2. 主题设置

进入 **WordPress 后台 → Theme Settings（主题设置）**，可按 Tab 配置：

- **个人资料**：头像图片、网站 Logo、作者名称、个人简介/签名、关于我内容（显示在文章详情页底部）
- **社交链接**：GitHub、Twitter、Facebook、邮箱地址、微博、微信公众号二维码、个人微信二维码
- **页脚设置**：ICP 备案号、投诉举报邮箱

> 注：兼容从旧版本升级，若未在原生设置页保存过，仍会自动回退读取旧版 `options_SUBO4_*` 与旧版作品自定义字段数据。

### 3. 导航菜单与作品展示

- 在 **外观 → 菜单** 中创建菜单并分配到「Primary Menu」位置。
- 在 **作品 → 添加作品** 中录入 Vibe Coding 作品信息；新建页面并选择「Vibe Coding 作品展示」模板即可展示全部作品。

## 技术栈

- **WordPress**: 5.0+（已在 6.7+ 验证）
- **PHP**: 7.4+（本地 PHP 8.4 验证）
- **前端**: 原生 HTML / CSS / JavaScript（仅代码文章按需加载 `highlight.js`）

## 浏览器支持

- Chrome / Edge / Firefox / Safari（latest）

## 许可证

GPL v2 or later

## 致谢

- 设计灵感来自 [hugo-zenHO](https://github.com/vran-dev/hugo-zenHo)
- WordPress 团队
- [Opencode](https://opencode.ai/zh) / [kimi K2.5](https://www.kimi.com/code)
- [FLYENV](https://flyenv.com/)
