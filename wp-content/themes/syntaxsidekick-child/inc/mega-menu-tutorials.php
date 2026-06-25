<?php
/**
 * Shared mega menu data and rendering.
 *
 * @package SyntaxSidekick_Child
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Return category URL by slug with safe fallback query URL.
 *
 * @param string $slug       Category slug.
 * @param string $base_path  Base path for fallback URLs.
 * @param string $query_arg  Query arg key used in fallback URL.
 *
 * @return string
 */
function syntaxsidekick_get_topic_term_url($slug, $base_path = '/', $query_arg = 'topic') {
    $slug = sanitize_key((string) $slug);
    $base_path = (string) $base_path;
    $query_arg = sanitize_key((string) $query_arg);

    $base_url = home_url($base_path);
    if ('' === $slug) {
        return $base_url;
    }

    $term = get_term_by('slug', $slug, 'category');
    if ($term instanceof WP_Term) {
        $term_link = get_term_link($term);
        if (! is_wp_error($term_link) && $term_link) {
            return $term_link;
        }
    }

    return add_query_arg($query_arg ? $query_arg : 'topic', rawurlencode($slug), $base_url);
}

/**
 * Backward-compatible tutorial topic URL helper.
 *
 * @param string $slug Topic/category slug.
 *
 * @return string
 */
function syntaxsidekick_get_tutorial_topic_url($slug) {
    return syntaxsidekick_get_topic_term_url($slug, '/tutorials/', 'topic');
}

/**
 * Map taxonomy/topic slug to icon name.
 *
 * @param string $slug Topic slug.
 *
 * @return string
 */
function syntaxsidekick_get_topic_icon($slug) {
    $slug = sanitize_key((string) $slug);

    $map = array(
        'html' => 'html5',
        'html5' => 'html5',
        'css' => 'css',
        'javascript' => 'javascript',
        'js' => 'javascript',
        'typescript' => 'typescript',
        'ts' => 'typescript',
        'react' => 'react',
        'vue' => 'vuejs',
        'vuejs' => 'vuejs',
        'wordpress' => 'wordpress',
        'accessibility' => 'accessibility',
        'performance' => 'performance',
        'performance-optimization' => 'performance',
    );

    return $map[$slug] ?? '';
}

/**
 * Backward-compatible tutorial icon helper.
 *
 * @param string $slug Tutorial slug.
 *
 * @return string
 */
function syntaxsidekick_get_tutorial_icon($slug) {
    return syntaxsidekick_get_topic_icon($slug);
}

/**
 * Fetch latest posts for a menu section with caching per request.
 *
 * @param string $section_slug Section category slug.
 * @param int    $limit        Number of posts.
 *
 * @return array<int,array<string,mixed>>
 */
function syntaxsidekick_get_mega_menu_posts($section_slug, $limit = 5) {
    static $cache = array();

    $section_slug = sanitize_key((string) $section_slug);
    $limit = max(1, (int) $limit);
    $cache_key = $section_slug . ':' . $limit;

    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }

    $version = function_exists('syntaxsidekick_perf_cache_version')
        ? syntaxsidekick_perf_cache_version()
        : 1;
    $transient_key = 'ss_mega_posts_' . md5($section_slug . ':' . $limit . ':' . $version);
    $stored_posts = get_transient($transient_key);
    if (is_array($stored_posts)) {
        $cache[$cache_key] = $stored_posts;
        return $stored_posts;
    }

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page' => $limit,
        'no_found_rows' => true,
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $section_term = get_category_by_slug($section_slug);
    if ($section_term instanceof WP_Term) {
        $args['category__in'] = array((int) $section_term->term_id);
    }

    $query = new WP_Query($args);
    $posts = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $posts[] = array(
                'id' => (int) get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'read_time' => function_exists('syntaxsidekick_get_post_read_time')
                    ? syntaxsidekick_get_post_read_time(get_the_ID())
                    : '1 min read',
            );
        }
    }

    wp_reset_postdata();
    set_transient($transient_key, $posts, 10 * MINUTE_IN_SECONDS);
    $cache[$cache_key] = $posts;

    return $posts;
}

/**
 * Build normalized category entries for mega menu sections.
 *
 * @param array<string,string> $category_map Slug => label map.
 * @param string               $base_path    Base section path.
 *
 * @return array<int,array<string,mixed>>
 */
