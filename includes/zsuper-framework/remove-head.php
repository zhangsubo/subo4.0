<?php
/**
 * Remove unnecessary head clutter and legacy features
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// 精简头部信息
remove_action( 'wp_head', 'feed_links_extra', 3 ); // 移除额外 feed（保留主站 RSS 发现）
remove_action( 'wp_head', 'rsd_link' ); // 移除离线编辑器开放接口
remove_action( 'wp_head', 'wlwmanifest_link' ); // 移除离线编辑器开放接口
remove_action( 'wp_head', 'index_rel_link' ); // 去除本页唯一链接信息
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // 清除前后文信息
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // 清除前后文信息
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'locale_stylesheet' );
remove_action( 'wp_head', 'wp_generator' ); // 移除 WordPress 版本
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );

// 移除 wp-json 头部发现链接与 oEmbed 发现链接（保留 REST API 本体可用）
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

if ( ! function_exists( 'disable_emojis' ) ) {
    /**
     * Disable the emoji's
     */
    function disable_emojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    }
    add_action( 'init', 'disable_emojis' );
}

if ( ! function_exists( 'disable_emojis_tinymce' ) ) {
    /**
     * Filter function used to remove the tinymce emoji plugin.
     *
     * @param  array $plugins TinyMCE plugins.
     * @return array Difference between the two arrays.
     */
    function disable_emojis_tinymce( $plugins ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    }
}

// 移除前端顶部工具栏
add_filter( 'show_admin_bar', '__return_false' );
