<?php
/** Homepage. */
defined('ABSPATH') || die('No direct script access allowed');
get_header();
$page_id = get_queried_object_id();
$assigned = get_post_meta($page_id, 'page_assignment', true);
$assigned = is_array($assigned) ? array_filter(array_map('absint', $assigned)) : array();
$heroes = $assigned ? get_posts(array('post_type' => 'kpt_hero', 'post_status' => 'publish', 'post__in' => $assigned, 'orderby' => 'post__in', 'posts_per_page' => -1)) : array();
$heroes = array_values(array_filter($heroes, static function (WP_Post $hero): bool {
    return 'no' !== get_post_meta($hero->ID, 'hero_on_home', true);
}));

$heroes = array_merge($heroes, get_posts(array(
    'post_type' => 'kpt_hero',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post__not_in' => $assigned,
    'meta_key' => 'hero_on_home',
    'meta_value' => 'yes',
)));
?>
<section id="home-hero" class="relative overflow-hidden bg-kp-background" aria-label="Introduction">
<div id="hero-carousel" class="relative" aria-roledescription="carousel">
<?php if ($heroes) : foreach ($heroes as $index => $hero) : $active = 0 === $index; $image_id = get_post_thumbnail_id($hero->ID); ?>
<div class="kp-hero-slide relative <?php echo $active ? '' : 'hidden'; ?>" data-slide="<?php echo esc_attr($index); ?>" aria-roledescription="slide" aria-label="<?php echo esc_attr(sprintf('Slide %d of %d', $index + 1, count($heroes))); ?>" <?php echo $active ? '' : 'hidden'; ?>>
<?php if ($image_id) : ?><div class="absolute inset-0"><?php echo wp_get_attachment_image($image_id, 'kp-hero', false, array('class' => 'h-full w-full object-cover', 'alt' => '', 'loading' => $active ? 'eager' : 'lazy', 'fetchpriority' => $active ? 'high' : 'auto', 'sizes' => '100vw')); ?></div><?php endif; ?>
<div class="kp-hero-overlay absolute inset-0"></div>
<div class="kp-container relative z-10 flex min-h-[380px] items-center pb-28 pt-12 md:min-h-[480px] md:pt-16"><div class="max-w-3xl">
<?php if ($active) : ?><h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl"><?php echo esc_html($hero->post_title); ?></h1><?php else : ?><h2 class="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl"><?php echo esc_html($hero->post_title); ?></h2><?php endif; ?>
<div class="kp-prose mb-8 max-w-2xl text-lg text-kp-text/90"><?php echo wp_kses_post(apply_filters('the_content', $hero->post_content)); ?></div>
<a href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>" class="kp-btn kp-btn-primary">Get in Touch</a></div></div></div>
<?php endforeach; else : ?>
<div class="kp-container flex min-h-[380px] items-center py-12 md:min-h-[480px] md:py-16"><div class="max-w-3xl"><p class="mb-5 font-semibold uppercase tracking-widest text-kp-light-blue">WordPress / DevOps / Hosting</p><h1 class="mb-6 text-4xl font-bold tracking-tight md:text-6xl">Building Better Digital Experiences</h1><p class="mb-8 text-lg leading-8 text-kp-muted">Professional WordPress development, reliable hosting, and DevOps solutions focused on performance, security, and maintainability.</p><a class="kp-btn kp-btn-primary" href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>">Get in Touch</a></div></div>
<?php endif; ?>
<?php if (count($heroes) > 1) : ?><div class="absolute bottom-8 right-8 z-20 flex gap-3"><button type="button" id="hero-prev" class="kp-btn kp-btn-secondary" aria-label="Previous slide">&larr;</button><button type="button" id="hero-next" class="kp-btn kp-btn-secondary" aria-label="Next slide">&rarr;</button></div><?php endif; ?>
</div></section>
<section id="services" class="py-20 md:py-28"><div class="kp-container"><div class="mb-12 max-w-2xl"><h2 class="kp-section-title">What I Do</h2><p class="kp-section-description">Practical solutions for building, hosting, and maintaining reliable websites and infrastructure.</p></div><div class="grid gap-6 md:grid-cols-3">
<?php foreach (array(array('WordPress Development', 'Custom WordPress solutions built for speed, usability, security, and maintainability.'), array('DevOps & Infrastructure', 'Deployment workflows, server management, automation, and infrastructure optimization.'), array('Hosting & Support', 'Reliable hosting, troubleshooting, performance tuning, and maintenance.')) as $service) : ?>
<article class="kp-card"><div class="mb-5 h-1 w-12 rounded-full bg-kp-orange"></div><h3 class="mb-4 text-xl font-bold"><?php echo esc_html($service[0]); ?></h3><p class="leading-7 text-kp-muted"><?php echo esc_html($service[1]); ?></p></article>
<?php endforeach; ?></div></div></section>
<?php if (have_posts()) : while (have_posts()) : the_post(); if (trim(get_the_content()) !== '') : ?><section id="about" class="border-y border-kp-border bg-kp-surface py-20"><div class="kp-container"><div class="kp-prose mx-auto max-w-4xl"><?php the_content(); ?></div></div></section><?php endif; endwhile; endif; ?>
<?php foreach (array(array('kpt_portfolio', 'Featured Work', 'A selection of projects and technical solutions.'), array('post', 'Latest Articles', 'Thoughts, tutorials, and practical insights.')) as $section) :
$query = new WP_Query(array('post_type' => $section[0], 'post_status' => 'publish', 'posts_per_page' => 3, 'no_found_rows' => true, 'ignore_sticky_posts' => true));
if ($query->have_posts()) : ?>
<section class="border-t border-kp-border py-20 md:py-28"><div class="kp-container"><div class="mb-12"><h2 class="kp-section-title"><?php echo esc_html($section[1]); ?></h2><p class="kp-section-description"><?php echo esc_html($section[2]); ?></p></div><div class="grid gap-7 md:grid-cols-3">
<?php while ($query->have_posts()) : $query->the_post(); get_template_part('partials/content-card'); endwhile; ?>
</div></div></section>
<?php endif; wp_reset_postdata(); endforeach; ?>
<section class="py-20 md:py-28"><div class="kp-container"><div class="kp-glass mx-auto max-w-5xl rounded-xl px-6 py-12 text-center md:px-12 md:py-16"><h2 class="mb-5 text-3xl font-bold md:text-4xl">Have a Project in Mind?</h2><p class="mx-auto mb-8 max-w-2xl text-lg leading-8 text-kp-muted">Need WordPress development, infrastructure improvements, or dependable support? Let's talk.</p><a href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>" class="kp-btn kp-btn-primary">Let's Talk</a></div></div></section>
<?php get_footer(); ?>
