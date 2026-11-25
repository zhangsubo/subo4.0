<?php
/**
 * Native Theme Settings Page (WordPress Settings API) and Option Helpers
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define all theme setting tabs and fields (called on demand to avoid early translation notices).
 *
 * @return array
 */
function subo4_get_theme_setting_schema() {
    return array(
        'tabs'   => array(
            'profile' => '个人资料',
            'social'  => '社交链接',
            'footer'  => '页脚设置',
        ),
        'fields' => array(
            'SUBO4_avatar' => array(
                'tab'         => 'profile',
                'label'       => '头像',
                'type'        => 'image',
                'preview'     => 'thumbnail',
                'description' => '上传个人头像（建议尺寸：200×200 像素），留空使用主题默认头像。',
                'default'     => '',
            ),
            'SUBO4_logo' => array(
                'tab'         => 'profile',
                'label'       => '网站 Logo',
                'type'        => 'image',
                'preview'     => 'medium',
                'description' => '上传网站顶部导航 Logo（建议高度 40px、宽度自适应）。',
                'default'     => '',
            ),
            'SUBO4_author_name' => array(
                'tab'     => 'profile',
                'label'   => '作者名称',
                'type'    => 'text',
                'default' => SUBO4_DEFAULT_AUTHOR_NAME,
            ),
            'SUBO4_bio' => array(
                'tab'     => 'profile',
                'label'   => '个人简介 / 签名',
                'type'    => 'text',
                'default' => SUBO4_DEFAULT_BIO,
            ),
            'SUBO4_aboutme_content' => array(
                'tab'         => 'profile',
                'label'       => '关于我内容',
                'type'        => 'textarea',
                'rows'        => 4,
                'description' => '显示在文章详情页底部的“关于我”区块内容。',
                'default'     => '',
            ),
            'SUBO4_github' => array(
                'tab'         => 'social',
                'label'       => 'GitHub 链接',
                'type'        => 'url',
                'placeholder' => 'https://github.com/username',
                'default'     => '',
            ),
            'SUBO4_twitter' => array(
                'tab'         => 'social',
                'label'       => 'Twitter 链接',
                'type'        => 'url',
                'placeholder' => 'https://twitter.com/username',
                'default'     => '',
            ),
            'SUBO4_facebook' => array(
                'tab'         => 'social',
                'label'       => 'Facebook 链接',
                'type'        => 'url',
                'placeholder' => 'https://facebook.com/username',
                'default'     => '',
            ),
            'SUBO4_email' => array(
                'tab'         => 'social',
                'label'       => '邮箱地址',
                'type'        => 'email',
                'placeholder' => 'hello@example.com',
                'default'     => '',
            ),
            'SUBO4_weibo' => array(
                'tab'         => 'social',
                'label'       => '微博链接',
                'type'        => 'url',
                'placeholder' => 'https://weibo.com/username',
                'description' => '输入微博个人主页链接。',
                'default'     => '',
            ),
            'SUBO4_wechat_qr' => array(
                'tab'         => 'social',
                'label'       => '微信公众号二维码',
                'type'        => 'image',
                'preview'     => 'thumbnail',
                'description' => '上传微信公众号二维码图片（建议正方形）。',
                'default'     => '',
            ),
            'SUBO4_wechat_id_qr' => array(
                'tab'         => 'social',
                'label'       => '微信号二维码',
                'type'        => 'image',
                'preview'     => 'thumbnail',
                'description' => '上传个人微信号二维码图片（建议正方形）。',
                'default'     => '',
            ),
            'SUBO4_icp_number' => array(
                'tab'         => 'footer',
                'label'       => 'ICP 备案号',
                'type'        => 'text',
                'description' => '网站底部的 ICP 备案号。',
                'default'     => '',
            ),
            'SUBO4_complaints_email' => array(
                'tab'         => 'footer',
                'label'       => '投诉举报邮箱',
                'type'        => 'email',
                'description' => '用于接收违法和不良信息举报的邮箱地址。',
                'default'     => '',
            ),
        ),
    );
}

/**
 * Read raw option value with seamless fallback to legacy ACF `options_*` rows.
 *
 * @param string $field_name Setting field key.
 * @return mixed
 */
function subo4_get_raw_option( $field_name ) {
    $saved = get_option( 'subo4_theme_settings', false );
    if ( is_array( $saved ) && array_key_exists( $field_name, $saved ) ) {
        return $saved[ $field_name ];
    }

    return get_option( 'options_' . $field_name, '' );
}

/**
 * Get theme setting value with fallback (automatically resolves image attachment IDs to URLs).
 *
 * @param string $field_name Setting key.
 * @param mixed  $default    Default value when empty.
 * @return mixed
 */
