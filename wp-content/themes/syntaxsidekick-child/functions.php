<?php
function syntaxsidekick_child_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height' => 180,
        'width' => 600,
        'flex-height' => true,
        'flex-width' => true,
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'syntaxsidekick-child'),
        'footer' => __('Footer Menu', 'syntaxsidekick-child'),
    ));
}
add_action('after_setup_theme', 'syntaxsidekick_child_setup');

function syntaxsidekick_child_dequeue_parent_assets() {
    // The child theme provides its own full stylesheet and navigation script.
    wp_dequeue_style('syntax-sidekick-style');
    wp_deregister_style('syntax-sidekick-style');

    wp_dequeue_script('syntax-sidekick-navigation');
    wp_deregister_script('syntax-sidekick-navigation');
}
add_action('wp_enqueue_scripts', 'syntaxsidekick_child_dequeue_parent_assets', 20);

function syntaxsidekick_child_enqueue_assets() {
    wp_enqueue_style('syntaxsidekick-child-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));

    $nav_script_path = get_stylesheet_directory() . '/assets/js/mega-menu.js';
    $nav_script_ver  = file_exists($nav_script_path)
        ? (string) filemtime($nav_script_path)
        : wp_get_theme()->get('Version');

    wp_enqueue_script(
        'syntaxsidekick-mega-menu',
        get_stylesheet_directory_uri() . '/assets/js/mega-menu.js',
        array(),
        $nav_script_ver,
        true
    );
}
add_action('wp_enqueue_scripts', 'syntaxsidekick_child_enqueue_assets', 30);

function syntaxsidekick_excerpt_length($length) {
    return 22;
}
add_filter('excerpt_length', 'syntaxsidekick_excerpt_length');

function syntaxsidekick_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'syntaxsidekick_excerpt_more');

function syntaxsidekick_fallback_menu($args = array()) {
    $menu_class = ! empty($args['menu_class']) ? (string) $args['menu_class'] : 'ss-nav-list ss-nav-list--fallback';

    $links = array(
        array(
            'label' => 'Home',
            'url' => home_url('/'),
            'active' => is_front_page() || is_home(),
        ),
        array(
            'label' => 'Articles',
            'url' => home_url('/articles/'),
            'active' => is_page('articles'),
        ),
        array(
            'label' => 'Tutorials',
            'url' => home_url('/tutorials/'),
            'active' => is_page('tutorials'),
        ),
        array(
            'label' => 'Guides',
            'url' => home_url('/guides/'),
            'active' => is_page('guides'),
        ),
        array(
            'label' => 'Resources',
            'url' => home_url('/resources/'),
            'active' => is_page('resources'),
        ),
        array(
            'label' => 'About',
            'url' => home_url('/about/'),
            'active' => is_page('about'),
        ),
        array(
            'label' => 'Contact',
            'url' => home_url('/contact/'),
            'active' => is_page('contact'),
        ),
    );

    echo '<ul class="' . esc_attr($menu_class) . '" role="list">';

    foreach ($links as $link) {
        $item_classes = 'ss-nav-item';
        $link_classes = 'ss-nav-link';

        if ($link['active']) {
            $item_classes .= ' current-menu-item is-active';
            $link_classes .= ' is-active';
        }

        echo '<li class="' . esc_attr($item_classes) . '"><a class="' . esc_attr($link_classes) . '" href="' . esc_url($link['url']) . '">' . esc_html($link['label']) . '</a></li>';
    }

    echo '</ul>';
}

function syntaxsidekick_primary_nav_item_classes($classes, $menu_item, $args) {
    if (empty($args->theme_location) || 'primary' !== $args->theme_location) {
        return $classes;
    }

    $classes[] = 'ss-nav-item';

    if (
        in_array('current-menu-item', $classes, true)
        || in_array('current-menu-parent', $classes, true)
        || in_array('current-menu-ancestor', $classes, true)
    ) {
        $classes[] = 'is-active';
    }

    return array_values(array_unique($classes));
}
add_filter('nav_menu_css_class', 'syntaxsidekick_primary_nav_item_classes', 10, 3);

