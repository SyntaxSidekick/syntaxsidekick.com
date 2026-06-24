<?php
/**
 * Header.
 *
 * @package Syntax_Sidekick
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'syntax-sidekick' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header__inner ss-container">
			<div class="site-branding">
				<a class="site-logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php bloginfo( 'name' ); ?> home">
					<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
						<img class="site-logo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.png' ); ?>" alt="<?php bloginfo( 'name' ); ?>">
					<?php endif; ?>
				</a>
				<span class="site-tagline"><?php bloginfo( 'description' ); ?></span>
			</div>

			<nav id="site-navigation" class="main-navigation" aria-label="Primary navigation">
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<span class="menu-toggle__bar"></span>
					<span class="menu-toggle__bar"></span>
					<span class="menu-toggle__bar"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'syntax-sidekick' ); ?></span>
				</button>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'fallback_cb'    => 'syntax_sidekick_fallback_menu',
						'depth'          => 2,
					)
				);
				?>
			</nav>
		</div>
	</header>