function subo4_get_option( $field_name, $default = '' ) {
    static $image_fields = array(
        'SUBO4_avatar',
        'SUBO4_logo',
        'SUBO4_wechat_qr',
        'SUBO4_wechat_id_qr',
    );

    $value = subo4_get_raw_option( $field_name );

    if ( in_array( $field_name, $image_fields, true ) ) {
        $url = subo4_resolve_image_url( $value, 'full' );
        return '' !== $url ? $url : $default;
    }

    if ( is_string( $value ) ) {
        return '' !== trim( $value ) ? $value : $default;
    }

    return ! empty( $value ) ? $value : $default;
}

/**
 * Get theme avatar URL
 *
 * @return string
 */
function subo4_get_avatar() {
    return (string) subo4_get_option(
        'SUBO4_avatar',
        SUBO4_THEME_URI . '/assets/images/avatar.jpg'
    );
}

/**
 * Get theme author name
 *
 * @return string
 */
function subo4_get_author_name() {
    return (string) subo4_get_option(
        'SUBO4_author_name',
        SUBO4_DEFAULT_AUTHOR_NAME
    );
}

/**
 * Get theme bio
 *
 * @return string
 */
function subo4_get_bio() {
    return (string) subo4_get_option(
        'SUBO4_bio',
        SUBO4_DEFAULT_BIO
    );
}

/**
 * Get about me content
 *
 * @return string
 */
function subo4_get_aboutme_content() {
    $default_aboutme = ( defined( 'SUBO4_DEFAULT_ABOUTME' ) && 'Welcome to my blog. I write about technology, products, and life.' !== SUBO4_DEFAULT_ABOUTME )
        ? SUBO4_DEFAULT_ABOUTME
        : __( 'Welcome to my blog. I write about technology, products, and life.', 'subo4-classic-theme' );

    return (string) subo4_get_option(
        'SUBO4_aboutme_content',
        $default_aboutme
    );
}

/**
 * Get logo URL
 *
 * @return string
 */
function subo4_get_logo() {
    return (string) subo4_get_option( 'SUBO4_logo', '' );
}

/**
 * Render reusable media library picker field (shared by theme settings and project meta boxes).
 *
 * @param array $args Field configuration: id, name, value, preview_size, button_label.
 */
function subo4_render_media_field( $args ) {
    $id           = isset( $args['id'] ) ? (string) $args['id'] : '';
    $name         = isset( $args['name'] ) ? (string) $args['name'] : '';
    $value        = isset( $args['value'] ) ? $args['value'] : '';
    $preview_size = isset( $args['preview_size'] ) ? (string) $args['preview_size'] : 'thumbnail';
    $button_label = isset( $args['button_label'] ) ? (string) $args['button_label'] : '选择图片';

    $preview_url = subo4_resolve_image_url( $value, $preview_size );
    $has_image   = '' !== $preview_url;
    ?>
    <div class="subo4-media-field" data-media-field>
        <input type="hidden"
               id="<?php echo esc_attr( $id ); ?>"
               name="<?php echo esc_attr( $name ); ?>"
               value="<?php echo esc_attr( (string) $value ); ?>"
               data-media-input>
        <div class="subo4-media-preview<?php echo $has_image ? ' has-image' : ''; ?>" data-media-preview>
            <?php if ( $has_image ) : ?>
                <img src="<?php echo esc_url( $preview_url ); ?>" alt="">
            <?php else : ?>
                <span class="subo4-media-placeholder">未选择图片</span>
            <?php endif; ?>
        </div>
        <div class="subo4-media-actions">
            <button type="button" class="button" data-media-select><?php echo esc_html( $button_label ); ?></button>
            <button type="button"
                    class="button-link-delete subo4-media-remove"
                    data-media-remove
                    <?php echo $has_image ? '' : 'hidden'; ?>>移除</button>
        </div>
    </div>
    <?php
}

/**
 * Register native Theme Settings admin page and WordPress Settings API group
 */
function subo4_register_theme_settings_admin() {
    add_menu_page(
        __( 'Theme Settings', 'subo4-classic-theme' ),
        __( 'Theme Settings', 'subo4-classic-theme' ),
        'edit_theme_options',
        'subo4-theme-settings',
        'subo4_render_theme_settings_page',
        'dashicons-admin-generic',
        30
    );
}
add_action( 'admin_menu', 'subo4_register_theme_settings_admin' );

function subo4_register_theme_settings_option() {
    register_setting(
        'subo4_theme_settings_group',
        'subo4_theme_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'subo4_sanitize_theme_settings',
            'default'           => array(),
        )
    );
}
add_action( 'admin_init', 'subo4_register_theme_settings_option' );

/**
 * Sanitize submitted theme settings before storing to `subo4_theme_settings`
 *
 * @param mixed $input Raw POST array.
 * @return array
 */
