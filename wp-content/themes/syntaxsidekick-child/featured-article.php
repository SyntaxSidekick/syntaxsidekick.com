<?php
/**
 * Featured Article functionality.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ss_add_featured_article_meta_box() {
	add_meta_box(
		'ss_featured_article',
		__( 'Featured Article', 'syntaxsidekick' ),
		'ss_render_featured_article_meta_box',
		'post',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ss_add_featured_article_meta_box' );

function ss_render_featured_article_meta_box( $post ) {
	wp_nonce_field( 'ss_save_featured_article', 'ss_featured_article_nonce' );

	$is_featured = get_post_meta( $post->ID, '_ss_featured_article', true );
	?>

	<p>
		<label>
			<input
				type="checkbox"
				name="ss_featured_article"
				value="1"
				<?php checked( $is_featured, '1' ); ?>
			/>
			<?php esc_html_e( 'Feature this article on the homepage', 'syntaxsidekick' ); ?>
		</label>
	</p>

	<?php
}

function ss_save_featured_article_meta( $post_id ) {
	if (
		! isset( $_POST['ss_featured_article_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ss_featured_article_nonce'] ) ), 'ss_save_featured_article' )
	) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$is_featured = isset( $_POST['ss_featured_article'] ) ? '1' : '';

	if ( '1' === $is_featured ) {
		$featured_posts = get_posts(
			array(
				'post_type'      => 'post',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => '_ss_featured_article',
				'meta_value'     => '1',
				'exclude'        => array( $post_id ),
			)
		);

		foreach ( $featured_posts as $featured_post_id ) {
			delete_post_meta( $featured_post_id, '_ss_featured_article' );
		}

		update_post_meta( $post_id, '_ss_featured_article', '1' );
		return;
	}

	delete_post_meta( $post_id, '_ss_featured_article' );
}
add_action( 'save_post_post', 'ss_save_featured_article_meta' );

function ss_get_featured_article() {
	$featured = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_ss_featured_article',
			'meta_value'     => '1',
			'no_found_rows'  => true,
		)
	);

	if ( ! empty( $featured ) ) {
		return $featured[0];
	}

	$fallback = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'no_found_rows'  => true,
		)
	);

	return ! empty( $fallback ) ? $fallback