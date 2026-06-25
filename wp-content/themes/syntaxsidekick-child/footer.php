<footer class="ss-site-footer">
    <div class="ss-container ss-site-footer__grid">
        <section class="ss-site-footer__brand" aria-label="SyntaxSidekick brand">
            <a class="ss-logo ss-site-footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="SyntaxSidekick home">
                <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo.png'); ?>" alt="SyntaxSidekick">
            </a>
            <p>Step-by-step tutorials and resources to level up your front-end skills and build amazing projects.</p>

            <ul class="ss-site-footer__social" role="list">
                <li>
                    <a href="https://x.com/riadkilani" aria-label="Follow on X">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M18.245 2H21.5l-7.11 8.1L22.75 22h-6.54l-5.12-6.7L5.2 22H1.94l7.6-8.66L1.5 2h6.7l4.62 6.1z" fill="currentColor"></path></svg>
                    </a>
                </li>
                <li>
                    <a href="https://github.com/riadkilani" aria-label="View GitHub profile">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M12 .5A11.5 11.5 0 0 0 .5 12.3c0 5.3 3.4 9.8 8 11.4.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.4-4-1.4-.6-1.4-1.3-1.8-1.3-1.8-1.1-.8.1-.8.1-.8 1.2.1 1.9 1.2 1.9 1.2 1 1.8 2.8 1.2 3.5.9.1-.8.4-1.2.7-1.5-2.6-.3-5.3-1.3-5.3-5.9 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.2 0 0 1-.3 3.3 1.2a11 11 0 0 1 6 0c2.3-1.5 3.3-1.2 3.3-1.2.7 1.7.3 2.9.1 3.2.8.8 1.2 1.8 1.2 3.1 0 4.6-2.8 5.5-5.4 5.8.4.4.8 1 .8 2.1v3.1c0 .3.2.7.8.6a11.8 11.8 0 0 0 8-11.4A11.5 11.5 0 0 0 12 .5z" fill="currentColor"></path></svg>
                    </a>
                </li>
                <li>
                    <a href="https://www.linkedin.com/in/riadkilani" aria-label="View LinkedIn profile">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M4.98 3.5A2.49 2.49 0 0 0 2.5 6a2.5 2.5 0 1 0 2.48-2.5zM3 8h4v13H3zm7 0h3.82v1.77h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-6.19c0-1.48-.03-3.39-2.06-3.39-2.06 0-2.38 1.61-2.38 3.29V21h-4z" fill="currentColor"></path></svg>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url(home_url('/feed/')); ?>" aria-label="RSS feed">
                        <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><circle cx="6" cy="18" r="2" fill="currentColor"></circle><path d="M4 11a9 9 0 0 1 9 9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M4 5a15 15 0 0 1 15 15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>
                    </a>
                </li>
            </ul>

            <p class="ss-site-footer__copyright">&copy; 2026 SyntaxSidekick. All rights reserved.</p>
        </section>

        <nav class="ss-site-footer__nav" aria-labelledby="ss-footer-nav-title">
            <h2 id="ss-footer-nav-title">Navigation</h2>
            <ul role="list">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <li><a href="<?php echo esc_url(home_url('/articles/')); ?>">Articles</a></li>
                <li><a href="<?php echo esc_url(home_url('/tutorials/')); ?>">Tutorials</a></li>
                <li><a href="<?php echo esc_url(home_url('/resources/')); ?>">Resources</a></li>
                <li><a href="<?php echo esc_url(home_url('/guides/')); ?>">Guides</a></li>
            </ul>
        </nav>

        <nav class="ss-site-footer__company" aria-labelledby="ss-footer-company-title">
            <h2 id="ss-footer-company-title">Company</h2>
            <ul role="list">
                <li><a href="<?php echo esc_url(home_url('/about/')); ?>">About</a></li>
                <li><a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a></li>
                <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a></li>
                <li><a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>">Terms of Service</a></li>
            </ul>
        </nav>

        <section class="ss-site-footer__newsletter" aria-labelledby="ss-footer-newsletter-title">
            <h2 id="ss-footer-newsletter-title">Stay in the Loop</h2>
            <p>Get the latest tutorials delivered to your inbox.</p>
            <form method="post" action="" novalidate>
                <label class="screen-reader-text" for="ss-footer-newsletter-email">Email address</label>
                <input id="ss-footer-newsletter-email" type="email" name="email" autocomplete="email" placeholder="Your email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </section>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
