<?php
/**
 * Profile Card Template Part
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$avatar       = subo4_get_avatar();
$author_name  = subo4_get_author_name();
$bio          = subo4_get_bio();
$eager_avatar = ! empty( $args['eager_avatar'] );
?>

<!-- 个人资料卡片 -->
<div class="content-card">
    <div class="row align-items-center">
        <div class="col-md-3 text-center mb-3 mb-md-0">
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="profile-avatar-link">
                <img src="<?php echo esc_url( $avatar ); ?>"
                     alt="<?php echo esc_attr( $author_name ); ?>"
                     class="profile-avatar-img"<?php echo $eager_avatar ? ' loading="eager"' : ''; ?>>
            </a>
        </div>
        <div class="col-md-9 text-center text-md-start">
            <h2 class="profile-name"><?php echo esc_html( $author_name ); ?></h2>
            <p class="profile-bio"><?php echo esc_html( $bio ); ?></p>
        </div>
    </div>
</div>
