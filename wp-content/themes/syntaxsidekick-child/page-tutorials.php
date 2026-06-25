<?php
/**
 * Tutorials page template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

get_template_part(
    'template-parts/components/content-hub',
    null,
    array(
        'section_key' => 'tutorials',
        'section_label' => 'Tutorials',
        'singular_label' => 'Tutorial',
        'base_category_slug' => 'tutorials',
        'topics' => array(
            'html' => 'HTML',
            'css' => 'CSS',
            'javascript' => 'JavaScript',
            'typescript' => 'TypeScript',
            'react' => 'React',
            'vue' => 'Vue',
            'performance' => 'Performance',
            'accessibility' => 'Accessibility',
            'tools-workflow' => 'Tools & Workflow',
        ),
        'supports_level' => true,
        'hero' => array(
            'id' => 'ss-tutorials-page-title',
            'label' => 'TUTORIALS',
            'title' => 'Tutorials',
            'description' => 'Step-by-step tutorials to help you build real-world projects and sharpen your front-end skills.',
            'show_visual' => true,
        ),
    )
);

get_footer();
