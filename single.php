<?php
/**
 * Single post template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

$aboutme_content = subo4_get_aboutme_content();

while ( have_posts() ) :
    the_post();
    ?>

<main class="main-wrapper">
    <article class="content-card post-article" data-post-toc>
        <div class="post-header text-center">
            <h1 class="post-title-main"><?php the_title(); ?></h1>
            <div class="post-meta">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    <?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
                </time>
                <?php
                printf(
                    /* translators: %s: post author */
                    esc_html__( ' · Author: %s', 'subo4-classic-theme' ),
                    '<span class="author">' . esc_html( get_the_author() ) . '</span>'
                );
                ?>
                <span class="post-reading-time">
                    <?php
                    $reading_minutes = subo4_get_reading_time( get_the_ID() );
                    printf(
                        /* translators: %d: estimated reading time in minutes */
                        esc_html( _n( ' · %d min read', ' · %d min read', $reading_minutes, 'subo4-classic-theme' ) ),
                        $reading_minutes
                    );
                    ?>
                </span>
            </div>
        </div>

        <hr class="post-header-divider">

        <div class="post-content">
            <?php the_content(); ?>
        </div>

        <div class="over-marker">
            <div class="line"></div>
            <span><?php esc_html_e( 'over', 'subo4-classic-theme' ); ?></span>
            <div class="line"></div>
        </div>

        <div class="about-me-section">
            <h3><?php esc_html_e( '#About Me', 'subo4-classic-theme' ); ?></h3>
            <p><?php echo wp_kses_post( nl2br( $aboutme_content ) ); ?></p>
        </div>

        <div class="cc-license">
            <p>
                <?php
                printf(
                    /* translators: %s: CC license link */
                    esc_html__( 'This work is licensed under %s', 'subo4-classic-theme' ),
                    '<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank" rel="noopener noreferrer">CC BY-NC-SA 4.0</a>'
                );
                ?>
            </p>
        </div>

        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        if ( $prev_post || $next_post ) :
            ?>
        <nav class="post-navigation" aria-label="<?php esc_attr_e( 'Post navigation', 'subo4-classic-theme' ); ?>">
            <div class="post-nav-item post-nav-prev">
                <?php if ( $prev_post ) : ?>
                    <span class="post-nav-label">&larr; <?php esc_html_e( 'Previous', 'subo4-classic-theme' ); ?></span>
                    <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="post-nav-title">
                        <?php echo esc_html( get_the_title( $prev_post ) ); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="post-nav-item post-nav-next">
                <?php if ( $next_post ) : ?>
                    <span class="post-nav-label"><?php esc_html_e( 'Next', 'subo4-classic-theme' ); ?> &rarr;</span>
                    <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="post-nav-title">
                        <?php echo esc_html( get_the_title( $next_post ) ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </nav>
        <?php endif; ?>
    </article>

    <?php get_template_part( 'template-parts/social-links' ); ?>

    <?php if ( comments_open() || get_comments_number() ) : ?>
    <div class="content-card comments-section">
        <?php comments_template(); ?>
    </div>
    <?php endif; ?>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