function subo4_sanitize_theme_settings( $input ) {
    $schema    = subo4_get_theme_setting_schema();
    $sanitized = array();
    $input     = is_array( $input ) ? $input : array();

    foreach ( $schema['fields'] as $key => $field ) {
        $raw = isset( $input[ $key ] ) ? $input[ $key ] : '';
        switch ( $field['type'] ) {
            case 'image':
                if ( is_numeric( $raw ) ) {
                    $sanitized[ $key ] = absint( $raw ) ?: '';
                } else {
                    $sanitized[ $key ] = esc_url_raw( trim( (string) $raw ) );
                }
                break;
            case 'url':
                $sanitized[ $key ] = esc_url_raw( trim( (string) $raw ) );
                break;
            case 'email':
                $sanitized[ $key ] = sanitize_email( (string) $raw );
                break;
            case 'textarea':
                $sanitized[ $key ] = sanitize_textarea_field( (string) $raw );
                break;
            case 'text':
            default:
                $sanitized[ $key ] = sanitize_text_field( (string) $raw );
                break;
        }
    }

    return $sanitized;
}

/**
 * Render native Theme Settings page in wp-admin
 */
function subo4_render_theme_settings_page() {
    if ( ! current_user_can( 'edit_theme_options' ) ) {
        return;
    }

    $schema = subo4_get_theme_setting_schema();
    $tabs   = $schema['tabs'];
    $fields = $schema['fields'];
    ?>
    <div class="wrap subo4-settings-wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <?php settings_errors(); ?>

        <nav class="nav-tab-wrapper subo4-nav-tabs" aria-label="设置分组">
            <?php
            $first = true;
            foreach ( $tabs as $tab_key => $tab_label ) :
                ?>
                <a href="#tab-<?php echo esc_attr( $tab_key ); ?>"
                   class="nav-tab<?php echo $first ? ' nav-tab-active' : ''; ?>"
                   data-subo4-tab="<?php echo esc_attr( $tab_key ); ?>">
                    <?php echo esc_html( $tab_label ); ?>
                </a>
                <?php
                $first = false;
            endforeach;
            ?>
        </nav>

        <form method="post" action="options.php" class="subo4-settings-form">
            <?php settings_fields( 'subo4_theme_settings_group' ); ?>

            <?php
            $first = true;
            foreach ( $tabs as $tab_key => $tab_label ) :
                ?>
                <section class="subo4-tab-panel"
                         data-subo4-panel="<?php echo esc_attr( $tab_key ); ?>"
                         <?php echo $first ? '' : 'hidden'; ?>>
                    <table class="form-table" role="presentation">
                        <tbody>
                            <?php
                            foreach ( $fields as $key => $field ) :
                                if ( $field['tab'] !== $tab_key ) {
                                    continue;
                                }
                                $raw_val     = subo4_get_raw_option( $key );
                                $display_val = ( '' !== $raw_val && null !== $raw_val ) ? $raw_val : ( isset( $field['default'] ) ? $field['default'] : '' );
                                $input_id    = 'subo4-opt-' . $key;
                                $input_name  = 'subo4_theme_settings[' . $key . ']';
                                ?>
                                <tr>
                                    <th scope="row">
                                        <label for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
                                    </th>
                                    <td>
                                        <?php if ( 'image' === $field['type'] ) : ?>
                                            <?php
                                            subo4_render_media_field( array(
                                                'id'           => $input_id,
                                                'name'         => $input_name,
                                                'value'        => $raw_val,
                                                'preview_size' => isset( $field['preview'] ) ? $field['preview'] : 'thumbnail',
                                            ) );
                                            ?>
                                        <?php elseif ( 'textarea' === $field['type'] ) : ?>
                                            <textarea id="<?php echo esc_attr( $input_id ); ?>"
                                                      name="<?php echo esc_attr( $input_name ); ?>"
                                                      rows="<?php echo isset( $field['rows'] ) ? absint( $field['rows'] ) : 4; ?>"
                                                      class="large-text"><?php echo esc_textarea( (string) $display_val ); ?></textarea>
                                        <?php else : ?>
                                            <input id="<?php echo esc_attr( $input_id ); ?>"
                                                   type="<?php echo esc_attr( $field['type'] ); ?>"
                                                   name="<?php echo esc_attr( $input_name ); ?>"
                                                   value="<?php echo esc_attr( (string) $display_val ); ?>"
                                                   placeholder="<?php echo isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : ''; ?>"
                                                   class="regular-text">
                                        <?php endif; ?>

                                        <?php if ( ! empty( $field['description'] ) ) : ?>
                                            <p class="description"><?php echo esc_html( $field['description'] ); ?></p>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
                <?php
                $first = false;
            endforeach;
            ?>

            <?php submit_button( '保存设置' ); ?>
        </form>
    </div>
    <?php
}