function syntaxsidekick_build_mega_menu_categories($category_map, $base_path) {
    $entries = array();

    foreach ($category_map as $slug => $label) {
        $term = get_category_by_slug($slug);

        $entries[] = array(
            'slug' => sanitize_key((string) $slug),
            'label' => (string) $label,
            'url' => syntaxsidekick_get_topic_term_url($slug, $base_path, 'topic'),
            'count' => $term instanceof WP_Term ? (int) $term->count : 0,
            'icon' => syntaxsidekick_get_topic_icon($slug),
            'color_class' => function_exists('syntaxsidekick_get_icon_color_class')
                ? syntaxsidekick_get_icon_color_class($slug)
                : 'ss-icon--neutral',
        );
    }

    return $entries;
}

/**
 * Return full shared mega menu data.
 *
 * @return array<string,array<string,mixed>>
 */
function syntaxsidekick_get_mega_menu_data() {
    static $cache = null;

    if (is_array($cache)) {
        return $cache;
    }

    $article_categories = array(
        'front-end-development' => 'Front-End Development',
        'css' => 'CSS',
        'javascript' => 'JavaScript',
        'typescript' => 'TypeScript',
        'ux-engineering' => 'UX Engineering',
        'accessibility' => 'Accessibility',
        'performance' => 'Performance',
        'design-systems' => 'Design Systems',
        'ai-development' => 'AI Development',
    );

    $tutorial_categories = array(
        'html' => 'HTML',
        'css' => 'CSS',
        'javascript' => 'JavaScript',
        'typescript' => 'TypeScript',
        'react' => 'React',
        'vue' => 'Vue',
        'wordpress' => 'WordPress',
        'accessibility' => 'Accessibility',
        'performance' => 'Performance',
    );

    $article_posts = syntaxsidekick_get_mega_menu_posts('articles', 5);
    $tutorial_posts = syntaxsidekick_get_mega_menu_posts('tutorials', 5);

    $cache = array(
        'home' => array(
            'key' => 'home',
            'label' => 'Home',
            'url' => home_url('/'),
            'description' => '',
            'icon' => '',
            'color_class' => 'ss-icon--neutral',
            'categories' => array(),
            'featured' => null,
            'latest' => array(),
            'tracks' => array(),
            'cta' => null,
            'has_mega_menu' => false,
            'active_paths' => array('/'),
        ),
        'articles' => array(
            'key' => 'articles',
            'label' => 'Articles',
            'url' => home_url('/articles/'),
            'description' => 'Insights, opinions, and practical front-end development articles.',
            'icon' => 'javascript',
            'color_class' => 'ss-icon--javascript',
            'categories' => syntaxsidekick_build_mega_menu_categories($article_categories, '/articles/'),
            'featured' => ! empty($article_posts) ? $article_posts[0] : null,
            'latest' => $article_posts,
            'tracks' => array(
                array('label' => 'Popular Front-End Topics', 'url' => home_url('/articles/?view=topics')),
                array('label' => 'Architecture Insights', 'url' => home_url('/articles/?topic=front-end-architecture')),
                array('label' => 'AI Development Articles', 'url' => home_url('/articles/?topic=ai-development')),
            ),
            'cta' => array(
                'label' => 'Browse all articles',
                'url' => home_url('/articles/'),
            ),
            'has_mega_menu' => true,
            'active_paths' => array('/articles/', '/category/'),
            'panel_template_id' => 'ss-articles-mega-template',
            'categories_heading' => 'Browse Categories',
            'tracks_heading' => 'Popular Topics',
            'featured_heading' => 'Featured Article',
            'latest_heading' => 'Latest Articles',
            'empty_latest_message' => 'No articles published yet.',
            'empty_featured_message' => 'No featured article yet. Publish an article to populate this area.',
        ),
        'tutorials' => array(
            'key' => 'tutorials',
            'label' => 'Tutorials',
            'url' => home_url('/tutorials/'),
            'description' => 'Step-by-step tutorials for building real-world front-end skills.',
            'icon' => 'typescript',
            'color_class' => 'ss-icon--typescript',
            'categories' => syntaxsidekick_build_mega_menu_categories($tutorial_categories, '/tutorials/'),
            'featured' => ! empty($tutorial_posts) ? $tutorial_posts[0] : null,
            'latest' => $tutorial_posts,
            'tracks' => array(
                array('label' => 'Front-End Foundations', 'url' => home_url('/tutorials/?track=frontend-foundations')),
                array('label' => 'Modern CSS Path', 'url' => home_url('/tutorials/?track=modern-css')),
                array('label' => 'JavaScript to TypeScript', 'url' => home_url('/tutorials/?track=js-to-ts')),
                array('label' => 'Accessibility in Practice', 'url' => home_url('/tutorials/?track=accessibility-practice')),
            ),
            'cta' => array(
                'label' => 'Browse all tutorials',
                'url' => home_url('/tutorials/'),
                'variant' => 'green',
            ),
            'has_mega_menu' => true,
            'active_paths' => array('/tutorials/'),
            'panel_template_id' => 'ss-tutorials-mega-template',
            'categories_heading' => 'Browse Categories',
            'tracks_heading' => 'Learning Paths',
            'featured_heading' => 'Featured Tutorial',
            'latest_heading' => 'Latest Tutorials',
            'empty_latest_message' => 'No tutorials published yet.',
            'empty_featured_message' => 'No featured tutorial yet. Publish a tutorial to populate this area.',
        ),
        'resources' => array(
            'key' => 'resources',
            'label' => 'Resources',
            'url' => home_url('/resources/'),
            'description' => '',
            'icon' => '',
            'color_class' => 'ss-icon--neutral',
            'categories' => array(),
            'featured' => null,
            'latest' => array(),
            'tracks' => array(),
            'cta' => null,
            'has_mega_menu' => false,
            'active_paths' => array('/resources/'),
        ),
        'guides' => array(
            'key' => 'guides',
            'label' => 'Guides',
            'url' => home_url('/guides/'),
            'description' => '',
            'icon' => '',
            'color_class' => 'ss-icon--neutral',
            'categories' => array(),
            'featured' => null,
            'latest' => array(),
            'tracks' => array(),
            'cta' => null,
            'has_mega_menu' => false,
            'active_paths' => array('/guides/'),
        ),
        'about' => array(
            'key' => 'about',
            'label' => 'About',
            'url' => home_url('/about/'),
            'description' => '',
            'icon' => '',
            'color_class' => 'ss-icon--neutral',
            'categories' => array(),
            'featured' => null,
            'latest' => array(),
            'tracks' => array(),
            'cta' => null,
            'has_mega_menu' => false,
            'active_paths' => array('/about/'),
        ),
        'contact' => array(
            'key' => 'contact',
            'label' => 'Contact',
            'url' => home_url('/contact/'),
            'description' => '',
            'icon' => '',
            'color_class' => 'ss-icon--neutral',
            'categories' => array(),
            'featured' => null,
            'latest' => array(),
            'tracks' => array(),
            'cta' => null,
            'has_mega_menu' => false,
            'active_paths' => array('/contact/'),
        ),
    );

    return $cache;
}

