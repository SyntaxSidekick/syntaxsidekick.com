<?php
/**
 * Reusable live video card.
 *
 * @package SyntaxSidekick_Child
 */

$data  = isset($args['data']) && is_array($args['data']) ? $args['data'] : array();
$state = isset($data['state']) ? (string) $data['state'] : 'offline';

$is_live      = 'live' === $state;
$title        = isset($data['title']) ? (string) $data['title'] : 'Live Stream';
$description  = isset($data['description']) ? (string) $data['description'] : '';
$platform     = isset($data['platform']) ? (string) $data['platform'] : 'YouTube';
$cta_label    = isset($data['cta_label']) ? (string) $data['cta_label'] : 'Watch';
$cta_url      = isset($data['cta_url']) ? (string) $data['cta_url'] : '#';
$latest_title = isset($data['latest_title']) ? (string) $data['latest_title'] : '';
$latest_url   = isset($data['latest_url']) ? (string) $data['latest_url'] : '#';
$latest_time  = isset($data['latest_duration']) ? (string) $data['latest_duration'] : '';
$latest_thumb = isset($data['latest_thumbnail']) ? (string) $data['latest_thumbnail'] : '';
$latest_alt   = isset($data['latest_thumbnail_alt']) ? (string) $data['latest_thumbnail_alt'] : 'Latest video thumbnail';
?>
<aside class="ss-live-card" aria-label="Live stream and latest video">
    <?php if ($is_live) : ?>
        <p class="ss-live-badge">Live</p>
    <?php endif; ?>

    <h3><?php echo esc_html($title); ?></h3>
    <?php if ('' !== $description) : ?>
        <p class="ss-live-description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>

    <a class="ss-button ss-button-primary ss-live-cta" href="<?php echo esc_url($cta_url); ?>"><?php echo esc_html($cta_label); ?></a>

    <p class="ss-live-platform"><?php echo esc_html($platform); ?></p>

    <?php if ('' !== $latest_title) : ?>
        <div class="ss-live-latest">
            <p class="ss-live-label">Latest Video</p>
            <a class="ss-live-thumb" href="<?php echo esc_url($latest_url); ?>" aria-label="Watch <?php echo esc_attr($latest_title); ?>">
                <?php if ('' !== $latest_thumb) : ?>
                    <img src="<?php echo esc_url($latest_thumb); ?>" alt="<?php echo esc_attr($latest_alt); ?>" loading="lazy" decoding="async">
                <?php else : ?>
                    <span class="ss-live-thumb__preview" aria-hidden="true"></span>
                <?php endif; ?>
                <span class="ss-live-thumb__title"><?php echo esc_html($latest_title); ?></span>
                <?php if ('' !== $latest_time) : ?>
                    <span class="ss-live-thumb__duration"><?php echo esc_html($latest_time); ?></span>
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
</aside>
