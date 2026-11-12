
<?php

/**
 * Footer Template
 *
 * Renders the site footer and closes the document.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

?>

    </main>

    <!-- Site Footer -->
    <footer
        id="site-footer"
        class="border-t border-kp-border bg-kp-surface"
        role="contentinfo"
    >

        <div class="kp-container">

            <div class="grid gap-10 py-14 md:grid-cols-3 lg:gap-16">

                <!-- About -->
                <div>
                    <h2 class="mb-4 text-lg font-bold text-kp-text">
                        <?php bloginfo('name'); ?>
                    </h2>

                    <p class="max-w-sm text-sm leading-7 text-kp-muted">
                        WordPress development, DevOps, hosting, and technical
                        solutions built with performance, reliability,
                        and security in mind.
                    </p>
                </div>

                <!-- Footer Navigation -->
                <div>
                    <h2 class="mb-4 text-lg font-bold text-kp-text">
                        <?php esc_html_e('Quick Links', 'kevinpirnie-v2'); ?>
                    </h2>

                    <?php
                    if (has_nav_menu('bottom')) {

                        wp_nav_menu(array(
                            'theme_location' => 'bottom',
                            'container' => 'nav',
                            'container_aria_label' => __('Footer Navigation', 'kevinpirnie-v2'),
                            'menu_class' => 'flex flex-col gap-3 text-sm text-kp-muted',
                            'fallback_cb' => false,
                            'depth' => 1,
                        ));

                    }
                    ?>
                </div>

                <!-- Contact -->
                <div>
                    <h2 class="mb-4 text-lg font-bold text-kp-text">
                        <?php esc_html_e('Get in Touch', 'kevinpirnie-v2'); ?>
                    </h2>

                    <p class="mb-5 text-sm leading-7 text-kp-muted">
                        Have a project in mind or need help with your
                        WordPress infrastructure?
                    </p>

                    <a
                        href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>"
                        class="kp-btn kp-btn-primary"
                    >
                        <?php esc_html_e('Contact Me', 'kevinpirnie-v2'); ?>
                    </a>
                </div>

            </div>

            <!-- Footer Bottom -->
            <div class="flex flex-col items-center justify-between gap-5
                        border-t border-kp-border py-6
                        md:flex-row">

                <p class="text-center text-sm text-kp-muted md:text-left">
                    &copy; <?php echo esc_html(wp_date('Y')); ?>
                    <?php echo esc_html(get_bloginfo('name')); ?>.
                    <?php esc_html_e('All rights reserved.', 'kevinpirnie-v2'); ?>
                </p>

                <?php
                if (has_nav_menu('social')) {

                    wp_nav_menu(array(
                        'theme_location' => 'social',
                        'container' => 'nav',
                        'container_aria_label' => __('Social Navigation', 'kevinpirnie-v2'),
                        'menu_class' => 'flex flex-wrap items-center justify-center gap-5 text-sm text-kp-muted',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));

                }
                ?>

            </div>

        </div>

    </footer>

    <!-- Scroll to Top -->
    <button
        id="scroll-to-top"
        type="button"
        class="pointer-events-none fixed bottom-6 right-6 z-40
               flex h-12 w-12 translate-y-4 items-center justify-center
               rounded-lg border border-kp-border
               bg-kp-surface/90 text-kp-text
               opacity-0 shadow-kp-card backdrop-blur-md
               transition-all duration-200
               hover:border-kp-light-blue hover:text-kp-light-blue
               focus-visible:opacity-100
               md:bottom-8 md:right-8"
        aria-label="<?php esc_attr_e('Scroll to top', 'kevinpirnie-v2'); ?>"
        aria-hidden="true"
        tabindex="-1"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            aria-hidden="true"
        >
            <path d="m6 15 6-6 6 6" />
        </svg>
    </button>

    <?php wp_footer(); ?>

</body>
</html>
