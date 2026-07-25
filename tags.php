<?php
/**
 * 标签页面模板
 *
 * @package SUBO4_Block_Theme
 */

get_header();

// 获取ACF字段值（使用辅助函数，带降级处理）
$avatar = subo4_get_avatar();
$author_name = subo4_get_author_name();
$bio = subo4_get_bio();
?>

<main class="main-wrapper">
    <!-- 个人资料卡片 -->
    <div class="content-card">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="profile-avatar-link">
                    <img src="<?php echo esc_url( $avatar ); ?>"
                         alt="<?php echo esc_attr( $author_name ); ?>"
                         class="profile-avatar-img">
                </a>
            </div>
            <div class="col-md-9 text-center text-md-start">
                <h2 class="profile-name"><?php echo esc_html( $author_name ); ?></h2>
                <p class="profile-bio"><?php echo esc_html( $bio ); ?></p>
            </div>
        </div>
    </div>

    <!-- 标签云 -->
    <div class="content-card">
        <h1 class="text-center mb-4 post-title-main"><?php esc_html_e( 'Tag Cloud', 'subo4-classic-theme' ); ?></h1>

        <div class="tags-cloud text-center">
            <?php
            $tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC' ) );
            if ( $tags ) :
                foreach ( $tags as $tag ) :
                    $count = $tag->count;
                    $font_size = min( 16, max( 12, 12 + $count ) );
                ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                       class="tag-item"
                       style="font-size: <?php echo absint( $font_size ); ?>px;">
                        <?php echo esc_html( $tag->name ); ?> (<?php echo absint( $count ); ?>)
                    </a>
                <?php endforeach;
            else : ?>
                <p class="empty-state"><?php esc_html_e( 'No tags found.', 'subo4-classic-theme' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>