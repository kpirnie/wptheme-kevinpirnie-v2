<?php
/** Template Name: Resume */
/** Resume template preserves the stored WordPress content. */
defined('ABSPATH') || die('No direct script access allowed');
get_header();
while (have_posts()) : the_post();
    get_template_part('partials/heroes/inner-page');
    ?>
    <article <?php post_class('py-16 md:py-20'); ?> id="post-<?php the_ID(); ?>">
    <div class="kp-container"><div class="mb-8 flex justify-end print:hidden"><button type="button" id="kpt-print-resume" class="kp-btn kp-btn-secondary">Print Resume</button></div>
    <div class="kp-prose mx-auto max-w-4xl"><?php the_content(); ?></div></div></article>
    <?php
endwhile;
get_footer();
