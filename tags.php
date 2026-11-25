<?php
/**
 * Template Name: 标签云
 * Template Post Type: page
 *
 * 标签页面模板
 *
 * @package SUBO4_Classic_Theme
 */

get_header();
?>

<main class="main-wrapper">
    <?php get_template_part( 'template-parts/profile-card' ); ?>

    <div class="content-card">
        <h1 class="text-center post-title-main"><?php esc_html_e( 'Tag Cloud', 'subo4-classic-theme' ); ?></h1>

        <div class="tags-cloud text-center">
            <?php
            $tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC' ) );
            if ( $tags ) :
                foreach ( $tags as $tag ) :
                    $count     = $tag->count;
                    $font_size = min( 16, max( 12, 12 + $count ) );
                    ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                       class="tag-item"
                       style="font-size: <?php echo absint( $font_size ); ?>px;">
                        <?php echo esc_html( $tag->name ); ?> (<?php echo absint( $count ); ?>)
                    </a>
                    <?php
                endforeach;
            else :
                ?>
                <p class="empty-state"><?php esc_html_e( 'No tags found.', 'subo4-classic-theme' ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>
