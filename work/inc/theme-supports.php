
<?php

/**
 * Theme Supports Class
 *
 * This file manages WordPress theme features
 * and custom image sizes.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT_Supports')) {

    /**
     * KPT_Supports
     *
     * Register WordPress theme functionality.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_Supports
    {

        /**
         * the_theme_supports
         *
         * Initialize WordPress theme features.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function the_theme_supports(): void
        {

            // register theme supports
            $this->theme_supports();

            // register custom image sizes
            $this->image_sizes();

        }

        /**
         * theme_supports
         *
         * Enable WordPress theme features.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function theme_supports(): void
        {

            // WordPress manages the document title
            add_theme_support('title-tag');

            // enable featured images
            add_theme_support('post-thumbnails');

            // enable automatic feed links
            add_theme_support('automatic-feed-links');

            // enable responsive embeds
            add_theme_support('responsive-embeds');

            // enable wide and full-width blocks
            add_theme_support('align-wide');

            // enable editor styles
            add_theme_support('editor-styles');

            // enable custom logo support
            add_theme_support('custom-logo', array(
                'height' => 242,
                'width' => 890,
                'flex-height' => true,
                'flex-width' => true,
                'unlink-homepage-logo' => true,
            ));

            // enable HTML5 markup
            add_theme_support('html5', array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ));

            // enable Gutenberg appearance tools
            add_theme_support('appearance-tools');

            // enable block spacing controls
            add_theme_support('custom-spacing');

            // enable custom units
            add_theme_support('custom-units', array(
                'px',
                'em',
                'rem',
                'vh',
                'vw',
            ));

        }

        /**
         * image_sizes
         *
         * Register optimized image sizes for the theme.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function image_sizes(): void
        {

            // homepage carousel
            add_image_size('kp-hero', 1920, 800, true);

            // inner page headers
            add_image_size('kp-page-hero', 1920, 480, true);

            // article featured images
            add_image_size('kp-article', 960, 540, true);

            // article listing thumbnails
            add_image_size('kp-article-card', 600, 400, true);

            // portfolio featured images
            add_image_size('kp-portfolio', 960, 720, true);

            // portfolio listing thumbnails
            add_image_size('kp-portfolio-card', 600, 450, true);

            // make custom image sizes available in the editor
            add_filter('image_size_names_choose', function ($sizes) {

                return array_merge($sizes, array(
                    'kp-hero' => __('Homepage Hero (1920x800)', 'kevinpirnie-v2'),
                    'kp-page-hero' => __('Page Hero (1920x480)', 'kevinpirnie-v2'),
                    'kp-article' => __('Article Featured (960x540)', 'kevinpirnie-v2'),
                    'kp-article-card' => __('Article Card (600x400)', 'kevinpirnie-v2'),
                    'kp-portfolio' => __('Portfolio Featured (960x720)', 'kevinpirnie-v2'),
                    'kp-portfolio-card' => __('Portfolio Card (600x450)', 'kevinpirnie-v2'),
                ));

            });

        }

    }

}
