<?php
/**
 * 单个 Vibe Coding 作品详情页模板
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

while ( have_posts() ) :
    the_post();
    $post_id           = get_the_ID();
    $project           = subo4_get_project_data( $post_id );
    $projects_page_url = subo4_get_projects_page_url();
    $raw_content       = trim( (string) get_post_field( 'post_content', $post_id ) );
    $has_shot          = ! empty( $project['screenshot_url'] );

    $hero_classes = array( 'showcase-detail-hero' );
    if ( ! $has_shot ) {
        $hero_classes[] = 'showcase-detail-hero--no-shot';
    }

    $has_meta = ! empty( $project['status_label'] )
        || ! empty( $project['version'] )
        || ! empty( $project['meta_note'] );
?>

<main class="showcase-wrapper showcase-detail-wrapper">
    <?php if ( '' !== $projects_page_url ) : ?>
        <div class="sc-detail-topbar">
            <a class="sc-back-link" href="<?php echo esc_url( $projects_page_url ); ?>">
                <span aria-hidden="true">←</span>
                <span>返回作品列表</span>
            </a>
        </div>
    <?php endif; ?>

    <section class="<?php echo esc_attr( implode( ' ', $hero_classes ) ); ?>">
        <div class="sc-detail-main">
            <div class="sc-card-top">
                <div class="sc-app-icon-link" aria-hidden="true">
                    <span class="sc-app-icon sc-app-icon--detail">
                        <?php if ( ! empty( $project['icon_url'] ) ) : ?>
                            <img src="<?php echo esc_url( $project['icon_url'] ); ?>"
                                 alt="<?php echo esc_attr( $project['title'] ); ?>"
                                 decoding="async">
                        <?php else : ?>
                            <span class="sc-app-icon-fallback"><?php echo esc_html( $project['monogram'] ); ?></span>
                        <?php endif; ?>
                    </span>
                </div>

                <?php if ( ! empty( $project['is_featured'] ) || ! empty( $project['primary_badge'] ) || ! empty( $project['tech_tags'] ) || ! empty( $project['categories'] ) ) : ?>
                    <div class="sc-card-badges">
                        <?php if ( ! empty( $project['is_featured'] ) && ! empty( $project['featured_label'] ) ) : ?>
                            <span class="sc-badge sc-badge--featured"><?php echo esc_html( $project['featured_label'] ); ?></span>
                        <?php endif; ?>

                        <div class="sc-badges">
                            <?php if ( ! empty( $project['primary_badge'] ) ) : ?>
                                <span class="sc-badge sc-badge--accent"><?php echo esc_html( $project['primary_badge'] ); ?></span>
                            <?php endif; ?>

                            <?php foreach ( $project['tech_tags'] as $tech_tag ) : ?>
                                <span class="sc-badge sc-badge--gray"><?php echo esc_html( $tech_tag ); ?></span>
                            <?php endforeach; ?>

                            <?php foreach ( $project['categories'] as $cat ) : ?>
                                <span class="sc-badge sc-badge--gray"><?php echo esc_html( $cat['name'] ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="sc-detail-copy">
                <h1 class="sc-detail-title"><?php the_title(); ?></h1>
                <?php if ( ! empty( $project['summary'] ) ) : ?>
                    <p class="sc-detail-summary"><?php echo esc_html( $project['summary'] ); ?></p>
                <?php endif; ?>
            </div>

            <?php if ( ! empty( $project['metrics'] ) ) : ?>
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

            <div class="sc-card-foot sc-detail-foot">
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
                    $meta_segments[] = '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date( 'Y-m-d' ) ) . '</time>';
                    echo implode( '<span class="sc-meta-sep" aria-hidden="true">·</span>', $meta_segments ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>

                <?php if ( ! empty( $project['primary_btn_url'] ) || ( 'custom' === $project['detail_mode'] && ! empty( $project['detail_url'] ) ) ) : ?>
                    <div class="sc-card-actions sc-detail-actions">
                        <?php if ( ! empty( $project['primary_btn_url'] ) ) : ?>
                            <a class="sc-btn sc-btn-primary"
                               href="<?php echo esc_url( $project['primary_btn_url'] ); ?>"
                               <?php echo ! empty( $project['primary_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <?php echo esc_html( $project['primary_btn_label'] ); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ( 'custom' === $project['detail_mode'] && ! empty( $project['detail_url'] ) ) : ?>
                            <a class="sc-btn sc-btn-secondary"
                               href="<?php echo esc_url( $project['detail_url'] ); ?>"
                               <?php echo ! empty( $project['detail_is_external'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                                <?php echo esc_html( $project['secondary_btn_label'] ); ?>
                                <span aria-hidden="true">→</span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( $has_shot ) : ?>
            <div class="sc-shot sc-detail-shot">
                <img class="sc-shot-img"
                     src="<?php echo esc_url( $project['screenshot_url'] ); ?>"
                     alt="<?php echo esc_attr( $project['title'] ); ?>"
                     decoding="async">
            </div>
        <?php endif; ?>
    </section>

    <?php if ( '' !== $raw_content ) : ?>
        <article class="showcase-detail-content-card">
            <div class="post-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endif; ?>

    <?php if ( comments_open() || get_comments_number() ) : ?>
        <div class="showcase-detail-comments-card comments-section">
            <?php comments_template(); ?>
        </div>
    <?php endif; ?>
</main>

<?php
endwhile;

get_footer();
