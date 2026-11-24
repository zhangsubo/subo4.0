<?php
/**
 * Template Name: Vibe Coding 作品展示
 * Template Post Type: page
 *
 * @package SUBO4_Classic_Theme
 */

get_header();

while ( have_posts() ) :
    the_post();
    $page_id      = get_the_ID();
    $eyebrow      = trim( (string) subo4_get_project_field( 'subo4_showcase_eyebrow', $page_id, 'VIBE CODING' ) );
    $subtitle     = trim( (string) subo4_get_project_field( 'subo4_showcase_subtitle', $page_id, '' ) );
    $show_toolbar = (bool) subo4_get_project_field( 'subo4_showcase_show_toolbar', $page_id, true );
    $show_count   = (bool) subo4_get_project_field( 'subo4_showcase_show_count', $page_id, true );

    // 查询所有已发布作品
    $projects_query = new WP_Query( array(
        'post_type'      => 'subo4_project',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => array(
            'menu_order' => 'ASC',
            'date'       => 'DESC',
        ),
        'no_found_rows'  => true,
    ) );

    $featured_projects = array();
    $regular_projects  = array();
    $used_term_ids     = array();

    if ( $projects_query->have_posts() ) {
        while ( $projects_query->have_posts() ) {
            $projects_query->the_post();
            $item = subo4_get_project_data( get_the_ID() );
            if ( empty( $item ) ) {
                continue;
            }

            if ( ! empty( $item['categories'] ) ) {
                foreach ( $item['categories'] as $cat ) {
                    $used_term_ids[ $cat['term_id'] ] = true;
                }
            }

            if ( ! empty( $item['is_featured'] ) ) {
                $featured_projects[] = $item;
            } else {
                $regular_projects[] = $item;
            }
        }
        wp_reset_postdata();
    }

    $all_projects = array_merge( $featured_projects, $regular_projects );
    $total_count  = count( $all_projects );

    // 获取实际关联到已发布作品的分类列表
    $active_categories = array();
    if ( ! empty( $used_term_ids ) ) {
        $terms = get_terms( array(
            'taxonomy'   => 'subo4_project_cat',
            'hide_empty' => true,
            'orderby'    => 'name',
            'order'      => 'ASC',
        ) );
        if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
            foreach ( $terms as $term ) {
                if ( isset( $used_term_ids[ $term->term_id ] ) ) {
                    $active_categories[] = $term;
                }
            }
        }
    }

    $raw_page_content = trim( (string) get_post_field( 'post_content', $page_id ) );
?>

<main class="showcase-wrapper" data-showcase-root>
    <section class="showcase-section" aria-labelledby="showcase-page-title">
        <header class="showcase-header">
            <?php if ( '' !== $eyebrow ) : ?>
                <div class="showcase-eyebrow"><?php echo esc_html( $eyebrow ); ?></div>
            <?php endif; ?>

            <div class="showcase-title-row">
                <h1 id="showcase-page-title" class="showcase-title"><?php the_title(); ?></h1>
                <?php if ( $show_count && $total_count > 0 ) : ?>
                    <span class="showcase-count-pill"
                          data-showcase-count
                          data-total="<?php echo absint( $total_count ); ?>">
                        共 <?php echo absint( $total_count ); ?> 款作品
                    </span>
                <?php endif; ?>
            </div>

            <?php if ( '' !== $subtitle ) : ?>
                <p class="showcase-lead"><?php echo nl2br( esc_html( $subtitle ) ); ?></p>
            <?php endif; ?>

            <?php if ( '' !== $raw_page_content ) : ?>
                <div class="showcase-intro-content">
                    <?php the_content(); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if ( $total_count > 0 ) : ?>
            <?php if ( $show_toolbar ) : ?>
                <div class="showcase-toolbar">
                    <?php if ( ! empty( $active_categories ) ) : ?>
                        <div class="sc-cat-tabs" role="group" aria-label="按作品分类筛选">
                            <button type="button"
                                    class="sc-cat-tab"
                                    data-category-filter="all"
                                    aria-pressed="true">全部</button>
                            <?php foreach ( $active_categories as $cat_term ) : ?>
                                <button type="button"
                                        class="sc-cat-tab"
                                        data-category-filter="<?php echo esc_attr( $cat_term->slug ); ?>"
                                        aria-pressed="false"><?php echo esc_html( $cat_term->name ); ?></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <label class="sc-search-box">
                        <span class="screen-reader-text">搜索作品</span>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.2-3.2"></path>
                        </svg>
                        <input type="search"
                               data-showcase-search
                               placeholder="搜索作品名称、技术栈或功能…"
                               autocomplete="off">
                        <button type="button"
                                class="sc-search-clear"
                                data-showcase-search-clear
                                hidden
                                aria-label="清空搜索">×</button>
                    </label>
                </div>
            <?php endif; ?>

            <div class="showcase-grid" data-showcase-grid>
                <?php
                foreach ( $all_projects as $project ) {
                    get_template_part(
                        'template-parts/project-card',
                        null,
                        array(
                            'project'  => $project,
                            'featured' => ! empty( $project['is_featured'] ),
                        )
                    );
                }
                ?>
            </div>

            <div class="showcase-empty" data-showcase-empty hidden>
                <strong>未找到匹配的作品</strong>
                <span>试试切换作品分类，或清空关键词重新检索。</span>
                <button type="button" class="sc-btn sc-btn-secondary sc-btn-sm showcase-empty-reset" data-showcase-reset>
                    重置筛选
                </button>
            </div>

            <div class="screen-reader-text" aria-live="polite" data-showcase-status></div>
        <?php else : ?>
            <div class="showcase-empty showcase-empty--initial">
                <strong>暂无公开展示的作品</strong>
                <span>可在 WordPress 后台「作品 → 添加作品」创建并发布你的 Vibe Coding 项目。</span>
                <?php if ( current_user_can( 'edit_posts' ) ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=subo4_project' ) ); ?>"
                       class="sc-btn sc-btn-primary sc-btn-sm showcase-empty-reset">
                        新建第一个作品 →
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php
endwhile;

get_footer();
