<?php
/**
 * Shared single content page layout.
 *
 * @package SyntaxSidekick_Child
 */

$post_id = isset($args['post_id']) ? (int) $args['post_id'] : get_the_ID();
$section_key = isset($args['section_key']) ? sanitize_key((string) $args['section_key']) : 'articles';
$section_label = isset($args['section_label']) ? (string) $args['section_label'] : 'Articles';
$singular_label = isset($args['singular_label']) ? (string) $args['singular_label'] : 'Article';
$base_category_slug = isset($args['base_category_slug']) ? sanitize_key((string) $args['base_category_slug']) : $section_key;
$listing_url = isset($args['listing_url']) ? (string) $args['listing_url'] : home_url('/' . $base_category_slug . '/');

$topic_slugs = array();
if (isset($args['topic_slugs']) && is_array($args['topic_slugs'])) {
    foreach ($args['topic_slugs'] as $slug) {
        $sanitized_slug = sanitize_key((string) $slug);
        if ('' !== $sanitized_slug) {
            $topic_slugs[] = $sanitized_slug;
        }
    }
}

if ($post_id <= 0) {
    return;
}

$strip_plugin_toc_from_content = static function ($content) {
    if (! is_string($content) || '' === trim($content) || ! class_exists('DOMDocument')) {
        return $content;
    }

    $internal_errors = libxml_use_internal_errors(true);
    $document = new DOMDocument('1.0', 'UTF-8');
    $html = '<div id="ss-single-content-root">' . mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8') . '</div>';

    if (! $document->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
        libxml_clear_errors();
        libxml_use_internal_errors($internal_errors);
        return $content;
    }

    $xpath = new DOMXPath($document);
    $toc_node = null;
    $toc_queries = array(
        '//*[@id="ez-toc-container"]',
        '//*[contains(concat(" ", normalize-space(@class), " "), " ez-toc-container ")]',
        '//*[contains(concat(" ", normalize-space(@class), " "), " ez-toc-widget-container ")]',
        '//*[contains(concat(" ", normalize-space(@class), " "), " toc_container ")]',
        '//*[contains(concat(" ", normalize-space(@class), " "), " lwptoc ")]',
    );

    foreach ($toc_queries as $query) {
        $nodes = $xpath->query($query);
        if ($nodes instanceof DOMNodeList && $nodes->length > 0) {
            $toc_node = $nodes->item(0);
            break;
        }
    }

    if ($toc_node instanceof DOMElement && $toc_node->parentNode) {
        $toc_node->parentNode->removeChild($toc_node);
    }

    $root = $document->getElementById('ss-single-content-root');
    $updated_content = '';
    if ($root instanceof DOMElement) {
        foreach ($root->childNodes as $child_node) {
            $updated_content .= $document->saveHTML($child_node);
        }
    }

    libxml_clear_errors();
    libxml_use_internal_errors($internal_errors);

    return $updated_content ? $updated_content : $content;
};

$get_level_label = static function ($target_post_id) {
    $level_slugs = array(
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
    );

    $category_terms = get_the_category($target_post_id);
    $category_slugs = array();
    foreach ($category_terms as $term) {
        $category_slugs[] = $term->slug;
    }

    $tag_terms = get_the_terms($target_post_id, 'post_tag');
    $tag_slugs = array();
    if (is_array($tag_terms)) {
        foreach ($tag_terms as $term) {
            $tag_slugs[] = $term->slug;
        }
    }

    foreach ($level_slugs as $slug => $label) {
        if (in_array($slug, $category_slugs, true) || in_array($slug, $tag_slugs, true)) {
            return $label;
        }
    }

    return '';
};

$categories = get_the_category($post_id);
$base_term = get_category_by_slug($base_category_slug);
$base_term_id = $base_term instanceof WP_Term ? (int) $base_term->term_id : 0;

$section_base_slugs = array('articles', 'tutorials', 'resources', 'guides', 'uncategorized');

$topic_term = null;

// Prefer explicit section topic terms when provided (e.g. Guides topics).
if (! empty($topic_slugs)) {
    foreach ($topic_slugs as $topic_slug) {
        foreach ($categories as $category) {
            if ($category instanceof WP_Term && $category->slug === $topic_slug) {
                $topic_term = $category;
                break 2;
            }
        }
    }
}

// Prefer terms that are direct children/descendants of the current section base term.
if (! $topic_term && $base_term_id > 0) {
    foreach ($categories as $category) {
        $term_id = (int) $category->term_id;
        if ($term_id === $base_term_id) {
            continue;
        }

        if (cat_is_ancestor_of($base_term_id, $term_id)) {
            $topic_term = $category;
            break;
        }
    }
}

