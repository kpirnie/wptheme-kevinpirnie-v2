<?php
/** WordPress navigation menu locations. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Menus')) {
    class KPT_Menus
    {

        public function social_menu_item_title(string $title, \WP_Post $item, \stdClass $args): string
        {
            if (($args->theme_location ?? '') !== 'social') {
                return $title;
            }

            $host = strtolower((string) wp_parse_url($item->url, PHP_URL_HOST));
            $host = preg_replace('/^www\./', '', $host);

            $icon = match ($host) {
                'facebook.com' => 'facebook-f',
                'x.com', 'twitter.com' => 'x-twitter',
                'linkedin.com' => 'linkedin-in',
                'reddit.com' => 'reddit-alien',
                'g.dev' => 'google',
                'profiles.wordpress.org' => 'wordpress-simple',
                'flipboard.com' => 'flipboard',
                'stackoverflow.com' => 'stack-overflow',
                'github.com' => 'github',
                'gist.github.com' => 'github-alt',
                'packagist.org' => 'php',
                'hub.docker.com' => 'docker',
                'xdaforums.com' => 'android',
                default => '',
            };

            $label = trim(wp_strip_all_tags($title));

            if ($icon === '' || $label === '') {
                return $title;
            }

            $sprite = get_theme_file_uri('assets/social-icons.svg') . '#' . $icon;

            return '<svg class="inline-block h-4 w-4 fill-current align-middle" aria-hidden="true" focusable="false">'
                . '<use href="' . esc_url($sprite) . '"></use></svg>'
                . '<span class="sr-only">' . esc_html($label) . '</span>';
        }

        public function register_menus(): void
        {
            register_nav_menus(array(
                'primary' => __('Primary Navigation', 'kevinpirnie-v2'),
                'top' => __('Top Header Navigation', 'kevinpirnie-v2'),
                'social' => __('Social Navigation', 'kevinpirnie-v2'),
                'bottom' => __('Footer Navigation', 'kevinpirnie-v2'),
            ));

            add_filter('nav_menu_item_title', array($this, 'social_menu_item_title'), 10, 3);
        }
    }
}
