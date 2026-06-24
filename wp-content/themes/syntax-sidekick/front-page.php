<?php
/**
 * Front page template.
 *
 * @package Syntax_Sidekick
 */
get_header();
?>
<main id="primary" class="site-main">
	<section class="ss-hero">
		<div class="ss-container ss-hero__grid">
			<div class="ss-hero__content">
				<p class="ss-eyebrow">Welcome to SyntaxSidekick</p>
				<h1>Modern front-end tutorials and articles for developers</h1>
				<p class="ss-hero__lede">Practical tutorials, modern CSS, JavaScript, React, accessibility, performance, and the developer mindset you need to grow.</p>
				<div class="ss-actions">
					<a class="ss-button ss-button--primary" href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>">Browse Tutorials</a>
					<a class="ss-button ss-button--secondary" href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">Read Articles</a>
				</div>
			</div>
			<div class="ss-code-card" aria-hidden="true">
				<div class="ss-code-card__dots"><span></span><span></span><span></span></div>
<pre><code>.container {
  container-type: inline-size;
  padding: 2rem;
}

@container (min-width: 600px) {
  .card {
    grid-template-columns: 1fr 1fr;
  }
}</code></pre>
			</div>
		</div>
	</section>

	<section class="ss-content-section">
		<div class="ss-container ss-layout">
			<div class="ss-main-column">
				<div class="ss-section-header">
					<h2>Latest Articles</h2>
				</div>
				<div class="ss-card-grid">
					<?php
					$latest = new WP_Query( array( 'posts_per_page' => 6, 'post_status' => 'publish' ) );
					if ( $latest->have_posts() ) :
						while ( $latest->have_posts() ) :
							$latest->the_post();
							get_template_part( 'template-parts/content', 'card' );
						endwhile;
						wp_reset_postdata();
					else :
						get_template_part( 'template-parts/content', 'none' );
					endif;
					?>
				</div>
			</div>

			<aside class="ss-sidebar" aria-label="Homepage sidebar">
				<section class="ss-panel">
					<h2>Browse Topics</h2>
					<ul class="ss-topic-list">
						<?php
						$cats = get_categories( array( 'orderby' => 'name', 'hide_empty' => false, 'number' => 12 ) );
						foreach ( $cats as $cat ) :
							?>
							<li><a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a><span><?php echo esc_html( $cat->count ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</section>
				<section class="ss-panel ss-newsletter">
					<h2>Stay in the loop</h2>
					<p>Get the latest tutorials and articles delivered to your inbox.</p>
					<form>
						<label class="screen-reader-text" for="ss-email">Email address</label>
						<input id="ss-email" type="email" placeholder="Your email address">
						<button type="submit">Subscribe</button>
					</form>
				</section>
			</aside>
		</div>
	</section>
</main>
<?php get_footer(); ?>
