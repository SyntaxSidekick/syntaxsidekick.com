<?php
/**
 * About page template.
 *
 * @package SyntaxSidekick_Child
 */

get_header();

$value_cards = array(
    array(
        'title' => 'High-Quality Content',
        'description' => 'Every tutorial, guide, and article is researched, written, and reviewed with care.',
        'icon' => 'cap',
    ),
    array(
        'title' => 'Practical & Actionable',
        'description' => 'Real-world examples, best practices, and code you can use today.',
        'icon' => 'code',
    ),
    array(
        'title' => 'Always Evolving',
        'description' => 'We stay on top of the latest tools, techniques, and industry changes so you don\'t have to.',
        'icon' => 'leaf',
    ),
    array(
        'title' => 'Community First',
        'description' => 'Built for developers, by developers. We value feedback, discussion, and collaboration.',
        'icon' => 'users',
    ),
);

$topic_pills = array(
    'Front-End Development',
    'Modern CSS',
    'JavaScript',
    'TypeScript',
    'React',
    'Vue',
    'Accessibility',
    'UX Engineering',
    'Design Systems',
    'Performance',
    'AI & Development',
);

$collaboration_cards = array(
    array(
        'title' => 'Project Consulting',
        'description' => 'From front-end architecture to UX engineering and performance optimization, I help teams build fast, accessible, and scalable web experiences.',
        'items' => array(
            'Front End Architecture',
            'UX Engineering',
            'Design Systems',
            'Performance Optimization',
            'Accessibility Audits',
            'Enterprise Consulting',
        ),
        'link_label' => 'Learn more',
        'link_url' => home_url('/contact/'),
        'icon' => 'rocket',
    ),
    array(
        'title' => 'Technical Audits & Code Reviews',
        'description' => 'Get expert eyes on your code and in-depth recommendations to improve quality, performance, accessibility, and maintainability.',
        'items' => array(
            'Code Quality',
            'Performance',
            'Accessibility',
            'SEO & Best Practices',
            'Modern CSS',
            'React Architecture',
        ),
        'link_label' => 'Learn more',
        'link_url' => home_url('/contact/'),
        'icon' => 'code',
    ),
    array(
        'title' => 'One-on-One Mentoring',
        'description' => 'Personalized mentoring for developers and designers who want to grow their skills, build confidence, and advance their career.',
        'items' => array(
            'Career Growth',
            'Portfolio Reviews',
            'Resume & LinkedIn Reviews',
            'Interview Preparation',
            'Technical Leadership',
            'And more...',
        ),
        'link_label' => 'Learn more',
        'link_url' => home_url('/contact/'),
        'icon' => 'mentor',
    ),
    array(
        'title' => 'Workshops & Speaking',
        'description' => 'Available for workshops, team training, and technical talks on modern front-end development, UX engineering, and more.',
        'items' => array(
            'Team Training',
            'Workshops',
            'Conferences',
            'Tech Talks',
            'Custom Sessions',
        ),
        'link_label' => 'Learn more',
        'link_url' => home_url('/contact/'),
        'icon' => 'calendar',
    ),
);

$creator_user = get_user_by('slug', 'riadkilani');

if (! $creator_user) {
    $creator_user = get_user_by('login', 'riadkilani');
}

if (! $creator_user) {
    $creator_user = get_user_by('email', 'hello@syntaxsidekick.com');
}

$creator_user_id = $creator_user instanceof WP_User ? (int) $creator_user->ID : 0;
$creator_image_relative_path = '/assets/images/riad-kilani-profile-pic.png';
$creator_image_absolute_path = get_stylesheet_directory() . $creator_image_relative_path;

$creator_avatar_url = file_exists($creator_image_absolute_path)
    ? (string) get_stylesheet_directory_uri() . $creator_image_relative_path
    : ($creator_user_id > 0
        ? (string) get_avatar_url($creator_user_id, array('size' => 216))
        : '');
?>

