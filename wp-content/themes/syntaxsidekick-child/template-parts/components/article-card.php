<?php
/**
 * Reusable article card.
 *
 * @package SyntaxSidekick_Child
 */

$post_id       = isset($args['post_id']) ? (int) $args['post_id'] : 0;
$heading_level = isset($args['heading_level']) ? max(2, min(4, (int) $args['heading_level'])) : 3;

if ($post_id <= 0) {
    return;
}

$title      = get_the_title($post_id);
$permalink  = get_permalink($post_id);
$excerpt    = get_the_excerpt($post_id);
$date       = get_the_date('M j, Y', $post_id);
$date_iso   = get_the_date('c', $post_id);
$read_time  = syntaxsidekick_get_post_read_time($post_id);
$categories = get_the_category($post_id);
$category   = ! empty($categories) ? $categories[0]->name : 'Article';
$thumb_id   = get_post_thumbnail_id($post_id);
?>
<article class="ss-card">
    <a class="ss-card-thumb" href="<?php echo esc_url($permalink); ?>">
        <?php if ($thumb_id) : ?>
            <?php
            echo wp_get_attachment_image(
                $thumb_id,
                'medium_large',
                false,
                array(
                    'loading' => 'lazy',
                    'decoding' => 'async',
                )
            );
            ?>
        <?php else : ?>
            <?php echo esc_html(wp_trim_words($title, 5, '')); ?>
        <?php endif; ?>
    </a>

    <div class="ss-card-body">
        <?php get_template_part('template-parts/components/category-badge', null, array('label' => $category)); ?>
        <<?php echo esc_attr('h' . $heading_level); ?>><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></<?php echo esc_attr('h' . $heading_level); ?>>
        <p><?php echo esc_html($excerpt); ?></p>
        <?php get_template_part('template-parts/components/metadata-row', null, array('date' => $date, 'date_iso' => $date_iso, 'read_time' => $read_time, 'class' => 'ss-card-meta')); ?>
    </div>
</article>
