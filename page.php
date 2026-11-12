
<?php

/**
 * Page Template
 *
 * Displays standard WordPress pages with the
 * inner-page hero and page content.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the header
get_header();

// start the page loop
while (have_posts()) : the_post();

    // pull in the inner-page hero
    get_template_part('partials/heroes/inner-page');

    ?>

    <!-- Page Content -->
    <article
        id="post-<?php the_ID(); ?>"
        <?php post_class('py-16 md:py-20 lg:py-24'); ?>
    >

        <div class="kp-container">

            <div class="mx-auto max-w-4xl">

                <div class="kp-prose">
                    <?php the_content(); ?>
                </div>

                <?php
                // display paginated content links
                wp_link_pages(array(
                    'before' => '<nav class="mt-10 flex flex-wrap gap-3" aria-label="' .
                        esc_attr__('Page navigation', 'kevinpirnie-v2') . '">',
                    'after' => '</nav>',
                    'link_before' => '<span class="kp-btn kp-btn-secondary">',
                    'link_after' => '</span>',
                ));
                ?>

            </div>

        </div>

    </article>

<?php endwhile; ?>

<?php

// get the footer
get_footer();
