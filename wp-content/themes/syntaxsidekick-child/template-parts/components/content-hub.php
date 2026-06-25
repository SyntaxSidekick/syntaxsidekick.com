<?php
/**
 * Shared content hub listing layout.
 *
 * @package SyntaxSidekick_Child
 */

$section_key = isset($args['section_key']) ? sanitize_key((string) $args['section_key']) : 'tutorials';
$section_label = isset($args['section_label']) ? (string) $args['section_label'] : 'Tutorials';
$singular_label = isset($args['singular_label']) ? (string) $args['singular_label'] : 'Tutorial';
$base_category_slug = isset($args['base_category_slug']) ? sanitize_key((string) $args['base_category_slug']) : $section_key;
$topics = isset($args['topics']) && is_array($args['topics']) ? $args['topics'] : array();
$supports_level = ! empty($args['supports_level']);
$posts_per_page = isset($args['posts_per_page']) ? max(1, (int) $args['posts_per_page']) : 9;

$hero = isset($args['hero']) && is_array($args['hero']) ? $args['hero'] : array();
$hero_id = isset($hero['id']) ? (string) $hero['id'] : 'ss-' . $section_key . '-page-title';
$hero_label = isset($hero['label']) ? (string) $hero['label'] : strtoupper($section_label);
$hero_title = isset($hero['title']) ? (string) $hero['title'] : $section_label;
$hero_description = isset($hero['description']) ? (string) $hero['description'] : '';
$show_visual = ! isset($hero['show_visual']) || (bool) $hero['show_visual'];

$base_url = isset($args['base_url']) && '' !== (string) $args['base_url']
    ? (string) $args['base_url']
    : (string) get_permalink();

$allowed_sorts = array('newest', 'oldest');
$sort = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'newest';
$sort = in_array($sort, $allowed_sorts, true) ? $sort : 'newest';

$current_topic = isset($_GET['topic']) ? sanitize_key(wp_unslash($_GET['topic'])) : '';
if (! isset($topics[$current_topic])) {
    $current_topic = '';
}

$level_items = array(
    'beginner' => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced' => 'Advanced',
);

$current_level = '';
if ($supports_level) {
    $current_level = isset($_GET['level']) ? sanitize_key(wp_unslash($_GET['level'])) : '';
    if (! isset($level_items[$current_level])) {
        $current_level = '';
    }
}

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

$base_term = get_category_by_slug($base_category_slug);
$category_and = array();
$missing_topic_term = false;

if ($base_term instanceof WP_Term) {
    $category_and[] = (int) $base_term->term_id;
}

if ('' !== $current_topic) {
    $topic_term = get_category_by_slug($current_topic);
    if ($topic_term instanceof WP_Term) {
        $category_and[] = (int) $topic_term->term_id;
    } else {
        $missing_topic_term = true;
    }
}

$query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'ignore_sticky_posts' => true,
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'orderby' => 'date',
    'order' => 'oldest' === $sort ? 'ASC' : 'DESC',
);

if (! empty($category_and)) {
    $query_args['category__and'] = $category_and;
}

if ($missing_topic_term) {
    $query_args['post__in'] = array(0);
}

$level_sources = array();
if ($supports_level && '' !== $current_level) {
    $level_tag = get_term_by('slug', $current_level, 'post_tag');
    if ($level_tag instanceof WP_Term) {
        $query_args['tag'] = $level_tag->slug;
    } else {
        $level_category = get_category_by_slug($current_level);
        if ($level_category instanceof WP_Term) {
            if (! isset($query_args['category__and']) || ! is_array($query_args['category__and'])) {
                $query_args['category__and'] = array();
            }
            $query_args['category__and'][] = (int) $level_category->term_id;
        }
    }
}

$tutorials_query = new WP_Query($query_args);
$total_posts = (int) $tutorials_query->found_posts;
$per_page = (int) $tutorials_query->get('posts_per_page');
$current_page = max(1, (int) $tutorials_query->get('paged'));
$range_start = $total_posts > 0 ? (($current_page - 1) * $per_page) + 1 : 0;
$range_end = $total_posts > 0 ? min($total_posts, $current_page * $per_page) : 0;

