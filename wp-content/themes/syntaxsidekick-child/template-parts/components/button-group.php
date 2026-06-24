<?php
/**
 * Reusable button group.
 *
 * @package SyntaxSidekick_Child
 */

$buttons = isset($args['buttons']) && is_array($args['buttons']) ? $args['buttons'] : array();

if (empty($buttons)) {
    return;
}
?>
<div class="ss-actions">
    <?php foreach ($buttons as $button) : ?>
        <?php
        get_template_part(
            'template-parts/components/cta-button',
            null,
            array(
                'label' => $button['label'] ?? '',
                'url' => $button['url'] ?? '#',
                'variant' => $button['variant'] ?? 'primary',
            )
        );
        ?>
    <?php endforeach; ?>
</div>
