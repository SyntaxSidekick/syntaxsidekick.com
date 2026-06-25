<?php
/**
 * Popular tutorials sidebar card.
 *
 * @package SyntaxSidekick_Child
 */

$section_id = isset($args['id']) ? (string) $args['id'] : 'ss-popular-tutorials-title';
$section_heading = isset($args['heading']) ? (string) $args['heading'] : 'Popular Tutorials';
$limit = isset($args['limit']) ? max(1, (int) $args['limit']) : 5;

$tutorials_term = get_category_by_slug('tutorials');
$query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
    'posts_per_page' => $limit,
    'orderby' => array(
        'comment_count' => 'DESC',
        'date' => 'DESC',
    ),
);

if ($tutorials_term instanceof WP_Term) {
    $query_args['category__in'] = array((int) $tutorials_term->term_id);
}

$popular_query = new WP_Query($query_args);
?>
<section class="ss-sidebar-card" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <h2 id="<?php echo esc_attr($section_id); ?>" class="ss-section-title"><?php echo esc_html($section_heading); ?></h2>

    <ul class="ss-sidebar-popular-list">
        <?php if ($popular_query->have_posts()) : ?>
            <?php while ($popular_query->have_posts()) : $popular_query->the_post(); ?>
                <?php
                $thumb_id = (int) get_post_thumbnail_id();
                $read_time = function_exists('syntaxsidekick_get_post_read_time')
                    ? syntaxsidekick_get_post_read_time(get_the_ID())
                    : max(1, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200)) . ' min read';
                ?>
                <li>
                    <a class="ss-sidebar-popular-link" href="<?php echo esc_url(get_permalink()); ?>">
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
                                <?php echo esc_html(strtoupper(substr(get_the_title(), 0, 2))); ?>
                            <?php endif; ?>
                        </span>
                        <span class="ss-sidebar-popular-copy">
                            <span class="ss-sidebar-popular-title"><?php echo esc_html(get_the_title()); ?></span>
                            <span class="ss-sidebar-popular-meta"><?php echo esc_html($read_time); ?></span>
                        </span>
                    </a>
                </li>
            <?php endwhile; ?>
        <?php else : ?>
            <li>
                <a class="ss-sidebar-popular-link" href="<?php echo esc_url(home_url('/tutorials/')); ?>">
                    <span class="ss-sidebar-popular-copy">
                        <span class="ss-sidebar-popular-title"><?php echo esc_html__('No tutorials published yet.', 'syntaxsidekick-child'); ?></span>
                        <span class="ss-sidebar-popular-meta">0</span>
                    </span>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <a class="ss-sidebar-footer-link" href="<?php echo esc_url(home_url('/tutorials/')); ?>"><?php echo esc_html__('View all tutorials', 'syntaxsidekick-child'); ?></a>
</section>
<?php
wp_reset_postdata();
