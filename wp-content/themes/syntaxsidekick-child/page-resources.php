<?php
/**
 * Resources page template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

get_template_part(
    'template-parts/components/content-hub',
    null,
    array(
        'section_key' => 'resources',
        'section_label' => 'Resources',
        'singular_label' => 'Resource',
        'base_category_slug' => 'resources',
        'topics' => array(
            'tools' => 'Tools',
            'libraries' => 'Libraries',
            'references' => 'References',
            'assets' => 'Assets',
            'workflows' => 'Workflows',
        ),
        'supports_level' => false,
        'hero' => array(
            'id' => 'ss-resources-page-title',
            'label' => 'RESOURCES',
            'title' => 'Resources',
            'description' => 'Curated tools, libraries, references, and assets to support better front-end development workflows.',
            'show_visual' => true,
        ),
    )
);

get_footer();
