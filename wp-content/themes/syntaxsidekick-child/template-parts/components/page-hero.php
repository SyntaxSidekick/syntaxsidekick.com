<?php
/**
 * Reusable internal page hero.
 *
 * @package SyntaxSidekick_Child
 */

$hero_id = isset($args['id']) && is_string($args['id']) && '' !== $args['id']
    ? $args['id']
    : 'ss-page-hero-title';
$hero_label = isset($args['label']) ? (string) $args['label'] : '';
$hero_title = isset($args['title']) ? (string) $args['title'] : '';
$hero_description = isset($args['description']) ? (string) $args['description'] : '';
$show_visual = ! isset($args['show_visual']) || (bool) $args['show_visual'];
?>
<section class="ss-page-hero" aria-labelledby="<?php echo esc_attr($hero_id); ?>">
    <div class="ss-container ss-page-hero__grid">
        <div class="ss-page-hero__content">
            <?php if ('' !== $hero_label) : ?>
                <p class="ss-eyebrow"><?php echo esc_html($hero_label); ?></p>
            <?php endif; ?>

            <?php if ('' !== $hero_title) : ?>
                <h1 id="<?php echo esc_attr($hero_id); ?>"><?php echo esc_html($hero_title); ?></h1>
            <?php endif; ?>

            <?php if ('' !== $hero_description) : ?>
                <p><?php echo esc_html($hero_description); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($show_visual) : ?>
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
        <?php endif; ?>
    </div>
</section>