$perf_cache_version = function_exists('syntaxsidekick_perf_cache_version')
    ? syntaxsidekick_perf_cache_version()
    : 1;

$count_cache_key = 'ss_hub_counts_' . md5(
    wp_json_encode(
        array(
            'section' => $section_key,
            'base' => $base_category_slug,
            'topics' => array_keys($topics),
            'supports_level' => $supports_level,
            'v' => $perf_cache_version,
        )
    )
);

$count_cache = get_transient($count_cache_key);
$post_category_slugs = array();
$post_tag_slugs = array();

if (
    is_array($count_cache)
    && isset($count_cache['count_index'], $count_cache['level_sources'], $count_cache['post_category_slugs'], $count_cache['post_tag_slugs'])
) {
    $count_index = is_array($count_cache['count_index']) ? $count_cache['count_index'] : array('all' => array('all' => 0));
    $level_sources = is_array($count_cache['level_sources']) ? $count_cache['level_sources'] : array();
    $post_category_slugs = is_array($count_cache['post_category_slugs']) ? $count_cache['post_category_slugs'] : array();
    $post_tag_slugs = is_array($count_cache['post_tag_slugs']) ? $count_cache['post_tag_slugs'] : array();
} else {
    $count_index = array('all' => array('all' => 0));
    foreach (array_keys($topics) as $topic_slug) {
        $count_index[$topic_slug] = array('all' => 0);
    }

    if ($supports_level) {
        foreach (array_keys($level_items) as $level_slug) {
            $count_index['all'][$level_slug] = 0;
            foreach (array_keys($topics) as $topic_slug) {
                $count_index[$topic_slug][$level_slug] = 0;
            }

            $level_sources[$level_slug] = null;
            $level_tag = get_term_by('slug', $level_slug, 'post_tag');
            if ($level_tag instanceof WP_Term) {
                $level_sources[$level_slug] = 'tag';
                continue;
            }

            $level_category = get_category_by_slug($level_slug);
            if ($level_category instanceof WP_Term) {
                $level_sources[$level_slug] = 'category';
            }
        }
    }

    $count_query_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page' => -1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );

    if ($base_term instanceof WP_Term) {
        $count_query_args['category__and'] = array((int) $base_term->term_id);
    }

    $count_post_ids = get_posts($count_query_args);

    if (! empty($count_post_ids)) {
        $category_terms = wp_get_object_terms($count_post_ids, 'category', array('fields' => 'all_with_object_id'));
        if (! is_wp_error($category_terms)) {
            foreach ($category_terms as $term) {
                $post_id = (int) $term->object_id;
                if (! isset($post_category_slugs[$post_id])) {
                    $post_category_slugs[$post_id] = array();
                }
                $post_category_slugs[$post_id][] = $term->slug;
            }
        }

        $tag_terms = wp_get_object_terms($count_post_ids, 'post_tag', array('fields' => 'all_with_object_id'));
        if (! is_wp_error($tag_terms)) {
            foreach ($tag_terms as $term) {
                $post_id = (int) $term->object_id;
                if (! isset($post_tag_slugs[$post_id])) {
                    $post_tag_slugs[$post_id] = array();
                }
                $post_tag_slugs[$post_id][] = $term->slug;
            }
        }
    }

    foreach ($count_post_ids as $post_id) {
        $post_id = (int) $post_id;
        $cats = $post_category_slugs[$post_id] ?? array();
        $tags = $post_tag_slugs[$post_id] ?? array();

        $matching_topics = array();
        foreach (array_keys($topics) as $topic_slug) {
            if (in_array($topic_slug, $cats, true)) {
                $matching_topics[] = $topic_slug;
                $count_index[$topic_slug]['all']++;
            }
        }

        $count_index['all']['all']++;

        if (! $supports_level) {
            continue;
        }

        foreach (array_keys($level_items) as $level_slug) {
            $source = $level_sources[$level_slug] ?? null;
            if (! $source) {
                continue;
            }

            $matches_level = ('tag' === $source && in_array($level_slug, $tags, true))
                || ('category' === $source && in_array($level_slug, $cats, true));

            if (! $matches_level) {
                continue;
            }

            $count_index['all'][$level_slug]++;
            foreach ($matching_topics as $topic_slug) {
                $count_index[$topic_slug][$level_slug]++;
            }
        }
    }

    set_transient(
        $count_cache_key,
        array(
            'count_index' => $count_index,
            'level_sources' => $level_sources,
            'post_category_slugs' => $post_category_slugs,
            'post_tag_slugs' => $post_tag_slugs,
        ),
        10 * MINUTE_IN_SECONDS
    );
}

