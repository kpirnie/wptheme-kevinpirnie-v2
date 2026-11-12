
<?php

/**
 * Theme Menu Class
 *
 * This file manages WordPress navigation menu locations.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT_Menus')) {

    /**
     * KPT_Menus
     *
     * Register the theme's navigation menus.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_Menus
    {

        /**
         * register_menus
         *
         * Register WordPress navigation menu locations.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function register_menus(): void
        {

            // register our menu locations
            register_nav_menus(array(
                'primary' => __('Primary Navigation', 'kevinpirnie-v2'),
                'top' => __('Top Header Navigation', 'kevinpirnie-v2'),
                'social' => __('Social Navigation', 'kevinpirnie-v2'),
                'bottom' => __('Footer Navigation', 'kevinpirnie-v2'),
            ));

        }

    }

}
