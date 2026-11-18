<?php
/** Image-backed inner-page header and breadcrumbs. */
defined('ABSPATH') || die('No direct script access allowed');
$page_id = get_queried_object_id();
$title = is_home() && get_option('page_for_posts') ? get_the_title((int) get_option('page_for_posts')) : (is_singular() ? get_the_title($page_id) : get_the_archive_title());
$image_id = get_post_thumbnail_id($page_id);
if ($page_id && (is_page() || is_home())) {
    $hero_ids = get_posts(array(
        'post_type' => 'kpt_hero',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'orderby' => array('date' => 'DESC', 'ID' => 'DESC'),
        'meta_query' => array(
            array('key' => 'hero_page_ids', 'compare' => 'EXISTS'),
        ),
    ));

    foreach ($hero_ids as $hero_id) {
        $assigned_pages = get_post_meta($hero_id, 'hero_page_ids', true);

        if (is_array($assigned_pages) && in_array($page_id, array_map('absint', $assigned_pages), true)) {
            $image_id = get_post_thumbnail_id($hero_id) ?: $image_id;
            break;
        }
    }
}
$ancestors = is_page() ? array_reverse(get_post_ancestors($page_id)) : array();
$subtitle = get_post_meta($page_id, 'page_secondary_title', true);
?>
<section id="page-hero" class="relative isolate flex min-h-[240px] items-center overflow-hidden bg-kp-surface md:min-h-[320px] lg:min-h-[360px]" aria-labelledby="page-hero-title">
    <?php if ($image_id) : ?><div class="absolute inset-0 -z-20"><?php echo wp_get_attachment_image($image_id, 'kp-page-hero', false, array('class' => 'h-full w-full object-cover', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '100vw')); ?></div><?php endif; ?>
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-kp-background/95 via-kp-background/85 to-kp-background/55" aria-hidden="true"></div>
    <div class="kp-container py-10 md:py-12">
        <nav class="mb-7" aria-label="Breadcrumb"><ol class="flex flex-wrap items-center gap-2 text-sm text-kp-muted">
            <li><a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-kp-light-blue">Home</a></li>
            <?php foreach ($ancestors as $ancestor_id) : ?><li aria-hidden="true" class="text-kp-orange">/</li><li><a href="<?php echo esc_url(get_permalink($ancestor_id)); ?>" class="hover:text-kp-light-blue"><?php echo esc_html(get_the_title($ancestor_id)); ?></a></li><?php endforeach; ?>
            <li aria-hidden="true" class="text-kp-orange">/</li><li aria-current="page" class="text-kp-text"><?php echo esc_html($title); ?></li>
        </ol></nav>
        <div class="max-w-4xl"><div class="mb-6 h-1 w-14 rounded-full bg-kp-orange"></div><h1 id="page-hero-title" class="text-4xl font-bold leading-tight tracking-tight text-kp-text md:text-5xl lg:text-6xl"><?php echo esc_html($title); ?></h1><?php if ($subtitle) : ?><p class="mt-6 max-w-2xl text-lg leading-8 text-kp-muted md:text-xl"><?php echo esc_html($subtitle); ?></p><?php endif; ?></div>
    </div>
</section>
