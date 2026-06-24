<?php
/**
 * Article card.
 *
 * @package Syntax_Sidekick
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ss-card' ); ?>>
	<a class="ss-card__media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'medium_large' ); ?>
		<?php else : ?>
			<span><?php echo esc_html( wp_get_post_categories( get_the_ID(), array( 'fields' => 'names' ) )[0] ?? 'Article' ); ?></span>
		<?php endif; ?>
	</a>
	<div class="ss-card__body">
		<?php $category = get_the_category(); if ( ! empty( $category ) ) : ?>
			<a class="ss-card__category" href="<?php echo esc_url( get_category_link( $category[0] ) ); ?>"><?php echo esc_html( $category[0]->name ); ?></a>
		<?php endif; ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="ss-card__excerpt"><?php the_excerpt(); ?></div>
		<p class="ss-card__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time><span>•</span><span><?php echo esc_html( syntax_sidekick_reading_time() ); ?></span></p>
	</div>
</article>
