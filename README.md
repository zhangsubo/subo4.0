# SUBO4.0 Theme

时隔九年，在大模型的加持下制作了一个新的blog 网站。主要使用[ Opencode](https://opencode.ai/zh)+[kimi K2.5](https://www.kimi.com/code) 完成，自己只是修改了一些文字而已。

基于 Bootstrap 5 的 WordPress 经典主题，灵感来自 hugo-zenHO 主题。简洁、优雅、响应式。

## 特性

- ✅ **Classic Theme** - WordPress 传统 PHP 模板主题
- ✅ **Bootstrap 5** - 现代化的响应式框架
- ✅ **ACF PRO 集成** - 内置高级自定义字段（需放置 ACF PRO 文件）
- ✅ **响应式设计** - 完美适配桌面、平板和手机
- ✅ **简洁优雅** - 极简主义设计风格
- ✅ **SEO 友好** - 语义化 HTML 结构
- ✅ **中文优化** - 针对中文内容优化排版

## 文件结构

```
subo2026/
├── style.css                 # 主题信息（必需）
├── style-min.css            # 主题样式（压缩版）
├── functions.php             # 主题功能
├── index.php                 # 首页/文章列表
├── home.php                  # 自定义首页模板
├── single.php                # 文章详情页
├── page.php                  # 页面模板
├── archive.php               # 归档页
├── tags.php                  # 标签页
├── header.php                # 页头模板
├── footer.php                # 页脚模板
├── screenshot.png            # 主题截图
├── README.md                 # 说明文档
├── assets/
│   ├── css/
│   │   ├── theme.css        # 主题样式（源文件）
│   │   ├── theme-min.css    # 主题样式（压缩版）
│   │   ├── admin.css        # 后台样式（源文件）
│   │   ├── admin-min.css    # 后台样式（压缩版）
│   │   ├── editor-style.css # 编辑器样式（源文件）
│   │   └── editor-style-min.css # 编辑器样式（压缩版）
│   ├── js/
│   │   ├── theme.js         # 主题脚本（源文件）
│   │   └── theme-min.js     # 主题脚本（压缩版）
│   ├── fonts/               # 字体文件
│   │   ├── Roboto-*.ttf     # Roboto 字体
│   │   └── open-sans-*.woff2 # Open Sans 字体
│   └── images/              # 图片资源
│       └── avatar.jpg       # 默认头像
├── includes/
│   ├── acf/                 # ACF PRO 插件（需放置）
│   ├── template-tags.php    # 模板标签
│   ├── customizer.php       # 自定义器设置
│   └── zsuper-framework/    # Zsuper 框架
│       └── remove-head.php  # 移除头部冗余代码
├── acf-fields/
│   └── theme-settings.php   # ACF 字段配置
└── template-parts/
    └── social-links.php     # 社交链接模板
```

## 安装

### 1. 上传主题

1. 将主题文件夹上传到 `/wp-content/themes/`
2. 在 WordPress 后台 **外观 → 主题** 中激活主题

### 2. 放置 ACF PRO（重要）

因为后台配置我使用[ACF PRO](https://www.advancedcustomfields.com/pro/) 来实现。在使用主题前，你可能需要放置 ACF PRO 插件文件：

```
将 ACF PRO 插件文件复制到：
/wp-content/themes/subo2026/includes/acf/

确保 acf.php 文件位于该目录
```

## 配置

### 主题设置

进入 **WordPress 后台 → 主题设置**，可以配置：

**个人资料**
- 头像图片
- 作者名称
- 个人简介
- 关于我内容（显示在文章详情页）

**社交链接**
- GitHub URL
- Twitter URL
- Facebook URL
- 邮箱地址
- 微博 URL
- 微信公众号（二维码）
- 微信个人号（二维码）

**页脚设置**
- ICP 备案号

- 投诉举报邮箱

  

### 导航菜单

在 **外观 → 菜单** 中创建菜单并分配到 "主导航菜单" 位置。



## 技术栈

- **WordPress**: 5.0+
- **Bootstrap**: 5.3.2
- **ACF PRO**: 6.x
- **PHP**: 7.4+

## 浏览器支持

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 许可证

GPL v3.0 or later

## 致谢

- 设计灵感来自 [hugo-zenHO](https://github.com/vran-dev/hugo-zenHo)
- Bootstrap 5 框架
- WordPress 团队
- [ Opencode](https://opencode.ai/zh)
- [kimi K2.5](https://www.kimi.com/code) 
- [FLYENV](https://flyenv.com/)

## 支持

如有问题，可以提 issue
