<?php
/** 
 * Contact form submission storage, verification and moderation
 * 
 * @since 8.5
 * @author Kevin Pirnie <iam@kevinpirnie.com>
 * @package Kevin's Theme v2
 * 
 */

// no direct access fool!
defined( 'ABSPATH' ) || die( 'No direct script access allowed' );

// make sure the class doesn't already exist
if ( ! class_exists( 'KPT_Contact_Form' ) ) {

    /** 
     * KPT_Contact_Form
     * 
     * Handles contact form submissions, spam checks and moderation
     * 
     * @since 8.5
     * @author Kevin Pirnie <iam@kevinpirnie.com>
     * @package Kevin's Theme v2
     * 
     */
    class KPT_Contact_Form
    {

        /** 
         * init
         * 
         * Hook everything into WordPress
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function init( ) : void
        {
            add_action( 'init', array( $this, 'register_cpt' ) );
            add_action( 'template_redirect', array( $this, 'process_submission' ) );
            add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
            add_filter( 'manage_kpt_contact_posts_columns', array( $this, 'columns' ) );
            add_action( 'manage_kpt_contact_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
            add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
            add_action( 'admin_action_kpt_toggle_spam', array( $this, 'toggle_spam' ) );
        }

        /** 
         * register_cpt
         * 
         * Register the contact submission post type and spam status
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function register_cpt( ) : void
        {

            // the post type
            register_post_type( 'kpt_contact', array(
                'labels' => array( 
                    'name' => 'Contact Submissions', 
                    'singular_name' => 'Contact Submission', 
                    'menu_name' => 'Contact Form' 
                ),
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_rest' => false,
                'menu_icon' => 'dashicons-email',
                'supports' => array( 'title' ),
                'capability_type' => 'post',
                'capabilities' => array( 'create_posts' => 'do_not_allow' ),
                'map_meta_cap' => true,
                'publicly_queryable' => false,
                'exclude_from_search' => true,
            ) );

            // the spam status
            register_post_status( 'spam', array(
                'label' => 'Spam',
                'public' => false,
                'show_in_admin_all_list' => true,
                'show_in_admin_status_list' => true,
                'label_count' => _n_noop( 'Spam <span class="count">(%s)</span>', 'Spam <span class="count">(%s)</span>' )
            ) );
        }

        /** 
         * redirect
         * 
         * Redirect back to the referring page with a contact status
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param string $status The contact status to pass along
         * 
         * @return void This method does not return anything
         */
        private function redirect( string $status ) : void
        {

            // where are we going back to
            $url = wp_get_referer( ) ?: home_url( '/about-kevin-pirnie/lets-talk/' );

            // redirect and stop
            wp_safe_redirect( add_query_arg( 'contact_status', $status, $url ), 303 );
            exit;
        }

        /** 
         * process_submission
         * 
         * Validate, spam check, store and email a contact form submission
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function process_submission( ) : void
        {

            // only handle our form posts
            if ( ( $_SERVER['REQUEST_METHOD'] ?? '' ) !== 'POST' || ! isset( $_POST['kpt_contact_submit'] ) ) {
                return;
            }

            // verify the nonce
            $nonce = sanitize_text_field( wp_unslash( $_POST['kpt_contact_nonce'] ?? '' ) );
            if ( ! wp_verify_nonce( $nonce, 'kpt_contact_form' ) ) {
                wp_die( 'Security check failed.', '', array( 'response' => 403 ) );
            }

            // rate limit by ip
            $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '' ) );
            $key = 'kpt_contact_limit_' . md5( $ip );
            $count = ( int ) get_transient( $key );
            if ( $count >= 3 ) {
                wp_die( 'Too many submissions. Please try later.', '', array( 'response' => 429 ) );
            }
            set_transient( $key, $count + 1, HOUR_IN_SECONDS );

            // honeypot fields
            if ( ! empty( $_POST['website_url'] ) || ! empty( $_POST['company_name'] ) ) {
                $this->redirect( 'received' );
            }

            // The browser supplies elapsed time to avoid caching a static server-side timestamp.
            $elapsed = absint( $_POST['form_elapsed'] ?? 0 );
            if ( $elapsed < 3 || $elapsed > DAY_IN_SECONDS ) {
                $this->redirect( 'received' );
            }

            // sanitize the fields
            $first = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
            $last = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
            $email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
            $phone = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
            $url = esc_url_raw( wp_unslash( $_POST['url'] ?? '' ) );
            $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

            // required fields
            if ( ! $first || ! $last || ! is_email( $email ) || ! $message || ! isset( $_POST['privacy_consent'] ) ) {
                wp_die( 'Please complete all required fields.', '', array( 'response' => 400 ) );
            }

            // spam patterns
            $combined = $first . ' ' . $last . ' ' . $message;
            $patterns = array(
                '/\[url=/i',
                '/\[link=/i',
                '/<a\s+href/i',
                '/https?:\/\/[^\s]+\s+https?:\/\//i',
                '/viagra|cialis|casino|lottery|cryptocurrency|bitcoin|crypto wallet/i',
                '/click here.*https?:\/\//i',
                '/dear\s+(sir|madam|friend)/i',
                '/\b(SEO|backlink|rank|traffic)\b.*\b(service|offer|guarantee)/i'
            );

            // check them
            foreach ( $patterns as $pattern ) {
                if ( preg_match( $pattern, $combined ) ) {
                    $this->redirect( 'received' );
                }
            }

            // too many links or cyrillic characters
            if ( preg_match_all( '/https?:\/\//i', $message ) > 2 || preg_match( '/[\x{0400}-\x{04FF}]/u', $combined ) ) {
                $this->redirect( 'received' );
            }

            // grab the theme options
            $options = get_option( 'kptheme_settings', array( ) );
            $options = is_array( $options ) ? $options : array( );

            // verify recaptcha if there's a secret
            $secret = $options['kpt_recaptcha_secret_key'] ?? '';
            if ( $secret ) {

                // the token
                $token = sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ?? '' ) );
                if ( ! $token ) {
                    wp_die( 'reCAPTCHA verification failed.', '', array( 'response' => 403 ) );
                }

                // verify it with google
                $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', array( 
                    'timeout' => 10,
                    'body' => array( 'secret' => $secret, 'response' => $token, 'remoteip' => $ip )
                ) );
                if ( is_wp_error( $response ) ) {
                    wp_die( 'Unable to verify submission.', '', array( 'response' => 503 ) );
                }

                // check the result
                $data = json_decode( wp_remote_retrieve_body( $response ) );
                if ( ! is_object( $data ) || empty( $data -> success ) || ( $data -> score ?? 0 ) < .7 || ( $data -> action ?? '' ) !== 'contact_form' ) {
                    wp_die( 'reCAPTCHA verification failed.', '', array( 'response' => 403 ) );
                }
            }

            // store the submission
            $post_id = wp_insert_post( array( 
                'post_type' => 'kpt_contact', 
                'post_title' => $first . ' ' . $last,
                'post_status' => 'publish'
            ), true );
            if ( is_wp_error( $post_id ) || ! $post_id ) {
                wp_die( 'Unable to save submission.', '', array( 'response' => 500 ) );
            }

            // store the meta
            foreach ( array( 
                '_contact_first_name' => $first, 
                '_contact_last_name' => $last,
                '_contact_email' => $email, 
                '_contact_phone' => $phone, 
                '_contact_url' => $url,
                '_contact_message' => $message, 
                '_contact_ip' => $ip,
                '_contact_user_agent' => sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ?? '' ) )
            ) as $name => $value ) {
                update_post_meta( $post_id, $name, $value );
            }

            // email it
            $body = "Name: $first $last\nEmail: $email\nPhone: $phone\nWebsite: $url\n\nMessage:\n$message\n\n" .
                admin_url( 'post.php?post=' . $post_id . '&action=edit' );
            wp_mail( 
                get_option( 'admin_email' ), 
                'Contact form submission from ' . $first . ' ' . $last, 
                $body,
                array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email )
            );

            // all done
            $this->redirect( 'received' );
        }

        /** 
         * columns
         * 
         * Set the admin list columns
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param array $columns The existing columns
         * 
         * @return array The new columns
         */
        public function columns( array $columns ) : array
        {
            return array( 
                'cb' => $columns['cb'] ?? '', 
                'title' => 'Name', 
                'email' => 'Email',
                'phone' => 'Phone', 
                'spam_status' => 'Status', 
                'date' => $columns['date'] ?? '' 
            );
        }

        /** 
         * column_content
         * 
         * Output the admin list column content
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param string $column The column name
         * @param int $post_id The post id
         * 
         * @return void This method does not return anything
         */
        public function column_content( string $column, int $post_id ) : void
        {

            // email
            if ( 'email' === $column ) {
                echo esc_html( get_post_meta( $post_id, '_contact_email', true ) );
            }

            // phone
            if ( 'phone' === $column ) {
                echo esc_html( get_post_meta( $post_id, '_contact_phone', true ) );
            }

            // status
            if ( 'spam_status' === $column ) {
                echo esc_html( 'spam' === get_post_status( $post_id ) ? 'Spam' : 'Clean' );
            }
        }

        /** 
         * add_meta_boxes
         * 
         * Add the submission details meta box
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function add_meta_boxes( ) : void
        {
            add_meta_box( 'kpt_contact_details', 'Submission Details', array( $this, 'show_details' ), 'kpt_contact' );
        }

        /** 
         * show_details
         * 
         * Output the submission details
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param WP_Post $post The current post
         * 
         * @return void This method does not return anything
         */
        public function show_details( $post ) : void
        {

            // loop the fields
            foreach ( array( 
                '_contact_first_name' => 'First Name', 
                '_contact_last_name' => 'Last Name',
                '_contact_email' => 'Email', 
                '_contact_phone' => 'Phone', 
                '_contact_url' => 'Website',
                '_contact_message' => 'Message', 
                '_contact_ip' => 'IP Address', 
                '_contact_user_agent' => 'User Agent' 
            ) as $key => $label ) {

                // output it
                echo '<p><strong>' . esc_html( $label ) . ':</strong></p><p style="white-space:pre-wrap">' .
                    esc_html( get_post_meta( $post -> ID, $key, true ) ) . '</p>';
            }
        }

        /** 
         * row_actions
         * 
         * Add the spam toggle to the row actions
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @param array $actions The existing actions
         * @param WP_Post $post The current post
         * 
         * @return array The actions
         */
        public function row_actions( array $actions, $post ) : array
        {

            // only for our post type and users who can edit
            if ( 'kpt_contact' !== $post -> post_type || ! current_user_can( 'edit_post', $post -> ID ) ) {
                return $actions;
            }

            // what are we toggling to
            $mark = 'spam' === get_post_status( $post -> ID ) ? 'clean' : 'spam';

            // the toggle url
            $url = wp_nonce_url( add_query_arg( array( 
                'action' => 'kpt_toggle_spam', 
                'post_id' => $post -> ID,
                'mark' => $mark 
            ), admin_url( 'admin.php' ) ), 'kpt_spam_toggle_' . $post -> ID );

            // add the action
            $actions['toggle_spam'] = '<a href="' . esc_url( $url ) . '">' .
                esc_html( 'spam' === $mark ? 'Mark as Spam' : 'Not Spam' ) . '</a>';

            // return them
            return $actions;
        }

        /** 
         * toggle_spam
         * 
         * Toggle a submission between spam and clean
         * 
         * @since 8.5
         * @author Kevin Pirnie <iam@kevinpirnie.com>
         * 
         * @return void This method does not return anything
         */
        public function toggle_spam( ) : void
        {

            // the post id
            $id = absint( $_GET['post_id'] ?? 0 );

            // make sure it's valid and the user can edit it
            if ( ! $id || 'kpt_contact' !== get_post_type( $id ) || ! current_user_can( 'edit_post', $id ) ) {
                wp_die( 'Unauthorized.', '', array( 'response' => 403 ) );
            }

            // verify the nonce
            check_admin_referer( 'kpt_spam_toggle_' . $id );

            // the new status
            $mark = sanitize_key( wp_unslash( $_GET['mark'] ?? '' ) );
            if ( ! in_array( $mark, array( 'spam', 'clean' ), true ) ) {
                wp_die( 'Invalid status.' );
            }

            // update it
            wp_update_post( array( 'ID' => $id, 'post_status' => 'spam' === $mark ? 'spam' : 'publish' ) );

            // and go back to the list
            wp_safe_redirect( admin_url( 'edit.php?post_type=kpt_contact' ) );
            exit;
        }
    }
}