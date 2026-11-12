<?php
/** Sticky navigation. */
defined('ABSPATH') || die('No direct script access allowed');
$logo_url = get_template_directory_uri() . '/assets/kevinpirnie-logo-color.svg';
?>
<header id="main-header" class="kp-header">
    <div class="kp-container">
        <div class="flex min-h-20 items-center justify-between gap-4">
            <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>" class="block shrink-0">
                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="890" height="242" class="h-auto w-40 sm:w-48 lg:w-56" fetchpriority="high">
            </a>
            <nav class="hidden lg:flex lg:items-center" aria-label="<?php esc_attr_e('Primary Navigation', 'kevinpirnie-v2'); ?>">
                <?php if (has_nav_menu('primary')) { wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'kp-desktop-menu', 'fallback_cb' => false, 'depth' => 5)); } ?>
            </nav>
            <div class="flex items-center gap-2">
                <button id="header-search-toggle" type="button" class="flex h-11 w-11 items-center justify-center rounded-lg text-kp-muted hover:bg-kp-surface-raised hover:text-kp-text" aria-label="<?php esc_attr_e('Toggle search', 'kevinpirnie-v2'); ?>" aria-controls="header-search" aria-expanded="false">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><circle cx="10.75" cy="10.75" r="6.75"/><path d="m16 16 5 5"/></svg>
                </button>
                <button id="mobile-menu-toggle" type="button" class="flex h-11 w-11 items-center justify-center rounded-lg border border-kp-border text-kp-text hover:bg-kp-surface-raised lg:hidden" aria-label="<?php esc_attr_e('Toggle navigation', 'kevinpirnie-v2'); ?>" aria-controls="mobile-navigation" aria-expanded="false">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    <div id="header-search" class="hidden border-t border-kp-border bg-kp-background/95 backdrop-blur-md" hidden>
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="kp-container flex max-w-3xl gap-3 py-5">
            <label for="header-search-input" class="sr-only"><?php esc_html_e('Search the website', 'kevinpirnie-v2'); ?></label>
            <input id="header-search-input" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search articles, projects, and more..." class="min-w-0 flex-1 rounded-lg border border-kp-border bg-kp-surface px-4 py-3 text-kp-text">
            <button type="submit" class="kp-btn kp-btn-primary"><?php esc_html_e('Search', 'kevinpirnie-v2'); ?></button>
        </form>
    </div>
    <nav id="mobile-navigation" class="hidden border-t border-kp-border bg-kp-background lg:hidden" hidden aria-label="<?php esc_attr_e('Mobile Navigation', 'kevinpirnie-v2'); ?>">
        <div class="kp-container py-4">
            <?php if (has_nav_menu('primary')) { wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'kp-mobile-menu', 'fallback_cb' => false, 'depth' => 5)); } ?>
        </div>
    </nav>
</header>
