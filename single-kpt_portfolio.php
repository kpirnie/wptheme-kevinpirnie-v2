<?php
/** Single portfolio project. */
defined('ABSPATH') || die('No direct script access allowed');
get_header();
while (have_posts()) : the_post();
    get_template_part('partials/heroes/inner-page'); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('py-16 md:py-20 lg:py-24'); ?>><div class="kp-container"><div class="mx-auto max-w-5xl">
        <?php if (has_excerpt()) : ?><div class="mb-12 border-l-4 border-kp-orange bg-kp-surface px-6 py-5 text-lg text-kp-text"><?php echo esc_html(get_the_excerpt()); ?></div><?php endif; ?>
        <div class="kp-prose"><?php the_content(); ?></div>
        <?php wp_link_pages(array('before' => '<nav aria-label="Project pages" class="mt-10">', 'after' => '</nav>')); ?>
        <nav class="mt-12 flex justify-between gap-6 border-t border-kp-border pt-8" aria-label="Portfolio navigation"><?php previous_post_link('%link', '&larr; %title'); ?><?php next_post_link('%link', '%title &rarr;'); ?></nav>
        <div class="kp-glass mt-16 rounded-xl px-6 py-12 text-center"><h2 class="mb-4 text-3xl font-bold">Have a Similar Project?</h2><p class="mb-7 text-kp-muted">Let's discuss your project.</p><a class="kp-btn kp-btn-primary" href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>">Let's Talk</a></div>
    </div></div></article>
<?php endwhile;
get_footer();
