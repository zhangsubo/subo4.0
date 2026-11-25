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
    <!-- 搜索框与结果概览 -->
    <div class="content-card text-center">
        <h1 class="post-title-main mb-2">
            <?php
            printf(
                /* translators: %s: search query */
                esc_html__( 'Search Results for: %s', 'subo4-classic-theme' ),
                '<span>' . esc_html( $search_query ) . '</span>'
            );
            ?>
        </h1>
        <p class="search-result-meta mb-3">
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
                   class="form-control"
                   placeholder="<?php esc_attr_e( 'Search posts...', 'subo4-classic-theme' ); ?>"
                   value="<?php echo esc_attr( $search_query ); ?>"
                   name="s">
            <button type="submit" class="btn btn-dark"><?php esc_html_e( 'Search', 'subo4-classic-theme' ); ?></button>
        </form>
    </div>

    <!-- 搜索结果列表 -->
    <div class="content-card">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article class="row post-item">
                    <div class="col-md-2 text-center text-md-start mb-2 mb-md-0">
                        <div class="post-date-month">
                            <?php echo esc_html( get_the_date( 'M' ) ); ?>
                        </div>
                        <div class="post-date-year">
                            <?php echo esc_html( get_the_date( 'Y' ) ); ?>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h3 class="h5 post-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <p class="post-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 30, '...' ) ); ?></p>
                        <div class="post-tags">
                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) {
                                foreach ( $tags as $tag ) {
                                    printf(
                                        '<a href="%s" class="badge bg-light text-secondary text-decoration-none">#%s</a> ',
                                        esc_url( get_tag_link( $tag->term_id ) ),
                                        esc_html( $tag->name )
                                    );
                                }
                            }
                            ?>
                        </div>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="empty-state">
                <p class="mb-3"><?php esc_html_e( 'Sorry, nothing matched your search terms. Please try again with different keywords.', 'subo4-classic-theme' ); ?></p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-decoration-none">
                    &larr; <?php esc_html_e( 'Back to Home', 'subo4-classic-theme' ); ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- 分页 -->
        <div class="pagination-wrapper">
            <?php
            the_posts_pagination( array(
                'prev_text'          => '&larr;',
                'next_text'          => '&rarr;',
                'before_page_number' => '',
                'after_page_number'  => '',
                'mid_size'           => 2,
                'end_size'           => 1,
                'screen_reader_text' => esc_html__( 'Posts navigation', 'subo4-classic-theme' ),
            ) );
            ?>
        </div>
    </div>

    <?php get_template_part( 'template-parts/social-links' ); ?>
</main>

<?php get_footer(); ?>
