<?php
/**
 * About page collaboration section component.
 *
 * @package SyntaxSidekick_Child
 */

$eyebrow = isset($args['eyebrow']) && '' !== (string) $args['eyebrow']
    ? (string) $args['eyebrow']
    : 'WORK WITH ME';
$heading_prefix = isset($args['heading_prefix']) && '' !== (string) $args['heading_prefix']
    ? (string) $args['heading_prefix']
    : 'Let\'s Build Something Great';
$heading_accent = isset($args['heading_accent']) && '' !== (string) $args['heading_accent']
    ? (string) $args['heading_accent']
    : 'Together';
$description = isset($args['description']) && '' !== (string) $args['description']
    ? (string) $args['description']
    : 'Whether you need help with a project, want to improve your codebase, or are looking to level up your skills, I\'m here to help you succeed.';

$cards = isset($args['cards']) && is_array($args['cards'])
    ? $args['cards']
    : array();

$cta_title = isset($args['cta_title']) && '' !== (string) $args['cta_title']
    ? (string) $args['cta_title']
    : 'Not sure if I\'m the right fit?';
$cta_copy = isset($args['cta_copy']) && '' !== (string) $args['cta_copy']
    ? (string) $args['cta_copy']
    : 'Reach out anyway. Whether it\'s a quick question, architecture advice, mentoring, or a full project, I\'m always happy to have a conversation.';
$cta_button_label = isset($args['cta_button_label']) && '' !== (string) $args['cta_button_label']
    ? (string) $args['cta_button_label']
    : 'Let\'s Talk';
$cta_button_url = isset($args['cta_button_url']) && '' !== (string) $args['cta_button_url']
    ? (string) $args['cta_button_url']
    : home_url('/contact/');

if (empty($cards)) {
    return;
}
?>
<section class="ss-home-section ss-about-section ss-about-collaboration site-section" aria-labelledby="ss-about-collab-title">
    <div class="ss-container">
        <header class="section-header ss-about-collaboration__header">
            <p class="section-eyebrow ss-about-collaboration__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <h2 id="ss-about-collab-title" class="section-title ss-about-collaboration__title">
                <?php echo esc_html($heading_prefix); ?>
                <span><?php echo esc_html($heading_accent); ?></span>
            </h2>
            <p class="section-copy ss-about-collaboration__copy"><?php echo esc_html($description); ?></p>
        </header>

        <div class="ss-card-grid ss-about-collaboration__grid" role="list" aria-label="How we can work together">
            <?php foreach ($cards as $card) :
                $title = isset($card['title']) ? (string) $card['title'] : '';
                $copy = isset($card['description']) ? (string) $card['description'] : '';
                $items = isset($card['items']) && is_array($card['items']) ? $card['items'] : array();
                $link_label = isset($card['link_label']) && '' !== (string) $card['link_label']
                    ? (string) $card['link_label']
                    : 'Learn more';
                $link_url = isset($card['link_url']) && '' !== (string) $card['link_url']
                    ? (string) $card['link_url']
                    : home_url('/contact/');
                $icon = isset($card['icon']) && '' !== (string) $card['icon']
                    ? (string) $card['icon']
                    : 'code';

                if ('' === $title) {
                    continue;
                }
                ?>
                <article class="ss-card ss-about-collaboration-card" role="listitem">
                    <div class="ss-card-body ss-about-collaboration-card__body">
                        <span class="ss-about-collaboration-card__icon" aria-hidden="true">
                            <?php if ('rocket' === $icon) : ?>
                                <svg viewBox="0 0 24 24" focusable="false"><path d="M14 4l6 6-5.5 5.5-5-5L14 4z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path><path d="M9.5 10.5L5 15l-1 4 4-1 4.5-4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path><path d="M8 8l2 2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                            <?php elseif ('mentor' === $icon) : ?>
                                <svg viewBox="0 0 24 24" focusable="false"><path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M4.5 19.5C5.3 16.8 7.7 15 12 15s6.7 1.8 7.5 4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                            <?php elseif ('calendar' === $icon) : ?>
                                <svg viewBox="0 0 24 24" focusable="false"><rect x="4" y="5" width="16" height="15" rx="2" fill="none" stroke="currentColor" stroke-width="1.8"></rect><path d="M8 3.5v3M16 3.5v3M4 9h16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                            <?php else : ?>
                                <svg viewBox="0 0 24 24" focusable="false"><path d="M8 7l-5 5 5 5M16 7l5 5-5 5M13 5l-2 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            <?php endif; ?>
                        </span>
                        <h3><?php echo esc_html($title); ?></h3>
                        <?php if ('' !== $copy) : ?>
                            <p><?php echo esc_html($copy); ?></p>
                        <?php endif; ?>
                        <?php if (! empty($items)) : ?>
                            <ul class="ss-about-collaboration-card__list" role="list">
                                <?php foreach ($items as $item) : ?>
                                    <li><?php echo esc_html((string) $item); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <a class="ss-about-collaboration-card__link" href="<?php echo esc_url($link_url); ?>">
                            <?php echo esc_html($link_label); ?>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <aside class="ss-about-collaboration-cta" aria-label="Contact call to action">
            <div class="ss-about-collaboration-cta__intro">
                <span class="ss-about-collaboration-cta__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false"><path d="M4 5h16v11H4z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M7 9h10M7 13h7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                </span>
                <div>
                    <h3><?php echo esc_html($cta_title); ?></h3>
                    <p><?php echo esc_html($cta_copy); ?></p>
                </div>
            </div>
            <a class="ss-button ss-button-primary ss-about-collaboration-cta__button" href="<?php echo esc_url($cta_button_url); ?>"><?php echo esc_html($cta_button_label); ?></a>
        </aside>
    </div>
</section>
