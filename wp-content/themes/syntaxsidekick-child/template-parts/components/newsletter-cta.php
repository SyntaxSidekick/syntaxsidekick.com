<?php
/**
 * Reusable newsletter CTA strip.
 *
 * @package SyntaxSidekick_Child
 */

$layout = isset($args['layout']) ? (string) $args['layout'] : 'default';
$heading = isset($args['heading']) && '' !== (string) $args['heading']
    ? (string) $args['heading']
    : 'Stay in the loop';
$description = isset($args['description']) && '' !== (string) $args['description']
    ? (string) $args['description']
    : 'Get the latest front-end tips, tutorials, and resources straight to your inbox.';
$button_label = isset($args['button_label']) && '' !== (string) $args['button_label']
    ? (string) $args['button_label']
    : 'Subscribe';
$input_id = isset($args['input_id']) && '' !== (string) $args['input_id']
    ? (string) $args['input_id']
    : 'ss-newsletter-cta-email';
$help_id = isset($args['help_id']) && '' !== (string) $args['help_id']
    ? (string) $args['help_id']
    : 'ss-newsletter-cta-help';
$status_id = isset($args['status_id']) && '' !== (string) $args['status_id']
    ? (string) $args['status_id']
    : 'ss-newsletter-cta-status';

if ('sidebar' === $layout) :
    ?>
    <p><?php echo esc_html($description); ?></p>

    <form class="ss-newsletter-cta__form" method="post" action="#" novalidate>
        <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>">Your email address</label>
        <input id="<?php echo esc_attr($input_id); ?>" type="email" name="email" autocomplete="email" placeholder="Your email address" required aria-describedby="<?php echo esc_attr($help_id); ?> <?php echo esc_attr($status_id); ?>">
        <button type="submit"><?php echo esc_html($button_label); ?></button>
        <p id="<?php echo esc_attr($help_id); ?>" class="ss-form-note">No spam. Unsubscribe anytime.</p>
        <p id="<?php echo esc_attr($status_id); ?>" class="ss-form-status" role="status" aria-live="polite"></p>
    </form>
    <?php
    return;
endif;

if ('feature' === $layout) :
    ?>
    <section class="ss-newsletter-cta ss-newsletter-cta--feature" aria-labelledby="ss-newsletter-feature-title">
        <div class="ss-newsletter-cta__intro ss-newsletter-cta__intro--feature">
            <span class="ss-newsletter-cta__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" focusable="false">
                    <path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.8"></path>
                    <path d="M3 7l9 7 9-7" fill="none" stroke="currentColor" stroke-width="1.8"></path>
                </svg>
            </span>
            <div>
                <h2 id="ss-newsletter-feature-title"><?php echo esc_html($heading); ?></h2>
                <p><?php echo esc_html($description); ?></p>
            </div>
        </div>

        <form class="ss-newsletter-cta__form" method="post" action="#" novalidate>
            <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>">Your email address</label>
            <input id="<?php echo esc_attr($input_id); ?>" type="email" name="email" autocomplete="email" placeholder="Your email address" required aria-describedby="<?php echo esc_attr($help_id); ?> <?php echo esc_attr($status_id); ?>">
            <button type="submit"><?php echo esc_html($button_label); ?></button>
            <p id="<?php echo esc_attr($help_id); ?>" class="ss-form-note">No spam. Unsubscribe anytime.</p>
            <p id="<?php echo esc_attr($status_id); ?>" class="ss-form-status" role="status" aria-live="polite"></p>
        </form>
    </section>
    <?php
    return;
endif;
?>
<section class="ss-newsletter-cta" aria-labelledby="ss-newsletter-cta-title">
    <div class="ss-newsletter-cta__intro">
        <h2 id="ss-newsletter-cta-title"><?php echo esc_html($heading); ?></h2>
        <p><?php echo esc_html($description); ?></p>
    </div>

    <form class="ss-newsletter-cta__form" method="post" action="#" novalidate>
        <label class="screen-reader-text" for="<?php echo esc_attr($input_id); ?>">Your email address</label>
        <input id="<?php echo esc_attr($input_id); ?>" type="email" name="email" autocomplete="email" placeholder="Your email address" required aria-describedby="<?php echo esc_attr($help_id); ?> <?php echo esc_attr($status_id); ?>">
        <button type="submit"><?php echo esc_html($button_label); ?></button>
        <p id="<?php echo esc_attr($help_id); ?>" class="ss-form-note">No spam. Unsubscribe anytime.</p>
        <p id="<?php echo esc_attr($status_id); ?>" class="ss-form-status" role="status" aria-live="polite"></p>
    </form>
</section>
