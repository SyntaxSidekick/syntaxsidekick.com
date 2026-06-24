<aside class="ss-sidebar">
    <h2 class="ss-section-title">Browse Topics</h2>
    <ul class="ss-topic-list">
        <?php foreach (get_categories(array('hide_empty' => false, 'number' => 10)) as $topic) : ?>
            <li><a href="<?php echo esc_url(get_category_link($topic)); ?>"><?php echo esc_html($topic->name); ?> <span><?php echo esc_html($topic->count); ?></span></a></li>
        <?php endforeach; ?>
    </ul>
</aside>
