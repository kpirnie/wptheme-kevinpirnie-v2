
<?php

/**
 * Theme Assets Class
 *
 * This file manages frontend stylesheets and JavaScript.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT_Assets')) {

    /**
     * KPT_Assets
     *
     * Register and enqueue theme assets.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_Assets
    {

        /**
         * enqueue
         *
         * Setup frontend asset hooks.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function enqueue(): void
        {

            // only enqueue assets on the frontend
            add_action('wp_enqueue_scripts', array($this, 'frontend_assets'));

        }

        /**
         * frontend_assets
         *
         * Enqueue compiled CSS and JavaScript.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function frontend_assets(): void
        {

            // pull in our CSS
            $this->pull_css();

            // pull in our JavaScript
            $this->pull_js();

        }

        /**
         * pull_css
         *
         * Enqueue the compiled Tailwind stylesheet.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function pull_css(): void
        {

            // compiled stylesheet
            $file = 'assets/css/theme.min.css';
            $path = KPT_PATH . $file;

            // make sure the compiled file exists
            if (! file_exists($path)) {
                return;
            }

            // use the file modification time for cache busting
            $version = filemtime($path);

            // enqueue our theme stylesheet
            wp_enqueue_style(
                'kpt-theme',
                get_template_directory_uri() . '/' . $file,
                array(),
                $version
            );

        }

        /**
         * pull_js
         *
         * Enqueue the compiled JavaScript bundle.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function pull_js(): void
        {

            // compiled JavaScript
            $file = 'assets/js/theme.min.js';
            $path = KPT_PATH . $file;

            // make sure the compiled file exists
            if (! file_exists($path)) {
                return;
            }

            // use the file modification time for cache busting
            $version = filemtime($path);

            // enqueue our theme JavaScript
            wp_enqueue_script(
                'kpt-theme',
                get_template_directory_uri() . '/' . $file,
                array(),
                $version,
                array(
                    'strategy' => 'defer',
                    'in_footer' => true,
                )
            );

        }

    }

}
