<?php
/** Standard WordPress page. */
defined('ABSPATH') || die('No direct script access allowed');
get_header();
while (have_posts()) : the_post();
    get_template_part('partials/heroes/inner-page'); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('py-16 md:py-20 lg:py-24'); ?>><div class="kp-container"><div class="kp-prose mx-auto max-w-4xl"><?php the_content(); ?><?php wp_link_pages(array('before' => '<nav aria-label="Page navigation" class="mt-10 flex flex-wrap gap-3">', 'after' => '</nav>')); ?></div></div></article>
<?php endwhile;
get_footer();
