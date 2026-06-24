<?php
/**
 * Reusable metadata row.
 *
 * @package SyntaxSidekick_Child
 */

$date      = isset($args['date']) ? (string) $args['date'] : '';
$date_iso  = isset($args['date_iso']) ? (string) $args['date_iso'] : '';
$read_time = isset($args['read_time']) ? (string) $args['read_time'] : '';
$extra     = isset($args['extra']) ? (string) $args['extra'] : '';
$class     = isset($args['class']) ? (string) $args['class'] : 'ss-card-meta';
?>
<div class="<?php echo esc_attr($class); ?>">
    <?php if ('' !== $date) : ?>
        <?php if ('' !== $date_iso) : ?>
            <time datetime="<?php echo esc_attr($date_iso); ?>"><?php echo esc_html($date); ?></time>
        <?php else : ?>
            <span><?php echo esc_html($date); ?></span>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ('' !== $read_time) : ?>
        <span aria-hidden="true">·</span>
        <span><?php echo esc_html($read_time); ?></span>
    <?php endif; ?>
    <?php if ('' !== $extra) : ?>
        <span aria-hidden="true">·</span>
        <span><?php echo esc_html($extra); ?></span>
    <?php endif; ?>
</div>