/**
 * Backward-compatible tutorials-only data getter.
 *
 * @return array<string,mixed>
 */
function syntaxsidekick_get_tutorials_mega_menu_data() {
    $all = syntaxsidekick_get_mega_menu_data();
    return $all['tutorials'] ?? array();
}

/**
 * Determine whether the current request belongs to a shared nav section.
 *
 * @param string $section_key Menu section key.
 *
 * @return bool
 */
function syntaxsidekick_is_mega_menu_section_active($section_key) {
    $section_key = sanitize_key((string) $section_key);

    if ('home' === $section_key) {
        return is_front_page() || is_home();
    }

    if ('articles' === $section_key) {
        return is_page('articles') || is_category('articles') || has_category('articles');
    }

    if ('tutorials' === $section_key) {
        return is_page('tutorials') || has_category('tutorials') || is_category('tutorials');
    }

    if ('resources' === $section_key) {
        return is_page('resources');
    }

    if ('guides' === $section_key) {
        return is_page('guides');
    }

    if ('about' === $section_key) {
        return is_page('about');
    }

    if ('contact' === $section_key) {
        return is_page('contact');
    }

    return false;
}

/**
 * Return compact mega menu payload for navigation JS behavior.
 *
 * @return array<int,array<string,mixed>>
 */