$count_posts = static function ($topic_slug = '', $level_slug = '') use ($count_index) {
    $topic_key = $topic_slug ? $topic_slug : 'all';
    $level_key = $level_slug ? $level_slug : 'all';

    return (int) ($count_index[$topic_key][$level_key] ?? 0);
};

$get_level_label = static function ($post_id) use ($supports_level, $level_items, $level_sources, $post_category_slugs, $post_tag_slugs) {
    if (! $supports_level) {
        return '';
    }

    $post_id = (int) $post_id;
    $cats = $post_category_slugs[$post_id] ?? array();
    $tags = $post_tag_slugs[$post_id] ?? array();

    foreach ($level_items as $level_slug => $level_name) {
        $source = $level_sources[$level_slug] ?? null;
        if (! $source) {
            continue;
        }

        $matches_level = ('tag' === $source && in_array($level_slug, $tags, true))
            || ('category' === $source && in_array($level_slug, $cats, true));

        if ($matches_level) {
            return $level_name;
        }
    }

    return '';
};

$browse_items = array(
    array(
        'slug' => 'all',
        'label' => 'All ' . $section_label,
        'url' => add_query_arg(
            array_filter(
                array(
                    'level' => $supports_level && $current_level ? $current_level : null,
                    'sort' => 'newest' !== $sort ? $sort : null,
                )
            ),
            remove_query_arg(array('topic', 'paged'), $base_url)
        ),
        'count' => $count_posts(),
        'is_active' => '' === $current_topic,
    ),
);

foreach ($topics as $topic_slug => $topic_label) {
    $browse_items[] = array(
        'slug' => $topic_slug,
        'label' => $topic_label,
        'url' => add_query_arg(
            array_filter(
                array(
                    'topic' => $topic_slug,
                    'level' => $supports_level && $current_level ? $current_level : null,
                    'sort' => 'newest' !== $sort ? $sort : null,
                )
            ),
            $base_url
        ),
        'count' => $count_posts($topic_slug),
        'is_active' => $current_topic === $topic_slug,
    );
}

