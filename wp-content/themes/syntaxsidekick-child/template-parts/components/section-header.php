<?php
/**
 * Reusable section header.
 *
 * @package SyntaxSidekick_Child
 */

$title      = isset($args['title']) ? (string) $args['title'] : '';
$subtitle   = isset($args['subtitle']) ? (string) $args['subtitle'] : '';
$link_label = isset($args['link_label']) ? (string) $args['link_label'] : '';
$link_url   = isset($args['link_url']) ? (string) $args['link_url'] : '';
$title_id   = isset($args['title_id']) ? (string) $args['title_id'] : '';

if ('' === $title) {
    return;
}
?>
<header class="ss-section-header">
    <div>
        <h2 class="ss-home-title"<?php echo '' !== $title_id ? ' id="' . esc_attr($title_id) . '"' : ''; ?>><?php echo esc_html($title); ?></h2>
        <?php if ('' !== $subtitle) : ?>
            <p class="ss-home-subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>
    </div>
    <?php if ('' !== $link_label && '' !== $link_url) : ?>
        <a class="ss-section-link" href="<?php echo esc_url($link_url); ?>"><?php echo esc_html($link_label); ?></a>
    <?php endif; ?>
</header>
