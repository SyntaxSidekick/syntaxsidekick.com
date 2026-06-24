<?php
/**
 * SyntaxSidekick theme functions.
 *
 * @package Syntax_Sidekick
 */

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.1' );
}

function syntax_sidekick_setup() {
	load_theme_textdomain( 'syntax-sidekick', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'custom-logo', array( 'height' => 90, 'width' => 320, 'flex-width' => true, 'flex-height' => true ) );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary Menu', 'syntax-sidekick' ),
			'footer' => esc_html__( 'Footer Menu', 'syntax-sidekick' ),
		)
	);
}
add_action( 'after_setup_theme', 'syntax_sidekick_setup' );

function syntax_sidekick_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'syntax_sidekick_content_width', 920 );
}
add_action( 'after_setup_theme', 'syntax_sidekick_content_width', 0 );

function syntax_sidekick_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'syntax-sidekick' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'syntax-sidekick' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'syntax_sidekick_widgets_init' );

function syntax_sidekick_scripts() {
	wp_enqueue_style( 'syntax-sidekick-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_script( 'syntax-sidekick-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'syntax_sidekick_scripts' );

function syntax_sidekick_reading_time( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$content = get_post_field( 'post_content', $post_id );
	$words   = str_word_count( wp_strip_all_tags( $content ) );
	$minutes = max( 1, ceil( $words / 220 ) );
	return $minutes . ' min read';
}

function syntax_sidekick_fallback_menu() {
	echo '<ul id="primary-menu" class="menu">';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>';
	foreach ( array( 'Articles', 'Tutorials', 'Guides', 'Resources', 'About', 'Contact' ) as $page_name ) {
		$page = get_page_by_title( $page_name );
		if ( $page ) {
			echo '<li><a href="' . esc_url( get_permalink( $page ) ) . '">' . esc_html( $page_name ) . '</a></li>';
		}
	}
	echo '</ul>';
}
