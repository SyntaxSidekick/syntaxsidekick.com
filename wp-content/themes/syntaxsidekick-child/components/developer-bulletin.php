<?php
/**
 * Reusable developer bulletin component for homepage.
 *
 * @package SyntaxSidekick_Child
 */

$news_bulletins_term = syntaxsidekick_get_news_bulletins_term();

if (! ($news_bulletins_term instanceof WP_Term)) {
    return;
}

$bulletin_query = new WP_Query(
    array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
        'cat' => (int) $news_bulletins_term->term_id,
    )
);

if (! $bulletin_query->have_posts()) {
    wp_reset_postdata();
    return;
}

$bulletin_query->the_post();

$post_id = get_the_ID();
$title_id = 'ss-dev-bulletin-title-' . $post_id;
$accent_variant = syntaxsidekick_get_bulletin_accent_variant($post_id, (int) $news_bulletins_term->term_id);

$badge_label = $news_bulletins_term->name;
$post_categories = get_the_category($post_id);
if (! empty($post_categories)) {
    foreach ($post_categories as $category_term) {
        if (! ($category_term instanceof WP_Term)) {
            continue;
        }

        if ((int) $category_term->term_id === (int) $news_bulletins_term->term_id) {
            continue;
        }

        $badge_label = $category_term->name;
        break;
    }
}

$excerpt = has_excerpt($post_id)
    ? get_the_excerpt($post_id)
    : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 32, '...');

$bulletins_archive_url = get_term_link($news_bulletins_term);
if (is_wp_error($bulletins_archive_url) || ! is_string($bulletins_archive_url) || '' === trim($bulletins_archive_url)) {
    $bulletins_archive_url = home_url('/');
}

$primary_cta = syntaxsidekick_get_bulletin_cta(
    $post_id,
    'primary',
    esc_html__('Report an Issue', 'syntaxsidekick-child'),
    home_url('/contact/')
);

$secondary_cta = syntaxsidekick_get_bulletin_cta(
    $post_id,
    'secondary',
    esc_html__('View All Bulletins', 'syntaxsidekick-child'),
    $bulletins_archive_url
);

$visual_image_id = 0;
$visual_image_url = '';

if (has_post_thumbnail($post_id)) {
    $visual_image_id = (int) get_post_thumbnail_id($post_id);
}

if (0 === $visual_image_id && function_exists('get_field')) {
    $visual_field = get_field('bulletin_illustration', $post_id);

    if (is_array($visual_field)) {
        if (! empty($visual_field['ID'])) {
            $visual_image_id = (int) $visual_field['ID'];
        } elseif (! empty($visual_field['id'])) {
            $visual_image_id = (int) $visual_field['id'];
        } elseif (! empty($visual_field['url'])) {
            $visual_image_url = (string) $visual_field['url'];
        }
    } elseif (is_numeric($visual_field)) {
        $visual_image_id = (int) $visual_field;
    } elseif (is_string($visual_field) && '' !== trim($visual_field)) {
        $visual_image_url = $visual_field;
    }
}

if (0 === $visual_image_id) {
    $meta_illustration_id = (int) get_post_meta($post_id, 'bulletin_illustration_id', true);
    if ($meta_illustration_id > 0) {
        $visual_image_id = $meta_illustration_id;
    }
}

if ('' === $visual_image_url) {
    $meta_illustration_url = get_post_meta($post_id, 'bulletin_illustration_url', true);
    if (is_string($meta_illustration_url) && '' !== trim($meta_illustration_url)) {
        $visual_image_url = $meta_illustration_url;
    }
}
?>
<section class="ss-home-section ss-home-section--bulletin">
    <div class="ss-container">
        <section
            class="ss-dev-bulletin is-accent-<?php echo esc_attr($accent_variant); ?>"
            data-dev-bulletin
            data-bulletin-id="<?php echo esc_attr((string) $post_id); ?>"
            aria-labelledby="<?php echo esc_attr($title_id); ?>"
        >
            <div class="ss-dev-bulletin__track" role="list">
                <article class="ss-dev-bulletin__item" role="listitem">
                    <header class="ss-dev-bulletin__header">
                        <p class="ss-dev-bulletin__eyebrow"><?php echo esc_html__('Developer Bulletin', 'syntaxsidekick-child'); ?></p>

                        <button
                            class="ss-dev-bulletin__dismiss"
                            type="button"
                            data-dev-bulletin-dismiss
                            aria-label="<?php echo esc_attr__('Dismiss this bulletin', 'syntaxsidekick-child'); ?>"
                        >
                            <span><?php echo esc_html__('Dismiss', 'syntaxsidekick-child'); ?></span>
                            <span aria-hidden="true">&times;</span>
                            <span class="screen-reader-text"><?php echo esc_html__('Dismiss this bulletin', 'syntaxsidekick-child'); ?></span>
                        </button>
                    </header>

                    <div class="ss-dev-bulletin__body">
                        <div class="ss-dev-bulletin__content">
                            <?php get_template_part('template-parts/components/category-badge', null, array('label' => $badge_label)); ?>

                            <h2 id="<?php echo esc_attr($title_id); ?>" class="ss-dev-bulletin__title">
                                <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a>
                            </h2>

                            <?php if ('' !== trim($excerpt)) : ?>
                                <p class="ss-dev-bulletin__excerpt"><?php echo esc_html($excerpt); ?></p>
                            <?php endif; ?>

                            <p class="ss-dev-bulletin__meta">
                                <time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>"><?php echo esc_html(get_the_date(get_option('date_format'), $post_id)); ?></time>
                            </p>

                            <div class="ss-dev-bulletin__actions">
                                <?php
                                get_template_part(
                                    'template-parts/components/cta-button',
                                    null,
                                    array(
                                        'label' => $primary_cta['label'],
                                        'url' => $primary_cta['url'],
                                        'variant' => 'primary',
                                    )
                                );

                                get_template_part(
                                    'template-parts/components/cta-button',
                                    null,
                                    array(
                                        'label' => $secondary_cta['label'],
                                        'url' => $secondary_cta['url'],
                                        'variant' => 'secondary',
                                    )
                                );
                                ?>
                            </div>
                        </div>

                        <?php if ($visual_image_id > 0 || '' !== trim($visual_image_url)) : ?>
                            <figure class="ss-dev-bulletin__visual" aria-hidden="true">
                                <?php
                                if ($visual_image_id > 0) {
                                    echo wp_kses_post(
                                        wp_get_attachment_image(
                                            $visual_image_id,
                                            'medium_large',
                                            false,
                                            array(
                                                'class' => 'ss-dev-bulletin__image',
                                                'loading' => 'lazy',
                                                'decoding' => 'async',
                                                'alt' => '',
                                            )
                                        )
                                    );
                                } else {
                                    ?>
                                    <img class="ss-dev-bulletin__image" src="<?php echo esc_url($visual_image_url); ?>" alt="" loading="lazy" decoding="async">
                                    <?php
                                }
                                ?>
                            </figure>
                        <?php endif; ?>
                    </div>
                </article>
            </div>
        </section>
    </div>
</section>
<?php
wp_reset_postdata();
