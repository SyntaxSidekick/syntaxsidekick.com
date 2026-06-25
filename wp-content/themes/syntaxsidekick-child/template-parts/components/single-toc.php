<?php
/**
 * Reusable single-content sidebar TOC card.
 *
 * Uses TOC plugin output as source of truth and fails gracefully when unavailable.
 *
 * @package SyntaxSidekick_Child
 */

$heading = isset($args['heading']) ? (string) $args['heading'] : 'On This Page';
$title_id = isset($args['title_id']) ? sanitize_key((string) $args['title_id']) : 'ss-single-toc-title';
$fallback_html = isset($args['toc_html']) ? (string) $args['toc_html'] : '';

$toc_output = '';

if ('' !== trim($fallback_html)) {
    $toc_output = $fallback_html;
} elseif (function_exists('shortcode_exists') && shortcode_exists('ez-toc')) {
    $toc_output = (string) do_shortcode('[ez-toc]');
} elseif (function_exists('shortcode_exists') && shortcode_exists('toc')) {
    $toc_output = (string) do_shortcode('[toc]');
}

if ('' === trim(wp_strip_all_tags($toc_output))) {
    return;
}
?>
<section class="ss-sidebar-card ss-single-toc" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <h2 id="<?php echo esc_attr($title_id); ?>" class="ss-sidebar-card__title ss-section-title"><?php echo esc_html($heading); ?></h2>
    <div class="ss-single-toc__content">
        <?php echo wp_kses_post($toc_output); ?>
    </div>
</section>
