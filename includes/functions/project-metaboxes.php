<?php
/**
 * Native WordPress Meta Boxes for Projects (`subo4_project`) and Showcase Pages
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register meta boxes in wp-admin
 */
function subo4_register_project_metaboxes() {
    add_meta_box(
        'subo4_project_meta_box',
        '作品信息与展示设置',
        'subo4_render_project_meta_box',
        'subo4_project',
        'normal',
        'high'
    );

    add_meta_box(
        'subo4_showcase_page_meta_box',
        '作品展示页设置（仅「Vibe Coding 作品展示」模板生效）',
        'subo4_render_showcase_page_meta_box',
        'page',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'subo4_register_project_metaboxes' );

/**
 * Render `subo4_project` meta box
 *
 * @param WP_Post $post Current post object.
 */
function subo4_render_project_meta_box( $post ) {
    wp_nonce_field( 'subo4_save_project_meta', 'subo4_project_meta_nonce' );

    $post_id             = $post->ID;
    $icon                = get_post_meta( $post_id, 'subo4_project_icon', true );
    $summary             = (string) subo4_get_project_field( 'subo4_project_summary', $post_id, '' );
    $primary_badge       = (string) subo4_get_project_field( 'subo4_project_primary_badge', $post_id, '' );
    $tech_tags           = (string) subo4_get_project_field( 'subo4_project_tech_tags', $post_id, '' );
    $version             = (string) subo4_get_project_field( 'subo4_project_version', $post_id, '' );
    $status              = (string) subo4_get_project_field( 'subo4_project_status', $post_id, 'live' );
    $meta_note           = (string) subo4_get_project_field( 'subo4_project_meta_note', $post_id, '' );
    $is_featured         = (bool) subo4_get_project_field( 'subo4_project_featured', $post_id, false );
    $featured_label      = (string) subo4_get_project_field( 'subo4_project_featured_label', $post_id, '精选推荐' );
    $screenshot          = get_post_meta( $post_id, 'subo4_project_screenshot', true );
    $metrics             = subo4_get_project_field( 'subo4_project_metrics', $post_id, array() );
    $primary_btn_label   = (string) subo4_get_project_field( 'subo4_project_primary_btn_label', $post_id, '' );
    $primary_btn_url     = (string) subo4_get_project_field( 'subo4_project_primary_btn_url', $post_id, '' );
    $detail_mode         = (string) subo4_get_project_field( 'subo4_project_detail_mode', $post_id, 'single' );
    $secondary_btn_label = (string) subo4_get_project_field( 'subo4_project_secondary_btn_label', $post_id, '了解更多' );
    $custom_detail_url   = (string) subo4_get_project_field( 'subo4_project_custom_detail_url', $post_id, '' );
    $status_map          = subo4_get_project_status_map();
    ?>
    <div class="subo4-mb">
        <section class="subo4-mb-section">
            <h3 class="subo4-mb-heading">基础信息</h3>
            <div class="subo4-mb-grid">
                <div class="subo4-mb-field subo4-mb-field--full">
                    <label>应用图标</label>
                    <?php
                    subo4_render_media_field( array(
                        'id'           => 'subo4_project_icon',
                        'name'         => 'subo4_project_icon',
                        'value'        => $icon,
                        'preview_size' => 'thumbnail',
                        'button_label' => '选择图标',
                    ) );
                    ?>
                    <p class="description">建议上传正方形透明背景或圆角图标；留空将自动显示作品首字母图标。</p>
                </div>

                <div class="subo4-mb-field subo4-mb-field--full">
                    <label for="subo4_project_summary">一句话简介</label>
                    <textarea id="subo4_project_summary" name="subo4_project_summary" rows="3" class="large-text"><?php echo esc_textarea( $summary ); ?></textarea>
                    <p class="description">显示在作品卡片标题下方；留空时自动读取文章摘要或正文前 90 字。</p>
                </div>

                <div class="subo4-mb-field">
                    <label for="subo4_project_primary_badge">主平台 / 形态标签</label>
                    <input id="subo4_project_primary_badge" type="text" name="subo4_project_primary_badge"
                           value="<?php echo esc_attr( $primary_badge ); ?>"
                           placeholder="如：Web App / macOS / Chrome 插件" class="regular-text">
                </div>

                <div class="subo4-mb-field">
                    <label for="subo4_project_tech_tags">技术栈标签</label>
                    <input id="subo4_project_tech_tags" type="text" name="subo4_project_tech_tags"
                           value="<?php echo esc_attr( $tech_tags ); ?>"
                           placeholder="多个标签用逗号分隔，如：Next.js, Tailwind" class="regular-text">
                </div>

                <div class="subo4-mb-field">
                    <label for="subo4_project_version">当前版本</label>
                    <input id="subo4_project_version" type="text" name="subo4_project_version"
                           value="<?php echo esc_attr( $version ); ?>"
                           placeholder="如：v1.0.0" class="regular-text">
                </div>

                <div class="subo4-mb-field">
                    <label for="subo4_project_status">作品状态</label>
                    <select id="subo4_project_status" name="subo4_project_status">
                        <option value="">— 不显示 —</option>
                        <?php foreach ( $status_map as $key => $info ) : ?>
                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $status, $key ); ?>>
                                <?php echo esc_html( $info['label'] ); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="subo4-mb-field subo4-mb-field--full">
                    <label for="subo4_project_meta_note">底部补充说明</label>
                    <input id="subo4_project_meta_note" type="text" name="subo4_project_meta_note"
                           value="<?php echo esc_attr( $meta_note ); ?>"
                           placeholder="如：开源免费 · 零云端上传" class="regular-text">
                </div>
            </div>
        </section>

        <section class="subo4-mb-section">
            <h3 class="subo4-mb-heading">精选与截图</h3>
            <div class="subo4-mb-grid">
                <div class="subo4-mb-field">
                    <label class="subo4-checkbox-label">
                        <input type="checkbox" id="subo4_project_featured" name="subo4_project_featured" value="1" <?php checked( $is_featured ); ?> data-toggle-featured>
                        <span>设为精选推荐通栏大卡</span>
                    </label>
                    <p class="description">开启后该作品排在列表顶部并以双栏大图卡形式重点展示。</p>
                </div>

                <div class="subo4-mb-field" data-featured-only <?php echo $is_featured ? '' : 'hidden'; ?>>
                    <label for="subo4_project_featured_label">精选徽章文案</label>
                    <input id="subo4_project_featured_label" type="text" name="subo4_project_featured_label"
                           value="<?php echo esc_attr( $featured_label ); ?>"
                           placeholder="精选推荐" class="regular-text">
                </div>

                <div class="subo4-mb-field subo4-mb-field--full">
                    <label>作品界面截图</label>
                    <?php
                    subo4_render_media_field( array(
                        'id'           => 'subo4_project_screenshot',
                        'name'         => 'subo4_project_screenshot',
                        'value'        => $screenshot,
                        'preview_size' => 'medium',
                        'button_label' => '选择截图',
                    ) );
                    ?>
                    <p class="description">用于精选大卡右侧与详情页头图（建议 16:10 或 16:9 横图）；留空将回退使用特色图片。</p>
                </div>

                <div class="subo4-mb-field subo4-mb-field--full">
                    <label>核心亮点指标（最多 3 项）</label>
                    <table class="subo4-metrics-table">
                        <thead>
                            <tr>
                                <th>指标数值 / 关键词</th>
                                <th>指标说明</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ( $i = 0; $i < 3; $i++ ) :
                                $m_val   = isset( $metrics[ $i ]['metric_value'] ) ? $metrics[ $i ]['metric_value'] : '';
                                $m_label = isset( $metrics[ $i ]['metric_label'] ) ? $metrics[ $i ]['metric_label'] : '';
                                ?>
                                <tr>
                                    <td>
                                        <input type="text"
                                               name="subo4_project_metrics[<?php echo absint( $i ); ?>][metric_value]"
                                               value="<?php echo esc_attr( $m_val ); ?>"
                                               placeholder="如：4 小时 / 100% 本地">
                                    </td>
                                    <td>
                                        <input type="text"
                                               name="subo4_project_metrics[<?php echo absint( $i ); ?>][metric_label]"
                                               value="<?php echo esc_attr( $m_label ); ?>"
                                               placeholder="如：Vibe 构建耗时 / 数据不出设备">
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="subo4-mb-section">
            <h3 class="subo4-mb-heading">操作按钮</h3>
            <div class="subo4-mb-grid">
                <div class="subo4-mb-field">
                    <label for="subo4_project_primary_btn_label">主按钮文案</label>
                    <input id="subo4_project_primary_btn_label" type="text" name="subo4_project_primary_btn_label"
                           value="<?php echo esc_attr( $primary_btn_label ); ?>"
                           placeholder="如：在线体验 / GitHub" class="regular-text">
                </div>

                <div class="subo4-mb-field">
                    <label for="subo4_project_primary_btn_url">主按钮链接</label>
                    <input id="subo4_project_primary_btn_url" type="url" name="subo4_project_primary_btn_url"
                           value="<?php echo esc_attr( $primary_btn_url ); ?>"
                           placeholder="https://..." class="regular-text">
                </div>

                <div class="subo4-mb-field">
                    <label for="subo4_project_detail_mode">「了解更多」跳转方式</label>
                    <select id="subo4_project_detail_mode" name="subo4_project_detail_mode" data-detail-mode>
                        <option value="single" <?php selected( $detail_mode, 'single' ); ?>>跳转到本站作品详情页</option>
                        <option value="custom" <?php selected( $detail_mode, 'custom' ); ?>>跳转到自定义链接</option>
                        <option value="none" <?php selected( $detail_mode, 'none' ); ?>>不显示「了解更多」入口</option>
                    </select>
                </div>

                <div class="subo4-mb-field" data-detail-not-none <?php echo 'none' !== $detail_mode ? '' : 'hidden'; ?>>
                    <label for="subo4_project_secondary_btn_label">次操作文案</label>
                    <input id="subo4_project_secondary_btn_label" type="text" name="subo4_project_secondary_btn_label"
                           value="<?php echo esc_attr( $secondary_btn_label ); ?>"
                           placeholder="了解更多" class="regular-text">
                </div>

                <div class="subo4-mb-field subo4-mb-field--full" data-detail-custom-only <?php echo 'custom' === $detail_mode ? '' : 'hidden'; ?>>
                    <label for="subo4_project_custom_detail_url">自定义详情链接</label>
                    <input id="subo4_project_custom_detail_url" type="url" name="subo4_project_custom_detail_url"
                           value="<?php echo esc_attr( $custom_detail_url ); ?>"
                           placeholder="https://..." class="regular-text">
                </div>
            </div>
        </section>
    </div>
    <?php
}

