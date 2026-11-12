
<?php

/**
 * Index Template
 *
 * Default fallback template for WordPress content.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the header
get_header();

?>

<!-- Content Listing -->
<section class="py-16 md:py-20 lg:py-24">

    <div class="kp-container">

        <header class="mb-12 border-b border-kp-border pb-8">

            <h1 class="text-4xl font-bold tracking-tight text-kp-text md:text-5xl">
                <?php
                if (is_search()) {
                    printf(
                        esc_html__('Search Results for: %s', 'kevinpirnie-v2'),
                        esc_html(get_search_query())
                    );
                } elseif (is_archive()) {
                    echo esc_html(get_the_archive_title());
                } else {
                    esc_html_e('Latest Articles', 'kevinpirnie-v2');
                }
                ?>
            </h1>

            <?php if (is_archive() && get_the_archive_description()) : ?>

                <div class="kp-prose mt-5 max-w-3xl">
                    <?php the_archive_description(); ?>
                </div>

            <?php endif; ?>

        </header>

        <?php if (have_posts()) : ?>

            <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">

                <?php while (have_posts()) : the_post(); ?>

                    <article
                        id="post-<?php the_ID(); ?>"
                        <?php post_class('kp-card flex flex-col overflow-hidden'); ?>
                    >

                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail()) : ?>

                            <a
                                href="<?php the_permalink(); ?>"
                                class="mb-6 block overflow-hidden rounded-lg"
                                aria-label="<?php echo esc_attr(get_the_title()); ?>"
                            >

                                <?php
                                the_post_thumbnail('kp-article-card', array(
                                    'class' => 'aspect-[3/2] w-full object-cover',
                                    'loading' => 'lazy',
                                    'sizes' => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw',
                                ));
                                ?>

                            </a>

                        <?php endif; ?>

                        <!-- Post Metadata -->
                        <time
                            class="mb-3 text-sm text-kp-muted"
                            datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                        >
                            <?php echo esc_html(get_the_date()); ?>
                        </time>

                        <!-- Post Title -->
                        <h2 class="mb-4 text-xl font-bold leading-snug text-kp-text">

                            <a
                                href="<?php the_permalink(); ?>"
                                class="hover:text-kp-light-blue"
                            >
                                <?php the_title(); ?>
                            </a>

                        </h2>

                        <!-- Excerpt -->
                        <div class="mb-6 flex-1 text-sm leading-7 text-kp-muted">
                            <?php the_excerpt(); ?>
                        </div>

                        <!-- Read More -->
                        <a
                            href="<?php the_permalink(); ?>"
                            class="inline-flex items-center gap-2 self-start
                                   font-semibold text-kp-light-blue
                                   hover:text-kp-text"
                        >
                            <?php esc_html_e('Read More', 'kevinpirnie-v2'); ?>
                            <span aria-hidden="true">&rarr;</span>
                        </a>

                    </article>

                <?php endwhile; ?>

            </div>

            <!-- Pagination -->
            <nav
                class="mt-14"
                aria-label="<?php esc_attr_e('Content pagination', 'kevinpirnie-v2'); ?>"
            >

                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '&larr; ' . __('Previous', 'kevinpirnie-v2'),
                    'next_text' => __('Next', 'kevinpirnie-v2') . ' &rarr;',
                    'screen_reader_text' => __('Content navigation', 'kevinpirnie-v2'),
                ));
                ?>

            </nav>

        <?php else : ?>

            <!-- No Results -->
            <div class="mx-auto max-w-2xl py-16 text-center">

                <h2 class="mb-5 text-2xl font-bold text-kp-text">
                    <?php esc_html_e('Nothing Found', 'kevinpirnie-v2'); ?>
                </h2>

                <p class="mb-8 text-kp-muted">
                    <?php esc_html_e('No content matched your request.', 'kevinpirnie-v2'); ?>
                </p>

                <a
                    href="<?php echo esc_url(home_url('/')); ?>"
                    class="kp-btn kp-btn-primary"
                >
                    <?php esc_html_e('Return Home', 'kevinpirnie-v2'); ?>
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php

// get the footer
get_footer();
