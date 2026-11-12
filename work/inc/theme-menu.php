<?php
/** WordPress navigation menu locations. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Menus')) {
    class KPT_Menus
    {
        public function register_menus(): void
        {
            register_nav_menus(array(
                'primary' => __('Primary Navigation', 'kevinpirnie-v2'),
                'top' => __('Top Header Navigation', 'kevinpirnie-v2'),
                'social' => __('Social Navigation', 'kevinpirnie-v2'),
                'bottom' => __('Footer Navigation', 'kevinpirnie-v2'),
            ));
        }
    }
}
