<?php
/**
 * Tutorials category list card.
 *
 * @package SyntaxSidekick_Child
 */

$section_id = isset($args['id']) ? (string) $args['id'] : 'ss-browse-tutorials-title';
$section_heading = isset($args['heading']) ? (string) $args['heading'] : 'Browse Tutorials';
$items = isset($args['items']) && is_array($args['items']) ? $args['items'] : array();
?>
<section class="ss-sidebar-card" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <h2 id="<?php echo esc_attr($section_id); ?>" class="ss-section-title"><?php echo esc_html($section_heading); ?></h2>

    <ul class="ss-sidebar-list">
        <?php foreach ($items as $item) : ?>
            <?php
            $slug = isset($item['slug']) ? sanitize_key((string) $item['slug']) : '';
            $label = isset($item['label']) ? (string) $item['label'] : '';
            $url = isset($item['url']) ? (string) $item['url'] : home_url('/tutorials/');
            $count = isset($item['count']) ? (int) $item['count'] : 0;
            $is_active = ! empty($item['is_active']);
            $icon_name = function_exists('syntaxsidekick_get_tutorial_icon') ? syntaxsidekick_get_tutorial_icon($slug) : '';
            $color_class = function_exists('syntaxsidekick_get_icon_color_class') ? syntaxsidekick_get_icon_color_class($slug) : 'ss-icon--neutral';
            $icon_svg = $icon_name && function_exists('syntaxsidekick_icon')
                ? syntaxsidekick_icon(
                    $icon_name,
                    array(
                        'class' => 'ss-menu-icon ' . $color_class,
                        'decorative' => true,
                    )
                )
                : '';
            $fallback_text = strtoupper(substr('all' === $slug ? 'AT' : $label, 0, 'all' === $slug ? 2 : 1));
            ?>
            <li>
                <a class="<?php echo $is_active ? 'is-active' : ''; ?>" href="<?php echo esc_url($url); ?>">
                    <span class="ss-sidebar-topic">
                        <span class="ss-sidebar-icon" aria-hidden="true">
                            <?php if ('' !== $icon_svg) : ?>
                                <?php echo $icon_svg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            <?php else : ?>
                                <span class="ss-sidebar-icon-fallback"><?php echo esc_html($fallback_text); ?></span>
                            <?php endif; ?>
                        </span>
                        <span><?php echo esc_html($label); ?></span>
                    </span>
                    <strong><?php echo esc_html((string) $count); ?></strong>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