/**
 * Save `subo4_project` meta box fields
 *
 * @param int $post_id Post ID.
 */
function subo4_save_project_meta_box( $post_id ) {
    if ( ! isset( $_POST['subo4_project_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['subo4_project_meta_nonce'] ) ), 'subo4_save_project_meta' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $text_fields = array(
        'subo4_project_primary_badge',
        'subo4_project_tech_tags',
        'subo4_project_version',
        'subo4_project_meta_note',
        'subo4_project_featured_label',
        'subo4_project_primary_btn_label',
        'subo4_project_secondary_btn_label',
    );
    foreach ( $text_fields as $key ) {
        $val = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
        update_post_meta( $post_id, $key, $val );
    }

    $summary = isset( $_POST['subo4_project_summary'] ) ? sanitize_textarea_field( wp_unslash( $_POST['subo4_project_summary'] ) ) : '';
    update_post_meta( $post_id, 'subo4_project_summary', $summary );

    $url_fields = array(
        'subo4_project_primary_btn_url',
        'subo4_project_custom_detail_url',
    );
    foreach ( $url_fields as $key ) {
        $val = isset( $_POST[ $key ] ) ? esc_url_raw( trim( (string) wp_unslash( $_POST[ $key ] ) ) ) : '';
        update_post_meta( $post_id, $key, $val );
    }

    $image_fields = array(
        'subo4_project_icon',
        'subo4_project_screenshot',
    );
    foreach ( $image_fields as $key ) {
        $raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
        if ( is_numeric( $raw ) ) {
            update_post_meta( $post_id, $key, absint( $raw ) ?: '' );
        } else {
            update_post_meta( $post_id, $key, esc_url_raw( trim( (string) $raw ) ) );
        }
    }

    $status_raw = isset( $_POST['subo4_project_status'] ) ? sanitize_key( wp_unslash( $_POST['subo4_project_status'] ) ) : '';
    $status_map = subo4_get_project_status_map();
    update_post_meta( $post_id, 'subo4_project_status', isset( $status_map[ $status_raw ] ) ? $status_raw : '' );

    $detail_mode = isset( $_POST['subo4_project_detail_mode'] ) ? sanitize_key( wp_unslash( $_POST['subo4_project_detail_mode'] ) ) : 'single';
    if ( ! in_array( $detail_mode, array( 'single', 'custom', 'none' ), true ) ) {
        $detail_mode = 'single';
    }
    update_post_meta( $post_id, 'subo4_project_detail_mode', $detail_mode );

    $featured = ! empty( $_POST['subo4_project_featured'] ) ? '1' : '0';
    update_post_meta( $post_id, 'subo4_project_featured', $featured );

    $metrics = array();
    if ( isset( $_POST['subo4_project_metrics'] ) && is_array( $_POST['subo4_project_metrics'] ) ) {
        $raw_metrics = wp_unslash( $_POST['subo4_project_metrics'] );
        foreach ( $raw_metrics as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }
            $m_val   = isset( $row['metric_value'] ) ? sanitize_text_field( (string) $row['metric_value'] ) : '';
            $m_label = isset( $row['metric_label'] ) ? sanitize_text_field( (string) $row['metric_label'] ) : '';
            if ( '' === $m_val && '' === $m_label ) {
                continue;
            }
            $metrics[] = array(
                'metric_value' => $m_val,
                'metric_label' => $m_label,
            );
            if ( count( $metrics ) >= 3 ) {
                break;
            }
        }
    }
    update_post_meta( $post_id, 'subo4_project_metrics', $metrics );

    // Clean up legacy ACF flattened repeater sub-rows after saving native array
    for ( $i = 0; $i < 3; $i++ ) {
        delete_post_meta( $post_id, "subo4_project_metrics_{$i}_metric_value" );
        delete_post_meta( $post_id, "subo4_project_metrics_{$i}_metric_label" );
    }
}
add_action( 'save_post_subo4_project', 'subo4_save_project_meta_box' );

/**
 * Render showcase page template meta box
 *
 * @param WP_Post $post Current page object.
 */
function subo4_render_showcase_page_meta_box( $post ) {
    wp_nonce_field( 'subo4_save_showcase_meta', 'subo4_showcase_meta_nonce' );

    $post_id      = $post->ID;
    $eyebrow      = (string) subo4_get_project_field( 'subo4_showcase_eyebrow', $post_id, 'VIBE CODING' );
    $subtitle     = (string) subo4_get_project_field( 'subo4_showcase_subtitle', $post_id, '' );
    $show_toolbar = (bool) subo4_get_project_field( 'subo4_showcase_show_toolbar', $post_id, true );
    $show_count   = (bool) subo4_get_project_field( 'subo4_showcase_show_count', $post_id, true );
    ?>
    <div class="subo4-mb">
        <div class="subo4-mb-grid">
            <div class="subo4-mb-field">
                <label for="subo4_showcase_eyebrow">英文眉标 (Eyebrow)</label>
                <input id="subo4_showcase_eyebrow" type="text" name="subo4_showcase_eyebrow"
                       value="<?php echo esc_attr( $eyebrow ); ?>"
                       placeholder="VIBE CODING" class="regular-text">
            </div>

            <div class="subo4-mb-field subo4-mb-toggles">
                <label class="subo4-checkbox-label">
                    <input type="checkbox" name="subo4_showcase_show_toolbar" value="1" <?php checked( $show_toolbar ); ?>>
                    <span>显示分类与搜索栏</span>
                </label>
                <label class="subo4-checkbox-label">
                    <input type="checkbox" name="subo4_showcase_show_count" value="1" <?php checked( $show_count ); ?>>
                    <span>显示作品数量</span>
                </label>
            </div>

            <div class="subo4-mb-field subo4-mb-field--full">
                <label for="subo4_showcase_subtitle">页面副标题</label>
                <textarea id="subo4_showcase_subtitle" name="subo4_showcase_subtitle" rows="2" class="large-text"
                          placeholder="用 AI 协同打磨的个人工具、效率插件与实验性软件作品。"><?php echo esc_textarea( $subtitle ); ?></textarea>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Save showcase page template meta fields
 *
 * @param int $post_id Page ID.
 */
function subo4_save_showcase_page_meta_box( $post_id ) {
    if ( ! isset( $_POST['subo4_showcase_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['subo4_showcase_meta_nonce'] ) ), 'subo4_save_showcase_meta' ) ) {
        return;
    }
    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $eyebrow  = isset( $_POST['subo4_showcase_eyebrow'] ) ? sanitize_text_field( wp_unslash( $_POST['subo4_showcase_eyebrow'] ) ) : '';
    $subtitle = isset( $_POST['subo4_showcase_subtitle'] ) ? sanitize_textarea_field( wp_unslash( $_POST['subo4_showcase_subtitle'] ) ) : '';

    update_post_meta( $post_id, 'subo4_showcase_eyebrow', $eyebrow );
    update_post_meta( $post_id, 'subo4_showcase_subtitle', $subtitle );
    update_post_meta( $post_id, 'subo4_showcase_show_toolbar', ! empty( $_POST['subo4_showcase_show_toolbar'] ) ? '1' : '0' );
    update_post_meta( $post_id, 'subo4_showcase_show_count', ! empty( $_POST['subo4_showcase_show_count'] ) ? '1' : '0' );
}
add_action( 'save_post_page', 'subo4_save_showcase_page_meta_box' );
