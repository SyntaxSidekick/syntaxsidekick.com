<?php
/**
 * Reusable homepage hero section.
 *
 * @package SyntaxSidekick_Child
 */

$features = isset($args['features']) && is_array($args['features']) ? $args['features'] : array();
$buttons  = isset($args['buttons']) && is_array($args['buttons']) ? $args['buttons'] : array();
?>
<section class="ss-home-hero" aria-labelledby="ss-home-hero-title">
    <div class="ss-container ss-home-hero__grid">
        <div class="ss-home-hero__content">
            <p class="ss-eyebrow">Modern front-end development</p>
            <h1 id="ss-home-hero-title">Practical knowledge. <span>Cleaner code.</span></h1>
            <p>In-depth articles, tutorials, and tools to help you build modern, accessible, and high-performance web experiences.</p>

            <?php get_template_part('template-parts/components/button-group', null, array('buttons' => $buttons)); ?>

            <?php if (! empty($features)) : ?>
                <ul class="ss-feature-list">
                    <?php foreach ($features as $feature) : ?>
                        <?php get_template_part('template-parts/components/feature-highlight', null, $feature); ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="ss-home-hero__visual" aria-hidden="true">
            <div class="ss-home-visual-card">
                <div class="ss-window-dots"><span></span><span></span><span></span></div>
                <pre><code>.layout {
  display: grid;
  gap: var(--ss-space-3);
}

.card:focus-visible {
  outline: 3px solid var(--ss-color-accent);
}

@container (min-width: 42rem) {
  .layout { grid-template-columns: 1fr 1fr; }
}</code></pre>
            </div>
            <div class="ss-tech-stack">
                <span>CSS</span>
                <span>JS</span>
                <span>Next</span>
                <span>Tailwind</span>
            </div>
        </div>
    </div>
</section>
