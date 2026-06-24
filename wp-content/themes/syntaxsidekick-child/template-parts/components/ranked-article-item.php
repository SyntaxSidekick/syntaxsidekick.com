<?php
/**
 * Reusable ranked article item.
 *
 * @package SyntaxSidekick_Child
 */

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : 0;
$rank    = isset($args['rank']) ? (int) $args['rank'] : 1;

if ($post_id <= 0) {
    return;
}

$title     = get_the_title($post_id);
$permalink = get_permalink($post_id);
$excerpt   = get_the_excerpt($post_id);
$read_time = syntaxsidekick_get_post_read_time($post_id);
?>
<article class="ss-ranked-item">
    <span class="ss-ranked-item__position" aria-hidden="true"><?php echo esc_html((string) $rank); ?></span>
    <div class="ss-ranked-item__body">
        <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
        <p><?php echo esc_html(wp_trim_words($excerpt, 12, '...')); ?></p>
    </div>
    <span class="ss-ranked-item__meta"><?php echo esc_html($read_time); ?></span>
</article>