// Fall back to the first non-base, non-generic category.
if (! $topic_term) {
    foreach ($categories as $category) {
        $term_id = (int) $category->term_id;
        if ($term_id === $base_term_id) {
            continue;
        }

        if (in_array($category->slug, $section_base_slugs, true)) {
            continue;
        }

        $topic_term = $category;
        break;
    }
}

// Last-resort fallback to any non-base category.
if (! $topic_term) {
    foreach ($categories as $category) {
        if ((int) $category->term_id !== $base_term_id) {
            $topic_term = $category;
            break;
        }
    }
}

if (! $topic_term && ! empty($categories)) {
    $topic_term = $categories[0];
}

$topic_label = $topic_term instanceof WP_Term ? $topic_term->name : $singular_label;
$excerpt = has_excerpt($post_id)
    ? get_the_excerpt($post_id)
    : wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 28, '...');
$author_id = (int) get_post_field('post_author', $post_id);
$author_name = get_the_author_meta('display_name', $author_id);
$author_bio = trim((string) get_the_author_meta('description', $author_id));
$read_time = function_exists('syntaxsidekick_get_post_read_time')
    ? syntaxsidekick_get_post_read_time($post_id)
    : max(1, (int) ceil(str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id))) / 200)) . ' min read';
$level_label = 'tutorials' === $section_key ? $get_level_label($post_id) : '';
$rendered_content = apply_filters('the_content', (string) get_post_field('post_content', $post_id));
$rendered_content = $strip_plugin_toc_from_content($rendered_content);

$meta_type_label = '';
$meta_type_value = '';
if ('resources' === $section_key && $topic_term instanceof WP_Term) {
    $meta_type_label = 'Resource Type';
    $meta_type_value = $topic_term->name;
}
if ('guides' === $section_key && $topic_term instanceof WP_Term) {
    $meta_type_label = 'Guide Topic';
    $meta_type_value = $topic_term->name;
}

$related_topic_term_id = $topic_term instanceof WP_Term ? (int) $topic_term->term_id : 0;

$previous_content = null;
$next_content = null;

