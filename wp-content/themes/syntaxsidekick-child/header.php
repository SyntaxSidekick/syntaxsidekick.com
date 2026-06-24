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
            <img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/logo.png'); ?>" alt="SyntaxSidekick">
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

        <a class="ss-search-link" href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="Search">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="16.65" y1="16.65" x2="21" y2="21"></line>
            </svg>
        </a>
    </div>
</header>
