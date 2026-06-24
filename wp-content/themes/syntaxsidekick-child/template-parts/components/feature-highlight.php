<?php
/**
 * Reusable feature highlight item.
 *
 * @package SyntaxSidekick_Child
 */

$icon        = isset($args['icon']) ? (string) $args['icon'] : '</>';
$title       = isset($args['title']) ? (string) $args['title'] : '';
$description = isset($args['description']) ? (string) $args['description'] : '';

if ('' === $title) {
    return;
}
?>
<li class="ss-feature-item">
    <span class="ss-feature-icon" aria-hidden="true"><?php echo esc_html($icon); ?></span>
    <h3><?php echo esc_html($title); ?></h3>
    <?php if ('' !== $description) : ?>
        <p><?php echo esc_html($description); ?></p>
    <?php endif; ?>
</li>
