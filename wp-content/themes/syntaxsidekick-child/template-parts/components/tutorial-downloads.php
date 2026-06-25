<?php
/**
 * Tutorial downloadable resources card.
 *
 * @package SyntaxSidekick_Child
 */

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : 0;
$featured_image_id = isset($args['featured_image_id']) ? (int) $args['featured_image_id'] : 0;

$attachments = array();
if ($post_id > 0) {
    $attachments = get_children(
        array(
            'post_parent' => $post_id,
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'numberposts' => 3,
            'exclude' => $featured_image_id > 0 ? array($featured_image_id) : array(),
            'orderby' => 'menu_order date ID',
            'order' => 'ASC',
        )
    );
}
?>
<section class="ss-sidebar-card" aria-labelledby="ss-tutorial-downloads-title">
    <h2 id="ss-tutorial-downloads-title" class="ss-section-title"><?php echo esc_html__('Downloadable Resources', 'syntaxsidekick-child'); ?></h2>

    <?php if (! empty($attachments)) : ?>
        <ul class="ss-download-list">
            <?php foreach ($attachments as $attachment) : ?>
                <?php
                $attachment_id = (int) $attachment->ID;
                $attachment_url = wp_get_attachment_url($attachment_id);
                $file_path = get_attached_file($attachment_id);
                $extension = strtoupper((string) pathinfo((string) $file_path, PATHINFO_EXTENSION));
                $file_size = $file_path && file_exists($file_path) ? size_format((int) filesize($file_path), 0) : '';
                ?>
                <li>
                    <a class="ss-download-link" href="<?php echo esc_url($attachment_url ? $attachment_url : ''); ?>">
                        <span class="ss-download-link__badge"><?php echo esc_html($extension ? $extension : 'FILE'); ?></span>
                        <span class="ss-download-link__copy">
                            <span class="ss-download-link__title"><?php echo esc_html(get_the_title($attachment_id)); ?></span>
                            <?php if ('' !== $file_size) : ?>
                                <span class="ss-download-link__meta"><?php echo esc_html($file_size); ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p class="ss-sidebar-empty"><?php echo esc_html__('No downloadable resources are attached to this tutorial yet.', 'syntaxsidekick-child'); ?></p>
    <?php endif; ?>
</section>