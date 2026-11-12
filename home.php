<?php
/** Main WordPress posts page. */
defined('ABSPATH') || die('No direct script access allowed');
get_header();
if (get_option('page_for_posts')) { get_template_part('partials/heroes/inner-page'); } else { echo '<header class="kp-container py-16"><h1 class="text-5xl font-bold">Articles</h1></header>'; }
?>
<section class="py-16 md:py-20 lg:py-24"><div class="kp-container">
<?php if (have_posts()) : ?><div class="grid gap-7 md:grid-cols-2 lg:grid-cols-3"><?php while (have_posts()) : the_post(); get_template_part('partials/content-card'); endwhile; ?></div><div class="mt-14"><?php the_posts_pagination(array('mid_size' => 2)); ?></div>
<?php else : ?><p class="text-kp-muted">No articles found.</p><?php endif; ?>
</div></section>
<?php get_footer(); ?>
