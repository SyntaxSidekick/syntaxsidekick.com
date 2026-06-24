<?php
/**
 * Tutorials page template.
 *
 * Applies automatically to the page with slug "tutorials".
 *
 * @package SyntaxSidekick_Child
 */

get_header();

$allowed_sorts = array('newest', 'oldest');
$sort          = isset($_GET['sort']) ? sanitize_key(wp_unslash($_GET['sort'])) : 'newest';
$sort          = in_array($sort, $allowed_sorts, true) ? $sort : 'newest';

$topic_items = array(
    'html'          => 'HTML',
    'css'           => 'CSS',
    'javascript'    => 'JavaScript',
    'typescript'    => 'TypeScript',
    'react'         => 'React',
    'vue'           => 'Vue',
    'performance'   => 'Performance',
    'accessibility' => 'Accessibility',
);

$level_items = array(
    'beginner'     => 'Beginner',
    'intermediate' => 'Intermediate',
    'advanced'     => 'Advanced',
);

$current_topic = isset($_GET['topic']) ? sanitize_key(wp_unslash($_GET['topic'])) : '';
$current_level = isset($_GET['level']) ? sanitize_key(wp_unslash($_GET['level'])) : '';

if (! isset($topic_items[$current_topic])) {
    $current_topic = '';
}

if (! isset($level_items[$current_level])) {
    $current_level = '';
}

$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

$tutorials_category = get_category_by_slug('tutorials');
$category_and       = array();

if ($tutorials_category instanceof WP_Term) {
    $category_and[] = (int) $tutorials_category->term_id;
}

if ($current_topic) {
    $topic_term = get_category_by_slug($current_topic);
    if ($topic_term instanceof WP_Term) {
        $category_and[] = (int) $topic_term->term_id;
    }
}

$query_args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'ignore_sticky_posts' => true,
    'posts_per_page'      => 9,
    'paged'               => $paged,
    'orderby'             => 'date',
    'order'               => 'oldest' === $sort ? 'ASC' : 'DESC',
);

if (! empty($category_and)) {
    $query_args['category__and'] = $category_and;
}

if ($current_level) {
    $level_tag = get_term_by('slug', $current_level, 'post_tag');
    if ($level_tag instanceof WP_Term) {
        $query_args['tag'] = $level_tag->slug;
    } else {
        $level_category = get_category_by_slug($current_level);
        if ($level_category instanceof WP_Term) {
            $query_args['category__and'][] = (int) $level_category->term_id;
        }
    }
}

$tutorials_query = new WP_Query($query_args);

$total_posts  = (int) $tutorials_query->found_posts;
$per_page     = (int) $tutorials_query->get('posts_per_page');
$current_page = max(1, (int) $tutorials_query->get('paged'));
$range_start  = $total_posts > 0 ? (($current_page - 1) * $per_page) + 1 : 0;
$range_end    = $total_posts > 0 ? min($total_posts, $current_page * $per_page) : 0;

$base_tutorials_url = get_permalink();

$count_index = array('all' => array('all' => 0));
foreach (array_keys($topic_items) as $topic_slug) {
    $count_index[$topic_slug] = array('all' => 0);
}
foreach (array_keys($level_items) as $level_slug) {
    $count_index['all'][$level_slug] = 0;
    foreach (array_keys($topic_items) as $topic_slug) {
        $count_index[$topic_slug][$level_slug] = 0;
    }
}

