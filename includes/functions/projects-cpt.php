<?php
/**
 * Vibe Coding 作品集自定义文章类型、分类法与数据辅助函数
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 注册作品 CPT (subo4_project) 与作品分类 (subo4_project_cat)
 */
function subo4_register_project_post_type() {
    $project_labels = array(
        'name'                  => '作品',
        'singular_name'         => '作品',
        'menu_name'             => '作品',
        'name_admin_bar'        => '作品',
        'all_items'             => '全部作品',
        'add_new'               => '添加作品',
        'add_new_item'          => '添加新作品',
        'edit_item'             => '编辑作品',
        'new_item'              => '新作品',
        'view_item'             => '查看作品',
        'view_items'            => '查看作品',
        'search_items'          => '搜索作品',
        'not_found'             => '暂无作品',
        'not_found_in_trash'    => '回收站中暂无作品',
        'featured_image'        => '作品预览图（特色图片）',
        'set_featured_image'    => '设置作品预览图',
        'remove_featured_image' => '移除作品预览图',
        'use_featured_image'    => '设为作品预览图',
    );

    register_post_type( 'subo4_project', array(
        'labels'             => $project_labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_nav_menus'  => true,
        'show_in_rest'       => true,
        'query_var'          => true,
        'has_archive'        => false, // 避免与页面 /projects/ 或 /apps/ 的 slug 冲突
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-screenoptions',
        'rewrite'            => array(
            'slug'       => 'project',
            'with_front' => false,
        ),
        'supports'           => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'page-attributes',
            'comments',
        ),
        'taxonomies'         => array( 'subo4_project_cat' ),
    ) );

    $cat_labels = array(
        'name'              => '作品分类',
        'singular_name'     => '作品分类',
        'menu_name'         => '作品分类',
        'all_items'         => '全部作品分类',
        'edit_item'         => '编辑作品分类',
        'view_item'         => '查看作品分类',
        'update_item'       => '更新作品分类',
        'add_new_item'      => '添加新作品分类',
        'new_item_name'     => '新作品分类名称',
        'parent_item'       => '父级分类',
        'parent_item_colon' => '父级分类：',
        'search_items'      => '搜索作品分类',
        'not_found'         => '暂无作品分类',
    );

    register_taxonomy( 'subo4_project_cat', array( 'subo4_project' ), array(
        'labels'            => $cat_labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => false,
        'show_in_rest'      => true,
        'rewrite'           => array(
            'slug'       => 'project-cat',
            'with_front' => false,
        ),
    ) );

    // 首次注册后自动刷新固定链接一次
    if ( get_option( 'subo4_project_rewrite_ver' ) !== '1' ) {
        flush_rewrite_rules( false );
        update_option( 'subo4_project_rewrite_ver', '1' );
    }
}
add_action( 'init', 'subo4_register_project_post_type' );

/**
 * 切换主题时刷新固定链接
 */
function subo4_projects_flush_rewrites() {
    subo4_register_project_post_type();
    flush_rewrite_rules( false );
}
add_action( 'after_switch_theme', 'subo4_projects_flush_rewrites' );

/**
 * 为作品展示页与作品详情页追加宽栏 body class
 *
 * @param array $classes Body classes.
 * @return array
 */
function subo4_showcase_body_class( $classes ) {
    if ( is_page_template( 'page-vibe-showcase.php' ) || is_singular( 'subo4_project' ) ) {
        $classes[] = 'subo4-showcase-wide';
    }
    return $classes;
}
add_filter( 'body_class', 'subo4_showcase_body_class' );

/**
 * 后台作品列表增加精选、版本/状态、排序列
 *
 * @param array $columns Columns.
 * @return array
 */
function subo4_project_admin_columns( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $label ) {
        $new_columns[ $key ] = $label;
        if ( 'taxonomy-subo4_project_cat' === $key ) {
            $new_columns['subo4_featured'] = '精选';
            $new_columns['subo4_meta']     = '版本 / 状态';
            $new_columns['subo4_order']    = '排序';
        }
    }

    if ( ! isset( $new_columns['subo4_featured'] ) ) {
        $new_columns['subo4_featured'] = '精选';
        $new_columns['subo4_meta']     = '版本 / 状态';
        $new_columns['subo4_order']    = '排序';
    }

    return $new_columns;
}
add_filter( 'manage_subo4_project_posts_columns', 'subo4_project_admin_columns' );

