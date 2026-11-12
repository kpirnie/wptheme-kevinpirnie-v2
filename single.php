
<?php

/**
 * Single Post Template
 *
 * Displays individual WordPress articles.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the header
get_header();

// start the post loop
while (have_posts()) : the_post();

    // pull in the inner-page hero
    get_template_part('partials/heroes/inner-page');

    ?>

    <!-- Article Content -->
    <article
        id="post-<?php the_ID(); ?>"
        <?php post_class('py-16 md:py-20 lg:py-24'); ?>
    >

        <div class="kp-container">

            <div class="mx-auto max-w-4xl">

                <!-- Article Metadata -->
                <div class="mb-10 flex flex-wrap items-center gap-x-5 gap-y-3
                            border-b border-kp-border pb-7 text-sm text-kp-muted">

                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>

                    <span aria-hidden="true" class="text-kp-orange">/</span>

                    <span>
                        <?php
                        printf(
                            esc_html__('By %s', 'kevinpirnie-v2'),
                            esc_html(get_the_author())
                        );
                        ?>
                    </span>

                    <?php
                    $categories = get_the_category();

                    if (! empty($categories)) :
                    ?>

                        <span aria-hidden="true" class="text-kp-orange">/</span>

                        <span>
                            <?php echo get_the_category_list(', '); ?>
                        </span>

                    <?php endif; ?>

                </div>

                <!-- Article Body -->
                <div class="kp-prose">
                    <?php the_content(); ?>
                </div>

                <!-- Paginated Content -->
                <?php
                wp_link_pages(array(
                    'before' => '<nav class="mt-10 flex flex-wrap gap-3" aria-label="' .
                        esc_attr__('Article pages', 'kevinpirnie-v2') . '">',
                    'after' => '</nav>',
                    'link_before' => '<span class="kp-btn kp-btn-secondary">',
                    'link_after' => '</span>',
                ));
                ?>

                <!-- Article Tags -->
                <?php
                $tags = get_the_tags();

                if ($tags) :
                ?>

                    <div class="mt-12 flex flex-wrap items-center gap-3
                                border-t border-kp-border pt-8">

                        <span class="mr-2 text-sm font-semibold text-kp-text">
                            <?php esc_html_e('Tags:', 'kevinpirnie-v2'); ?>
                        </span>

                        <?php foreach ($tags as $tag) : ?>

                            <a
                                href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"
                                class="rounded-md border border-kp-border
                                       bg-kp-surface px-3 py-1.5
                                       text-sm text-kp-muted
                                       hover:border-kp-light-blue hover:text-kp-text"
                            >
                                <?php echo esc_html($tag->name); ?>
                            </a>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

                <!-- Article Navigation -->
                <nav
                    class="mt-12 grid gap-5 border-t border-kp-border pt-8 md:grid-cols-2"
                    aria-label="<?php esc_attr_e('Article navigation', 'kevinpirnie-v2'); ?>"
                >

                    <div>
                        <?php
                        $previous_post = get_previous_post();

                        if ($previous_post) :
                        ?>

                            <a
                                href="<?php echo esc_url(get_permalink($previous_post)); ?>"
                                class="group block rounded-lg border border-kp-border
                                       bg-kp-surface p-5 hover:border-kp-light-blue"
                            >
                                <span class="mb-2 block text-xs uppercase tracking-wider text-kp-muted">
                                    &larr; Previous Article
                                </span>

                                <span class="font-semibold text-kp-text group-hover:text-kp-light-blue">
                                    <?php echo esc_html(get_the_title($previous_post)); ?>
                                </span>
                            </a>

                        <?php endif; ?>
                    </div>

                    <div>
                        <?php
                        $next_post = get_next_post();

                        if ($next_post) :
                        ?>

                            <a
                                href="<?php echo esc_url(get_permalink($next_post)); ?>"
                                class="group block rounded-lg border border-kp-border
                                       bg-kp-surface p-5 text-right hover:border-kp-light-blue"
                            >
                                <span class="mb-2 block text-xs uppercase tracking-wider text-kp-muted">
                                    Next Article &rarr;
                                </span>

                                <span class="font-semibold text-kp-text group-hover:text-kp-light-blue">
                                    <?php echo esc_html(get_the_title($next_post)); ?>
                                </span>
                            </a>

                        <?php endif; ?>
                    </div>

                </nav>

            </div>

        </div>

    </article>

<?php endwhile; ?>

<?php

// get the footer
get_footer();
