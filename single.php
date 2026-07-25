<?php
/**
 * Single post template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

// Get ACF field value with fallback
$aboutme_content = subo4_get_aboutme_content();

while ( have_posts() ) : the_post();
?>

<main class="main-wrapper">
    <article class="content-card">
        <!-- Post Header -->
        <div class="text-center mb-4">
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
            </div>
        </div>

        <hr class="mb-4">

        <!-- Post Content -->
        <div class="post-content">
            <?php the_content(); ?>
        </div>

        <!-- Over Marker -->
        <div class="over-marker">
            <div class="line"></div>
            <span><?php esc_html_e( 'over', 'subo4-classic-theme' ); ?></span>
            <div class="line"></div>
        </div>

        <!-- About Me Section -->
        <div class="about-me-section">
            <h3><?php esc_html_e( '#About Me', 'subo4-classic-theme' ); ?></h3>
            <p><?php echo wp_kses_post( nl2br( $aboutme_content ) ); ?></p>
        </div>

        <!-- CC License Notice -->
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
    </article>

    <!-- Social Links -->
    <?php get_template_part( 'template-parts/social-links' ); ?>

    <!-- Comments Section -->
    <?php if ( comments_open() || get_comments_number() ) : ?>
    <div class="content-card comments-section">
        <?php comments_template(); ?>
    </div>
    <?php endif; ?>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