function syntaxsidekick_get_mega_menu_js_payload() {
    $items = syntaxsidekick_get_mega_menu_data();
    $payload = array();

    foreach ($items as $item) {
        $payload[] = array(
            'key' => isset($item['key']) ? (string) $item['key'] : '',
            'label' => isset($item['label']) ? (string) $item['label'] : '',
            'url' => isset($item['url']) ? (string) $item['url'] : home_url('/'),
            'hasMegaMenu' => ! empty($item['has_mega_menu']),
            'forceActive' => ! empty($item['key']) ? syntaxsidekick_is_mega_menu_section_active((string) $item['key']) : false,
            'activePaths' => isset($item['active_paths']) && is_array($item['active_paths'])
                ? array_values($item['active_paths'])
                : array(),
            'panelTemplateId' => isset($item['panel_template_id']) ? (string) $item['panel_template_id'] : '',
        );
    }

    return $payload;
}

/**
 * Render a mega menu panel for a specific menu key.
 *
 * @param string $menu_key Menu item key.
 *
 * @return void
 */
function syntaxsidekick_render_mega_menu_panel($menu_key) {
    $menu_key = sanitize_key((string) $menu_key);
    $all_data = syntaxsidekick_get_mega_menu_data();

    if (empty($all_data[$menu_key])) {
        return;
    }

    $item = $all_data[$menu_key];
    if (empty($item['has_mega_menu'])) {
        return;
    }

    $categories = isset($item['categories']) && is_array($item['categories']) ? $item['categories'] : array();
    $latest = isset($item['latest']) && is_array($item['latest']) ? $item['latest'] : array();
    $tracks = isset($item['tracks']) && is_array($item['tracks']) ? $item['tracks'] : array();
    $featured = isset($item['featured']) && is_array($item['featured']) ? $item['featured'] : null;
    $cta = isset($item['cta']) && is_array($item['cta']) ? $item['cta'] : null;

    $categories_heading = ! empty($item['categories_heading']) ? (string) $item['categories_heading'] : 'Browse Categories';
    $tracks_heading = ! empty($item['tracks_heading']) ? (string) $item['tracks_heading'] : 'Learning Paths';
    $featured_heading = ! empty($item['featured_heading']) ? (string) $item['featured_heading'] : 'Featured';
    $latest_heading = ! empty($item['latest_heading']) ? (string) $item['latest_heading'] : 'Latest';
    $empty_latest = ! empty($item['empty_latest_message']) ? (string) $item['empty_latest_message'] : 'No content published yet.';
    $empty_featured = ! empty($item['empty_featured_message']) ? (string) $item['empty_featured_message'] : 'No featured content yet.';
    $thumb_text = strtoupper(substr(! empty($item['label']) ? (string) $item['label'] : 'XX', 0, 2));
    $cta_class = 'ss-panel-cta';

    if (! empty($cta['variant']) && 'green' === $cta['variant']) {
        $cta_class .= ' ss-panel-cta-green';
    }
    ?>
    <div class="ss-mega-pointer"></div>
    <div class="ss-mega-content ss-mega-two-col">
        <div class="ss-mega-column">
            <h2 class="ss-mega-title"><?php echo esc_html($categories_heading); ?></h2>
            <?php if (! empty($item['description'])) : ?>
                <p><?php echo esc_html((string) $item['description']); ?></p>
            <?php endif; ?>

            <?php if (! empty($categories)) : ?>
                <ul class="ss-category-list" role="list">
                    <?php foreach ($categories as $category) : ?>
                        <?php
                        $slug = isset($category['slug']) ? (string) $category['slug'] : '';
                        $label = isset($category['label']) ? (string) $category['label'] : '';
                        $url = isset($category['url']) ? (string) $category['url'] : home_url('/');
                        $count = isset($category['count']) ? (int) $category['count'] : 0;
                        $icon_name = isset($category['icon']) ? (string) $category['icon'] : '';
                        $color_class = isset($category['color_class']) ? (string) $category['color_class'] : 'ss-icon--neutral';
                        $icon_svg = $icon_name && function_exists('syntaxsidekick_icon')
                            ? syntaxsidekick_icon(
                                $icon_name,
                                array(
                                    'class' => 'ss-menu-icon ' . $color_class,
                                    'decorative' => true,
                                )
                            )
                            : '';
                        ?>
                        <li>
                            <a href="<?php echo esc_url($url); ?>">
                                <span class="ss-topic">
                                    <?php if ('' !== $icon_svg) : ?>
                                        <?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <?php else : ?>
                                        <span class="ss-topic-icon"><?php echo esc_html(strtoupper(substr($slug, 0, 2))); ?></span>
                                    <?php endif; ?>
                                    <?php echo esc_html($label); ?>
                                </span>
                                <span class="ss-count"><?php echo esc_html((string) $count); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <ul class="ss-mega-list" role="list">
                    <li><span><?php echo esc_html__('Categories are not available yet.', 'syntaxsidekick-child'); ?></span></li>
                </ul>
            <?php endif; ?>

            <h3 class="ss-mega-title"><?php echo esc_html($tracks_heading); ?></h3>
            <?php if (! empty($tracks)) : ?>
                <ul class="ss-mega-list" role="list">
                    <?php foreach ($tracks as $track) : ?>
                        <li>
                            <a href="<?php echo esc_url((string) ($track['url'] ?? home_url('/'))); ?>"><?php echo esc_html((string) ($track['label'] ?? '')); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <ul class="ss-mega-list" role="list">
                    <li><span><?php echo esc_html__('Additional links are coming soon.', 'syntaxsidekick-child'); ?></span></li>
                </ul>
            <?php endif; ?>

            <?php if (! empty($cta['label']) && ! empty($cta['url'])) : ?>
                <a class="<?php echo esc_attr($cta_class); ?>" href="<?php echo esc_url((string) $cta['url']); ?>">
                    <span><?php echo esc_html((string) $cta['label']); ?></span>
                    <span aria-hidden="true">-></span>
                </a>
            <?php endif; ?>
        </div>

        <div class="ss-mega-column">
            <h2 class="ss-mega-title"><?php echo esc_html($featured_heading); ?></h2>
            <?php if ($featured && ! empty($featured['url']) && ! empty($featured['title'])) : ?>
                <a class="<?php echo esc_attr($cta_class); ?>" href="<?php echo esc_url((string) $featured['url']); ?>">
                    <span>
                        <?php echo esc_html((string) $featured['title']); ?>
                        <small><?php echo esc_html((string) ($featured['read_time'] ?? '')); ?></small>
                    </span>
                    <span aria-hidden="true">-></span>
                </a>
            <?php else : ?>
                <p><?php echo esc_html($empty_featured); ?></p>
            <?php endif; ?>

            <h3 class="ss-mega-title"><?php echo esc_html($latest_heading); ?></h3>
            <?php if (! empty($latest)) : ?>
                <ul class="ss-popular-list" role="list">
                    <?php foreach ($latest as $entry) : ?>
                        <?php
                        $entry_url = isset($entry['url']) ? (string) $entry['url'] : '';
                        $entry_title = isset($entry['title']) ? (string) $entry['title'] : '';
                        if ('' === $entry_url || '' === $entry_title) {
                            continue;
                        }
                        ?>
                        <li>
                            <a href="<?php echo esc_url($entry_url); ?>">
                                <span class="ss-thumb"><?php echo esc_html($thumb_text); ?></span>
                                <span class="ss-copy"><?php echo esc_html($entry_title); ?></span>
                                <span class="ss-meta"><?php echo esc_html((string) ($entry['read_time'] ?? '')); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <ul class="ss-mega-list" role="list">
                    <li><span><?php echo esc_html($empty_latest); ?></span></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Output all mega menu panel templates for header/nav hydration.
 *
 * @return void
 */
function syntaxsidekick_render_mega_menu_templates() {
    $all_data = syntaxsidekick_get_mega_menu_data();

    foreach ($all_data as $item) {
        if (empty($item['has_mega_menu']) || empty($item['panel_template_id'])) {
            continue;
        }

        $template_id = (string) $item['panel_template_id'];
        ?>
        <template id="<?php echo esc_attr($template_id); ?>">
            <?php
            if (! empty($item['key'])) {
                syntaxsidekick_render_mega_menu_panel((string) $item['key']);
            }
            ?>
        </template>
        <?php
    }
}

/**
 * Backward-compatible Tutorials panel renderer.
 *
 * @return void
 */
function syntaxsidekick_render_tutorials_mega_menu() {
    syntaxsidekick_render_mega_menu_panel('tutorials');
}