<main id="main-content" class="ss-main ss-about-page">
    <section class="ss-page-hero ss-about-hero site-section about-hero" aria-labelledby="ss-about-page-title">
        <div class="ss-container ss-page-hero__grid ss-about-hero__grid">
            <div class="ss-page-hero__content ss-about-hero__content about-hero__content">
                <p class="ss-eyebrow section-eyebrow">ABOUT SYNTAXSIDEKICK</p>
                <h1 id="ss-about-page-title">Helping developers learn, build, and grow.</h1>
                <p>SyntaxSidekick is a learning platform for front-end developers who care about building better websites and user experiences. We create in-depth tutorials, practical guides, and high-quality resources to help you stay sharp and level up your skills.</p>
                <div class="ss-actions ss-about-hero__actions">
                    <a class="ss-button ss-button-primary" href="<?php echo esc_url(home_url('/tutorials/')); ?>" aria-label="Explore tutorials">Explore Tutorials</a>
                    <a class="ss-button ss-button-secondary" href="<?php echo esc_url(home_url('/guides/')); ?>" aria-label="Browse guides">Browse Guides</a>
                </div>
            </div>

            <div class="ss-page-hero__visual ss-about-hero__visual about-hero__visual" aria-hidden="true">
                <div class="ss-about-hero-illustration">
                    <div class="ss-about-hero-illustration__dots"><span></span><span></span><span></span></div>
                    <div class="ss-about-hero-illustration__body">
                        <div class="ss-about-hero-illustration__glyph">&lt;/&gt;</div>
                        <div class="ss-about-hero-illustration__lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <ul class="ss-about-hero-illustration__checks">
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ss-home-section ss-about-section site-section" aria-labelledby="ss-about-purpose-heading">
        <div class="ss-container">
            <header class="section-header ss-about-purpose-header">
                <div>
                    <p class="section-eyebrow">OUR PURPOSE</p>
                    <h2 id="ss-about-purpose-heading" class="section-title">Practical knowledge. Real impact.</h2>
                </div>
                <p class="section-copy">We believe learning should be practical, accessible, and engaging. Our goal is to cut through the noise and deliver content that helps you solve real problems and build your career with confidence.</p>
            </header>

            <div class="ss-card-grid ss-about-values-grid" aria-label="Core values">
                <?php foreach ($value_cards as $card) : ?>
                    <article class="ss-card value-card">
                        <div class="ss-card-body value-card__body">
                            <span class="value-card__icon" aria-hidden="true">
                                <?php if ('cap' === $card['icon']) : ?>
                                    <svg viewBox="0 0 24 24" focusable="false"><path d="M3 10l9-5 9 5-9 5-9-5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path><path d="M7 12.2V16c0 .8 2.2 2 5 2s5-1.2 5-2v-3.8" fill="none" stroke="currentColor" stroke-width="1.8"></path></svg>
                                <?php elseif ('code' === $card['icon']) : ?>
                                    <svg viewBox="0 0 24 24" focusable="false"><path d="M8 7l-5 5 5 5M16 7l5 5-5 5M13 5l-2 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                                <?php elseif ('leaf' === $card['icon']) : ?>
                                    <svg viewBox="0 0 24 24" focusable="false"><path d="M19 4c-6 0-10 3.4-10 8.2 0 3.8 2.4 6.4 6.1 6.4C19.8 18.6 21 14.8 21 10V4h-2z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path><path d="M6 20c2.5-2.5 5.1-5 8.8-6.7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                                <?php else : ?>
                                    <svg viewBox="0 0 24 24" focusable="false"><path d="M16 11c1.7 0 3-1.3 3-3s-1.3-3-3-3-3 1.3-3 3 1.3 3 3 3zM8 13c2.2 0 4-1.8 4-4S10.2 5 8 5 4 6.8 4 9s1.8 4 4 4z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M2.5 19.5C3.3 16.9 5.4 15 8 15s4.7 1.9 5.5 4.5M13 19.5c.6-2 2.2-3.5 4-3.5 1.8 0 3.4 1.5 4 3.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                                <?php endif; ?>
                            </span>
                            <h3><?php echo esc_html((string) $card['title']); ?></h3>
                            <p><?php echo esc_html((string) $card['description']); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="creator" class="ss-home-section ss-about-section site-section" aria-labelledby="ss-about-story-title">
        <div class="ss-container">
            <article class="ss-panel story-panel">
                <div class="story-panel__content">
                    <p class="section-eyebrow">Our story</p>
                    <h2 id="ss-about-story-title" class="section-title">Built by a developer, for developers.</h2>
                    <p class="section-copy">SyntaxSidekick was created out of a simple idea: quality front-end learning should be practical, up-to-date, and easy to follow.</p>
                    <p class="section-copy">What started as a personal passion project has grown into a focused learning hub for developers who care about better front-end work.</p>
                </div>

                <aside class="creator-card" aria-label="Riad Kilani profile">
                    <div class="creator-card__media" role="img" aria-label="Portrait of Riad Kilani">
                        <?php if ('' !== $creator_avatar_url) : ?>
                            <img src="<?php echo esc_url($creator_avatar_url); ?>" alt="Riad Kilani" width="108" height="108" loading="lazy" decoding="async">
                        <?php else : ?>
                            RK
                        <?php endif; ?>
                    </div>
                    <div class="creator-card__body">
                        <h3>Riad Kilani</h3>
                        <p class="creator-card__role">Founder &amp; Content Creator</p>
                        <p class="creator-card__description">Senior Front-End Developer, UX Engineer, and architect with 17+ years of experience building scalable web applications and design systems.</p>
                        <ul class="creator-card__links" role="list">
                            <li>
                                <a href="https://www.linkedin.com/in/riadkilani" aria-label="View LinkedIn profile">
                                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M4.98 3.5A2.49 2.49 0 0 0 2.5 6a2.5 2.5 0 1 0 2.48-2.5zM3 8h4v13H3zm7 0h3.82v1.77h.05c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.78 2.65 4.78 6.1V21h-4v-6.19c0-1.48-.03-3.39-2.06-3.39-2.06 0-2.38 1.61-2.38 3.29V21h-4z" fill="currentColor"></path></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://github.com/riadkilani" aria-label="View GitHub profile">
                                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M12 .5A11.5 11.5 0 0 0 .5 12.3c0 5.3 3.4 9.8 8 11.4.6.1.8-.3.8-.6v-2.2c-3.3.7-4-1.4-4-1.4-.6-1.4-1.3-1.8-1.3-1.8-1.1-.8.1-.8.1-.8 1.2.1 1.9 1.2 1.9 1.2 1 1.8 2.8 1.2 3.5.9.1-.8.4-1.2.7-1.5-2.6-.3-5.3-1.3-5.3-5.9 0-1.3.5-2.3 1.2-3.1-.1-.3-.5-1.5.1-3.2 0 0 1-.3 3.3 1.2a11 11 0 0 1 6 0c2.3-1.5 3.3-1.2 3.3-1.2.7 1.7.3 2.9.1 3.2.8.8 1.2 1.8 1.2 3.1 0 4.6-2.8 5.5-5.4 5.8.4.4.8 1 .8 2.1v3.1c0 .3.2.7.8.6a11.8 11.8 0 0 0 8-11.4A11.5 11.5 0 0 0 12 .5z" fill="currentColor"></path></svg>
                                </a>
                            </li>
                            <li>
                                <a href="mailto:hello@syntaxsidekick.com" aria-label="Send email">
                                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M3 6h18v12H3z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M3 7l9 7 9-7" fill="none" stroke="currentColor" stroke-width="1.8"></path></svg>
                                </a>
                            </li>
                            <li>
                                <a href="https://sintacks.studio/" aria-label="Visit Sintacks Studio portfolio">
                                    <svg viewBox="0 0 24 24" focusable="false" aria-hidden="true"><path d="M4 7h16v10H4z" fill="none" stroke="currentColor" stroke-width="1.8"></path><path d="M9 17h6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path></svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>
            </article>
        </div>
    </section>

    <?php
    get_template_part(
        'template-parts/components/about-collaboration',
        null,
        array(
            'eyebrow' => 'WORK WITH ME',
            'heading_prefix' => 'Let\'s Build Something Great',
            'heading_accent' => 'Together',
            'description' => 'Whether you need help with a project, want to improve your codebase, or are looking to level up your skills, I\'m here to help you succeed.',
            'cards' => $collaboration_cards,
            'cta_title' => 'Not sure if I\'m the right fit?',
            'cta_copy' => 'Reach out anyway. Whether it\'s a quick question, architecture advice, mentoring, or a full project, I\'m always happy to have a conversation.',
            'cta_button_label' => 'Let\'s Talk',
            'cta_button_url' => home_url('/contact/'),
        )
    );
    ?>

    <section class="ss-home-section ss-about-section site-section" aria-labelledby="ss-about-topics-heading">
        <div class="ss-container">
            <header class="section-header ss-about-topics-header">
                <p class="section-eyebrow">TOPICS WE COVER</p>
                <h2 id="ss-about-topics-heading" class="section-title">Focused on the modern front-end.</h2>
            </header>
            <ul class="ss-about-topics" role="list">
                <?php foreach ($topic_pills as $topic) : ?>
                    <li class="topic-pill"><?php echo esc_html($topic); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>

    <section class="ss-home-section ss-about-section ss-about-newsletter site-section" aria-label="Newsletter sign up">
        <div class="ss-container">
            <?php
            get_template_part(
                'template-parts/components/newsletter-cta',
                null,
                array(
                    'layout' => 'feature',
                    'heading' => 'Stay in the loop',
                    'description' => 'Get the latest tutorials, guides, and resources delivered to your inbox.',
                    'input_id' => 'ss-about-newsletter-email',
                    'help_id' => 'ss-about-newsletter-help',
                    'status_id' => 'ss-about-newsletter-status',
                    'button_label' => 'Subscribe',
                )
            );
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
