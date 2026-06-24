<?php get_header(); ?>
<main id="main-content" class="ss-main">
    <div class="ss-container">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="ss-content">
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </article>
        <?php endwhile; endif; ?>
    </div>
</main>
<?php get_footer(); ?>
