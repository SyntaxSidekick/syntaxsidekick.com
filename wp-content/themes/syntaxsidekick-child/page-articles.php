<?php
/**
 * Articles page template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

get_template_part(
    'template-parts/components/content-hub',
    null,
    array(
        'section_key' => 'articles',
        'section_label' => 'Articles',
        'singular_label' => 'Article',
        'base_category_slug' => 'articles',
        'topics' => array(
            'front-end-development' => 'Front-End Development',
            'ux-engineering' => 'UX Engineering',
            'accessibility' => 'Accessibility',
            'performance' => 'Performance',
            'design-systems' => 'Design Systems',
            'ai-development' => 'AI Development',
        ),
        'supports_level' => false,
        'hero' => array(
            'id' => 'ss-articles-page-title',
            'label' => 'ARTICLES',
            'title' => 'Articles',
            'description' => 'Insights, tutorials, and practical guidance on modern front-end development, UX engineering, accessibility, performance, and design systems.',
            'show_visual' => true,
        ),
    )
);

get_footer();
