
<?php

/**
 * Front Page Template
 *
 * Homepage hero, services, portfolio, articles, and CTA.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the header
get_header();

// get the assigned homepage heroes
$assigned_heroes = get_post_meta(get_queried_object_id(), 'page_assignment', true);
$assigned_heroes = is_array($assigned_heroes) ? array_map('absint', $assigned_heroes) : array();

$heroes = ! empty($assigned_heroes) ? get_posts(array(
    'post_type' => 'kpt_hero',
    'post_status' => 'publish',
    'post__in' => $assigned_heroes,
    'orderby' => 'post__in',
    'posts_per_page' => -1,
)) : array();

?>

<!-- Homepage Hero -->
<section
    id="home-hero"
    class="relative overflow-hidden bg-kp-background"
    aria-label="<?php esc_attr_e('Introduction', 'kevinpirnie-v2'); ?>"
>

    <div
        id="hero-carousel"
        class="relative min-h-[540px] md:min-h-[640px]"
        aria-roledescription="carousel"
    >

        <?php if (! empty($heroes)) : ?>

            <?php foreach ($heroes as $index => $hero) : ?>

                <?php
                $hero_image = get_the_post_thumbnail_url($hero->ID, 'kp-hero');
                $active = 0 === $index;
                ?>

                <div
                    class="kp-hero-slide absolute inset-0 <?php echo $active ? '' : 'hidden'; ?>"
                    data-slide="<?php echo esc_attr($index); ?>"
                    aria-roledescription="slide"
                    aria-label="<?php echo esc_attr(sprintf(
                        __('Slide %1$d of %2$d', 'kevinpirnie-v2'),
                        $index + 1,
                        count($heroes)
                    )); ?>"
                    <?php echo $active ? '' : 'hidden'; ?>
                >

                    <?php if ($hero_image) : ?>

                        <div class="absolute inset-0">
                            <img
                                src="<?php echo esc_url($hero_image); ?>"
                                alt=""
                                class="h-full w-full object-cover"
                                <?php echo $active ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
                            >
                        </div>

                    <?php endif; ?>

                    <div class="kp-hero-overlay absolute inset-0"></div>

                    <div class="kp-container relative z-10 flex min-h-[540px] items-center py-20 md:min-h-[640px]">

                        <div class="max-w-3xl">

                            <?php if ($active) : ?>
                                <h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl">
                                    <?php echo esc_html($hero->post_title); ?>
                                </h1>
                            <?php else : ?>
                                <h2 class="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl">
                                    <?php echo esc_html($hero->post_title); ?>
                                </h2>
                            <?php endif; ?>

                            <div class="mb-8 max-w-2xl text-lg leading-8 text-kp-text/90 md:text-xl">
                                <?php echo wp_kses_post(apply_filters('the_content', $hero->post_content)); ?>
                            </div>

                            <a
                                href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>"
                                class="kp-btn kp-btn-primary"
                            >
                                <?php esc_html_e('Get in Touch', 'kevinpirnie-v2'); ?>
                            </a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else : ?>

            <!-- Fallback Hero -->
            <div class="kp-container flex min-h-[540px] items-center py-20 md:min-h-[640px]">

                <div class="max-w-3xl">

                    <p class="mb-5 font-semibold uppercase tracking-widest text-kp-light-blue">
                        WordPress / DevOps / Hosting
                    </p>

                    <h1 class="mb-6 text-4xl font-bold leading-tight tracking-tight md:text-6xl">
                        Building Better Digital Experiences
                    </h1>

                    <p class="mb-8 max-w-2xl text-lg leading-8 text-kp-muted md:text-xl">
                        Professional WordPress development, reliable hosting,
                        and DevOps solutions focused on performance,
                        security, and long-term maintainability.
                    </p>

                    <a
                        href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>"
                        class="kp-btn kp-btn-primary"
                    >
                        <?php esc_html_e('Get in Touch', 'kevinpirnie-v2'); ?>
                    </a>

                </div>

            </div>

        <?php endif; ?>

        <?php if (count($heroes) > 1) : ?>

            <!-- Carousel Controls -->
            <div class="absolute bottom-8 right-4 z-20 flex gap-3 sm:right-8">

                <button
                    type="button"
                    id="hero-prev"
                    class="flex h-11 w-11 items-center justify-center rounded-lg border border-white/20 bg-kp-background/70 text-white backdrop-blur-md hover:bg-kp-surface"
                    aria-label="<?php esc_attr_e('Previous slide', 'kevinpirnie-v2'); ?>"
                >
                    <span aria-hidden="true">&larr;</span>
                </button>

                <button
                    type="button"
                    id="hero-next"
                    class="flex h-11 w-11 items-center justify-center rounded-lg border border-white/20 bg-kp-background/70 text-white backdrop-blur-md hover:bg-kp-surface"
                    aria-label="<?php esc_attr_e('Next slide', 'kevinpirnie-v2'); ?>"
                >
                    <span aria-hidden="true">&rarr;</span>
                </button>

            </div>

        <?php endif; ?>

    </div>

</section>

<!-- Services -->
<section id="services" class="py-20 md:py-28">

    <div class="kp-container">

        <div class="mb-12 max-w-2xl">

            <h2 class="kp-section-title">
                What I Do
            </h2>

            <p class="kp-section-description">
                Practical solutions for building, hosting, and maintaining
                reliable websites and infrastructure.
            </p>

        </div>

        <div class="grid gap-6 md:grid-cols-3">

            <?php
            $services = array(
                array(
                    'title' => 'WordPress Development',
                    'description' => 'Custom WordPress solutions built for speed, usability, security, and maintainability.',
                ),
                array(
                    'title' => 'DevOps & Infrastructure',
                    'description' => 'Deployment workflows, server management, automation, and infrastructure optimization.',
                ),
                array(
                    'title' => 'Hosting & Support',
                    'description' => 'Reliable hosting environments, troubleshooting, performance tuning, and ongoing maintenance.',
                ),
            );
            ?>

            <?php foreach ($services as $service) : ?>

                <article class="kp-card">

                    <div class="mb-5 h-1 w-12 rounded-full bg-kp-orange"></div>

                    <h3 class="mb-4 text-xl font-bold text-kp-text">
                        <?php echo esc_html($service['title']); ?>
                    </h3>

                    <p class="leading-7 text-kp-muted">
                        <?php echo esc_html($service['description']); ?>
                    </p>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<!-- Homepage Content -->
<?php if (have_posts()) : ?>

    <?php while (have_posts()) : the_post(); ?>

        <?php if (trim(get_the_content()) !== '') : ?>

            <section id="about" class="border-y border-kp-border bg-kp-surface py-20">

                <div class="kp-container">

                    <div class="mx-auto max-w-4xl">

                        <div class="kp-home-content text-lg leading-8 text-kp-muted">
                            <?php the_content(); ?>
                        </div>

                    </div>

                </div>

            </section>

        <?php endif; ?>

    <?php endwhile; ?>

<?php endif; ?>

<!-- Featured Portfolio -->
<?php
$portfolio = new WP_Query(array(
    'post_type' => 'kpt_portfolio',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'no_found_rows' => true,
));
?>

<?php if ($portfolio->have_posts()) : ?>

    <section id="portfolio" class="py-20 md:py-28">

        <div class="kp-container">

            <div class="mb-12">

                <h2 class="kp-section-title">
                    Featured Work
                </h2>

                <p class="kp-section-description">
                    A selection of projects and technical solutions.
                </p>

            </div>

            <div class="grid gap-6 md:grid-cols-3">

                <?php while ($portfolio->have_posts()) : $portfolio->the_post(); ?>

                    <article <?php post_class('kp-card overflow-hidden'); ?>>

                        <?php if (has_post_thumbnail()) : ?>

                            <a href="<?php the_permalink(); ?>" class="mb-5 block overflow-hidden rounded-lg">

                                <?php
                                the_post_thumbnail('kp-portfolio-card', array(
                                    'class' => 'aspect-[4/3] w-full object-cover',
                                    'loading' => 'lazy',
                                ));
                                ?>

                            </a>

                        <?php endif; ?>

                        <h3 class="mb-3 text-xl font-bold">
                            <a href="<?php the_permalink(); ?>" class="hover:text-kp-light-blue">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <p class="mb-5 text-sm leading-7 text-kp-muted">
                            <?php echo esc_html(get_the_excerpt()); ?>
                        </p>

                        <a href="<?php the_permalink(); ?>" class="font-semibold text-kp-light-blue hover:text-kp-text">
                            View Project &rarr;
                        </a>

                    </article>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

<?php endif; ?>

<?php wp_reset_postdata(); ?>

<!-- Latest Articles -->
<?php
$articles = new WP_Query(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'ignore_sticky_posts' => true,
    'no_found_rows' => true,
));
?>

<?php if ($articles->have_posts()) : ?>

    <section id="articles" class="border-y border-kp-border bg-kp-surface py-20 md:py-28">

        <div class="kp-container">

            <div class="mb-12">

                <h2 class="kp-section-title">
                    Latest Articles
                </h2>

                <p class="kp-section-description">
                    Thoughts, tutorials, and practical insights
                    from the world of development and infrastructure.
                </p>

            </div>

            <div class="grid gap-6 md:grid-cols-3">

                <?php while ($articles->have_posts()) : $articles->the_post(); ?>

                    <article <?php post_class('kp-card flex flex-col'); ?>>

                        <?php if (has_post_thumbnail()) : ?>

                            <a href="<?php the_permalink(); ?>" class="mb-5 block overflow-hidden rounded-lg">

                                <?php
                                the_post_thumbnail('kp-article-card', array(
                                    'class' => 'aspect-[3/2] w-full object-cover',
                                    'loading' => 'lazy',
                                ));
                                ?>

                            </a>

                        <?php endif; ?>

                        <time
                            class="mb-3 text-sm text-kp-muted"
                            datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                        >
                            <?php echo esc_html(get_the_date()); ?>
                        </time>

                        <h3 class="mb-3 text-xl font-bold">
                            <a href="<?php the_permalink(); ?>" class="hover:text-kp-light-blue">
                                <?php the_title(); ?>
                            </a>
                        </h3>

                        <p class="mb-5 flex-1 text-sm leading-7 text-kp-muted">
                            <?php echo esc_html(get_the_excerpt()); ?>
                        </p>

                        <a href="<?php the_permalink(); ?>" class="font-semibold text-kp-light-blue hover:text-kp-text">
                            Read Article &rarr;
                        </a>

                    </article>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

<?php endif; ?>

<?php wp_reset_postdata(); ?>

<!-- Contact CTA -->
<section id="contact-cta" class="py-20 md:py-28">

    <div class="kp-container">

        <div class="kp-glass mx-auto max-w-5xl rounded-xl px-6 py-12 text-center shadow-kp-card md:px-12 md:py-16">

            <h2 class="mb-5 text-3xl font-bold tracking-tight md:text-4xl">
                Have a Project in Mind?
            </h2>

            <p class="mx-auto mb-8 max-w-2xl text-lg leading-8 text-kp-muted">
                Whether you need WordPress development, infrastructure
                improvements, or dependable technical support,
                let's talk about your project.
            </p>

            <a
                href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>"
                class="kp-btn kp-btn-primary"
            >
                Let's Talk
            </a>

        </div>

    </div>

</section>

<?php

// get the footer
get_footer();