function syntaxsidekick_primary_nav_link_attributes($atts, $menu_item, $args) {
    if (empty($args->theme_location) || 'primary' !== $args->theme_location) {
        return $atts;
    }

    $classes   = isset($atts['class']) ? (string) $atts['class'] : '';
    $class_arr = preg_split('/\s+/', trim($classes));
    $class_arr = is_array($class_arr) ? array_filter($class_arr) : array();
    $class_arr[] = 'ss-nav-link';

    if (
        in_array('current-menu-item', $menu_item->classes, true)
        || in_array('current-menu-parent', $menu_item->classes, true)
        || in_array('current-menu-ancestor', $menu_item->classes, true)
    ) {
        $class_arr[] = 'is-active';
    }

    $atts['class'] = implode(' ', array_values(array_unique($class_arr)));

    return $atts;
}
add_filter('nav_menu_link_attributes', 'syntaxsidekick_primary_nav_link_attributes', 10, 3);

function syntaxsidekick_has_seo_plugin() {
    return defined('RANK_MATH_VERSION') || defined('WPSEO_VERSION') || defined('AIOSEO_VERSION') || defined('SEOPRESS_VERSION');
}

function syntaxsidekick_fallback_meta_description() {
    if (is_front_page() || is_home()) {
        return get_bloginfo('description');
    }

    if (is_singular()) {
        $description = trim((string) get_the_excerpt());
        if ('' === $description) {
            $description = wp_strip_all_tags((string) get_the_content());
        }

        return wp_trim_words($description, 28, '...');
    }

    if (is_archive()) {
        $description = trim((string) get_the_archive_description());
        if ('' !== $description) {
            return wp_strip_all_tags($description);
        }
    }

    return get_bloginfo('description');
}

function syntaxsidekick_output_fallback_seo_meta() {
    if (syntaxsidekick_has_seo_plugin()) {
        return;
    }

    $description = syntaxsidekick_fallback_meta_description();
    if ('' !== $description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }

    $canonical = wp_get_canonical_url();
    if (! $canonical) {
        $canonical = is_home() ? home_url('/') : get_permalink();
    }

    if ($canonical) {
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }

    if (is_single()) {
        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => get_the_title(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => array(
                '@type' => 'Person',
                'name' => get_the_author(),
            ),
            'mainEntityOfPage' => array(
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ),
        );

        if (has_post_thumbnail()) {
            $image_url = get_the_post_thumbnail_url(null, 'large');
            if ($image_url) {
                $schema['image'] = array($image_url);
            }
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
add_action('wp_head', 'syntaxsidekick_output_fallback_seo_meta', 5);

function syntaxsidekick_get_post_read_time($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return '1 min read';
    }

    $word_count = str_word_count(wp_strip_all_tags((string) get_post_field('post_content', $post_id)));
    $minutes    = max(1, (int) ceil($word_count / 200));

    return $minutes . ' min read';
}

function syntaxsidekick_get_home_feature_highlights() {
    return array(
        array(
            'icon' => '</>',
            'title' => 'Modern Standards',
            'description' => 'CSS, JS, TS and beyond',
        ),
        array(
            'icon' => 'A11y',
            'title' => 'Accessibility First',
            'description' => 'Inclusive by default',
        ),
        array(
            'icon' => 'Perf',
            'title' => 'Performance Focused',
            'description' => 'Fast by design',
        ),
        array(
            'icon' => 'Idea',
            'title' => 'Practical Learning',
            'description' => 'Real world examples',
        ),
    );
}

function syntaxsidekick_get_live_video_data() {
    $default = array(
        'state' => 'live',
        'title' => 'Live on Twitch',
        'description' => 'Building a responsive dashboard with CSS Grid and Container Queries.',
        'platform' => 'Twitch',
        'cta_label' => 'Watch Live',
        'cta_url' => 'https://www.twitch.tv/',
        'latest_title' => 'CSS Container Queries: Full Guide',
        'latest_url' => 'https://www.youtube.com/',
        'latest_duration' => '18:42',
        'latest_thumbnail' => '',
        'latest_thumbnail_alt' => 'Latest video thumbnail',
        // Reserved for future integrations.
        'providers' => array('twitch', 'youtube_live', 'youtube_latest'),
        'provider_priority' => array('twitch', 'youtube_live', 'youtube_latest'),
    );

    return apply_filters('syntaxsidekick_live_video_data', $default);
}
