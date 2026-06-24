<?php
/**
 * Reusable article list item.
 *
 * @package SyntaxSidekick_Child
 */

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : 0;
if ($post_id <= 0) {
    return;
}

$title      = get_the_title($post_id);
$permalink  = get_permalink($post_id);
$excerpt    = get_the_excerpt($post_id);
$date       = get_the_date('M j, Y', $post_id);
$date_iso   = get_the_date('c', $post_id);
$read_time  = syntaxsidekick_get_post_read_time($post_id);
$thumb_id   = get_post_thumbnail_id($post_id);
?>
<article class="ss-list-item">
    <a class="ss-list-item__thumb" href="<?php echo esc_url($permalink); ?>">
        <?php if ($thumb_id) : ?>
            <?php
            echo wp_get_attachment_image(
                $thumb_id,
                'thumbnail',
                false,
                array(
                    'loading' => 'lazy',
                    'decoding' => 'async',
                )
            );
            ?>
        <?php else : ?>
            <span><?php echo esc_html(wp_trim_words($title, 2, '')); ?></span>
        <?php endif; ?>
    </a>
    <div class="ss-list-item__body">
        <h3><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
        <p><?php echo esc_html(wp_trim_words($excerpt, 14, '...')); ?></p>
        <?php get_template_part('template-parts/components/metadata-row', null, array('date' => $date, 'date_iso' => $date_iso, 'read_time' => $read_time, 'class' => 'ss-card-meta')); ?>
    </div>
</article>