if ($base_term_id > 0) {
    $previous_posts = get_posts(
        array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'category__and' => array($base_term_id),
            'date_query' => array(
                array(
                    'before' => get_the_date('c', $post_id),
                ),
            ),
            'orderby' => 'date',
            'order' => 'DESC',
        )
    );

    $next_posts = get_posts(
        array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'post__not_in' => array($post_id),
            'ignore_sticky_posts' => true,
            'category__and' => array($base_term_id),
            'date_query' => array(
                array(
                    'after' => get_the_date('c', $post_id),
                ),
            ),
            'orderby' => 'date',
            'order' => 'ASC',
        )
    );

    $previous_content = ! empty($previous_posts) ? $previous_posts[0] : null;
    $next_content = ! empty($next_posts) ? $next_posts[0] : null;
}
?>
<main id="main-content" class="ss-main ss-single-tutorial-page ss-single-content-page ss-single-content-page--<?php echo esc_attr($section_key); ?>">
    <div class="ss-container">
        <nav class="ss-breadcrumbs" aria-label="Breadcrumb">
            <ol>
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <li><a href="<?php echo esc_url($listing_url); ?>"><?php echo esc_html($section_label); ?></a></li>
                <?php if ($topic_term instanceof WP_Term) : ?>
                    <li><a href="<?php echo esc_url(get_category_link($topic_term)); ?>"><?php echo esc_html($topic_label); ?></a></li>
                <?php endif; ?>
                <li aria-current="page"><?php echo esc_html(get_the_title($post_id)); ?></li>
            </ol>
        </nav>

        <div class="ss-single-tutorial-layout ss-single-content-layout">
            <article <?php post_class('ss-single-tutorial ss-single-content', $post_id); ?>>
                <header class="ss-single-tutorial-hero ss-single-content-hero">
                    <p class="ss-card-kicker ss-single-tutorial-topic"><?php echo esc_html($topic_label); ?></p>
                    <h1 class="ss-single-tutorial-title"><?php echo esc_html(get_the_title($post_id)); ?></h1>

                    <?php
                    get_template_part(
                        'template-parts/components/single-content-meta',
                        null,
                        array(
                            'date' => get_the_date('M j, Y', $post_id),
                            'date_iso' => get_the_date('c', $post_id),
                            'read_time' => $read_time,
                            'level' => $level_label,
                            'author_name' => $author_name,
                            'author_id' => $author_id,
                            'meta_type_label' => $meta_type_label,
                            'meta_type_value' => $meta_type_value,
                        )
                    );
                    ?>

                    <?php if ('' !== $excerpt) : ?>
                        <div class="ss-single-tutorial-excerpt">
                            <p><?php echo esc_html($excerpt); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (has_post_thumbnail($post_id)) : ?>
                        <?php
                        $thumb_id = (int) get_post_thumbnail_id($post_id);
                        $thumb_alt = trim((string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true));
                        if ('' === $thumb_alt) {
                            $thumb_alt = get_the_title($post_id);
                        }
                        ?>
                        <div class="ss-single-tutorial-featured-image">
                            <?php
                            echo wp_get_attachment_image(
                                $thumb_id,
                                'full',
                                false,
                                array(
                                    'alt' => $thumb_alt,
                                    'loading' => 'eager',
                                    'decoding' => 'async',
                                )
                            );
                            ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="ss-mobile-toc-mount" aria-live="polite"></div>

                <div class="ss-content ss-single-tutorial-body">
                    <?php echo $rendered_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>

                <section class="ss-single-tutorial-author" aria-labelledby="ss-content-author-title">
                    <div class="ss-single-tutorial-author__avatar">
                        <?php echo get_avatar($author_id, 64, '', $author_name); ?>
                    </div>
                    <div class="ss-single-tutorial-author__body">
                        <h2 id="ss-content-author-title">Written by <?php echo esc_html($author_name); ?></h2>
                        <?php if ('' !== $author_bio) : ?>
                            <p><?php echo esc_html($author_bio); ?></p>
                        <?php else : ?>
                            <p><?php echo esc_html__('Front-end developer sharing practical tutorials and modern web patterns.', 'syntaxsidekick-child'); ?></p>
                        <?php endif; ?>
                    </div>
                </section>

                <nav class="ss-single-tutorial-pagination" aria-label="Content navigation">
                    <?php if ($previous_content instanceof WP_Post) : ?>
                        <a class="ss-single-tutorial-pagination__card" href="<?php echo esc_url(get_permalink($previous_content)); ?>">
                            <span class="ss-single-tutorial-pagination__label"><?php echo esc_html__('Previous', 'syntaxsidekick-child') . ' ' . esc_html($singular_label); ?></span>
                            <strong><?php echo esc_html(get_the_title($previous_content)); ?></strong>
                        </a>
                    <?php endif; ?>

                    <?php if ($next_content instanceof WP_Post) : ?>
                        <a class="ss-single-tutorial-pagination__card ss-single-tutorial-pagination__card--next" href="<?php echo esc_url(get_permalink($next_content)); ?>">
                            <span class="ss-single-tutorial-pagination__label"><?php echo esc_html__('Next', 'syntaxsidekick-child') . ' ' . esc_html($singular_label); ?></span>
                            <strong><?php echo esc_html(get_the_title($next_content)); ?></strong>
                        </a>
                    <?php endif; ?>
                </nav>

                <?php if (comments_open($post_id) || get_comments_number($post_id)) : ?>
                    <?php comments_template(); ?>
                <?php endif; ?>
            </article>

            <aside class="ss-single-tutorial-sidebar ss-single-sidebar" aria-label="<?php echo esc_attr($singular_label); ?> sidebar">
                <div class="ss-sidebar-toc-mount">
                    <?php get_template_part('template-parts/components/single-toc'); ?>
                </div>

                <?php
                get_template_part(
                    'template-parts/components/single-content-meta',
                    null,
                    array(
                        'layout' => 'sidebar',
                        'heading' => $singular_label . ' Details',
                        'date' => get_the_date('M j, Y', $post_id),
                        'date_iso' => get_the_date('c', $post_id),
                        'read_time' => $read_time,
                        'level' => $level_label,
                        'author_name' => $author_name,
                        'author_id' => $author_id,
                        'meta_type_label' => $meta_type_label,
                        'meta_type_value' => $meta_type_value,
                    )
                );

                get_template_part(
                    'template-parts/components/related-content',
                    null,
                    array(
                        'post_id' => $post_id,
                        'base_category_slug' => $base_category_slug,
                        'topic_term_id' => $related_topic_term_id,
                        'limit' => 5,
                        'heading' => 'Related ' . $section_label,
                        'listing_url' => $listing_url,
                        'footer_label' => 'View all ' . strtolower($section_label),
                    )
                );
                ?>

                <section class="ss-sidebar-card ss-newsletter-card" aria-labelledby="ss-single-newsletter-title">
                    <h2 id="ss-single-newsletter-title" class="ss-section-title">Stay in the Loop</h2>
                    <?php
                    get_template_part(
                        'template-parts/components/newsletter-cta',
                        null,
                        array(
                            'layout' => 'sidebar',
                            'description' => 'Get the latest ' . strtolower($section_label) . ' delivered to your inbox.',
                            'input_id' => 'ss-single-newsletter-email',
                            'help_id' => 'ss-single-newsletter-help',
                            'status_id' => 'ss-single-newsletter-status',
                        )
                    );
                    ?>
                </section>
            </aside>
        </div>
    </div>
</main>
