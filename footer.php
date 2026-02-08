<?php
/**
 * Footer template
 *
 * @package SUBO4_Classic_Theme
 */

// 获取主题设置
$icp_number = get_field('SUBO4_icp_number', 'option');
$complaints_email = get_field('SUBO4_complaints_email', 'option');
?>

    <footer class="text-center py-4" style="color: #666; font-size: 13px;">
        <div class="container" style="max-width: 800px;">
            <p class="mb-1">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> · 
                主题：<a href="https://github.com/zhangsubo/subo4.0" target="_blank" style="color: #666; text-decoration: none;">SUBO4.0</a>
            </p>
            <?php if ($icp_number || $complaints_email) : ?>
            <p class="mb-0" style="font-size: 12px;">
                <?php if ($icp_number) : ?>
                <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer" style="color: #666; text-decoration: none;">
                    <?php echo esc_html($icp_number); ?>
                </a>
                <?php endif; ?>
                <?php if ($icp_number && $complaints_email) : ?> · <?php endif; ?>
                <?php if ($complaints_email) : ?>
                违法和不良信息举报邮箱：<a href="mailto:<?php echo esc_attr($complaints_email); ?>" style="color: #666; text-decoration: none;"><?php echo esc_html($complaints_email); ?></a>
                <?php endif; ?>
            </p>
            <?php endif; ?>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>