$level_sources = array();
foreach (array_keys($level_items) as $level_slug) {
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

$count_query_args = array(
    'post_type'              => 'post',
    'post_status'            => 'publish',
    'ignore_sticky_posts'    => true,
    'posts_per_page'         => -1,
    'fields'                 => 'ids',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
);

if ($tutorials_category instanceof WP_Term) {
    $count_query_args['category__and'] = array((int) $tutorials_category->term_id);
}

$count_post_ids = get_posts($count_query_args);
$post_category_slugs = array();
$post_tag_slugs      = array();

if (! empty($count_post_ids)) {
    $category_terms = wp_get_object_terms(
        $count_post_ids,
        'category',
        array('fields' => 'all_with_object_id')
    );

    if (! is_wp_error($category_terms)) {
        foreach ($category_terms as $term) {
            $post_id = (int) $term->object_id;
            if (! isset($post_category_slugs[$post_id])) {
                $post_category_slugs[$post_id] = array();
            }
            $post_category_slugs[$post_id][] = $term->slug;
        }
    }

    $tag_terms = wp_get_object_terms(
        $count_post_ids,
        'post_tag',
        array('fields' => 'all_with_object_id')
    );

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
    $cats    = $post_category_slugs[$post_id] ?? array();
    $tags    = $post_tag_slugs[$post_id] ?? array();

    $matching_topics = array();
    foreach (array_keys($topic_items) as $topic_slug) {
        if (in_array($topic_slug, $cats, true)) {
            $matching_topics[] = $topic_slug;
            $count_index[$topic_slug]['all']++;
        }
    }

    $count_index['all']['all']++;

    foreach (array_keys($level_items) as $level_slug) {
        $source = $level_sources[$level_slug];
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

$count_tutorial_posts = static function ($topic_slug = '', $level_slug = '') use ($count_index) {
    $topic_key = $topic_slug ? $topic_slug : 'all';
    $level_key = $level_slug ? $level_slug : 'all';

    return (int) ($count_index[$topic_key][$level_key] ?? 0);
};
?>

<main id="main-content" class="ss-main ss-tutorials-page">
    <section class="ss-page-hero" aria-labelledby="ss-tutorials-page-title">
        <div class="ss-container ss-page-hero__grid">
            <div class="ss-page-hero__content">
                <p class="ss-eyebrow">Tutorials</p>
                <h1 id="ss-tutorials-page-title">Tutorials</h1>
                <p>Step-by-step tutorials to help you build real-world projects and sharpen your front-end skills.</p>
            </div>

            <div class="ss-page-hero__visual" aria-hidden="true">
                <div class="ss-page-hero__code-card">
                    <div class="ss-page-hero__dots"><span></span><span></span><span></span></div>
                    <div class="ss-page-hero__code-body">
                        <div class="ss-page-hero__glyph">&lt;/&gt;</div>
                        <div class="ss-page-hero__lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <ul class="ss-page-hero__checks">
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="ss-container ss-tutorials-layout">
        <section class="ss-tutorials-content" aria-labelledby="ss-tutorial-results-heading">
            <div class="ss-tutorials-toolbar">
                <p id="ss-tutorial-results-heading" class="ss-results-count"><?php echo esc_html(sprintf('Showing %1$d-%2$d of %3$d tutorials', $range_start, $range_end, $total_posts)); ?></p>

                <form class="ss-sort-form" method="get" action="<?php echo esc_url($base_tutorials_url); ?>">
                    <?php if ($current_topic) : ?>
                        <input type="hidden" name="topic" value="<?php echo esc_attr($current_topic); ?>">
                    <?php endif; ?>
                    <?php if ($current_level) : ?>
                        <input type="hidden" name="level" value="<?php echo esc_attr($current_level); ?>">
                    <?php endif; ?>
                    <label for="ss-sort">Sort by:</label>
                    <select id="ss-sort" name="sort">
                        <option value="newest" <?php selected($sort, 'newest'); ?>>Newest First</option>
                        <option value="oldest" <?php selected($sort, 'oldest'); ?>>Oldest First</option>
                    </select>
                    <button type="submit" class="ss-sort-submit">Apply</button>
                </form>
            </div>

            <?php if ($tutorials_query->have_posts()) : ?>
                <div class="ss-tutorial-list">
                    <?php while ($tutorials_query->have_posts()) : $tutorials_query->the_post(); ?>
                        <?php
                        $categories    = get_the_category();
                        $category_name = 'Tutorial';

                        if (! empty($categories)) {
                            foreach ($categories as $category) {
                                if ('tutorials' !== $category->slug) {
                                    $category_name = $category->name;
                                    break;
                                }
                            }

                            if ('Tutorial' === $category_name) {
                                $category_name = $categories[0]->name;
                            }
                        }

                        $read_time = max(1, (int) ceil(str_word_count(wp_strip_all_tags(get_the_content())) / 200));
                        ?>
                        <article class="ss-tutorial-card">
                            <a class="ss-tutorial-card__thumb" href="<?php the_permalink(); ?>" aria-label="Read <?php echo esc_attr(get_the_title()); ?>">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php
                                    $thumb_id  = (int) get_post_thumbnail_id();
                                    $thumb_alt = trim((string) get_post_meta($thumb_id, '_wp_attachment_image_alt', true));
                                    if ('' === $thumb_alt) {
                                        $thumb_alt = get_the_title();
                                    }

                                    echo wp_get_attachment_image(
                                        $thumb_id,
                                        'medium_large',
                                        false,
                                        array(
                                            'alt'     => $thumb_alt,
                                            'loading' => 'lazy',
                                        )
                                    );
                                    ?>
                                <?php else : ?>
                                    <span class="ss-tutorial-card__fallback"><?php echo esc_html(wp_trim_words(get_the_title(), 4, '')); ?></span>
                                <?php endif; ?>
                            </a>

                            <div class="ss-tutorial-card__body">
                                <p class="ss-card-kicker"><?php echo esc_html($category_name); ?></p>
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <p class="ss-tutorial-card__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                                <div class="ss-tutorial-card__meta">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('M j, Y')); ?></time>
                                    <span aria-hidden="true">|</span>
                                    <span><?php echo esc_html($read_time); ?> min read</span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <?php
                $pagination_links = paginate_links(
                    array(
                        'base'      => trailingslashit($base_tutorials_url) . '%_%',
                        'format'    => 'page/%#%/',
                        'current'   => $current_page,
                        'total'     => (int) $tutorials_query->max_num_pages,
                        'type'      => 'array',
                        'prev_text' => 'Previous',
                        'next_text' => 'Next',
                        'add_args'  => array_filter(
                            array(
                                'sort'  => 'newest' !== $sort ? $sort : null,
                                'topic' => $current_topic ? $current_topic : null,
                                'level' => $current_level ? $current_level : null,
                            )
                        ),
                    )
                );
                ?>

                <?php if (! empty($pagination_links)) : ?>
                    <nav class="ss-pagination" aria-label="Tutorial pagination">
                        <ul>
                            <?php foreach ($pagination_links as $link) : ?>
                                <li><?php echo wp_kses_post(str_replace('page-numbers', 'ss-page-button', $link)); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <article class="ss-empty-state">
                    <h2>No tutorials found</h2>
                    <p>There are no tutorials matching this filter yet. Try adjusting the filters or publishing new tutorial posts.</p>
                </article>
            <?php endif; ?>
        </section>

        <aside class="ss-tutorials-sidebar" aria-label="Tutorial filters and newsletter">
            <section class="ss-sidebar-card" aria-labelledby="ss-browse-tutorials-title">
                <h2 id="ss-browse-tutorials-title" class="ss-section-title">Browse Tutorials</h2>
                <ul class="ss-sidebar-list">
                    <li>
                        <a class="<?php echo $current_topic ? '' : 'is-active'; ?>" href="<?php echo esc_url(remove_query_arg(array('topic', 'paged'), $base_tutorials_url)); ?>">
                            <span>All Tutorials</span>
                            <strong><?php echo esc_html($count_tutorial_posts()); ?></strong>
                        </a>
                    </li>
                    <?php foreach ($topic_items as $topic_slug => $topic_label) : ?>
                        <li>
                            <a class="<?php echo $current_topic === $topic_slug ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(array_filter(array('topic' => $topic_slug, 'level' => $current_level ? $current_level : null, 'sort' => 'newest' !== $sort ? $sort : null)), $base_tutorials_url)); ?>">
                                <span><?php echo esc_html($topic_label); ?></span>
                                <strong><?php echo esc_html($count_tutorial_posts($topic_slug)); ?></strong>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="ss-sidebar-card" aria-labelledby="ss-skill-level-title">
                <h2 id="ss-skill-level-title" class="ss-section-title">Skill Level</h2>
                <ul class="ss-sidebar-list">
                    <li>
                        <a class="<?php echo $current_level ? '' : 'is-active'; ?>" href="<?php echo esc_url(remove_query_arg(array('level', 'paged'), $base_tutorials_url)); ?>">
                            <span>All Levels</span>
                            <strong><?php echo esc_html($count_tutorial_posts($current_topic)); ?></strong>
                        </a>
                    </li>
                    <?php foreach ($level_items as $level_slug => $level_label) : ?>
                        <li>
                            <a class="<?php echo $current_level === $level_slug ? 'is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(array_filter(array('level' => $level_slug, 'topic' => $current_topic ? $current_topic : null, 'sort' => 'newest' !== $sort ? $sort : null)), $base_tutorials_url)); ?>">
                                <span><?php echo esc_html($level_label); ?></span>
                                <strong><?php echo esc_html($count_tutorial_posts($current_topic, $level_slug)); ?></strong>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <section class="ss-sidebar-card ss-newsletter-card" aria-labelledby="ss-newsletter-title">
                <h2 id="ss-newsletter-title" class="ss-section-title">Stay in the loop</h2>
                <p>Get the latest tutorials delivered to your inbox.</p>
                <form method="post" action="#" novalidate>
                    <label class="screen-reader-text" for="ss-newsletter-email">Email address</label>
                    <input id="ss-newsletter-email" type="email" name="email" autocomplete="email" placeholder="Your email address" required aria-describedby="ss-newsletter-help ss-newsletter-status">
                    <p id="ss-newsletter-help" class="ss-form-note">Enter a valid email address.</p>
                    <p id="ss-newsletter-status" class="ss-form-status" role="status" aria-live="polite"></p>
                    <button type="submit">Subscribe</button>
                </form>
            </section>
        </aside>
    </div>
</main>

<?php
wp_reset_postdata();
get_footer();
