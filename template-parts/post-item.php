<?php
/**
 * Post List Item Template Part
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<article class="post-item">
    <div class="post-date-col">
        <div class="post-date-month"><?php echo esc_html( get_the_date( 'M' ) ); ?></div>
        <div class="post-date-year"><?php echo esc_html( get_the_date( 'Y' ) ); ?></div>
    </div>
    <div class="post-body-col">
        <h3 class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?></p>
        <?php
        $tags = get_the_tags();
        if ( $tags ) :
            ?>
            <div class="post-tags">
                <?php
                foreach ( $tags as $tag ) {
                    printf(
                        '<a href="%s" class="post-tag-badge">#%s</a> ',
                        esc_url( get_tag_link( $tag->term_id ) ),
                        esc_html( $tag->name )
                    );
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</article>
