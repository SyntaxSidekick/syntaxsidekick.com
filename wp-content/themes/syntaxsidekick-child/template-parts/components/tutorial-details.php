<?php
/**
 * Tutorial details metadata row.
 *
 * @package SyntaxSidekick_Child
 */

$date = isset($args['date']) ? (string) $args['date'] : '';
$date_iso = isset($args['date_iso']) ? (string) $args['date_iso'] : '';
$read_time = isset($args['read_time']) ? (string) $args['read_time'] : '';
$level = isset($args['level']) ? (string) $args['level'] : '';
$author_name = isset($args['author_name']) ? (string) $args['author_name'] : '';
$author_id = isset($args['author_id']) ? (int) $args['author_id'] : 0;
$layout = isset($args['layout']) ? (string) $args['layout'] : 'inline';

if ('sidebar' === $layout) :
    ?>
    <section class="ss-sidebar-card ss-tutorial-details-card" aria-labelledby="ss-tutorial-details-title">
        <h2 id="ss-tutorial-details-title" class="ss-section-title"><?php echo esc_html__('Tutorial Details', 'syntaxsidekick-child'); ?></h2>

        <ul class="ss-tutorial-details-list">
            <?php if ('' !== $date) : ?>
                <li>
                    <span class="ss-tutorial-details-list__label"><?php echo esc_html__('Published', 'syntaxsidekick-child'); ?></span>
                    <?php if ('' !== $date_iso) : ?>
                        <time datetime="<?php echo esc_attr($date_iso); ?>"><?php echo esc_html($date); ?></time>
                    <?php else : ?>
                        <span><?php echo esc_html($date); ?></span>
                    <?php endif; ?>
                </li>
            <?php endif; ?>

            <?php if ('' !== $read_time) : ?>
                <li>
                    <span class="ss-tutorial-details-list__label"><?php echo esc_html__('Read Time', 'syntaxsidekick-child'); ?></span>
                    <span><?php echo esc_html($read_time); ?></span>
                </li>
            <?php endif; ?>

            <?php if ('' !== $level) : ?>
                <li>
                    <span class="ss-tutorial-details-list__label"><?php echo esc_html__('Difficulty', 'syntaxsidekick-child'); ?></span>
                    <span><?php echo esc_html($level); ?></span>
                </li>
            <?php endif; ?>

            <?php if ('' !== $author_name) : ?>
                <li class="ss-tutorial-details-list__author">
                    <span class="ss-tutorial-details-list__label"><?php echo esc_html__('Author', 'syntaxsidekick-child'); ?></span>
                    <span class="ss-tutorial-details-list__author-name">
                        <?php if ($author_id > 0) : ?>
                            <span class="ss-tutorial-details-list__avatar"><?php echo get_avatar($author_id, 22, '', $author_name); ?></span>
                        <?php endif; ?>
                        <span><?php echo esc_html($author_name); ?></span>
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </section>
    <?php
    return;
endif;
?>
<div class="ss-single-tutorial-meta">
    <?php if ('' !== $date) : ?>
        <span class="ss-single-tutorial-meta__item">
            <?php if ('' !== $date_iso) : ?>
                <time datetime="<?php echo esc_attr($date_iso); ?>"><?php echo esc_html($date); ?></time>
            <?php else : ?>
                <span><?php echo esc_html($date); ?></span>
            <?php endif; ?>
        </span>
    <?php endif; ?>

    <?php if ('' !== $read_time) : ?>
        <span class="ss-single-tutorial-meta__item"><?php echo esc_html($read_time); ?></span>
    <?php endif; ?>

    <?php if ('' !== $level) : ?>
        <span class="ss-single-tutorial-meta__item"><?php echo esc_html($level); ?></span>
    <?php endif; ?>

    <?php if ('' !== $author_name) : ?>
        <span class="ss-single-tutorial-meta__item ss-single-tutorial-meta__item--author">
            <?php if ($author_id > 0) : ?>
                <span class="ss-single-tutorial-meta__avatar"><?php echo get_avatar($author_id, 24, '', $author_name); ?></span>
            <?php endif; ?>
            <span><?php echo esc_html($author_name); ?></span>
        </span>
    <?php endif; ?>
</div>