/**
 * 渲染后台作品列表自定义列内容
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function subo4_project_admin_column_content( $column, $post_id ) {
    if ( 'subo4_featured' === $column ) {
        $is_featured = (bool) subo4_get_project_field( 'subo4_project_featured', $post_id, false );
        echo $is_featured
            ? '<span style="display:inline-block;padding:2px 8px;border-radius:999px;background:#e6f0ff;color:#0066cc;font-weight:600;font-size:12px;">精选大卡</span>'
            : '<span style="color:#999;">—</span>';
    } elseif ( 'subo4_meta' === $column ) {
        $version    = trim( (string) subo4_get_project_field( 'subo4_project_version', $post_id, '' ) );
        $status_key = trim( (string) subo4_get_project_field( 'subo4_project_status', $post_id, '' ) );
        $status_map = subo4_get_project_status_map();
        $parts      = array();

        if ( '' !== $version ) {
            $parts[] = '<code>' . esc_html( $version ) . '</code>';
        }
        if ( '' !== $status_key && isset( $status_map[ $status_key ] ) ) {
            $parts[] = esc_html( $status_map[ $status_key ]['label'] );
        }

        echo ! empty( $parts ) ? implode( ' · ', $parts ) : '<span style="color:#999;">—</span>';
    } elseif ( 'subo4_order' === $column ) {
        $post = get_post( $post_id );
        echo $post ? absint( $post->menu_order ) : 0;
    }
}
add_action( 'manage_subo4_project_posts_custom_column', 'subo4_project_admin_column_content', 10, 2 );

/**
 * 注册后台排序列可点击排序
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function subo4_project_sortable_columns( $columns ) {
    $columns['subo4_order'] = 'menu_order';
    return $columns;
}
add_filter( 'manage_edit-subo4_project_sortable_columns', 'subo4_project_sortable_columns' );

/**
 * 安全读取单篇作品自定义字段（纯原生 postmeta，兼容旧版 ACF 平铺 Repeater 数据）
 *
 * @param string $field_name Field name.
 * @param int    $post_id    Post ID.
 * @param mixed  $default    Default value.
 * @return mixed
 */
function subo4_get_project_field( $field_name, $post_id, $default = '' ) {
    $post_id = (int) $post_id;
    if ( $post_id <= 0 ) {
        return $default;
    }

    // 指标数组：兼容原生序列化数组与旧 ACF Repeater 平铺存储
    if ( 'subo4_project_metrics' === $field_name ) {
        $raw = get_post_meta( $post_id, $field_name, true );
        if ( is_array( $raw ) ) {
            return $raw;
        }
        $count = is_numeric( $raw ) ? min( 3, max( 0, (int) $raw ) ) : 0;
        if ( $count > 0 ) {
            $metrics = array();
            for ( $i = 0; $i < $count; $i++ ) {
                $metrics[] = array(
                    'metric_value' => (string) get_post_meta( $post_id, "subo4_project_metrics_{$i}_metric_value", true ),
                    'metric_label' => (string) get_post_meta( $post_id, "subo4_project_metrics_{$i}_metric_label", true ),
                );
            }
            return $metrics;
        }
        return is_array( $default ) ? $default : array();
    }

    if ( is_bool( $default ) ) {
        if ( ! metadata_exists( 'post', $post_id, $field_name ) ) {
            return $default;
        }
        $value = get_post_meta( $post_id, $field_name, true );
        return in_array( (string) $value, array( '1', 'true', 'yes', 'on' ), true );
    }

    $value = get_post_meta( $post_id, $field_name, true );
    if ( null !== $value && false !== $value && '' !== $value ) {
        return $value;
    }

    return $default;
}

/**
 * 判断链接是否指向站外
 *
 * @param string $url Target URL.
 * @return bool
 */
function subo4_is_external_url( $url ) {
    if ( '' === $url || 0 === strpos( $url, '/' ) || 0 === strpos( $url, '#' ) ) {
        return false;
    }
    $target_host = wp_parse_url( $url, PHP_URL_HOST );
    $home_host   = wp_parse_url( home_url(), PHP_URL_HOST );
    if ( ! $target_host || ! $home_host ) {
        return false;
    }
    return strtolower( $target_host ) !== strtolower( $home_host );
}

/**
 * 作品状态选项与视觉色调映射
 *
 * @return array
 */
