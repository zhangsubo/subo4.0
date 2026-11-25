<?php
/**
 * Search Results Template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

global $wp_query;
$search_query  = get_search_query();
$results_count = isset( $wp_query->found_posts ) ? (int) $wp_query->found_posts : 0;
?>

<main class="main-wrapper">
    <div class="content-card text-center">
        <h1 class="post-title-main">
            <?php
            printf(
                /* translators: %s: search query */
                esc_html__( 'Search Results for: %s', 'subo4-classic-theme' ),
                '<span>' . esc_html( $search_query ) . '</span>'
            );
            ?>
        </h1>
        <p class="search-result-meta">
            <?php
            printf(
                /* translators: %d: number of matching posts */
                esc_html( _n( '%d result found', '%d results found', $results_count, 'subo4-classic-theme' ) ),
                $results_count
            );
            ?>
        </p>

        <form role="search" method="get" class="search-form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="subo4-search-input"><?php esc_html_e( 'Search for:', 'subo4-classic-theme' ); ?></label>
            <input id="subo4-search-input"
                   type="search"
                   class="search-input"
                   placeholder="<?php esc_attr_e( 'Search posts...', 'subo4-classic-theme' ); ?>"
                   value="<?php echo esc_attr( $search_query ); ?>"
                   name="s">
            <button type="submit" class="search-submit-btn"><?php esc_html_e( 'Search', 'subo4-classic-theme' ); ?></button>
        </form>
    </div>

    <div class="content-card">
        <?php if ( have_posts() ) : ?>
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/post-item' );
            endwhile;
            ?>
        <?php else : ?>
            <div class="empty-state">
                <p><?php esc_html_e( 'Sorry, nothing matched your search terms. Please try again with different keywords.', 'subo4-classic-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-home-link">
                    &larr; <?php esc_html_e( 'Back to Home', 'subo4-classic-theme' ); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="pagination-wrapper">
            <?php subo4_render_pagination(); ?>
        </div>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
