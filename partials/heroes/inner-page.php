
<?php

/**
 * Inner Page Hero
 *
 * Displays the page title, featured image,
 * dark overlay, and breadcrumb navigation.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the current page
$page_id = get_queried_object_id();

// get the featured image
$image_id = get_post_thumbnail_id($page_id);

// get the page title
$title = get_the_title($page_id);

// get the parent pages
$ancestors = is_page() ? array_reverse(get_post_ancestors($page_id)) : array();

?>

<!-- Inner Page Hero -->
<section
    id="page-hero"
    class="relative isolate flex min-h-[280px] items-center overflow-hidden
           bg-kp-surface md:min-h-[380px] lg:min-h-[440px]"
    aria-labelledby="page-hero-title"
>

    <!-- Background Image -->
    <?php if ($image_id) : ?>

        <div class="absolute inset-0 -z-20">

            <?php
            echo wp_get_attachment_image(
                $image_id,
                'kp-page-hero',
                false,
                array(
                    'class' => 'h-full w-full object-cover',
                    'alt' => '',
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                    'sizes' => '100vw',
                )
            );
            ?>

        </div>

    <?php endif; ?>

    <!-- Dark Image Overlay -->
    <div
        class="absolute inset-0 -z-10 bg-gradient-to-r
               from-kp-background/95 via-kp-background/85
               to-kp-background/55"
        aria-hidden="true"
    ></div>

    <!-- Hero Content -->
    <div class="kp-container py-16 md:py-20">

        <!-- Breadcrumbs -->
        <nav
            class="mb-7"
            aria-label="<?php esc_attr_e('Breadcrumb', 'kevinpirnie-v2'); ?>"
        >

            <ol class="flex flex-wrap items-center gap-2 text-sm text-kp-muted">

                <li>
                    <a
                        href="<?php echo esc_url(home_url('/')); ?>"
                        class="hover:text-kp-light-blue"
                    >
                        <?php esc_html_e('Home', 'kevinpirnie-v2'); ?>
                    </a>
                </li>

                <?php foreach ($ancestors as $ancestor_id) : ?>

                    <li aria-hidden="true" class="text-kp-orange">
                        /
                    </li>

                    <li>
                        <a
                            href="<?php echo esc_url(get_permalink($ancestor_id)); ?>"
                            class="hover:text-kp-light-blue"
                        >
                            <?php echo esc_html(get_the_title($ancestor_id)); ?>
                        </a>
                    </li>

                <?php endforeach; ?>

                <li aria-hidden="true" class="text-kp-orange">
                    /
                </li>

                <li
                    class="text-kp-text"
                    aria-current="page"
                >
                    <?php echo esc_html($title); ?>
                </li>

            </ol>

        </nav>

        <!-- Page Heading -->
        <div class="max-w-4xl">

            <div class="mb-6 h-1 w-14 rounded-full bg-kp-orange"></div>

            <h1
                id="page-hero-title"
                class="text-4xl font-bold leading-tight tracking-tight
                       text-kp-text md:text-5xl lg:text-6xl"
            >
                <?php echo esc_html($title); ?>
            </h1>

            <?php
            $subtitle = get_post_meta($page_id, 'page_secondary_title', true);
            ?>

            <?php if (! empty($subtitle)) : ?>

                <p class="mt-6 max-w-2xl text-lg leading-8 text-kp-muted md:text-xl">
                    <?php echo esc_html($subtitle); ?>
                </p>

            <?php endif; ?>

        </div>

    </div>

</section>
