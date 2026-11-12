
<?php

/**
 * Single Portfolio Template
 *
 * Displays individual portfolio projects.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the header
get_header();

// start the portfolio loop
while (have_posts()) : the_post();

    // pull in the inner-page hero
    get_template_part('partials/heroes/inner-page');

    ?>

    <!-- Portfolio Content -->
    <article
        id="post-<?php the_ID(); ?>"
        <?php post_class('py-16 md:py-20 lg:py-24'); ?>
    >

        <div class="kp-container">

            <div class="mx-auto max-w-5xl">

                <!-- Project Introduction -->
                <?php if (has_excerpt()) : ?>

                    <div class="mb-12 border-l-4 border-kp-orange
                                bg-kp-surface px-6 py-5 md:px-8">

                        <p class="text-lg leading-8 text-kp-text md:text-xl">
                            <?php echo esc_html(get_the_excerpt()); ?>
                        </p>

                    </div>

                <?php endif; ?>

                <!-- Project Content -->
                <div class="kp-prose">
                    <?php the_content(); ?>
                </div>

                <!-- Paginated Content -->
                <?php
                wp_link_pages(array(
                    'before' => '<nav class="mt-10 flex flex-wrap gap-3" aria-label="' .
                        esc_attr__('Project pages', 'kevinpirnie-v2') . '">',
                    'after' => '</nav>',
                    'link_before' => '<span class="kp-btn kp-btn-secondary">',
                    'link_after' => '</span>',
                ));
                ?>

                <!-- Project Navigation -->
                <nav
                    class="mt-16 grid gap-5 border-t border-kp-border pt-8 md:grid-cols-2"
                    aria-label="<?php esc_attr_e('Portfolio navigation', 'kevinpirnie-v2'); ?>"
                >

                    <div>

                        <?php
                        $previous_project = get_previous_post(false, '', false, 'kpt_portfolio');

                        if ($previous_project) :
                        ?>

                            <a
                                href="<?php echo esc_url(get_permalink($previous_project)); ?>"
                                class="group block rounded-lg border border-kp-border
                                       bg-kp-surface p-5 hover:border-kp-light-blue"
                            >

                                <span class="mb-2 block text-xs uppercase tracking-wider text-kp-muted">
                                    &larr; Previous Project
                                </span>

                                <span class="font-semibold text-kp-text group-hover:text-kp-light-blue">
                                    <?php echo esc_html(get_the_title($previous_project)); ?>
                                </span>

                            </a>

                        <?php endif; ?>

                    </div>

                    <div>

                        <?php
                        $next_project = get_next_post(false, '', false, 'kpt_portfolio');

                        if ($next_project) :
                        ?>

                            <a
                                href="<?php echo esc_url(get_permalink($next_project)); ?>"
                                class="group block rounded-lg border border-kp-border
                                       bg-kp-surface p-5 text-right hover:border-kp-light-blue"
                            >

                                <span class="mb-2 block text-xs uppercase tracking-wider text-kp-muted">
                                    Next Project &rarr;
                                </span>

                                <span class="font-semibold text-kp-text group-hover:text-kp-light-blue">
                                    <?php echo esc_html(get_the_title($next_project)); ?>
                                </span>

                            </a>

                        <?php endif; ?>

                    </div>

                </nav>

                <!-- Contact CTA -->
                <div class="kp-glass mt-16 rounded-xl px-6 py-10 text-center
                            shadow-kp-card md:px-10 md:py-12">

                    <h2 class="mb-4 text-2xl font-bold text-kp-text md:text-3xl">
                        Have a Similar Project?
                    </h2>

                    <p class="mx-auto mb-7 max-w-xl text-kp-muted">
                        Need help with WordPress development, hosting,
                        or infrastructure? Let's discuss your project.
                    </p>

                    <a
                        href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>"
                        class="kp-btn kp-btn-primary"
                    >
                        Let's Talk
                    </a>

                </div>

            </div>

        </div>

    </article>

<?php endwhile; ?>

<?php

// get the footer
get_footer();
