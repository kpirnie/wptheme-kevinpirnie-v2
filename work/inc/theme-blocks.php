<?php
/** Reusable Gutenberg blocks retaining the existing block identifiers. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Blocks')) {
    class KPT_Blocks
    {
        public function register_blocks(): void
        {
            add_filter('block_categories_all', function ($categories) {
                array_unshift($categories, array('slug' => 'kpt-blocks', 'title' => __('KP Theme Blocks', 'kevinpirnie-v2')));
                return $categories;
            });
            add_action('init', array($this, 'register_all'));
        }
        public function register_all(): void
        {
            if (! function_exists('register_block_type')) return;
            $this->editor('cta', array('wp-blocks','wp-element','wp-block-editor','wp-components','wp-data','wp-server-side-render'));
            $this->editor('portfolio', array('wp-blocks','wp-element','wp-block-editor','wp-components','wp-server-side-render'));
            $this->editor('contact-form', array('wp-blocks','wp-element','wp-components'));
            register_block_type('kpt/cta-block', array(
                'api_version' => 2, 'title' => 'CTA Display', 'category' => 'kpt-blocks', 'icon' => 'megaphone',
                'attributes' => array('ctaId' => array('type' => 'number', 'default' => 0)),
                'editor_script' => 'kpt-cta-editor', 'render_callback' => array($this, 'render_cta'),
            ));
            register_block_type('kpt/portfolio-block', array(
                'api_version' => 2, 'title' => 'Portfolio', 'category' => 'kpt-blocks', 'icon' => 'images-alt2',
                'attributes' => array('imageSize' => array('type' => 'string', 'default' => 'portfolio')),
                'editor_script' => 'kpt-portfolio-editor', 'render_callback' => array($this, 'render_portfolio'),
            ));
            register_block_type('kpt/contact-form-block', array(
                'api_version' => 2, 'title' => 'Contact Form', 'category' => 'kpt-blocks', 'icon' => 'email',
                'editor_script' => 'kpt-contact-form-editor', 'render_callback' => array($this, 'render_contact'),
            ));
        }
        private function editor(string $name, array $dependencies): void
        {
            $file = 'assets/js/blocks/' . $name . '-block.js';
            if (file_exists(KPT_PATH . $file)) {
                wp_register_script('kpt-' . $name . '-editor', get_template_directory_uri() . '/' . $file,
                    $dependencies, filemtime(KPT_PATH . $file), true);
            }
        }
        public function render_cta(array $attributes): string
        {
            $id = absint($attributes['ctaId'] ?? 0);
            if (! $id || 'kpt_cta' !== get_post_type($id) || 'publish' !== get_post_status($id)) return '';
            ob_start();
            get_template_part('partials/cpts/cta', null, array('cta_id' => $id));
            return ob_get_clean();
        }
        public function render_portfolio(array $attributes): string
        {
            $sizes = array('portfolio' => 'kp-portfolio', 'portfolio-featured' => 'kp-portfolio',
                'portfolio-grid' => 'kp-portfolio-card', 'kp-portfolio' => 'kp-portfolio', 'kp-portfolio-card' => 'kp-portfolio-card');
            $size = $sizes[$attributes['imageSize'] ?? 'portfolio'] ?? 'kp-portfolio';
            ob_start();
            get_template_part('partials/cpts/portfolio', null, array('image_size' => $size));
            return ob_get_clean();
        }
        public function render_contact(): string
        {
            if (defined('REST_REQUEST') && REST_REQUEST && is_user_logged_in()) {
                return '<p>' . esc_html__('Contact form appears on the frontend.', 'kevinpirnie-v2') . '</p>';
            }
            ob_start();
            get_template_part('partials/contact');
            return ob_get_clean();
        }
    }
}
