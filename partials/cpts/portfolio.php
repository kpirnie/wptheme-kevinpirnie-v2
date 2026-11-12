<?php
/** Portfolio block grid. */
defined('ABSPATH') || die('No direct script access allowed');
$size = $args['image_size'] ?? 'kp-portfolio';
if (! in_array($size, array('kp-portfolio', 'kp-portfolio-card'), true)) $size = 'kp-portfolio';
$portfolio = new WP_Query(array('post_type' => 'kpt_portfolio', 'post_status' => 'publish',
    'posts_per_page' => -1, 'no_found_rows' => true));
if (! $portfolio->have_posts()) return;
?>
<section class="kpt-portfolio my-12 w-full"><div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3">
<?php while ($portfolio->have_posts()) : $portfolio->the_post(); ?>
<article <?php post_class('kp-card flex flex-col'); ?>>
<?php if (has_post_thumbnail()) : ?><a href="<?php the_permalink(); ?>" class="mb-6 block overflow-hidden rounded-lg"><?php the_post_thumbnail($size, array('class' => 'aspect-[4/3] w-full object-cover','loading' => 'lazy')); ?></a><?php endif; ?>
<h3 class="mb-4 text-xl font-bold"><a href="<?php the_permalink(); ?>" class="hover:text-kp-light-blue"><?php the_title(); ?></a></h3>
<div class="mb-6 flex-1 text-sm leading-7 text-kp-muted"><?php the_excerpt(); ?></div>
<a href="<?php the_permalink(); ?>" class="font-semibold text-kp-light-blue">View Project &rarr;</a></article>
<?php endwhile; wp_reset_postdata(); ?>
</div></section>
