<?php
/**
 * 404 Not Found Template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();
?>

<main class="main-wrapper">
    <div class="content-card text-center py-5">
        <div class="error-404-code">404</div>
        <h1 class="post-title-main mb-3"><?php esc_html_e( 'Page Not Found', 'subo4-classic-theme' ); ?></h1>
        <p class="error-404-desc mb-4">
            <?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'subo4-classic-theme' ); ?>
        </p>

        <form role="search" method="get" class="search-form-inline mb-4" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="subo4-404-search"><?php esc_html_e( 'Search for:', 'subo4-classic-theme' ); ?></label>
            <input id="subo4-404-search"
                   type="search"
                   class="form-control"
                   placeholder="<?php esc_attr_e( 'Search posts...', 'subo4-classic-theme' ); ?>"
                   value="<?php echo esc_attr( get_search_query() ); ?>"
                   name="s">
            <button type="submit" class="btn btn-dark"><?php esc_html_e( 'Search', 'subo4-classic-theme' ); ?></button>
        </form>

        <div>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-decoration-none">
                &larr; <?php esc_html_e( 'Back to Home', 'subo4-classic-theme' ); ?>
            </a>
        </div>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
