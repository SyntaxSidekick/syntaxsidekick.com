<?php
/**
 * Reusable category badge.
 *
 * @package SyntaxSidekick_Child
 */

$label = isset($args['label']) ? (string) $args['label'] : '';

if ('' === $label) {
    return;
}
?>
<span class="ss-card-kicker"><?php echo esc_html($label); ?></span>
