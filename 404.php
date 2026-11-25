<?php
/**
 * 404 Not Found Template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();
?>

<main class="main-wrapper">
    <div class="content-card error-404-card text-center">
        <div class="error-404-code">404</div>
        <h1 class="post-title-main"><?php esc_html_e( 'Page Not Found', 'subo4-classic-theme' ); ?></h1>
        <p class="error-404-desc">
            <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'subo4-classic-theme' ); ?>
        </p>

        <form role="search" method="get" class="search-form-inline" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="subo4-404-search"><?php esc_html_e( 'Search for:', 'subo4-classic-theme' ); ?></label>
            <input id="subo4-404-search"
                   type="search"
                   class="search-input"
                   placeholder="<?php esc_attr_e( 'Search posts...', 'subo4-classic-theme' ); ?>"
                   value="<?php echo esc_attr( get_search_query() ); ?>"
                   name="s">
            <button type="submit" class="search-submit-btn"><?php esc_html_e( 'Search', 'subo4-classic-theme' ); ?></button>
        </form>

        <div class="error-404-actions">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="back-home-link">
                &larr; <?php esc_html_e( 'Back to Home', 'subo4-classic-theme' ); ?>
            </a>
        </div>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
