<?php get_header(); ?>
<main id="main-content" class="ss-main">
    <div class="ss-container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="ss-content">
                <p class="ss-eyebrow"><?php $cat = get_the_category(); echo esc_html($cat ? $cat[0]->name : 'Article'); ?></p>
                <h1><?php the_title(); ?></h1>
                <p class="ss-card-meta"><?php echo esc_html(get_the_date()); ?> · by <?php echo esc_html(get_the_author()); ?></p>
                <?php if (has_post_thumbnail()) { the_post_thumbnail('large'); } ?>
                <?php the_content(); ?>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>
<?php get_footer(); ?>
