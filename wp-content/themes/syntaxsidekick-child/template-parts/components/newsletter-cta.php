<?php
/**
 * Reusable newsletter CTA strip.
 *
 * @package SyntaxSidekick_Child
 */
?>
<section class="ss-newsletter-cta" aria-labelledby="ss-newsletter-cta-title">
    <div class="ss-newsletter-cta__intro">
        <h2 id="ss-newsletter-cta-title">Stay in the loop</h2>
        <p>Get the latest front-end tips, tutorials, and resources straight to your inbox.</p>
    </div>

    <form class="ss-newsletter-cta__form" method="post" action="#" novalidate>
        <label class="screen-reader-text" for="ss-newsletter-cta-email">Your email address</label>
        <input id="ss-newsletter-cta-email" type="email" name="email" autocomplete="email" placeholder="Your email address" required aria-describedby="ss-newsletter-cta-help ss-newsletter-cta-status">
        <button type="submit">Subscribe</button>
        <p id="ss-newsletter-cta-help" class="ss-form-note">No spam. Unsubscribe anytime.</p>
        <p id="ss-newsletter-cta-status" class="ss-form-status" role="status" aria-live="polite"></p>
    </form>
</section>
