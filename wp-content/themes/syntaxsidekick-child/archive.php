<?php get_header(); ?>
<main id="main-content" class="ss-main">
    <div class="ss-container ss-layout">
        <section>
            <h1 class="ss-section-title"><?php the_archive_title(); ?></h1>
            <div class="ss-card-grid">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article class="ss-card">
                        <a class="ss-card-thumb" href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large'); } else { echo esc_html(wp_trim_words(get_the_title(), 5, '')); } ?></a>
                        <div class="ss-card-body">
                            <span class="ss-card-kicker"><?php $cat = get_the_category(); echo esc_html($cat ? $cat[0]->name : 'Article'); ?></span>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; endif; ?>
            </div>
            <?php the_posts_pagination(); ?>
        </section>
        <?php get_sidebar(); ?>
    </div>
</main>
<?php get_footer(); ?>
