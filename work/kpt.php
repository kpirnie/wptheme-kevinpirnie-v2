<?php
/** Primary theme initialization. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT')) {
    class KPT
    {
        public function init(): void
        {
            $theme_classes = array(
                array('class' => 'KPT_Supports', 'method' => 'the_theme_supports'),
                array('class' => 'KPT_Assets', 'method' => 'enqueue'),
                array('class' => 'KPT_Menus', 'method' => 'register_menus'),
                array('class' => 'KPT_CPTs', 'method' => 'add_cpts'),
                array('class' => 'KPT_Blocks', 'method' => 'register_blocks'),
                array('class' => 'KPT_Settings', 'method' => 'add_settings'),
                array('class' => 'KPT_Contact_Form', 'method' => 'init'),
            );
            foreach ($theme_classes as $item) {
                if (! class_exists($item['class'])) {
                    continue;
                }
                $instance = new $item['class']();
                if (method_exists($instance, $item['method'])) {
                    $instance->{$item['method']}();
                }
            }
        }
    }
}
