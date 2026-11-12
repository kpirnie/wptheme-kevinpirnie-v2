
<?php

/**
 * Theme Class
 *
 * This is the primary theme class responsible for
 * initializing the theme's functionality.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT')) {

    /**
     * KPT
     *
     * The primary theme class.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT
    {

        /**
         * init
         *
         * Initialize the theme's functionality.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function init(): void
        {

            // hold our classes and their initialization methods
            $theme_classes = array(
                array('class' => 'KPT_Supports', 'method' => 'the_theme_supports'),
                array('class' => 'KPT_Assets', 'method' => 'enqueue'),
                array('class' => 'KPT_Menus', 'method' => 'register_menus'),
                array('class' => 'KPT_CPTs', 'method' => 'add_cpts'),
                array('class' => 'KPT_Blocks', 'method' => 'register_blocks'),
                array('class' => 'KPT_Settings', 'method' => 'add_settings'),
                array('class' => 'KPT_Contact_Form', 'method' => 'init'),
            );

            // loop over each item
            foreach ($theme_classes as $item) {

                // make sure the class exists
                if (! class_exists($item['class'])) {
                    continue;
                }

                // instantiate the class
                $instance = new $item['class']();

                // make sure the method exists
                if (method_exists($instance, $item['method'])) {

                    // initialize the functionality
                    $instance->{$item['method']}();

                }

                // clean up
                unset($instance);

            }

            // clean up
            unset($theme_classes);

        }

    }

}
