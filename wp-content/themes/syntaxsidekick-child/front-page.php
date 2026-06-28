<?php
get_header();

$hero_buttons = array(
    array(
        'label' => 'Browse Articles',
        'url' => home_url('/articles/'),
        'variant' => 'primary',
    ),
    array(
        'label' => 'Explore Guides',
        'url' => home_url('/guides/'),
        'variant' => 'secondary',
    ),
);

$featured_query = new WP_Query(
    array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
    )
);

$featured_ids = wp_list_pluck($featured_query->posts, 'ID');

$recent_query_args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 5,
    'ignore_sticky_posts' => true,
    'no_found_rows' => true,
);

// When there are only a few posts, avoid an empty recent list by allowing overlap with featured.
if (count($featured_ids) >= 3) {
    $recent_query_args['post__not_in'] = $featured_ids;
}

$recent_query = new WP_Query($recent_query_args);

if (! $recent_query->have_posts() && ! empty($featured_ids)) {
    unset($recent_query_args['post__not_in']);
    $recent_query = new WP_Query($recent_query_args);
}

$popular_query = new WP_Query(
    array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'ignore_sticky_posts' => true,
        'orderby' => 'comment_count',
        'order' => 'DESC',
        'no_found_rows' => true,
    )
);

$live_video_data = syntaxsidekick_get_live_video_data();
?>

<style id="ss-home-dark-fallback">
    :root[data-theme="dark"] .ss-homepage .ss-home-subtitle,
    :root[data-theme="dark"] .ss-homepage .ss-list-item__body p,
    :root[data-theme="dark"] .ss-homepage .ss-ranked-item__body p,
    :root[data-theme="dark"] .ss-homepage .ss-ranked-item__meta,
    :root[data-theme="dark"] .ss-homepage .ss-card p,
    :root[data-theme="dark"] .ss-homepage .ss-home-hero__content > p:not(.ss-eyebrow) {
        color: #f8fbff !important;
    }

    :root[data-theme="dark"] .ss-homepage .ss-section-link {
        color: color-mix(in srgb, var(--ss-color-brand) 78%, var(--ss-color-text));
    }

    :root[data-theme="dark"] .ss-homepage .ss-home-panel {
        background: var(--ss-color-surface-raised);
        border-color: var(--ss-color-border-strong);
    }

    :root[data-theme="dark"] .ss-homepage .ss-ranked-item {
        border-bottom-color: var(--ss-color-border-strong);
    }

    :root[data-theme="dark"] .ss-homepage .ss-feature-item p,
    :root[data-theme="dark"] .ss-homepage .ss-live-description,
    :root[data-theme="dark"] .ss-homepage .ss-live-platform,
    :root[data-theme="dark"] .ss-homepage .ss-live-thumb__duration,
    :root[data-theme="dark"] .ss-homepage .ss-newsletter-cta__intro p,
    :root[data-theme="dark"] .ss-homepage .ss-newsletter-cta__form .ss-form-note,
    :root[data-theme="dark"] .ss-homepage .ss-newsletter-cta__form .ss-form-status {
        color: #f8fbff !important;
    }
</style>

<main id="main-content" class="ss-main ss-homepage">
    <?php
    get_template_part(
        'template-parts/components/hero-section',
        null,
        array(
            'buttons' => $hero_buttons,
            'features' => syntaxsidekick_get_home_feature_highlights(),
        )
    );
    ?>

    <section class="ss-home-section ss-featured" aria-labelledby="ss-featured-heading">
        <div class="ss-container">
            <?php
            get_template_part(
                'template-parts/components/section-header',
                null,
                array(
                    'title_id' => 'ss-featured-heading',
                    'title' => 'Featured Articles',
                    'subtitle' => 'Handpicked content to level up your front-end skills.',
                    'link_label' => 'View all articles',
                    'link_url' => home_url('/articles/'),
                )
            );
            ?>

            <div class="ss-featured-grid">
                <div class="ss-card-grid">
                    <?php if ($featured_query->have_posts()) : ?>
                        <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                            <?php get_template_part('template-parts/components/article-card', null, array('post_id' => get_the_ID(), 'heading_level' => 3)); ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <article class="ss-empty-state">
                            <h2>No featured articles yet</h2>
                            <p>Publish a post to populate the featured section.</p>
                        </article>
                    <?php endif; ?>
                </div>

                <?php get_template_part('template-parts/components/live-video-card', null, array('data' => $live_video_data)); ?>
            </div>
        </div>
    </section>

    <section class="ss-home-section">
        <div class="ss-container ss-dual-columns">
            <section class="ss-home-panel" aria-labelledby="ss-recent-heading">
                <?php
                get_template_part(
                    'template-parts/components/section-header',
                    null,
                    array(
                        'title_id' => 'ss-recent-heading',
                        'title' => 'Recent Articles',
                        'link_label' => 'View all articles',
                        'link_url' => home_url('/articles/'),
                    )
                );
                ?>
                <div class="ss-list-stack">
                    <?php if ($recent_query->have_posts()) : ?>
                        <?php while ($recent_query->have_posts()) : $recent_query->the_post(); ?>
                            <?php get_template_part('template-parts/components/article-list-item', null, array('post_id' => get_the_ID())); ?>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <article class="ss-empty-state">
                            <h2>No recent articles yet</h2>
                            <p>Recent posts will appear here once published.</p>
                        </article>
                    <?php endif; ?>
                </div>
            </section>

            <section class="ss-home-panel" aria-labelledby="ss-popular-heading">
                <?php
                get_template_part(
                    'template-parts/components/section-header',
                    null,
                    array(
                        'title_id' => 'ss-popular-heading',
                        'title' => 'Popular Articles',
                        'link_label' => 'View all articles',
                        'link_url' => home_url('/articles/'),
                    )
                );
                ?>
                <div class="ss-ranked-stack">
                    <?php
                    $rank = 1;
                    if ($popular_query->have_posts()) :
                        while ($popular_query->have_posts()) :
                            $popular_query->the_post();
                            get_template_part('template-parts/components/ranked-article-item', null, array('post_id' => get_the_ID(), 'rank' => $rank));
                            $rank++;
                        endwhile;
                    else :
                        ?>
                        <article class="ss-empty-state">
                            <h2>No popular articles yet</h2>
                            <p>Popular content will populate after articles receive engagement.</p>
                        </article>
                        <?php
                    endif;
                    ?>
                </div>
            </section>
        </div>
    </section>

    <section class="ss-home-section">
        <div class="ss-container">
            <?php get_template_part('template-parts/components/newsletter-cta'); ?>
        </div>
    </section>
</main>

<?php
wp_reset_postdata();
get_footer();
?>
