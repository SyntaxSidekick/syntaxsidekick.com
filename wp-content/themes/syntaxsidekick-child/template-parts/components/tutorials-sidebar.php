<?php
/**
 * Tutorials page sidebar composition.
 *
 * @package SyntaxSidekick_Child
 */

$browse_items = isset($args['browse_items']) && is_array($args['browse_items']) ? $args['browse_items'] : array();
?>
<aside class="ss-sidebar ss-sidebar--hub ss-tutorials-sidebar" aria-label="Tutorial filters and newsletter">
    <?php
    get_template_part(
        'template-parts/components/tutorial-category-list',
        null,
        array(
            'id' => 'ss-browse-tutorials-title',
            'heading' => 'Browse Tutorials',
            'items' => $browse_items,
        )
    );
    ?>

    <?php
    get_template_part(
        'template-parts/components/popular-tutorials',
        null,
        array(
            'id' => 'ss-popular-tutorials-title',
            'heading' => 'Popular Tutorials',
            'limit' => 5,
        )
    );
    ?>

    <section class="ss-sidebar-card ss-newsletter-card" aria-labelledby="ss-newsletter-title">
        <h2 id="ss-newsletter-title" class="ss-section-title">Stay in the Loop</h2>
        <?php
        get_template_part(
            'template-parts/components/newsletter-cta',
            null,
            array(
                'layout' => 'sidebar',
                'description' => 'Get the latest tutorials delivered to your inbox.',
                'input_id' => 'ss-newsletter-email',
                'help_id' => 'ss-newsletter-help',
                'status_id' => 'ss-newsletter-status',
            )
        );
        ?>
    </section>
</aside>
