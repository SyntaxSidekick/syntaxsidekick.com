<?php
/**
 * Shared content hub sidebar composition.
 *
 * @package SyntaxSidekick_Child
 */

$section_key = isset($args['section_key']) ? sanitize_key((string) $args['section_key']) : 'content';
$section_label = isset($args['section_label']) ? (string) $args['section_label'] : 'Content';
$browse_items = isset($args['browse_items']) && is_array($args['browse_items']) ? $args['browse_items'] : array();
$base_category_slug = isset($args['base_category_slug']) ? sanitize_key((string) $args['base_category_slug']) : '';
$listing_url = isset($args['listing_url']) ? (string) $args['listing_url'] : home_url('/');

$section_label_lower = strtolower($section_label);
?>
<aside class="ss-content-sidebar ss-tutorials-sidebar" aria-label="<?php echo esc_attr($section_label); ?> filters and newsletter">
    <?php
    get_template_part(
        'template-parts/components/content-category-list',
        null,
        array(
            'id' => 'ss-browse-' . $section_key . '-title',
            'heading' => 'Browse ' . $section_label,
            'items' => $browse_items,
        )
    );
    ?>

    <?php
    get_template_part(
        'template-parts/components/popular-content',
        null,
        array(
            'id' => 'ss-popular-' . $section_key . '-title',
            'heading' => 'Popular ' . $section_label,
            'base_category_slug' => $base_category_slug,
            'listing_url' => $listing_url,
            'limit' => 5,
            'empty_message' => 'No ' . $section_label_lower . ' published yet.',
            'footer_label' => 'View all ' . $section_label_lower,
        )
    );
    ?>

    <section class="ss-sidebar-card ss-newsletter-card" aria-labelledby="ss-newsletter-<?php echo esc_attr($section_key); ?>-title">
        <h2 id="ss-newsletter-<?php echo esc_attr($section_key); ?>-title" class="ss-section-title">Stay in the Loop</h2>
        <?php
        get_template_part(
            'template-parts/components/newsletter-cta',
            null,
            array(
                'layout' => 'sidebar',
                'description' => 'Get the latest ' . $section_label_lower . ' delivered to your inbox.',
                'input_id' => 'ss-newsletter-' . $section_key . '-email',
                'help_id' => 'ss-newsletter-' . $section_key . '-help',
                'status_id' => 'ss-newsletter-' . $section_key . '-status',
            )
        );
        ?>
    </section>
</aside>
