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

<div class="content-card">
    <div class="profile-card-layout">
        <div class="profile-avatar-col">
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="profile-avatar-link">
                <img src="<?php echo esc_url( $avatar ); ?>"
                     alt="<?php echo esc_attr( $author_name ); ?>"
                     class="profile-avatar-img"<?php echo $eager_avatar ? ' loading="eager"' : ''; ?>>
            </a>
        </div>
        <div class="profile-info-col">
            <h2 class="profile-name"><?php echo esc_html( $author_name ); ?></h2>
            <p class="profile-bio"><?php echo esc_html( $bio ); ?></p>
        </div>
    </div>
</div>
