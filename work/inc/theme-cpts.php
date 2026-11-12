
<?php

/**
 * Theme Custom Post Types
 *
 * Registers the portfolio, hero, and CTA post types.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT_CPTs')) {

    /**
     * KPT_CPTs
     *
     * Manage the theme's custom post types.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_CPTs
    {

        /**
         * add_cpts
         *
         * Register our custom post types.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function add_cpts(): void
        {

            // register our post types on init
            add_action('init', function () {

                $this->create_portfolio();
                $this->create_hero();
                $this->create_cta();

            });

        }

        /**
         * create_portfolio
         *
         * Register portfolio projects.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function create_portfolio(): void
        {

            register_post_type('kpt_portfolio', array(
                'labels' => array(
                    'name' => __('Portfolio', 'kevinpirnie-v2'),
                    'singular_name' => __('Portfolio Item', 'kevinpirnie-v2'),
                    'add_new' => __('Add New', 'kevinpirnie-v2'),
                    'add_new_item' => __('Add New Portfolio Item', 'kevinpirnie-v2'),
                    'edit_item' => __('Edit Portfolio Item', 'kevinpirnie-v2'),
                    'new_item' => __('New Portfolio Item', 'kevinpirnie-v2'),
                    'view_item' => __('View Portfolio Item', 'kevinpirnie-v2'),
                    'all_items' => __('All Portfolio Items', 'kevinpirnie-v2'),
                    'search_items' => __('Search Portfolio', 'kevinpirnie-v2'),
                    'not_found' => __('No portfolio items found', 'kevinpirnie-v2'),
                    'menu_name' => __('Portfolio', 'kevinpirnie-v2'),
                ),
                'public' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-portfolio',
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'excerpt',
                    'revisions',
                ),
                'has_archive' => false,
                'exclude_from_search' => false,
                'show_in_nav_menus' => false,
                'show_in_rest' => true,
                'rewrite' => array(
                    'slug' => 'about-kevin-pirnie/enterprise-web-solutions-and-portfolio',
                    'with_front' => false,
                ),
            ));

        }

        /**
         * create_hero
         *
         * Register homepage and page heroes.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function create_hero(): void
        {

            register_post_type('kpt_hero', array(
                'labels' => array(
                    'name' => __('Page Heroes', 'kevinpirnie-v2'),
                    'singular_name' => __('Hero', 'kevinpirnie-v2'),
                    'add_new' => __('Add New', 'kevinpirnie-v2'),
                    'add_new_item' => __('Add New Hero', 'kevinpirnie-v2'),
                    'edit_item' => __('Edit Hero', 'kevinpirnie-v2'),
                    'new_item' => __('New Hero', 'kevinpirnie-v2'),
                    'all_items' => __('All Heroes', 'kevinpirnie-v2'),
                    'menu_name' => __('Page Heroes', 'kevinpirnie-v2'),
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-format-gallery',
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'revisions',
                ),
                'has_archive' => false,
                'rewrite' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'show_in_rest' => true,
            ));

        }

        /**
         * create_cta
         *
         * Register reusable calls to action.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function create_cta(): void
        {

            register_post_type('kpt_cta', array(
                'labels' => array(
                    'name' => __('Call To Actions', 'kevinpirnie-v2'),
                    'singular_name' => __('Call To Action', 'kevinpirnie-v2'),
                    'add_new' => __('Add New', 'kevinpirnie-v2'),
                    'add_new_item' => __('Add New CTA', 'kevinpirnie-v2'),
                    'edit_item' => __('Edit CTA', 'kevinpirnie-v2'),
                    'new_item' => __('New CTA', 'kevinpirnie-v2'),
                    'all_items' => __('All CTAs', 'kevinpirnie-v2'),
                    'menu_name' => __('CTAs', 'kevinpirnie-v2'),
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-megaphone',
                'supports' => array(
                    'title',
                    'editor',
                    'thumbnail',
                    'revisions',
                ),
                'has_archive' => false,
                'rewrite' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'show_in_rest' => true,
            ));

        }

    }

}
