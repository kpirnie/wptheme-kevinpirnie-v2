<?php
/** Contact form submission storage, verification and moderation. */
defined('ABSPATH') || die('No direct script access allowed');
if (! class_exists('KPT_Contact_Form')) {
    class KPT_Contact_Form
    {
        public function init(): void
        {
            add_action('init', array($this, 'register_cpt'));
            add_action('template_redirect', array($this, 'process_submission'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_filter('manage_kpt_contact_posts_columns', array($this, 'columns'));
            add_action('manage_kpt_contact_posts_custom_column', array($this, 'column_content'), 10, 2);
            add_filter('post_row_actions', array($this, 'row_actions'), 10, 2);
            add_action('admin_action_kpt_toggle_spam', array($this, 'toggle_spam'));
        }
        public function register_cpt(): void
        {
            register_post_type('kpt_contact', array(
                'labels' => array('name' => 'Contact Submissions', 'singular_name' => 'Contact Submission', 'menu_name' => 'Contact Form'),
                'public' => false, 'show_ui' => true, 'show_in_menu' => true, 'show_in_rest' => false,
                'menu_icon' => 'dashicons-email', 'supports' => array('title'),
                'capability_type' => 'post', 'capabilities' => array('create_posts' => 'do_not_allow'),
                'map_meta_cap' => true, 'publicly_queryable' => false, 'exclude_from_search' => true,
            ));
            register_post_status('spam', array('label' => 'Spam', 'public' => false,
                'show_in_admin_all_list' => true, 'show_in_admin_status_list' => true,
                'label_count' => _n_noop('Spam <span class="count">(%s)</span>', 'Spam <span class="count">(%s)</span>')));
        }
        private function redirect(string $status): void
        {
            $url = wp_get_referer() ?: home_url('/about-kevin-pirnie/lets-talk/');
            wp_safe_redirect(add_query_arg('contact_status', $status, $url), 303);
            exit;
        }
        public function process_submission(): void
        {
            if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || ! isset($_POST['kpt_contact_submit'])) return;
            $nonce = sanitize_text_field(wp_unslash($_POST['kpt_contact_nonce'] ?? ''));
            if (! wp_verify_nonce($nonce, 'kpt_contact_form')) {
                wp_die('Security check failed.', '', array('response' => 403));
            }
            $ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? ''));
            $key = 'kpt_contact_limit_' . md5($ip);
            $count = (int) get_transient($key);
            if ($count >= 3) wp_die('Too many submissions. Please try later.', '', array('response' => 429));
            set_transient($key, $count + 1, HOUR_IN_SECONDS);
            if (! empty($_POST['website_url']) || ! empty($_POST['company_name'])) $this->redirect('received');
            // The browser supplies elapsed time to avoid caching a static server-side timestamp.
            $elapsed = absint($_POST['form_elapsed'] ?? 0);
            if ($elapsed < 3 || $elapsed > DAY_IN_SECONDS) $this->redirect('received');
            $first = sanitize_text_field(wp_unslash($_POST['first_name'] ?? ''));
            $last = sanitize_text_field(wp_unslash($_POST['last_name'] ?? ''));
            $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
            $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
            $url = esc_url_raw(wp_unslash($_POST['url'] ?? ''));
            $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
            if (! $first || ! $last || ! is_email($email) || ! $message || ! isset($_POST['privacy_consent'])) {
                wp_die('Please complete all required fields.', '', array('response' => 400));
            }
            $combined = $first . ' ' . $last . ' ' . $message;
            $patterns = array('/\[url=/i','/\[link=/i','/<a\s+href/i','/https?:\/\/[^\s]+\s+https?:\/\//i',
                '/viagra|cialis|casino|lottery|cryptocurrency|bitcoin|crypto wallet/i',
                '/click here.*https?:\/\//i','/dear\s+(sir|madam|friend)/i',
                '/\b(SEO|backlink|rank|traffic)\b.*\b(service|offer|guarantee)/i');
            foreach ($patterns as $pattern) if (preg_match($pattern, $combined)) $this->redirect('received');
            if (preg_match_all('/https?:\/\//i', $message) > 2 || preg_match('/[\x{0400}-\x{04FF}]/u', $combined)) {
                $this->redirect('received');
            }
            $options = get_option('kptheme_settings', array());
            $options = is_array($options) ? $options : array();
            $secret = $options['kpt_recaptcha_secret_key'] ?? '';
            if ($secret) {
                $token = sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'] ?? ''));
                if (! $token) wp_die('reCAPTCHA verification failed.', '', array('response' => 403));
                $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array('timeout' => 10,
                    'body' => array('secret' => $secret, 'response' => $token, 'remoteip' => $ip)));
                if (is_wp_error($response)) wp_die('Unable to verify submission.', '', array('response' => 503));
                $data = json_decode(wp_remote_retrieve_body($response));
                if (! is_object($data) || empty($data->success) || ($data->score ?? 0) < .7 || ($data->action ?? '') !== 'contact_form') {
                    wp_die('reCAPTCHA verification failed.', '', array('response' => 403));
                }
            }
            $post_id = wp_insert_post(array('post_type' => 'kpt_contact', 'post_title' => $first . ' ' . $last,
                'post_status' => 'publish'), true);
            if (is_wp_error($post_id) || ! $post_id) wp_die('Unable to save submission.', '', array('response' => 500));
            foreach (array('_contact_first_name' => $first, '_contact_last_name' => $last,
                '_contact_email' => $email, '_contact_phone' => $phone, '_contact_url' => $url,
                '_contact_message' => $message, '_contact_ip' => $ip,
                '_contact_user_agent' => sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? ''))) as $name => $value) {
                update_post_meta($post_id, $name, $value);
            }
            $body = "Name: $first $last\nEmail: $email\nPhone: $phone\nWebsite: $url\n\nMessage:\n$message\n\n" .
                admin_url('post.php?post=' . $post_id . '&action=edit');
            wp_mail(get_option('admin_email'), 'Contact form submission from ' . $first . ' ' . $last, $body,
                array('Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email));
            $this->redirect('received');
        }
        public function columns(array $columns): array
        {
            return array('cb' => $columns['cb'] ?? '', 'title' => 'Name', 'email' => 'Email',
                'phone' => 'Phone', 'spam_status' => 'Status', 'date' => $columns['date'] ?? '');
        }
        public function column_content(string $column, int $post_id): void
        {
            if ($column === 'email') echo esc_html(get_post_meta($post_id, '_contact_email', true));
            if ($column === 'phone') echo esc_html(get_post_meta($post_id, '_contact_phone', true));
            if ($column === 'spam_status') echo esc_html(get_post_status($post_id) === 'spam' ? 'Spam' : 'Clean');
        }
        public function add_meta_boxes(): void
        {
            add_meta_box('kpt_contact_details', 'Submission Details', array($this, 'show_details'), 'kpt_contact');
        }
        public function show_details($post): void
        {
            foreach (array('_contact_first_name' => 'First Name', '_contact_last_name' => 'Last Name',
                '_contact_email' => 'Email', '_contact_phone' => 'Phone', '_contact_url' => 'Website',
                '_contact_message' => 'Message', '_contact_ip' => 'IP Address', '_contact_user_agent' => 'User Agent') as $key => $label) {
                echo '<p><strong>' . esc_html($label) . ':</strong></p><p style="white-space:pre-wrap">' .
                    esc_html(get_post_meta($post->ID, $key, true)) . '</p>';
            }
        }
        public function row_actions(array $actions, $post): array
        {
            if ($post->post_type !== 'kpt_contact' || ! current_user_can('edit_post', $post->ID)) return $actions;
            $mark = get_post_status($post->ID) === 'spam' ? 'clean' : 'spam';
            $url = wp_nonce_url(add_query_arg(array('action' => 'kpt_toggle_spam', 'post_id' => $post->ID,
                'mark' => $mark), admin_url('admin.php')), 'kpt_spam_toggle_' . $post->ID);
            $actions['toggle_spam'] = '<a href="' . esc_url($url) . '">' .
                esc_html($mark === 'spam' ? 'Mark as Spam' : 'Not Spam') . '</a>';
            return $actions;
        }
        public function toggle_spam(): void
        {
            $id = absint($_GET['post_id'] ?? 0);
            if (! $id || get_post_type($id) !== 'kpt_contact' || ! current_user_can('edit_post', $id)) {
                wp_die('Unauthorized.', '', array('response' => 403));
            }
            check_admin_referer('kpt_spam_toggle_' . $id);
            $mark = sanitize_key(wp_unslash($_GET['mark'] ?? ''));
            if (! in_array($mark, array('spam', 'clean'), true)) wp_die('Invalid status.');
            wp_update_post(array('ID' => $id, 'post_status' => $mark === 'spam' ? 'spam' : 'publish'));
            wp_safe_redirect(admin_url('edit.php?post_type=kpt_contact'));
            exit;
        }
    }
}
