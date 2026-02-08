<?php
/**
 * Theme Customizer
 *
 * @package SUBO4_Block_Theme
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function SUBO4_customize_register( $wp_customize ) {
    // Site Identity section is already included by default
    
    // Add Theme Options Panel
    $wp_customize->add_panel(
        'SUBO4_theme_options',
        array(
            'title'    => __( 'Theme Options', 'SUBO4-block-theme' ),
            'priority' => 130,
        )
    );
    
    // Social Links Section
    $wp_customize->add_section(
        'SUBO4_social_links',
        array(
            'title'       => __( 'Social Links', 'SUBO4-block-theme' ),
            'panel'       => 'SUBO4_theme_options',
            'priority'    => 10,
        )
    );
    
    // GitHub
    $wp_customize->add_setting(
        'SUBO4_github_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        'SUBO4_github_url',
        array(
            'label'   => __( 'GitHub URL', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_social_links',
            'type'    => 'url',
        )
    );
    
    // Twitter
    $wp_customize->add_setting(
        'SUBO4_twitter_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        'SUBO4_twitter_url',
        array(
            'label'   => __( 'Twitter URL', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_social_links',
            'type'    => 'url',
        )
    );
    
    // Email
    $wp_customize->add_setting(
        'SUBO4_email',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_email',
        )
    );
    $wp_customize->add_control(
        'SUBO4_email',
        array(
            'label'   => __( 'Email Address', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_social_links',
            'type'    => 'email',
        )
    );
    
    // Footer Section
    $wp_customize->add_section(
        'SUBO4_footer_options',
        array(
            'title'       => __( 'Footer Options', 'SUBO4-block-theme' ),
            'panel'       => 'SUBO4_theme_options',
            'priority'    => 20,
        )
    );
    
    // ICP Number
    $wp_customize->add_setting(
        'SUBO4_icp_number',
        array(
            'default'           => '津ICP备15001051号-2',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'SUBO4_icp_number',
        array(
            'label'   => __( 'ICP Number', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_footer_options',
            'type'    => 'text',
        )
    );
    
    // ICP URL
    $wp_customize->add_setting(
        'SUBO4_icp_url',
        array(
            'default'           => 'https://beian.miit.gov.cn/',
            'sanitize_callback' => 'esc_url_raw',
        )
    );
    $wp_customize->add_control(
        'SUBO4_icp_url',
        array(
            'label'   => __( 'ICP URL', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_footer_options',
            'type'    => 'url',
        )
    );
    
    // Complaints Email
    $wp_customize->add_setting(
        'SUBO4_complaints_email',
        array(
            'default'           => 'complaints@zhangsubo.cn',
            'sanitize_callback' => 'sanitize_email',
        )
    );
    $wp_customize->add_control(
        'SUBO4_complaints_email',
        array(
            'label'   => __( 'Complaints Email', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_footer_options',
            'type'    => 'email',
        )
    );
    
    // Show Powered by
    $wp_customize->add_setting(
        'SUBO4_show_powered_by',
        array(
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
        )
    );
    $wp_customize->add_control(
        'SUBO4_show_powered_by',
        array(
            'label'   => __( 'Show "Powered by WordPress"', 'SUBO4-block-theme' ),
            'section' => 'SUBO4_footer_options',
            'type'    => 'checkbox',
        )
    );
}
add_action( 'customize_register', 'SUBO4_customize_register' );