$results_label = strtolower($section_label);
?>
<main id="main-content" class="ss-main ss-content-hub ss-content-hub--<?php echo esc_attr($section_key); ?> ss-tutorials-page">
    <?php
    get_template_part(
        'template-parts/components/page-hero',
        null,
        array(
            'id' => $hero_id,
            'label' => $hero_label,
            'title' => $hero_title,
            'description' => $hero_description,
            'show_visual' => $show_visual,
        )
    );
    ?>

    <div class="ss-container ss-content-grid ss-tutorials-layout">
        <section class="ss-content-hub__content ss-tutorials-content" aria-labelledby="ss-<?php echo esc_attr($section_key); ?>-results-heading">
            <div class="ss-tutorials-toolbar">
                <p id="ss-<?php echo esc_attr($section_key); ?>-results-heading" class="ss-results-count">
                    Showing <?php echo esc_html((string) $range_start); ?>&ndash;<?php echo esc_html((string) $range_end); ?> of <?php echo esc_html((string) $total_posts); ?> <?php echo esc_html($results_label); ?>
                </p>

                <form class="ss-sort-form" method="get" action="<?php echo esc_url($base_url); ?>">
                    <?php if (! empty($topics)) : ?>
                        <div class="ss-filter-field">
                            <label class="screen-reader-text" for="ss-<?php echo esc_attr($section_key); ?>-topic-filter">Filter by category</label>
                            <select id="ss-<?php echo esc_attr($section_key); ?>-topic-filter" name="topic" onchange="this.form.submit()" aria-label="Filter by category">
                                <option value="">All Categories</option>
                                <?php foreach ($topics as $topic_slug => $topic_label) : ?>
                                    <option value="<?php echo esc_attr($topic_slug); ?>" <?php selected($current_topic, $topic_slug); ?>><?php echo esc_html($topic_label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <?php if ($supports_level) : ?>
                        <div class="ss-filter-field">
                            <label class="screen-reader-text" for="ss-<?php echo esc_attr($section_key); ?>-level-filter">Filter by level</label>
                            <select id="ss-<?php echo esc_attr($section_key); ?>-level-filter" name="level" onchange="this.form.submit()" aria-label="Filter by level">
                                <option value="">All Levels</option>
                                <?php foreach ($level_items as $level_slug => $level_name) : ?>
                                    <option value="<?php echo esc_attr($level_slug); ?>" <?php selected($current_level, $level_slug); ?>><?php echo esc_html($level_name); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="ss-filter-field ss-filter-field--sort">
                        <label for="ss-<?php echo esc_attr($section_key); ?>-sort">Sort by:</label>
                        <select id="ss-<?php echo esc_attr($section_key); ?>-sort" name="sort" onchange="this.form.submit()">
                            <option value="newest" <?php selected($sort, 'newest'); ?>>Newest First</option>
                            <option value="oldest" <?php selected($sort, 'oldest'); ?>>Oldest First</option>
                        </select>
                    </div>

                    <noscript>
                        <button type="submit" class="ss-sort-submit">Apply</button>
                    </noscript>
                </form>
            </div>

            <?php if ($tutorials_query->have_posts()) : ?>
                <div class="ss-content-list ss-tutorial-list">
                    <?php while ($tutorials_query->have_posts()) : $tutorials_query->the_post(); ?>
                        <?php
                        get_template_part(
                            'template-parts/components/content-card',
                            null,
                            array(
                                'post_id' => get_the_ID(),
                                'base_category_slug' => $base_category_slug,
                                'section_key' => $section_key,
                                'singular_label' => $singular_label,
                                'level_label' => $get_level_label(get_the_ID()),
                            )
                        );
                        ?>
                    <?php endwhile; ?>
                </div>

                <?php
                $pagination_links = paginate_links(
                    array(
                        'base' => trailingslashit($base_url) . '%_%',
                        'format' => 'page/%#%/',
                        'current' => $current_page,
                        'total' => (int) $tutorials_query->max_num_pages,
                        'type' => 'array',
                        'prev_text' => 'Previous',
                        'next_text' => 'Next',
                        'add_args' => array_filter(
                            array(
                                'sort' => 'newest' !== $sort ? $sort : null,
                                'topic' => '' !== $current_topic ? $current_topic : null,
                                'level' => $supports_level && '' !== $current_level ? $current_level : null,
                            )
                        ),
                    )
                );
                ?>

                <?php if (! empty($pagination_links)) : ?>
                    <nav class="ss-pagination" aria-label="<?php echo esc_attr($section_label); ?> pagination">
                        <ul>
                            <?php foreach ($pagination_links as $link) : ?>
                                <li><?php echo wp_kses_post(str_replace('page-numbers', 'ss-page-button', $link)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <article class="ss-empty-state">
                    <h2>No <?php echo esc_html($results_label); ?> found</h2>
                    <p>There are no <?php echo esc_html($results_label); ?> matching this filter yet. Try adjusting the filters or publishing new <?php echo esc_html($results_label); ?>.</p>
                </article>
            <?php endif; ?>
        </section>

        <?php
        get_template_part(
            'template-parts/components/content-hub-sidebar',
            null,
            array(
                'section_key' => $section_key,
                'section_label' => $section_label,
                'browse_items' => $browse_items,
                'base_category_slug' => $base_category_slug,
                'listing_url' => $base_url,
            )
        );
        ?>
    </div>
</main>
<?php
wp_reset_postdata();
