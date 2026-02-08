<?php
/**
 * 单个文章模板
 *
 * @package SUBO4_Block_Theme
 */

get_header();

// 获取ACF字段值
$aboutme_content = get_field('SUBO4_aboutme_content', 'option') ?: '张小璋，野蛮生长成世界500强企业供应链金融产品经理的法语毕业生。微信公众号：iamzhangsubo。一直在互联网金融公司从事产品经理工作并负责互联网金融产品线，深耕互联网金融和区块链领域。「PMCAFF」、「人人都是产品经理」专栏作家、「PmTalk」签约作家。';

while (have_posts()) : the_post();
?>

<main class="main-wrapper">
    <article class="content-card">
        <!-- 文章头部 -->
        <div class="text-center mb-4">
            <h1 class="post-title-main" style="font-size: 24px;"><?php the_title(); ?></h1>
            <div class="post-meta text-muted" style="font-size: 14px;">
                <?php echo get_the_date('M j, Y'); ?> · 作者：<?php the_author(); ?>
            </div>
        </div>

        <hr class="mb-4">

        <!-- 文章内容 -->
        <div class="post-content">
            <?php the_content(); ?>
        </div>

        <!-- Over标记 -->
        <div class="over-marker">
            <div class="line"></div>
            <span>over</span>
            <div class="line"></div>
        </div>

        <!-- About Me区块 -->
        <div class="about-me-section">
            <h3>#About Me</h3>
            <p><?php echo nl2br(esc_html($aboutme_content)); ?></p>
        </div>

        <!-- CC协议声明 -->
        <div class="cc-license">
            <p>
                本作品采用 <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/deed.zh" target="_blank" rel="noopener noreferrer">CC BY-NC-SA 4.0</a> 协议进行许可
            </p>
        </div>
    </article>

    <!-- 社交链接 -->
    <?php get_template_part('template-parts/social-links'); ?>

    <!-- 评论区 -->
    <?php if (comments_open()) : ?>
    <div class="content-card comments-section">
        <?php comments_template(); ?>
    </div>
    <?php endif; ?>
</main>
<?php endwhile; ?>

<?php get_footer(); ?>
