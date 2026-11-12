
<?php

/**
 * Main Navigation
 *
 * Sticky navigation header with responsive menus.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// original, unchanged SVG logo
$uploads = wp_get_upload_dir();
$logo_url = $uploads['baseurl'] . '/2025/10/kevinpirnie-logo-color.svg';

?>
<header id="main-header" class="kp-header">

    <div class="kp-container">

        <div class="flex min-h-20 items-center justify-between gap-6">

            <!-- Site Branding -->
            <div class="flex shrink-0 items-center">

                <a
                    href="<?php echo esc_url(home_url('/')); ?>"
                    class="block"
                    aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>"
                >
                    <img
                        src="<?php echo esc_url($logo_url); ?>"
                        alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        width="890"
                        height="242"
                        class="h-auto w-48 md:w-56 lg:w-64"
                        fetchpriority="high"
                    >
                </a>

            </div>

            <!-- Desktop Navigation -->
            <nav
                id="desktop-navigation"
                class="hidden lg:flex lg:items-center"
                aria-label="<?php esc_attr_e('Primary Navigation', 'kevinpirnie-v2'); ?>"
            >

                <?php
                if (has_nav_menu('primary')) {

                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false,
                        'menu_id' => 'primary-menu',
                        'menu_class' => 'kp-desktop-menu',
                        'fallback_cb' => false,
                        'depth' => 5,
                    ));

                }
                ?>

            </nav>

            <!-- Search Toggle -->
            <button
                id="header-search-toggle"
                type="button"
                class="inline-flex h-11 w-11 shrink-0 items-center justify-center
                       rounded-lg text-kp-muted transition-colors
                       hover:bg-kp-surface-raised hover:text-kp-text"
                aria-label="<?php esc_attr_e('Toggle search', 'kevinpirnie-v2'); ?>"
                aria-controls="header-search"
                aria-expanded="false"
            >
                <svg
                    class="h-5 w-5"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.75"
                    aria-hidden="true"
                >
                    <circle cx="10.75" cy="10.75" r="6.75" />
                    <path stroke-linecap="round" d="m16 16 5 5" />
                </svg>
            </button>

            <!-- Mobile Menu Toggle -->
            <button
                id="mobile-menu-toggle"
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center
                       rounded-lg border border-kp-border
                       text-kp-text transition-colors
                       hover:bg-kp-surface-raised
                       lg:hidden"
                aria-label="<?php esc_attr_e('Toggle navigation', 'kevinpirnie-v2'); ?>"
                aria-controls="mobile-navigation"
                aria-expanded="false"
            >

                <svg
                    class="h-6 w-6"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.75"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

            </button>

        </div>

    </div>

    <!-- Header Search -->
    <div
        id="header-search"
        class="hidden border-t border-kp-border bg-kp-background/95 backdrop-blur-md"
        hidden
    >

        <div class="kp-container py-5">

            <form
                role="search"
                method="get"
                action="<?php echo esc_url(home_url('/')); ?>"
                class="mx-auto flex max-w-3xl gap-3"
            >

                <label for="header-search-input" class="sr-only">
                    <?php esc_html_e('Search the website', 'kevinpirnie-v2'); ?>
                </label>

                <input
                    id="header-search-input"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr(get_search_query()); ?>"
                    placeholder="<?php esc_attr_e('Search articles, projects, and more...', 'kevinpirnie-v2'); ?>"
                    class="min-w-0 flex-1 rounded-lg border border-kp-border
                           bg-kp-surface px-4 py-3 text-kp-text
                           placeholder:text-kp-muted
                           focus:border-kp-light-blue focus:outline-none"
                >

                <button type="submit" class="kp-btn kp-btn-primary">
                    <?php esc_html_e('Search', 'kevinpirnie-v2'); ?>
                </button>

            </form>

        </div>

    </div>

    <!-- Mobile Navigation -->
    <nav
        id="mobile-navigation"
        class="hidden border-t border-kp-border bg-kp-background lg:hidden"
        aria-label="<?php esc_attr_e('Mobile Navigation', 'kevinpirnie-v2'); ?>"
        hidden
    >

        <div class="kp-container py-4">

            <?php
            if (has_nav_menu('primary')) {

                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_id' => 'mobile-menu',
                    'menu_class' => 'kp-mobile-menu',
                    'fallback_cb' => false,
                    'depth' => 5,
                ));

            }
            ?>

        </div>

    </nav>

</header>
