<?php
/** Site footer. */
defined('ABSPATH') || die('No direct script access allowed');
?>
</main>
<footer id="site-footer" class="border-t border-kp-border bg-kp-surface">
    <div class="kp-container">
        <div class="grid gap-10 py-14 md:grid-cols-3 lg:gap-16">
            <div><h2 class="mb-4 text-lg font-bold text-kp-text"><?php bloginfo('name'); ?></h2><p class="text-sm leading-7 text-kp-muted">WordPress development, DevOps, hosting, and technical solutions built for performance, reliability, and security.</p></div>
            <div><h2 class="mb-4 text-lg font-bold text-kp-text"><?php esc_html_e('Quick Links', 'kevinpirnie-v2'); ?></h2><?php if (has_nav_menu('bottom')) { wp_nav_menu(array('theme_location' => 'bottom', 'container' => 'nav', 'container_aria_label' => __('Footer Navigation', 'kevinpirnie-v2'), 'menu_class' => 'flex flex-col gap-3 text-sm text-kp-muted', 'fallback_cb' => false, 'depth' => 1)); } ?></div>
            <div><h2 class="mb-4 text-lg font-bold text-kp-text"><?php esc_html_e('Get in Touch', 'kevinpirnie-v2'); ?></h2><p class="mb-5 text-sm leading-7 text-kp-muted">Have a project in mind or need help with your WordPress infrastructure?</p><a href="<?php echo esc_url(home_url('/about-kevin-pirnie/lets-talk/')); ?>" class="kp-btn kp-btn-primary">Contact Me</a></div>
        </div>
        <div class="flex flex-col items-center justify-between gap-5 border-t border-kp-border py-6 md:flex-row">
            <p class="text-sm text-kp-muted">&copy; <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. All rights reserved.</p>
            <?php if (has_nav_menu('social')) { wp_nav_menu(array('theme_location' => 'social', 'container' => 'nav', 'container_aria_label' => __('Social Navigation', 'kevinpirnie-v2'), 'menu_class' => 'flex flex-wrap items-center gap-5 text-sm text-kp-muted', 'fallback_cb' => false, 'depth' => 1)); } ?>
        </div>
    </div>
</footer>
<?php
$cookie_consent = isset($_COOKIE['kp_cookie_consent']) && is_string($_COOKIE['kp_cookie_consent']) ? sanitize_text_field(wp_unslash($_COOKIE['kp_cookie_consent'])) : '';
if (! in_array($cookie_consent, array('accepted', 'declined'), true)) : ?>
<div id="cookie-consent" class="fixed inset-x-0 bottom-0 z-50 border-t border-kp-border bg-kp-surface/95 shadow-kp-header backdrop-blur-md" role="region" aria-label="Cookie consent">
    <div class="kp-container flex flex-col gap-5 py-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="max-w-3xl"><h2 class="mb-2 text-lg font-bold">Your Privacy Matters</h2><p class="text-sm leading-7 text-kp-muted">This website uses optional cookies for analytics and related services. <a href="<?php echo esc_url(home_url('/about-kevin-pirnie/cookie-policy/')); ?>" class="text-kp-light-blue underline">Cookie Policy</a></p></div>
        <div class="flex gap-3"><button type="button" id="cookie-decline" class="kp-btn kp-btn-secondary">Decline</button><button type="button" id="cookie-accept" class="kp-btn kp-btn-primary">Accept</button></div>
    </div>
</div>
<?php endif; ?>
<button id="scroll-to-top" type="button" class="pointer-events-none fixed bottom-6 right-6 z-40 flex h-12 w-12 translate-y-4 items-center justify-center rounded-lg border border-kp-border bg-kp-surface/90 text-kp-text opacity-0 shadow-kp-card backdrop-blur-md transition-all duration-200 md:bottom-8 md:right-8" aria-label="Scroll to top" aria-hidden="true" tabindex="-1">
    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m6 15 6-6 6 6"/></svg>
</button>
<?php wp_footer(); ?>
</body></html>
