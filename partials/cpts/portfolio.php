
<?php

/**
 * Portfolio Display Template
 *
 * Displays portfolio projects in a responsive grid.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the requested image size
$image_size = $args['image_size'] ?? 'kp-portfolio';

// allow only registered portfolio image sizes
if (! in_array($image_size, array('kp-portfolio', 'kp-portfolio-card'), true)) {
    $image_size = 'kp-portfolio';
}

// retrieve published portfolio projects
$portfolio = new WP_Query(array(
    'post_type' => 'kpt_portfolio',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'no_found_rows' => true,
));

// nothing to display
if (! $portfolio->have_posts()) {
    return;
}

?>

<!-- Portfolio Listing -->
<section class="kpt-portfolio my-12 w-full">

    <div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">

        <?php while ($portfolio->have_posts()) : $portfolio->the_post(); ?>

            <article
                id="portfolio-<?php the_ID(); ?>"
                <?php post_class('kp-card flex flex-col overflow-hidden'); ?>
            >

                <!-- Project Image -->
                <?php if (has_post_thumbnail()) : ?>

                    <a
                        href="<?php the_permalink(); ?>"
                        class="mb-6 block overflow-hidden rounded-lg"
                        aria-label="<?php echo esc_attr(get_the_title()); ?>"
                    >

                        <?php
                        the_post_thumbnail($image_size, array(
                            'class' => 'aspect-[4/3] w-full object-cover',
                            'loading' => 'lazy',
                            'sizes' => '(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw',
                        ));
                        ?>

                    </a>

                <?php endif; ?>

                <!-- Project Title -->
                <h3 class="mb-4 text-xl font-bold leading-snug text-kp-text">

                    <a
                        href="<?php the_permalink(); ?>"
                        class="hover:text-kp-light-blue"
                    >
                        <?php the_title(); ?>
                    </a>

                </h3>

                <!-- Project Description -->
                <div class="mb-6 flex-1 text-sm leading-7 text-kp-muted">
                    <?php the_excerpt(); ?>
                </div>

                <!-- Project Link -->
                <a
                    href="<?php the_permalink(); ?>"
                    class="inline-flex items-center gap-2 self-start
                           font-semibold text-kp-light-blue
                           hover:text-kp-text"
                >
                    <?php esc_html_e('View Project', 'kevinpirnie-v2'); ?>
                    <span aria-hidden="true">&rarr;</span>
                </a>

            </article>

        <?php endwhile; ?>

    </div>

</section>

<?php

// restore the original post data
wp_reset_postdata();
