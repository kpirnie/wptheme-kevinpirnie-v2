
<?php

/**
 * Contact Form Handler
 *
 * Handles contact submissions, spam protection,
 * storage, and email notifications.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

if (! class_exists('KPT_Contact_Form')) {

    /**
     * KPT_Contact_Form
     *
     * Manage contact form submissions.
     *
     * @since 1.0.0
     * @access public
     */
    class KPT_Contact_Form
    {

        /**
         * init
         *
         * Register the contact form functionality.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function init(): void
        {

            add_action('init', array($this, 'register_cpt'));
            add_action('template_redirect', array($this, 'process_submission'));
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
            add_filter('manage_kpt_contact_posts_columns', array($this, 'admin_columns'));
            add_action('manage_kpt_contact_posts_custom_column', array($this, 'render_admin_column'), 10, 2);
            add_filter('post_row_actions', array($this, 'row_actions'), 10, 2);
            add_action('admin_action_kpt_toggle_spam', array($this, 'handle_spam_toggle'));

        }

        /**
         * register_cpt
         *
         * Register stored contact submissions.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function register_cpt(): void
        {

            register_post_type('kpt_contact', array(
                'labels' => array(
                    'name' => __('Contact Submissions', 'kevinpirnie-v2'),
                    'singular_name' => __('Contact Submission', 'kevinpirnie-v2'),
                    'menu_name' => __('Contact Form', 'kevinpirnie-v2'),
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'menu_icon' => 'dashicons-email',
                'capability_type' => 'post',
                'capabilities' => array(
                    'create_posts' => 'do_not_allow',
                ),
                'map_meta_cap' => true,
                'supports' => array('title'),
                'has_archive' => false,
                'rewrite' => false,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'show_in_rest' => false,
            ));

            register_post_status('spam', array(
                'label' => _x('Spam', 'post status', 'kevinpirnie-v2'),
                'public' => false,
                'exclude_from_search' => true,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop(
                    'Spam <span class="count">(%s)</span>',
                    'Spam <span class="count">(%s)</span>',
                    'kevinpirnie-v2'
                ),
            ));

        }

        /**
         * process_submission
         *
         * Validate, filter, and store submissions.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function process_submission(): void
        {

            // only process contact form submissions
            if (
                'POST' !== ($_SERVER['REQUEST_METHOD'] ?? '') ||
                ! isset($_POST['kpt_contact_submit'])
            ) {
                return;
            }

            // verify the form nonce
            $nonce = isset($_POST['kpt_contact_nonce']) && is_string($_POST['kpt_contact_nonce'])
                ? sanitize_text_field(wp_unslash($_POST['kpt_contact_nonce']))
                : '';

            if (! wp_verify_nonce($nonce, 'kpt_contact_form')) {
                wp_die(esc_html__('Security check failed.', 'kevinpirnie-v2'), '', array('response' => 403));
            }

            // limit submissions by IP address
            $ip = sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'] ?? ''));
            $rate_key = 'kpt_contact_limit_' . md5($ip);
            $submissions = (int) get_transient($rate_key);

            if ($submissions >= 3) {
                wp_die(esc_html__('Too many submissions. Please try again later.', 'kevinpirnie-v2'));
            }

            set_transient($rate_key, $submissions + 1, HOUR_IN_SECONDS);

            // reject honeypot submissions
            if (! empty($_POST['website_url']) || ! empty($_POST['company_name'])) {
                $this->redirect('contact_success', '1');
            }

            // verify form submission timing
            $token = isset($_POST['form_token']) && is_string($_POST['form_token'])
                ? sanitize_text_field(wp_unslash($_POST['form_token']))
                : '';

            $decoded = base64_decode($token, true);
            $form_time = false !== $decoded && ctype_digit($decoded) ? (int) $decoded : 0;
            $elapsed = time() - $form_time;

            if ($form_time <= 0 || $elapsed < 3 || $elapsed > DAY_IN_SECONDS) {
                wp_die(esc_html__('Invalid form submission.', 'kevinpirnie-v2'));
            }

            // sanitize submitted fields
            $first_name = sanitize_text_field(wp_unslash($_POST['first_name'] ?? ''));
            $last_name = sanitize_text_field(wp_unslash($_POST['last_name'] ?? ''));
            $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
            $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
            $url = esc_url_raw(wp_unslash($_POST['url'] ?? ''));
            $message = sanitize_textarea_field(wp_unslash($_POST['message'] ?? ''));
            $privacy_consent = isset($_POST['privacy_consent']);

            // validate required fields
            if (
                empty($first_name) ||
                empty($last_name) ||
                empty($email) ||
                empty($message) ||
                ! $privacy_consent
            ) {
                wp_die(esc_html__('Please complete all required fields and accept the privacy policy.', 'kevinpirnie-v2'));
            }

            // validate the email address
            if (! is_email($email)) {
                wp_die(esc_html__('Please provide a valid email address.', 'kevinpirnie-v2'));
            }

            // check for common spam patterns
            $spam_patterns = array(
                '/\[url=/i',
                '/\[link=/i',
                '/<a\s+href/i',
                '/https?:\/\/[^\s]+\s+https?:\/\//i',
                '/viagra|cialis|casino|lottery|cryptocurrency|bitcoin|crypto wallet/i',
                '/click here.*https?:\/\//i',
                '/dear\s+(sir|madam|friend)/i',
                '/\b(SEO|backlink|rank|traffic)\b.*\b(service|offer|guarantee)/i',
            );

            $combined = implode(' ', array($first_name, $last_name, $message));

            foreach ($spam_patterns as $pattern) {

                if (preg_match($pattern, $combined)) {
                    $this->redirect('contact_success', '1');
                }

            }

            // reject excessive links
            if (preg_match_all('/https?:\/\//i', $message) > 2) {
                $this->redirect('contact_success', '1');
            }

            // retain existing Cyrillic spam filtering
            if (preg_match('/[\x{0400}-\x{04FF}]/u', $combined)) {
                $this->redirect('contact_success', '1');
            }

            // verify reCAPTCHA when configured
            $options = get_option('kptheme_settings', array());
            $options = is_array($options) ? $options : array();

            $recaptcha_secret = $options['kpt_recaptcha_secret_key'] ?? '';

            if (! empty($recaptcha_secret)) {

                $recaptcha_token = sanitize_text_field(
                    wp_unslash($_POST['g-recaptcha-response'] ?? '')
                );

                if (empty($recaptcha_token)) {
                    wp_die(esc_html__('reCAPTCHA verification failed.', 'kevinpirnie-v2'));
                }

                $response = wp_remote_post(
                    'https://www.google.com/recaptcha/api/siteverify',
                    array(
                        'timeout' => 10,
                        'body' => array(
                            'secret' => $recaptcha_secret,
                            'response' => $recaptcha_token,
                            'remoteip' => $ip,
                        ),
                    )
                );

                if (is_wp_error($response)) {
                    wp_die(esc_html__('Unable to verify your submission.', 'kevinpirnie-v2'));
                }

                $result = json_decode(wp_remote_retrieve_body($response));

                if (
                    ! is_object($result) ||
                    empty($result->success) ||
                    ! isset($result->score) ||
                    $result->score < 0.7 ||
                    ($result->action ?? '') !== 'contact_form'
                ) {
                    wp_die(esc_html__('reCAPTCHA verification failed.', 'kevinpirnie-v2'));
                }

            }

            // store the submission
            $post_id = wp_insert_post(array(
                'post_type' => 'kpt_contact',
                'post_title' => $first_name . ' ' . $last_name,
                'post_status' => 'publish',
            ), true);

            if (is_wp_error($post_id) || ! $post_id) {
                wp_die(esc_html__('Unable to process your submission.', 'kevinpirnie-v2'));
            }

            // save submission metadata
            update_post_meta($post_id, '_contact_first_name', $first_name);
            update_post_meta($post_id, '_contact_last_name', $last_name);
            update_post_meta($post_id, '_contact_email', $email);
            update_post_meta($post_id, '_contact_phone', $phone);
            update_post_meta($post_id, '_contact_url', $url);
            update_post_meta($post_id, '_contact_message', $message);
            update_post_meta($post_id, '_contact_ip', $ip);
            update_post_meta(
                $post_id,
                '_contact_user_agent',
                sanitize_text_field(wp_unslash($_SERVER['HTTP_USER_AGENT'] ?? ''))
            );

            // prepare the notification
            $subject = 'New Contact Form Submission from ' . $first_name . ' ' . $last_name;

            $body = "New contact form submission:\n\n";
            $body .= "Name: {$first_name} {$last_name}\n";
            $body .= "Email: {$email}\n";
            $body .= "Phone: {$phone}\n";

            if ($url) {
                $body .= "Website: {$url}\n";
            }

            $body .= "\nMessage:\n{$message}\n\n";
            $body .= "---\n";
            $body .= 'View submission: ' . admin_url('post.php?post=' . $post_id . '&action=edit');

            // send the notification
            wp_mail(
                get_option('admin_email'),
                $subject,
                $body,
                array(
                    'Content-Type: text/plain; charset=UTF-8',
                    'Reply-To: ' . $email,
                )
            );

            // redirect after successful submission
            $this->redirect('contact_success', '1');

        }

        /**
         * redirect
         *
         * Redirect back to the contact page.
         *
         * @since 1.0.0
         * @access private
         * @return void
         */
        private function redirect(string $key, string $value): void
        {

            $url = wp_get_referer() ?: home_url('/');

            wp_safe_redirect(add_query_arg($key, $value, $url), 303);
            exit;

        }

        /**
         * admin_columns
         *
         * Customize contact submission columns.
         *
         * @since 1.0.0
         * @access public
         * @return array
         */
        public function admin_columns($columns): array
        {

            return array(
                'cb' => $columns['cb'] ?? '',
                'title' => __('Name', 'kevinpirnie-v2'),
                'email' => __('Email', 'kevinpirnie-v2'),
                'phone' => __('Phone', 'kevinpirnie-v2'),
                'spam_status' => __('Status', 'kevinpirnie-v2'),
                'date' => $columns['date'] ?? '',
            );

        }

        /**
         * render_admin_column
         *
         * Display contact submission metadata.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function render_admin_column($column, $post_id): void
        {

            if ('email' === $column) {
                echo esc_html(get_post_meta($post_id, '_contact_email', true));
            } elseif ('phone' === $column) {
                echo esc_html(get_post_meta($post_id, '_contact_phone', true));
            } elseif ('spam_status' === $column) {
                echo 'spam' === get_post_status($post_id)
                    ? esc_html__('Spam', 'kevinpirnie-v2')
                    : esc_html__('Clean', 'kevinpirnie-v2');
            }

        }

        /**
         * add_meta_boxes
         *
         * Display contact submission details.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function add_meta_boxes(): void
        {

            add_meta_box(
                'kpt_contact_details',
                __('Submission Details', 'kevinpirnie-v2'),
                array($this, 'render_meta_box'),
                'kpt_contact',
                'normal',
                'high'
            );

        }

        /**
         * render_meta_box
         *
         * Render stored submission information.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function render_meta_box($post): void
        {

            $fields = array(
                '_contact_first_name' => 'First Name',
                '_contact_last_name' => 'Last Name',
                '_contact_email' => 'Email',
                '_contact_phone' => 'Phone',
                '_contact_url' => 'Website',
                '_contact_message' => 'Message',
                '_contact_ip' => 'IP Address',
                '_contact_user_agent' => 'User Agent',
            );

            foreach ($fields as $key => $label) {

                $value = get_post_meta($post->ID, $key, true);

                echo '<p><strong>' . esc_html($label) . ':</strong></p>';
                echo '<p style="white-space: pre-wrap;">' . esc_html($value) . '</p>';

            }

        }

        /**
         * row_actions
         *
         * Add spam management links.
         *
         * @since 1.0.0
         * @access public
         * @return array
         */
        public function row_actions($actions, $post): array
        {

            if (
                'kpt_contact' !== $post->post_type ||
                ! current_user_can('edit_post', $post->ID)
            ) {
                return $actions;
            }

            $mark = 'spam' === get_post_status($post->ID) ? 'clean' : 'spam';

            $url = wp_nonce_url(
                add_query_arg(
                    array(
                        'action' => 'kpt_toggle_spam',
                        'post_id' => $post->ID,
                        'mark' => $mark,
                    ),
                    admin_url('admin.php')
                ),
                'kpt_spam_toggle_' . $post->ID
            );

            $actions['toggle_spam'] = sprintf(
                '<a href="%s">%s</a>',
                esc_url($url),
                'spam' === $mark
                    ? esc_html__('Mark as Spam', 'kevinpirnie-v2')
                    : esc_html__('Not Spam', 'kevinpirnie-v2')
            );

            return $actions;

        }

        /**
         * handle_spam_toggle
         *
         * Update a submission's spam status.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function handle_spam_toggle(): void
        {

            $post_id = absint($_GET['post_id'] ?? 0);
            $mark = sanitize_key(wp_unslash($_GET['mark'] ?? ''));

            if (
                ! $post_id ||
                'kpt_contact' !== get_post_type($post_id) ||
                ! current_user_can('edit_post', $post_id)
            ) {
                wp_die(esc_html__('Unauthorized.', 'kevinpirnie-v2'), '', array('response' => 403));
            }

            check_admin_referer('kpt_spam_toggle_' . $post_id);

            if (! in_array($mark, array('spam', 'clean'), true)) {
                wp_die(esc_html__('Invalid status.', 'kevinpirnie-v2'));
            }

            wp_update_post(array(
                'ID' => $post_id,
                'post_status' => 'spam' === $mark ? 'spam' : 'publish',
            ));

            wp_safe_redirect(admin_url('edit.php?post_type=kpt_contact'));
            exit;

        }

    }

}
