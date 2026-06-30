<?php
/**
 * Reusable live video card.
 *
 * @package SyntaxSidekick_Child
 */

$data  = isset($args['data']) && is_array($args['data']) ? $args['data'] : array();
$state = isset($data['state']) ? (string) $data['state'] : 'offline';

$is_live       = 'live' === $state;
$title         = isset($data['title']) ? (string) $data['title'] : 'Live Stream';
$display_title = $is_live ? 'Live on Twitch' : $title;
$description   = isset($data['description']) ? (string) $data['description'] : '';
$platform      = isset($data['platform']) ? (string) $data['platform'] : 'Twitch';
$cta_label     = isset($data['cta_label']) ? (string) $data['cta_label'] : 'Watch';
$cta_url       = isset($data['cta_url']) ? (string) $data['cta_url'] : '#';
$latest_title  = isset($data['latest_title']) ? (string) $data['latest_title'] : '';
$latest_url    = isset($data['latest_url']) ? (string) $data['latest_url'] : '#';
$latest_time   = isset($data['latest_duration']) ? (string) $data['latest_duration'] : '';
$latest_thumb  = isset($data['latest_thumbnail']) ? (string) $data['latest_thumbnail'] : '';
$latest_alt    = isset($data['latest_thumbnail_alt']) ? (string) $data['latest_thumbnail_alt'] : 'Latest video thumbnail';
$embed_src     = isset($data['embed_src']) ? (string) $data['embed_src'] : '';
$embed_title   = isset($data['embed_title']) ? (string) $data['embed_title'] : 'SyntaxSidekick Twitch video player';
$has_embed     = '' !== $embed_src;
$media_title   = $is_live ? 'Live Stream' : 'Latest Video';
$media_label   = '' !== $latest_title ? $latest_title : $display_title;
?>
<aside class="ss-live-card" aria-label="SyntaxSidekick Twitch stream and latest video">
    <div class="ss-live-card__header">
        <p class="ss-live-badge"><span aria-hidden="true"></span><?php echo esc_html($is_live ? 'Live' : 'Twitch'); ?></p>
        <svg class="ss-live-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path d="M4.5 3 3 7.1v13.4h4.8V23h2.7l2.5-2.5h3.8L21 16.3V3H4.5Zm14.1 12.2-2.9 2.9h-4.2L9 20.6v-2.5H5.9V5.4h12.7v9.8Zm-3.1-6.3v4.4h-2.1V8.9h2.1Zm-5.5 0v4.4H7.9V8.9H10Z"/>
        </svg>
    </div>

    <h3><?php echo esc_html($display_title); ?></h3>
    <?php if ('' !== $description) : ?>
        <p class="ss-live-description"><?php echo esc_html($description); ?></p>
    <?php endif; ?>

    <a class="ss-button ss-button-primary ss-live-cta" href="<?php echo esc_url($cta_url); ?>">
        <?php echo esc_html($cta_label); ?>
        <span aria-hidden="true">↗</span>
    </a>

    <div class="ss-live-divider" aria-hidden="true"><span>OR</span></div>

    <div class="ss-live-latest">
        <p class="ss-live-label"><?php echo esc_html($media_title); ?></p>

        <?php if ($has_embed) : ?>
            <div class="ss-live-embed">
                <iframe
                    data-ss-twitch-embed
                    data-twitch-src="<?php echo esc_url($embed_src); ?>"
                    title="<?php echo esc_attr($embed_title); ?>"
                    loading="lazy"
                    allowfullscreen>
                </iframe>
            </div>
            <noscript>
                <p class="ss-live-description">
                    Twitch video playback requires JavaScript. Use the link above to watch on Twitch.
                </p>
            </noscript>
        <?php elseif ('' !== $latest_title) : ?>
            <a class="ss-live-thumb" href="<?php echo esc_url($latest_url); ?>" aria-label="Watch <?php echo esc_attr($latest_title); ?>">
                <?php if ('' !== $latest_thumb) : ?>
                    <img src="<?php echo esc_url($latest_thumb); ?>" alt="<?php echo esc_attr($latest_alt); ?>" loading="lazy" decoding="async">
                <?php else : ?>
                    <span class="ss-live-thumb__preview" aria-hidden="true"></span>
                <?php endif; ?>
                <?php if ('' !== $latest_time) : ?>
                    <span class="ss-live-thumb__duration"><?php echo esc_html($latest_time); ?></span>
                <?php endif; ?>
            </a>
        <?php else : ?>
            <div class="ss-live-thumb ss-live-thumb--fallback" aria-hidden="true">
                <span class="ss-live-thumb__preview"></span>
            </div>
        <?php endif; ?>

        <p class="ss-live-thumb__title"><?php echo esc_html($media_label); ?></p>
        <a class="ss-live-link" href="<?php echo esc_url($cta_url); ?>">
            <?php echo esc_html($is_live ? 'Watch on Twitch' : $cta_label); ?>
            <span aria-hidden="true">→</span>
        </a>
    </div>

    <p class="ss-live-platform"><?php echo esc_html($platform); ?></p>
</aside>

<?php if ($has_embed && empty($GLOBALS['syntaxsidekick_twitch_embed_script_printed'])) : ?>
    <?php $GLOBALS['syntaxsidekick_twitch_embed_script_printed'] = true; ?>
    <script>
    (function () {
      var host = window.location.hostname;
      if (!host) {
        return;
      }

      document.querySelectorAll('[data-ss-twitch-embed][data-twitch-src]').forEach(function (iframe) {
        var src = iframe.getAttribute('data-twitch-src');
        if (!src || iframe.getAttribute('src')) {
          return;
        }

        iframe.setAttribute('src', src + (src.indexOf('?') === -1 ? '?' : '&') + 'parent=' + encodeURIComponent(host));
      });
    }());
    </script>
<?php endif; ?>
