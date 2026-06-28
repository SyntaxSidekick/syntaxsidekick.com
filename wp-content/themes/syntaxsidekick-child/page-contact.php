<?php
/**
 * Contact page template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

$contact_channels = array(
    array(
        'title' => 'Email',
        'value' => 'hello@syntaxsidekick.com',
        'href' => 'mailto:hello@syntaxsidekick.com',
        'note' => 'I usually reply within 24-48 hours.',
        'icon' => 'mail',
    ),
    array(
        'title' => 'Twitter / X',
        'value' => '@riadkilani',
        'href' => 'https://x.com/riadkilani',
        'note' => 'I share updates and insights here.',
        'icon' => 'x',
    ),
    array(
        'title' => 'LinkedIn',
        'value' => 'linkedin.com/in/riadkilani',
        'href' => 'https://www.linkedin.com/in/riadkilani',
        'note' => 'Let\'s connect.',
        'icon' => 'linkedin',
    ),
);

$faq_items = array(
    array(
        'question' => 'How long does it take to get a response?',
        'answer' => 'I typically respond within 24-48 hours, Monday to Friday.',
    ),
    array(
        'question' => 'Can I contribute a tutorial or article?',
        'answer' => 'I\'m always open to great content ideas. Send me a message with your proposal.',
    ),
    array(
        'question' => 'Do you offer coaching or consulting?',
        'answer' => 'I\'m not currently offering 1:1 coaching or consulting. Check back in the future!',
    ),
);
?>

<main id="main-content" class="ss-main ss-contact-page">
    <section class="ss-page-hero ss-contact-hero" aria-labelledby="ss-contact-page-title">
        <div class="ss-container ss-page-hero__grid ss-contact-hero__grid">
            <div class="ss-page-hero__content ss-contact-hero__content">
                <p class="ss-eyebrow">CONTACT</p>
                <h1 id="ss-contact-page-title">Let's get in touch</h1>
                <p>Have a question, feedback, or just want to say hello?<br>I'd love to hear from you.</p>

                <ul class="ss-contact-highlights" role="list">
                    <li class="ss-contact-highlights__item">
                        <span class="ss-contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false"><path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M3 7l9 7 9-7" fill="none" stroke="currentColor" stroke-width="1.8"></path></svg>
                        </span>
                        <div>
                            <p class="ss-contact-highlights__title">I read every message</p>
                            <p>I'll get back to you as soon as I can.</p>
                        </div>
                    </li>
                    <li class="ss-contact-highlights__item">
                        <span class="ss-contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false"><path d="M6 6h12a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H9l-4 3v-5H6a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path></svg>
                        </span>
                        <div>
                            <p class="ss-contact-highlights__title">Real responses</p>
                            <p>No automated replies, just real answers.</p>
                        </div>
                    </li>
                    <li class="ss-contact-highlights__item">
                        <span class="ss-contact-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" focusable="false"><path d="M12 20s-7-4.4-7-9.3C5 8.1 6.8 6.5 9 6.5c1.4 0 2.6.7 3 1.8.4-1.1 1.6-1.8 3-1.8 2.2 0 4 1.6 4 4.2C19 15.6 12 20 12 20z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path></svg>
                        </span>
                        <div>
                            <p class="ss-contact-highlights__title">Built with care</p>
                            <p>Every message helps make this better.</p>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="ss-contact-hero__visual" aria-hidden="true">
                <div class="ss-contact-illustration">
                    <div class="ss-contact-illustration__header"><span></span><span></span><span></span></div>
                    <div class="ss-contact-illustration__body">
                        <div class="ss-contact-illustration__mail">
                            <svg viewBox="0 0 24 24" focusable="false"><path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M3 7l9 7 9-7" fill="none" stroke="currentColor" stroke-width="1.8"></path></svg>
                        </div>
                        <div class="ss-contact-illustration__lines"><span></span><span></span><span></span></div>
                        <ul class="ss-contact-illustration__dots"><li></li><li></li><li></li></ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ss-home-section ss-contact-section" aria-labelledby="ss-contact-form-heading">
        <div class="ss-container ss-contact-grid">
            <article class="ss-card ss-contact-form-card">
                <div class="ss-card-body">
                    <h2 id="ss-contact-form-heading">Send a message</h2>
                    <p class="ss-contact-form-intro">Fill out the form below and I'll get back to you.</p>

                    <?php // TODO: Replace this static form block with a Contact Form 7 shortcode/component. ?>
                    <form class="ss-contact-form" method="post" action="" novalidate>
                        <div class="ss-contact-form__field">
                            <label for="ss-contact-name">Name</label>
                            <input id="ss-contact-name" name="name" type="text" autocomplete="name" placeholder="Your name" required>
                        </div>

                        <div class="ss-contact-form__field">
                            <label for="ss-contact-email">Email</label>
                            <input id="ss-contact-email" name="email" type="email" autocomplete="email" placeholder="your.email@example.com" required>
                        </div>

                        <div class="ss-contact-form__field">
                            <label for="ss-contact-subject">Subject</label>
                            <select id="ss-contact-subject" name="subject" required>
                                <option value="" selected disabled>Select a subject</option>
                                <option value="general">General Question</option>
                                <option value="tutorial">Tutorial Feedback</option>
                                <option value="contribution">Contribution Idea</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="ss-contact-form__field">
                            <label for="ss-contact-message">Message</label>
                            <textarea id="ss-contact-message" name="message" rows="6" placeholder="Your message..." required></textarea>
                        </div>

                        <button class="ss-button ss-button-primary ss-contact-submit" type="submit">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3 12h14"></path><path d="M13 6l6 6-6 6"></path></svg>
                            <span>Send Message</span>
                        </button>

                        <p class="ss-contact-form__privacy">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7 11V8a5 5 0 0 1 10 0v3"></path><rect x="5" y="11" width="14" height="10" rx="2"></rect></svg>
                            <span>Your information is safe and will never be shared.</span>
                        </p>
                    </form>
                </div>
            </article>

            <aside class="ss-contact-sidebar" aria-label="Contact information">
                <section class="ss-panel ss-contact-panel" aria-labelledby="ss-contact-info-title">
                    <h2 id="ss-contact-info-title" class="ss-section-title">GET IN TOUCH</h2>
                    <ul class="ss-contact-sidebar-list" role="list">
                        <?php foreach ($contact_channels as $channel) : ?>
                            <li class="ss-contact-sidebar-list__item">
                                <span class="ss-contact-icon ss-contact-icon--soft" aria-hidden="true">
                                    <?php if ('mail' === $channel['icon']) : ?>
                                        <svg viewBox="0 0 24 24" focusable="false"><path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M3 7l9 7 9-7" fill="none" stroke="currentColor" stroke-width="1.8"></path></svg>
                                    <?php elseif ('x' === $channel['icon']) : ?>
                                        <svg viewBox="0 0 24 24" focusable="false"><path d="M18.245 2H21.5l-7.11 8.1L22.75 22h-6.54l-5.12-6.7L5.2 22H1.94l7.6-8.66L1.5 2h6.7l4.62 6.1z" fill="currentColor"></path></svg>
                                    <?php else : ?>
                                        <svg viewBox="0 0 24 24" focusable="false"><path d="M4.98 3.5A2.49 2.49 0 0 0 2.5 6a2.5 2.5 0 1 0 2.48-2.5zM3 8h4v13H3zm7 0h3.82v1.77h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-6.19c0-1.48-.03-3.39-2.06-3.39-2.06 0-2.38 1.61-2.38 3.29V21h-4z" fill="currentColor"></path></svg>
                                    <?php endif; ?>
                                </span>
                                <div>
                                    <p class="ss-contact-sidebar-list__title"><?php echo esc_html($channel['title']); ?></p>
                                    <a href="<?php echo esc_url($channel['href']); ?>"><?php echo esc_html($channel['value']); ?></a>
                                    <p><?php echo esc_html($channel['note']); ?></p>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>

                <section class="ss-panel ss-contact-panel" aria-labelledby="ss-contact-before-title">
                    <h2 id="ss-contact-before-title" class="ss-section-title">BEFORE YOU SEND</h2>
                    <p class="ss-contact-before-copy">You might find what you're looking for in these sections:</p>
                    <ul class="ss-contact-links" role="list">
                        <li>
                            <a href="#faq">
                                <span class="ss-contact-links__left"><span class="ss-contact-links__icon" aria-hidden="true">?</span><span>Frequently Asked Questions</span></span>
                                <span class="ss-contact-links__arrow" aria-hidden="true">&#8594;</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/about/')); ?>">
                                <span class="ss-contact-links__left"><span class="ss-contact-links__icon" aria-hidden="true">i</span><span>About SyntaxSidekick</span></span>
                                <span class="ss-contact-links__arrow" aria-hidden="true">&#8594;</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/guides/')); ?>">
                                <span class="ss-contact-links__left"><span class="ss-contact-links__icon" aria-hidden="true">&#9633;</span><span>Content Guidelines</span></span>
                                <span class="ss-contact-links__arrow" aria-hidden="true">&#8594;</span>
                            </a>
                        </li>
                    </ul>
                </section>
            </aside>
        </div>
    </section>

    <section id="faq" class="ss-home-section ss-contact-section ss-contact-faq-section" aria-labelledby="ss-contact-faq-title">
        <div class="ss-container ss-contact-faq-grid">
            <div>
                <h2 id="ss-contact-faq-title" class="ss-contact-faq-title">Frequently asked questions</h2>
                <div class="ss-contact-faq-list">
                    <?php foreach ($faq_items as $index => $faq_item) : ?>
                        <details class="ss-contact-faq-item"<?php echo 0 === $index ? ' open' : ''; ?>>
                            <summary>
                                <span class="ss-contact-faq-item__icon" aria-hidden="true">?</span>
                                <span><?php echo esc_html($faq_item['question']); ?></span>
                            </summary>
                            <p><?php echo esc_html($faq_item['answer']); ?></p>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>

            <aside class="ss-panel ss-contact-thanks" aria-label="Thank you note">
                <span class="ss-contact-icon ss-contact-icon--soft" aria-hidden="true">
                    <svg viewBox="0 0 24 24" focusable="false"><path d="M12 20s-7-4.4-7-9.3C5 8.1 6.8 6.5 9 6.5c1.4 0 2.6.7 3 1.8.4-1.1 1.6-1.8 3-1.8 2.2 0 4 1.6 4 4.2C19 15.6 12 20 12 20z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path></svg>
                </span>
                <h3>Thank you!</h3>
                <p>SyntaxSidekick is a one-person project built with passion and dedication. Your support means a lot.</p>
            </aside>
        </div>
    </section>

    <section class="ss-home-section ss-contact-section ss-contact-newsletter" aria-label="Newsletter sign up">
        <div class="ss-container">
            <style>
                .ss-contact-page .ss-newsletter-cta {
                    border: 1px solid color-mix(in srgb, var(--ss-color-white) 15%, transparent);
                    border-radius: var(--ss-radius-md);
                    background:
                        radial-gradient(circle at 88% 12%, color-mix(in srgb, var(--ss-color-brand) 22%, transparent), transparent 32%),
                        linear-gradient(170deg, var(--ss-color-code-bg), color-mix(in srgb, var(--ss-color-code-bg) 78%, var(--ss-color-bg)));
                    color: var(--ss-color-white);
                    padding: var(--ss-space-3);
                    display: grid;
                    grid-template-columns: minmax(280px, 1fr) minmax(300px, 1.15fr);
                    gap: var(--ss-space-3);
                    align-items: center;
                }

                .ss-contact-page .ss-newsletter-cta__intro--feature {
                    display: grid;
                    grid-template-columns: auto minmax(0, 1fr);
                    gap: .95rem;
                    align-items: center;
                }

                .ss-contact-page .ss-newsletter-cta__icon {
                    inline-size: 2.9rem;
                    block-size: 2.9rem;
                    display: inline-grid;
                    place-items: center;
                    border: 1px solid color-mix(in srgb, var(--ss-color-brand) 50%, transparent);
                    border-radius: .7rem;
                    background: color-mix(in srgb, var(--ss-color-brand-dark) 18%, transparent);
                    color: var(--ss-color-brand);
                }

                .ss-contact-page .ss-newsletter-cta__icon svg {
                    inline-size: 1.3rem;
                    block-size: 1.3rem;
                }

                .ss-contact-page .ss-newsletter-cta__form {
                    display: grid;
                    grid-template-columns: minmax(0, 1fr) auto;
                    gap: .65rem;
                    align-items: start;
                }

                .ss-contact-page .ss-newsletter-cta__form input {
                    min-block-size: 44px;
                    border: 1px solid color-mix(in srgb, var(--ss-color-white) 35%, transparent);
                    border-radius: var(--ss-radius-sm);
                    background: color-mix(in srgb, var(--ss-color-white) 10%, transparent);
                    color: var(--ss-color-white);
                    padding: .65rem .8rem;
                    font: inherit;
                }

                .ss-contact-page .ss-newsletter-cta__form input::placeholder {
                    color: color-mix(in srgb, var(--ss-color-white) 68%, var(--ss-color-code-text));
                }

                .ss-contact-page .ss-newsletter-cta__form button {
                    min-block-size: 44px;
                    border: 1px solid transparent;
                    border-radius: var(--ss-radius-sm);
                    background: var(--ss-color-brand-dark);
                    color: var(--ss-color-white);
                    font-weight: 800;
                    padding-inline: 1.1rem;
                }

                .ss-contact-page .ss-newsletter-cta__form .ss-form-note,
                .ss-contact-page .ss-newsletter-cta__form .ss-form-status {
                    grid-column: 1 / -1;
                    color: color-mix(in srgb, var(--ss-color-white) 78%, var(--ss-color-code-text));
                    margin: 0;
                }

                @media (max-width: 900px) {
                    .ss-contact-page .ss-newsletter-cta,
                    .ss-contact-page .ss-newsletter-cta__intro--feature,
                    .ss-contact-page .ss-newsletter-cta__form {
                        grid-template-columns: 1fr;
                    }
                }
            </style>
            <?php
            get_template_part(
                'template-parts/components/newsletter-cta',
                null,
                array(
                    'layout' => 'feature',
                    'heading' => 'Stay in the loop',
                    'description' => 'Get the latest tutorials, guides, and resources delivered to your inbox.',
                    'input_id' => 'ss-contact-newsletter-email',
                    'help_id' => 'ss-contact-newsletter-help',
                    'status_id' => 'ss-contact-newsletter-status',
                    'button_label' => 'Subscribe',
                )
            );
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
