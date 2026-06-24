<?php
/**
 * Reusable CTA button.
 *
 * @package SyntaxSidekick_Child
 */

$label   = isset($args['label']) ? (string) $args['label'] : '';
$url     = isset($args['url']) ? (string) $args['url'] : '#';
$variant = isset($args['variant']) ? (string) $args['variant'] : 'primary';

if ('' === $label) {
    return;
}

$classes = 'ss-button';
$classes .= 'secondary' === $variant ? ' ss-button-secondary' : ' ss-button-primary';
?>
<a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url($url); ?>"><?php echo esc_html($label); ?></a>
