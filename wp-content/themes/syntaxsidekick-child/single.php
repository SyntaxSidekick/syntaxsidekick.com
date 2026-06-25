<?php
/**
 * Single post template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

$section_configs = array(
    'articles' => array(
        'section_key' => 'articles',
        'section_label' => 'Articles',
        'singular_label' => 'Article',
        'base_category_slug' => 'articles',
        'listing_url' => home_url('/articles/'),
    ),
    'tutorials' => array(
        'section_key' => 'tutorials',
        'section_label' => 'Tutorials',
        'singular_label' => 'Tutorial',
        'base_category_slug' => 'tutorials',
        'listing_url' => home_url('/tutorials/'),
    ),
    'resources' => array(
        'section_key' => 'resources',
        'section_label' => 'Resources',
        'singular_label' => 'Resource',
        'base_category_slug' => 'resources',
        'listing_url' => home_url('/resources/'),
    ),
    'guides' => array(
        'section_key' => 'guides',
        'section_label' => 'Guides',
        'singular_label' => 'Guide',
        'base_category_slug' => 'guides',
        'listing_url' => home_url('/guides/'),
    ),
);

if (have_posts()) :
    while (have_posts()) :
        the_post();

        $post_id = get_the_ID();
        $category_slugs = array();
        $post_categories = get_the_category($post_id);
        foreach ($post_categories as $category) {
            $category_slugs[] = $category->slug;
        }

        $section_key = 'articles';
        foreach (array('tutorials', 'articles', 'resources', 'guides') as $candidate_key) {
            if (in_array($candidate_key, $category_slugs, true)) {
                $section_key = $candidate_key;
                break;
            }
        }

        $layout_args = $section_configs[$section_key] ?? $section_configs['articles'];
        $layout_args['post_id'] = $post_id;

        get_template_part(
            'template-parts/components/single-content-layout',
            null,
            $layout_args
        );
    endwhile;
endif;

get_footer();
