<?php
/** Page not found. */
defined('ABSPATH') || die('No direct script access allowed');
get_header(); ?>
<section class="flex min-h-[65vh] items-center py-20"><div class="kp-container mx-auto max-w-2xl text-center"><p class="mb-6 text-8xl font-bold text-kp-orange">404</p><h1 class="mb-6 text-4xl font-bold">Page Not Found</h1><p class="mb-10 text-lg text-kp-muted">The page may have moved or no longer exists.</p><?php get_search_form(); ?><div class="mt-8"><a class="kp-btn kp-btn-primary" href="<?php echo esc_url(home_url('/')); ?>">Return Home</a></div></div></section>
<?php get_footer(); ?>