function subo4_get_project_status_map() {
    return array(
        'live'        => array(
            'label' => '已上线',
            'tone'  => 'ok',
        ),
        'open_source' => array(
            'label' => '开源',
            'tone'  => 'accent',
        ),
        'beta'        => array(
            'label' => '公测中',
            'tone'  => 'warn',
        ),
        'wip'         => array(
            'label' => '持续迭代',
            'tone'  => 'accent',
        ),
        'experiment'  => array(
            'label' => '实验作品',
            'tone'  => 'neutral',
        ),
        'archived'    => array(
            'label' => '已归档',
            'tone'  => 'neutral',
        ),
    );
}

/**
 * 组装单篇作品用于前端展示的规范化数组
 *
 * @param int $post_id Project post ID.
 * @return array
 */
function subo4_get_project_data( $post_id ) {
    $post = get_post( $post_id );
    if ( ! $post ) {
        return array();
    }

    $title     = get_the_title( $post_id );
    $permalink = get_permalink( $post_id );

    // 图标与首字兜底
    $icon_raw = subo4_get_project_field( 'subo4_project_icon', $post_id, '' );
    $icon_url = subo4_resolve_image_url( $icon_raw, 'thumbnail' );

    $plain_title = trim( wp_strip_all_tags( $title ) );
    $monogram    = '' !== $plain_title ? mb_substr( $plain_title, 0, 1, 'UTF-8' ) : 'V';
    if ( preg_match( '/^[a-z]$/', $monogram ) ) {
        $monogram = strtoupper( $monogram );
    }

    // 简介降级链路：ACF summary -> 摘要 -> 正文前 90 字
    $summary = trim( (string) subo4_get_project_field( 'subo4_project_summary', $post_id, '' ) );
    if ( '' === $summary ) {
        if ( has_excerpt( $post_id ) ) {
            $summary = trim( wp_strip_all_tags( $post->post_excerpt ) );
        } elseif ( ! empty( $post->post_content ) ) {
            $summary = wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 90, '...' );
        }
    }

    // 徽章与技术栈
    $primary_badge = trim( (string) subo4_get_project_field( 'subo4_project_primary_badge', $post_id, '' ) );
    $raw_tags      = trim( (string) subo4_get_project_field( 'subo4_project_tech_tags', $post_id, '' ) );
    $tech_tags     = array();
    if ( '' !== $raw_tags ) {
        $split     = preg_split( '/[,，、]+/u', $raw_tags );
        $tech_tags = array_values( array_filter( array_map( 'trim', (array) $split ), 'strlen' ) );
    }

    // 版本、状态与补充说明
    $version    = trim( (string) subo4_get_project_field( 'subo4_project_version', $post_id, '' ) );
    $status_key = trim( (string) subo4_get_project_field( 'subo4_project_status', $post_id, '' ) );
    $status_map = subo4_get_project_status_map();
    $status_label = '';
    $status_tone  = 'neutral';
    if ( '' !== $status_key && isset( $status_map[ $status_key ] ) ) {
        $status_label = $status_map[ $status_key ]['label'];
        $status_tone  = $status_map[ $status_key ]['tone'];
    }
    $meta_note = trim( (string) subo4_get_project_field( 'subo4_project_meta_note', $post_id, '' ) );

    // 精选设置与截图
    $is_featured    = (bool) subo4_get_project_field( 'subo4_project_featured', $post_id, false );
    $featured_label = trim( (string) subo4_get_project_field( 'subo4_project_featured_label', $post_id, '精选推荐' ) );
    if ( '' === $featured_label ) {
        $featured_label = '精选推荐';
    }

    $shot_raw       = subo4_get_project_field( 'subo4_project_screenshot', $post_id, '' );
    $screenshot_url = subo4_resolve_image_url( $shot_raw, 'large' );
    if ( '' === $screenshot_url ) {
        $thumb_url      = get_the_post_thumbnail_url( $post_id, 'large' );
        $screenshot_url = $thumb_url ? $thumb_url : '';
    }

    // 核心亮点指标（最多 3 项）
    $metrics_raw = subo4_get_project_field( 'subo4_project_metrics', $post_id, array() );
    $metrics     = array();
    if ( is_array( $metrics_raw ) ) {
        foreach ( $metrics_raw as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }
            $m_val   = isset( $row['metric_value'] ) ? trim( (string) $row['metric_value'] ) : '';
            $m_label = isset( $row['metric_label'] ) ? trim( (string) $row['metric_label'] ) : '';
            if ( '' === $m_val && '' === $m_label ) {
                continue;
            }
            $metrics[] = array(
                'value' => $m_val,
                'label' => $m_label,
            );
            if ( count( $metrics ) >= 3 ) {
                break;
            }
        }
    }

    // 主按钮与了解更多链接
    $primary_btn_url   = trim( (string) subo4_get_project_field( 'subo4_project_primary_btn_url', $post_id, '' ) );
    $primary_btn_label = trim( (string) subo4_get_project_field( 'subo4_project_primary_btn_label', $post_id, '' ) );
    if ( '' !== $primary_btn_url && '' === $primary_btn_label ) {
        $primary_btn_label = '访问项目';
    }

    $detail_mode = trim( (string) subo4_get_project_field( 'subo4_project_detail_mode', $post_id, 'single' ) );
    if ( ! in_array( $detail_mode, array( 'single', 'custom', 'none' ), true ) ) {
        $detail_mode = 'single';
    }

    $secondary_btn_label = trim( (string) subo4_get_project_field( 'subo4_project_secondary_btn_label', $post_id, '了解更多' ) );
    if ( '' === $secondary_btn_label ) {
        $secondary_btn_label = '了解更多';
    }

    $detail_url         = '';
    $detail_is_external = false;
    if ( 'single' === $detail_mode ) {
        $detail_url         = $permalink;
        $detail_is_external = false;
    } elseif ( 'custom' === $detail_mode ) {
        $detail_url         = trim( (string) subo4_get_project_field( 'subo4_project_custom_detail_url', $post_id, '' ) );
        $detail_is_external = subo4_is_external_url( $detail_url );
    }

    // 标题与预览图点击跳转：优先详情页，其次主按钮外链
    $title_url         = '';
    $title_is_external = false;
    if ( '' !== $detail_url ) {
        $title_url         = $detail_url;
        $title_is_external = $detail_is_external;
    } elseif ( '' !== $primary_btn_url ) {
        $title_url         = $primary_btn_url;
        $title_is_external = subo4_is_external_url( $primary_btn_url );
    }

    // 分类信息
    $terms          = get_the_terms( $post_id, 'subo4_project_cat' );
    $categories     = array();
    $category_slugs = array();
    $category_names = array();
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $categories[]     = array(
                'term_id' => (int) $term->term_id,
                'slug'    => $term->slug,
                'name'    => $term->name,
            );
            $category_slugs[] = $term->slug;
            $category_names[] = $term->name;
        }
    }

    // 聚合搜索文本
    $search_parts = array_merge(
        array(
            $plain_title,
            $summary,
            $primary_badge,
            $version,
            $status_label,
            $meta_note,
        ),
        $tech_tags,
        $category_names
    );
    $search_text = mb_strtolower(
        trim( implode( ' ', array_filter( $search_parts, 'strlen' ) ) ),
        'UTF-8'
    );

    return array(
        'id'                  => (int) $post_id,
        'title'               => $title,
        'permalink'           => $permalink,
        'icon_url'            => $icon_url,
        'monogram'            => $monogram,
        'summary'             => $summary,
        'primary_badge'       => $primary_badge,
        'tech_tags'           => $tech_tags,
        'version'             => $version,
        'status_key'          => $status_key,
        'status_label'        => $status_label,
        'status_tone'         => $status_tone,
        'meta_note'           => $meta_note,
        'is_featured'         => $is_featured,
        'featured_label'      => $featured_label,
        'screenshot_url'      => $screenshot_url,
        'metrics'             => $metrics,
        'primary_btn_url'     => $primary_btn_url,
        'primary_btn_label'   => $primary_btn_label,
        'primary_is_external' => subo4_is_external_url( $primary_btn_url ),
        'detail_mode'         => $detail_mode,
        'secondary_btn_label' => $secondary_btn_label,
        'detail_url'          => $detail_url,
        'detail_is_external'  => $detail_is_external,
        'title_url'           => $title_url,
        'title_is_external'   => $title_is_external,
        'categories'          => $categories,
        'category_slugs'      => implode( ' ', $category_slugs ),
        'search_text'         => $search_text,
    );
}

/**
 * 获取当前已发布的作品展示页 URL（用于详情页返回列表）
 *
 * @return string
 */
function subo4_get_projects_page_url() {
    static $cached_url = null;

    if ( null !== $cached_url ) {
        return $cached_url;
    }

    $pages = get_pages( array(
        'meta_key'    => '_wp_page_template',
        'meta_value'  => 'page-vibe-showcase.php',
        'post_status' => 'publish',
        'number'      => 1,
        'sort_column' => 'menu_order,post_date',
    ) );

    if ( ! empty( $pages ) ) {
        $cached_url = get_permalink( $pages[0]->ID );
    } else {
        $cached_url = '';
    }

    return $cached_url;
}
