<?php
/**
 * Related tutorials sidebar card.
 *
 * @package SyntaxSidekick_Child
 */

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : 0;
$tutorials_term_id = isset($args['tutorials_term_id']) ? (int) $args['tutorials_term_id'] : 0;
$topic_term_id = isset($args['topic_term_id']) ? (int) $args['topic_term_id'] : 0;
$limit = isset($args['limit']) ? max(1, (int) $args['limit']) : 5;

$related_posts = array();
$collected_ids = array($post_id);

if ($post_id > 0 && $tutorials_term_id > 0 && $topic_term_id > 0) {
    $same_topic_posts = get_posts(
        array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'post__not_in' => $collected_ids,
            'ignore_sticky_posts' => true,
            'category__and' => array($tutorials_term_id, $topic_term_id),
            'orderby' => 'date',
            'order' => 'DESC',
        )
    );

    foreach ($same_topic_posts as $same_topic_post) {
        $related_posts[] = $same_topic_post;
        $collected_ids[] = (int) $same_topic_post->ID;
    }
}

if (count($related_posts) < $limit && $tutorials_term_id > 0) {
    $fallback_posts = get_posts(
        array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $limit - count($related_posts),
            'post__not_in' => $collected_ids,
            'ignore_sticky_posts' => true,
            'category__and' => array($tutorials_term_id),
            'orderby' => 'date',
            'order' => 'DESC',
        )
    );

    foreach ($fallback_posts as $fallback_post) {
        $related_posts[] = $fallback_post;
    }
}
?>
<section class="ss-sidebar-card" aria-labelledby="ss-related-tutorials-title">
    <h2 id="ss-related-tutorials-title" class="ss-section-title"><?php echo esc_html__('Related Tutorials', 'syntaxsidekick-child'); ?></h2>

    <ul class="ss-sidebar-popular-list">
        <?php if (! empty($related_posts)) : ?>
            <?php foreach ($related_posts as $related_post) : ?>
                <?php
                $related_post_id = (int) $related_post->ID;
                $thumb_id = (int) get_post_thumbnail_id($related_post_id);
                $read_time = function_exists('syntaxsidekick_get_post_read_time')
                    ? syntaxsidekick_get_post_read_time($related_post_id)
                    : max(1, (int) ceil(str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $related_post_id))) / 200)) . ' min read';
                ?>
                <li>
                    <a class="ss-sidebar-popular-link" href="<?php echo esc_url(get_permalink($related_post_id)); ?>">
                        <span class="ss-sidebar-popular-thumb" aria-hidden="true">
                            <?php if ($thumb_id) : ?>
                                <?php
                                echo wp_get_attachment_image(
                                    $thumb_id,
                                    'thumbnail',
                                    false,
                                    array(
                                        'alt' => '',
                                        'loading' => 'lazy',
                                        'decoding' => 'async',
                                    )
                                );
                                ?>
                            <?php else : ?>
                                <?php echo esc_html(strtoupper(substr(get_the_title($related_post_id), 0, 2))); ?>
                            <?php endif; ?>
                        </span>
                        <span class="ss-sidebar-popular-copy">
                            <span class="ss-sidebar-popular-title"><?php echo esc_html(get_the_title($related_post_id)); ?></span>
                            <span class="ss-sidebar-popular-meta"><?php echo esc_html($read_time); ?></span>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>
                <span class="ss-sidebar-empty"><?php echo esc_html__('No related tutorials yet.', 'syntaxsidekick-child'); ?></span>
            </li>
        <?php endif; ?>
    </ul>

    <a class="ss-sidebar-footer-link" href="<?php echo esc_url(home_url('/tutorials/')); ?>"><?php echo esc_html__('View all tutorials', 'syntaxsidekick-child'); ?></a>
</section>