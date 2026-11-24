<?php
/**
 * ACF 作品信息与作品展示页字段定义
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'acf_add_local_field_group' ) ) :

    // 1. 单个作品字段组
    acf_add_local_field_group( array(
        'key'                   => 'group_subo4_project_meta',
        'title'                 => '作品信息与展示设置',
        'fields'                => array(
            // Tab 1: 基础信息
            array(
                'key'               => 'field_subo4_project_tab_basic',
                'label'             => '基础信息',
                'name'              => '',
                'type'              => 'tab',
                'placement'         => 'left',
                'endpoint'          => 0,
            ),
            array(
                'key'               => 'field_subo4_project_icon',
                'label'             => '应用图标',
                'name'              => 'subo4_project_icon',
                'type'              => 'image',
                'instructions'      => '建议上传正方形透明背景或圆角图标（如 256×256 PNG/WebP）；留空将自动显示作品首字母圆角图标。',
                'required'          => 0,
                'return_format'     => 'url',
                'preview_size'      => 'thumbnail',
                'library'           => 'all',
            ),
            array(
                'key'               => 'field_subo4_project_summary',
                'label'             => '一句话简介',
                'name'              => 'subo4_project_summary',
                'type'              => 'textarea',
                'instructions'      => '显示在作品卡片标题下方；留空时自动读取文章摘要或正文前 90 字。',
                'required'          => 0,
                'rows'              => 3,
                'new_lines'         => '',
            ),
            array(
                'key'               => 'field_subo4_project_primary_badge',
                'label'             => '主平台 / 形态标签',
                'name'              => 'subo4_project_primary_badge',
                'type'              => 'text',
                'instructions'      => '卡片右上角蓝色高亮胶囊，如运行平台或产品形态。',
                'required'          => 0,
                'placeholder'       => '如：Web App / macOS / Chrome 插件 / AI Agent',
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_tech_tags',
                'label'             => '技术栈标签',
                'name'              => 'subo4_project_tech_tags',
                'type'              => 'text',
                'instructions'      => '多个标签用逗号分隔，展示在主标签右侧。',
                'required'          => 0,
                'placeholder'       => '如：Next.js, Tailwind, Claude',
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_version',
                'label'             => '当前版本',
                'name'              => 'subo4_project_version',
                'type'              => 'text',
                'instructions'      => '可选，展示在卡片底部状态行。',
                'required'          => 0,
                'placeholder'       => '如：v1.0.0',
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_status',
                'label'             => '作品状态',
                'name'              => 'subo4_project_status',
                'type'              => 'select',
                'instructions'      => '显示在卡片底部状态行（带状态圆点）。',
                'required'          => 0,
                'choices'           => array(
                    'live'        => '已上线',
                    'open_source' => '开源',
                    'beta'        => '公测中',
                    'wip'         => '持续迭代',
                    'experiment'  => '实验作品',
                    'archived'    => '已归档',
                ),
                'default_value'     => 'live',
                'allow_null'        => 1,
                'ui'                => 0,
                'return_format'     => 'value',
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_meta_note',
                'label'             => '底部补充说明',
                'name'              => 'subo4_project_meta_note',
                'type'              => 'text',
                'instructions'      => '可选，接在状态与版本后方（例如：开源免费 · 2026.11）。',
                'required'          => 0,
                'placeholder'       => '如：周末 Vibe Coding · 开源免费',
            ),

            // Tab 2: 精选与截图
            array(
                'key'               => 'field_subo4_project_tab_featured',
                'label'             => '精选与截图',
                'name'              => '',
                'type'              => 'tab',
                'placement'         => 'left',
                'endpoint'          => 0,
            ),
            array(
                'key'               => 'field_subo4_project_featured',
                'label'             => '设为精选推荐通栏大卡',
                'name'              => 'subo4_project_featured',
                'type'              => 'true_false',
                'instructions'      => '开启后该作品将自动排在作品列表顶部，以双栏通栏大图卡形式重点展示（建议精选 1 个代表作）。',
                'required'          => 0,
                'default_value'     => 0,
                'ui'                => 1,
                'ui_on_text'        => '精选',
                'ui_off_text'       => '常规',
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_featured_label',
                'label'             => '精选徽章文案',
                'name'              => 'subo4_project_featured_label',
                'type'              => 'text',
                'required'          => 0,
                'default_value'     => '精选推荐',
                'placeholder'       => '精选推荐',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_subo4_project_featured',
                            'operator' => '==',
                            'value'    => '1',
                        ),
                    ),
                ),
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_screenshot',
                'label'             => '作品界面截图',
                'name'              => 'subo4_project_screenshot',
                'type'              => 'image',
                'instructions'      => '用于精选大卡右侧与作品详情页头图展示（建议 16:10 或 16:9 横图）；留空时将自动使用右侧「特色图片」。',
                'required'          => 0,
                'return_format'     => 'url',
                'preview_size'      => 'medium',
                'library'           => 'all',
            ),
            array(
                'key'               => 'field_subo4_project_metrics',
                'label'             => '核心亮点指标（最多 3 项）',
                'name'              => 'subo4_project_metrics',
                'type'              => 'repeater',
                'instructions'      => '在精选通栏大卡和作品详情页中部展示 3 格数据亮点条，如构建耗时、体积、特性等。',
                'required'          => 0,
                'min'               => 0,
                'max'               => 3,
                'layout'            => 'table',
                'button_label'      => '添加亮点指标',
                'collapsed'         => 'field_subo4_project_metric_value',
                'sub_fields'        => array(
                    array(
                        'key'         => 'field_subo4_project_metric_value',
                        'label'       => '指标数值 / 关键词',
                        'name'        => 'metric_value',
                        'type'        => 'text',
                        'placeholder' => '如：~210 MB / 100% 本地 / 3 小时',
                    ),
                    array(
                        'key'         => 'field_subo4_project_metric_label',
                        'label'       => '指标说明',
                        'name'        => 'metric_label',
                        'type'        => 'text',
                        'placeholder' => '如：整体内存 / 数据不出端 / Vibe 构建',
                    ),
                ),
            ),

            // Tab 3: 操作按钮
            array(
                'key'               => 'field_subo4_project_tab_actions',
                'label'             => '操作按钮',
                'name'              => '',
                'type'              => 'tab',
                'placement'         => 'left',
                'endpoint'          => 0,
            ),
            array(
                'key'               => 'field_subo4_project_primary_btn_label',
                'label'             => '主按钮文案',
                'name'              => 'subo4_project_primary_btn_label',
                'type'              => 'text',
                'instructions'      => '黑色胶囊主按钮，留空且填写了链接时默认显示「访问项目」。',
                'required'          => 0,
                'placeholder'       => '如：在线体验 / ↓ 下载 v1.0 / GitHub',
                'wrapper'           => array(
                    'width' => '35',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_primary_btn_url',
                'label'             => '主按钮链接',
                'name'              => 'subo4_project_primary_btn_url',
                'type'              => 'url',
                'instructions'      => '体验地址、安装包下载或仓库地址；留空则隐藏黑色主按钮。',
                'required'          => 0,
                'placeholder'       => 'https://...',
                'wrapper'           => array(
                    'width' => '65',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_detail_mode',
                'label'             => '「了解更多」跳转方式',
                'name'              => 'subo4_project_detail_mode',
                'type'              => 'select',
                'instructions'      => '控制卡片右下角「了解更多 →」的跳转目标。',
                'required'          => 0,
                'choices'           => array(
                    'single' => '跳转到本站作品详情页',
                    'custom' => '跳转到自定义链接（如博客文章或外链）',
                    'none'   => '不显示「了解更多」入口',
                ),
                'default_value'     => 'single',
                'allow_null'        => 0,
                'ui'                => 0,
                'return_format'     => 'value',
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_secondary_btn_label',
                'label'             => '次操作文案',
                'name'              => 'subo4_project_secondary_btn_label',
                'type'              => 'text',
                'required'          => 0,
                'default_value'     => '了解更多',
                'placeholder'       => '了解更多',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_subo4_project_detail_mode',
                            'operator' => '!=',
                            'value'    => 'none',
                        ),
                    ),
                ),
                'wrapper'           => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'               => 'field_subo4_project_custom_detail_url',
                'label'             => '自定义详情链接',
                'name'              => 'subo4_project_custom_detail_url',
                'type'              => 'url',
                'instructions'      => '填写文章地址或外部介绍页 URL。',
                'required'          => 0,
                'placeholder'       => 'https://...',
                'conditional_logic' => array(
                    array(
                        array(
                            'field'    => 'field_subo4_project_detail_mode',
                            'operator' => '==',
                            'value'    => 'custom',
                        ),
                    ),
                ),
            ),
        ),
        'location'              => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'subo4_project',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
    ) );

    // 2. 作品展示页面模板设置字段组
    acf_add_local_field_group( array(
        'key'                   => 'group_subo4_showcase_page',
        'title'                 => '作品展示页设置',
        'fields'                => array(
            array(
                'key'           => 'field_subo4_showcase_eyebrow',
                'label'         => '英文眉标 (Eyebrow)',
                'name'          => 'subo4_showcase_eyebrow',
                'type'          => 'text',
                'instructions'  => '显示在主标题上方的蓝色小型英文标识。',
                'required'      => 0,
                'default_value' => 'VIBE CODING',
                'placeholder'   => 'VIBE CODING / APPS',
                'wrapper'       => array(
                    'width' => '50',
                ),
            ),
            array(
                'key'           => 'field_subo4_showcase_show_toolbar',
                'label'         => '显示分类与搜索栏',
                'name'          => 'subo4_showcase_show_toolbar',
                'type'          => 'true_false',
                'required'      => 0,
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => array(
                    'width' => '25',
                ),
            ),
            array(
                'key'           => 'field_subo4_showcase_show_count',
                'label'         => '显示作品数量',
                'name'          => 'subo4_showcase_show_count',
                'type'          => 'true_false',
                'required'      => 0,
                'default_value' => 1,
                'ui'            => 1,
                'wrapper'       => array(
                    'width' => '25',
                ),
            ),
            array(
                'key'           => 'field_subo4_showcase_subtitle',
                'label'         => '页面副标题',
                'name'          => 'subo4_showcase_subtitle',
                'type'          => 'textarea',
                'instructions'  => '显示在大标题下方的一两句说明；留空则只显示标题。',
                'required'      => 0,
                'rows'          => 2,
                'new_lines'     => '',
                'placeholder'   => '用 AI 协同打磨的个人工具、效率插件与实验性软件作品。',
            ),
        ),
        'location'              => array(
            array(
                array(
                    'param'    => 'page_template',
                    'operator' => '==',
                    'value'    => 'page-vibe-showcase.php',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
    ) );

endif;
