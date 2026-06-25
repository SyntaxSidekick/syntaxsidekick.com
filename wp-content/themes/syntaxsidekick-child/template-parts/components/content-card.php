<?php
/**
 * Shared content hub card.
 *
 * @package SyntaxSidekick_Child
 */

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : 0;
$base_category_slug = isset($args['base_category_slug']) ? sanitize_key((string) $args['base_category_slug']) : '';
$section_key = isset($args['section_key']) ? sanitize_key((string) $args['section_key']) : '';
$level_label = isset($args['level_label']) ? (string) $args['level_label'] : '';
$singular_label = isset($args['singular_label']) ? (string) $args['singular_label'] : 'Content';

if ($post_id <= 0) {
    return;
}

$categories = get_the_category($post_id);
$badge = $singular_label;

if (! empty($categories)) {
    foreach ($categories as $category) {
        if ($base_category_slug && $base_category_slug === $category->slug) {
            continue;
        }

        $badge = $category->name;
        break;
    }

    if ($singular_label === $badge) {
        $badge = $categories[0]->name;
    }
}

$read_time = function_exists('syntaxsidekick_get_post_read_time')
    ? syntaxsidekick_get_post_read_time($post_id)
    : max(1, (int) ceil(str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id))) / 200)) . ' min read';

$meta_extra = '';
if ('' !== $level_label) {
    $meta_extra = $level_label;
} elseif ('resources' === $section_key && '' !== $badge) {
    $meta_extra = $badge;
}
?>
<article class="ss-tutorial-card ss-content-card ss-content-card--<?php echo esc_attr($section_key ? $section_key : 'default'); ?>">
    <div class="ss-tutorial-card__media">
        <a class="ss-tutorial-card__thumb" href="<?php echo esc_url(get_permalink($post_id)); ?>" aria-label="Read <?php echo esc_attr(get_the_title($post_id)); ?>">
            <?php if (has_post_thumbnail($post_id)) : ?>
                <?php
                $thumb_id = (int) get_post_thumbnail_id($post_id);
                $thumb_alt = trim((string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true));
                if ('' === $thumb_alt) {
                    $thumb_alt = get_the_title($post_id);
                }

                echo wp_get_attachment_image(
                    $thumb_id,
                    'medium_large',
                    false,
                    array(
                        'alt' => $thumb_alt,
                        'loading' => 'lazy',
                        'decoding' => 'async',
                    )
                );
                ?>
            <?php else : ?>
                <span class="ss-tutorial-card__fallback"><?php echo esc_html(wp_trim_words(get_the_title($post_id), 4, '')); ?></span>
            <?php endif; ?>
        </a>

        <span class="ss-card-kicker ss-tutorial-card__badge"><?php echo esc_html($badge); ?></span>
    </div>

    <div class="ss-tutorial-card__body">
        <h2><a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a></h2>
        <p class="ss-tutorial-card__excerpt"><?php echo esc_html(get_the_excerpt($post_id)); ?></p>
        <div class="ss-tutorial-card__meta">
            <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date('M j, Y', $post_id)); ?></time>
            <?php if ('' !== $read_time) : ?>
                <span aria-hidden="true">|</span>
                <span><?php echo esc_html($read_time); ?></span>
            <?php endif; ?>
            <?php if ('' !== $meta_extra) : ?>
                <span aria-hidden="true">|</span>
                <span><?php echo esc_html($meta_extra); ?></span>
            <?php endif; ?>
        </div>
    </div>
</article>
