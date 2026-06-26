<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="ss-skip-link" href="#main-content">Skip to main content</a>

<header class="ss-site-header ss-mega-nav" data-ss-mega-nav>
    <div class="ss-container ss-header-grid">
        <a class="ss-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name') . ' home'); ?>">
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo.png'); ?>" alt="SyntaxSidekick" width="600" height="180" decoding="async" fetchpriority="high">
        </a>

        <button class="ss-menu-toggle" type="button" aria-controls="ss-primary-navigation" aria-expanded="false" aria-label="Open main menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav id="ss-primary-navigation" class="ss-primary-nav" aria-label="Primary navigation">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_class' => 'ss-nav-list ss-nav-list--fallback',
                    'container' => false,
                    'fallback_cb' => 'syntaxsidekick_fallback_menu',
                )
            );
            ?>
        </nav>

        <?php if (function_exists('syntaxsidekick_render_mega_menu_templates')) : ?>
            <?php syntaxsidekick_render_mega_menu_templates(); ?>
        <?php endif; ?>

        <div class="ss-header-actions">
            <button
                class="theme-toggle"
                type="button"
                aria-label="Switch to dark mode"
                aria-pressed="false"
                data-theme-toggle
            >
                <span class="theme-toggle__track" aria-hidden="true">
                    <span class="theme-toggle__thumb">
                        <svg class="theme-toggle__icon theme-toggle__icon--sun" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2.5v2.25M12 19.25v2.25M4.93 4.93l1.6 1.6M17.47 17.47l1.6 1.6M2.5 12h2.25M19.25 12h2.25M4.93 19.07l1.6-1.6M17.47 6.53l1.6-1.6"></path>
                        </svg>
                        <svg class="theme-toggle__icon theme-toggle__icon--moon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path d="M21 13.2A8.8 8.8 0 1 1 10.8 3 7 7 0 0 0 21 13.2z"></path>
                        </svg>
                    </span>
                </span>
            </button>

            <a class="ss-search-link" href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="Search">
                <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
                </svg>
            </a>
        </div>
    </div>
</header>
