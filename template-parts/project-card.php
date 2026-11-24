<?php
/**
 * 作品展示卡片模板部件
 *
 * @package SUBO4_Classic_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$project = isset( $args['project'] ) && is_array( $args['project'] ) ? $args['project'] : array();
if ( empty( $project ) ) {
    return;
}

$is_featured = isset( $args['featured'] ) ? (bool) $args['featured'] : ! empty( $project['is_featured'] );
$has_shot    = $is_featured && ! empty( $project['screenshot_url'] );

$card_classes = array( 'showcase-card' );
if ( $is_featured ) {
    $card_classes[] = 'showcase-card--featured';
    if ( ! $has_shot ) {
        $card_classes[] = 'showcase-card--no-shot';
    }
}

$has_badges = ! empty( $project['primary_badge'] ) || ! empty( $project['tech_tags'] );
$fallback_cats = ( ! $has_badges && ! empty( $project['categories'] ) ) ? $project['categories'] : array();

$has_meta      = ! empty( $project['status_label'] ) || ! empty( $project['version'] ) || ! empty( $project['meta_note'] );
$has_primary   = ! empty( $project['primary_btn_url'] );
$has_secondary = ! empty( $project['detail_url'] );
$has_foot      = $has_meta || $has_primary || $has_secondary;

$title_tag = $is_featured ? 'h2' : 'h3';
?>
<article class="<?php echo esc_attr( implode( ' ', $card_classes ) ); ?>"
         data-featured="<?php echo $is_featured ? '1' : '0'; ?>"
         data-categories="<?php echo esc_attr( $project['category_slugs'] ); ?>"
         data-search="<?php echo esc_attr( $project['search_text'] ); ?>">

    <div class="sc-card-body">
        <div class="sc-card-top">
            <?php if ( ! empty( $project['title_url'] ) ) : ?>
                <a href="<?php echo esc_url( $project['title_url'] ); ?>"
                   class="sc-app-icon-link"
                   aria-hidden="true"
                   tabindex="-1"
                   <?php echo ! empty( $project['title_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
            <?php else : ?>
                <div class="sc-app-icon-link" aria-hidden="true">
            <?php endif; ?>
                <span class="sc-app-icon">
                    <?php if ( ! empty( $project['icon_url'] ) ) : ?>
                        <img src="<?php echo esc_url( $project['icon_url'] ); ?>"
                             alt="<?php echo esc_attr( $project['title'] ); ?>"
                             loading="lazy"
                             decoding="async">
                    <?php else : ?>
                        <span class="sc-app-icon-fallback"><?php echo esc_html( $project['monogram'] ); ?></span>
                    <?php endif; ?>
                </span>
            <?php if ( ! empty( $project['title_url'] ) ) : ?>
                </a>
            <?php else : ?>
                </div>
            <?php endif; ?>

            <?php if ( ( $is_featured && ! empty( $project['featured_label'] ) ) || $has_badges || ! empty( $fallback_cats ) ) : ?>
                <div class="sc-card-badges">
                    <?php if ( $is_featured && ! empty( $project['featured_label'] ) ) : ?>
                        <span class="sc-badge sc-badge--featured"><?php echo esc_html( $project['featured_label'] ); ?></span>
                    <?php endif; ?>

                    <div class="sc-badges">
                        <?php if ( ! empty( $project['primary_badge'] ) ) : ?>
                            <span class="sc-badge sc-badge--accent"><?php echo esc_html( $project['primary_badge'] ); ?></span>
                        <?php endif; ?>

                        <?php foreach ( $project['tech_tags'] as $tech_tag ) : ?>
                            <span class="sc-badge sc-badge--gray"><?php echo esc_html( $tech_tag ); ?></span>
                        <?php endforeach; ?>

                        <?php foreach ( $fallback_cats as $cat_index => $cat ) : ?>
                            <span class="sc-badge <?php echo 0 === $cat_index ? 'sc-badge--accent' : 'sc-badge--gray'; ?>">
                                <?php echo esc_html( $cat['name'] ); ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="sc-card-copy">
            <<?php echo $title_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="sc-card-title">
                <?php if ( ! empty( $project['title_url'] ) ) : ?>
                    <a href="<?php echo esc_url( $project['title_url'] ); ?>"
                       <?php echo ! empty( $project['title_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                        <span><?php echo esc_html( $project['title'] ); ?></span>
                        <span class="sc-card-arrow" aria-hidden="true">↗</span>
                    </a>
                <?php else : ?>
                    <span><?php echo esc_html( $project['title'] ); ?></span>
                <?php endif; ?>
            </<?php echo $title_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

            <?php if ( ! empty( $project['summary'] ) ) : ?>
                <p class="sc-card-summary"><?php echo esc_html( $project['summary'] ); ?></p>
            <?php endif; ?>
        </div>

        <?php if ( $is_featured && ! empty( $project['metrics'] ) ) : ?>
            <div class="sc-specs" style="--specs-cols: <?php echo count( $project['metrics'] ); ?>;">
                <?php foreach ( $project['metrics'] as $metric ) : ?>
                    <div class="sc-spec-item">
                        <?php if ( '' !== $metric['value'] ) : ?>
                            <b><?php echo esc_html( $metric['value'] ); ?></b>
                        <?php endif; ?>
                        <?php if ( '' !== $metric['label'] ) : ?>
                            <span><?php echo esc_html( $metric['label'] ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( $has_foot ) : ?>
            <div class="sc-card-foot">
                <?php if ( $has_meta ) : ?>
                    <div class="sc-card-meta">
                        <?php
                        $meta_segments = array();
                        if ( ! empty( $project['status_label'] ) ) {
                            $meta_segments[] = sprintf(
                                '<span class="sc-status-pill"><i class="sc-status-dot sc-status-dot--%s" aria-hidden="true"></i>%s</span>',
                                esc_attr( $project['status_tone'] ),
                                esc_html( $project['status_label'] )
                            );
                        }
                        if ( ! empty( $project['version'] ) ) {
                            $meta_segments[] = '<span class="sc-meta-version">' . esc_html( $project['version'] ) . '</span>';
                        }
                        if ( ! empty( $project['meta_note'] ) ) {
                            $meta_segments[] = '<span class="sc-meta-note">' . esc_html( $project['meta_note'] ) . '</span>';
                        }
                        echo implode( '<span class="sc-meta-sep" aria-hidden="true">·</span>', $meta_segments ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </div>
                <?php endif; ?>

                <?php if ( $has_primary || $has_secondary ) : ?>
                    <div class="sc-card-actions">
                        <?php if ( $has_primary ) : ?>
                            <a class="sc-btn sc-btn-primary<?php echo $is_featured ? '' : ' sc-btn-sm'; ?>"
                               href="<?php echo esc_url( $project['primary_btn_url'] ); ?>"
                               <?php echo ! empty( $project['primary_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <?php echo esc_html( $project['primary_btn_label'] ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( $has_secondary ) : ?>
                            <?php if ( $is_featured ) : ?>
                                <a class="sc-btn sc-btn-secondary"
                                   href="<?php echo esc_url( $project['detail_url'] ); ?>"
                                   <?php echo ! empty( $project['detail_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                    <?php echo esc_html( $project['secondary_btn_label'] ); ?>
                                    <span aria-hidden="true">→</span>
                                </a>
                            <?php else : ?>
                                <a class="sc-more-link"
                                   href="<?php echo esc_url( $project['detail_url'] ); ?>"
                                   <?php echo ! empty( $project['detail_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                    <?php echo esc_html( $project['secondary_btn_label'] ); ?>
                                    <span aria-hidden="true">→</span>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ( $has_shot ) : ?>
        <?php if ( ! empty( $project['title_url'] ) ) : ?>
            <a class="sc-shot"
               href="<?php echo esc_url( $project['title_url'] ); ?>"
               tabindex="-1"
               aria-hidden="true"
               <?php echo ! empty( $project['title_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                <img class="sc-shot-img"
                     src="<?php echo esc_url( $project['screenshot_url'] ); ?>"
                     alt="<?php echo esc_attr( $project['title'] ); ?>"
                     loading="lazy"
                     decoding="async">
            </a>
        <?php else : ?>
            <div class="sc-shot">
                <img class="sc-shot-img"
                     src="<?php echo esc_url( $project['screenshot_url'] ); ?>"
                     alt="<?php echo esc_attr( $project['title'] ); ?>"
                     loading="lazy"
                     decoding="async">
            </div>
        <?php endif; ?>
    <?php endif; ?>

</article>
