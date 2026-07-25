<?php
/**
 * Main index template
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

// Get ACF field values with fallbacks
$avatar = subo4_get_avatar();
$author_name = subo4_get_author_name();
$bio = subo4_get_bio();
?>

<main class="main-wrapper">
    <!-- Profile Card -->
    <div class="content-card">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="profile-avatar-link">
                    <img src="<?php echo esc_url( $avatar ); ?>"
                         alt="<?php echo esc_attr( $author_name ); ?>"
                         class="profile-avatar-img"
                         loading="eager">
                </a>
            </div>
            <div class="col-md-9 text-center text-md-start">
                <h2 class="profile-name"><?php echo esc_html( $author_name ); ?></h2>
                <p class="profile-bio"><?php echo esc_html( $bio ); ?></p>
            </div>
        </div>
    </div>

    <!-- Post List -->
    <div class="content-card">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
        ?>
            <article class="row post-item">
                <div class="col-md-2 text-center text-md-start mb-2 mb-md-0">
                    <div class="text-muted post-date-month">
                        <?php echo get_the_date( 'M' ); ?>
                    </div>
                    <div class="post-date-year">
                        <?php echo get_the_date( 'Y' ); ?>
                    </div>
                </div>
                <div class="col-md-10">
                    <h3 class="h5 post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="post-excerpt"><?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?></p>
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
        <?php
            endwhile;
        else :
        ?>
            <p class="empty-state"><?php esc_html_e( 'No posts found.', 'subo4-classic-theme' ); ?></p>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            <?php
            the_posts_pagination( array(
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'before_page_number' => '',
                'after_page_number'  => '',
                'mid_size'  => 2,
                'end_size'  => 1,
                'screen_reader_text' => esc_html__( 'Posts navigation', 'subo4-classic-theme' ),
            ) );
            ?>
        </div>
    </div>

    <!-- Social Links -->
    <?php get_template_part( 'template-parts/social-links' ); ?>

</main>

<?php get_footer(); ?>
