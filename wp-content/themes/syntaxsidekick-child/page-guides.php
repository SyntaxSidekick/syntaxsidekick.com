<?php
/**
 * Guides page template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

get_template_part(
    'template-parts/components/content-hub',
    null,
    array(
        'section_key' => 'guides',
        'section_label' => 'Guides',
        'singular_label' => 'Guide',
        'base_category_slug' => 'guides',
        'topics' => array(
            'architecture' => 'Architecture',
            'design-systems' => 'Design Systems',
            'accessibility' => 'Accessibility',
            'performance' => 'Performance',
            'modern-workflows' => 'Modern Workflows',
        ),
        'supports_level' => false,
        'hero' => array(
            'id' => 'ss-guides-page-title',
            'label' => 'GUIDES',
            'title' => 'Guides',
            'description' => 'Long-form guides for understanding front-end architecture, design systems, accessibility, performance, and modern development workflows.',
            'show_visual' => true,
        ),
    )
);

get_footer();
