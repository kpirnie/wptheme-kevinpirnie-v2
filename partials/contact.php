
<?php

/**
 * Contact Form Template
 *
 * Displays the contact form with validation,
 * spam protection, and reCAPTCHA integration.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the reCAPTCHA site key
$options = get_option('kptheme_settings', array());
$options = is_array($options) ? $options : array();

$recaptcha_site_key = $options['kpt_recaptcha_site_key'] ?? '';

// check for a successful submission
$success = isset($_GET['contact_success']) && '1' === $_GET['contact_success'];

// form field styling
$input_class = 'w-full rounded-lg border border-kp-border bg-kp-background px-4 py-3 text-kp-text placeholder:text-kp-muted focus:border-kp-light-blue focus:outline-none';
$label_class = 'mb-2 block text-sm font-semibold text-kp-text';

?>

<div class="kpt-contact-form-wrapper mx-auto w-full max-w-3xl">

    <?php if ($success) : ?>

        <!-- Success Message -->
        <div
            class="mb-8 rounded-lg border border-green-700 bg-green-950 p-6"
            role="status"
        >

            <h3 class="mb-2 text-xl font-bold text-green-300">
                <?php esc_html_e('Thank You!', 'kevinpirnie-v2'); ?>
            </h3>

            <p class="text-green-100">
                <?php esc_html_e('Your message has been received. I will get back to you soon.', 'kevinpirnie-v2'); ?>
            </p>

        </div>

    <?php endif; ?>

    <!-- Contact Form -->
    <form
        id="kpt-contact-form"
        method="post"
        action=""
        class="space-y-6"
    >

        <?php wp_nonce_field('kpt_contact_form', 'kpt_contact_nonce'); ?>

        <!-- Spam Honeypots -->
        <div
            class="absolute -left-[9999px]"
            aria-hidden="true"
        >
            <input
                type="text"
                name="website_url"
                tabindex="-1"
                autocomplete="off"
            >

            <input
                type="text"
                name="company_name"
                tabindex="-1"
                autocomplete="off"
            >
        </div>

        <!-- Submission Timing -->
        <input
            type="hidden"
            name="form_token"
            value="<?php echo esc_attr(base64_encode((string) time())); ?>"
        >

        <p class="text-right text-sm text-kp-muted">
            <span class="text-kp-orange">*</span>
            <?php esc_html_e('Required fields', 'kevinpirnie-v2'); ?>
        </p>

        <!-- Name Fields -->
        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <label for="first_name" class="<?php echo esc_attr($label_class); ?>">
                    <?php esc_html_e('First Name', 'kevinpirnie-v2'); ?>
                    <span class="text-kp-orange">*</span>
                </label>

                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    autocomplete="given-name"
                    maxlength="100"
                    required
                    class="<?php echo esc_attr($input_class); ?>"
                >
            </div>

            <div>
                <label for="last_name" class="<?php echo esc_attr($label_class); ?>">
                    <?php esc_html_e('Last Name', 'kevinpirnie-v2'); ?>
                    <span class="text-kp-orange">*</span>
                </label>

                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    autocomplete="family-name"
                    maxlength="100"
                    required
                    class="<?php echo esc_attr($input_class); ?>"
                >
            </div>

        </div>

        <!-- Email and Phone -->
        <div class="grid gap-6 md:grid-cols-2">

            <div>
                <label for="email" class="<?php echo esc_attr($label_class); ?>">
                    <?php esc_html_e('Email Address', 'kevinpirnie-v2'); ?>
                    <span class="text-kp-orange">*</span>
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    maxlength="254"
                    required
                    class="<?php echo esc_attr($input_class); ?>"
                >
            </div>

            <div>
                <label for="phone" class="<?php echo esc_attr($label_class); ?>">
                    <?php esc_html_e('Phone Number', 'kevinpirnie-v2'); ?>
                </label>

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    autocomplete="tel"
                    maxlength="30"
                    class="<?php echo esc_attr($input_class); ?>"
                >
            </div>

        </div>

        <!-- Website -->
        <div>
            <label for="url" class="<?php echo esc_attr($label_class); ?>">
                <?php esc_html_e('Website URL', 'kevinpirnie-v2'); ?>
            </label>

            <input
                type="url"
                id="url"
                name="url"
                placeholder="https://"
                maxlength="2048"
                class="<?php echo esc_attr($input_class); ?>"
            >
        </div>

        <!-- Message -->
        <div>
            <label for="message" class="<?php echo esc_attr($label_class); ?>">
                <?php esc_html_e('Message', 'kevinpirnie-v2'); ?>
                <span class="text-kp-orange">*</span>
            </label>

            <textarea
                id="message"
                name="message"
                rows="7"
                maxlength="10000"
                required
                class="<?php echo esc_attr($input_class); ?>"
            ></textarea>
        </div>

        <!-- Privacy Consent -->
        <div class="rounded-lg border border-kp-border bg-kp-background p-5">

            <label class="flex cursor-pointer items-start gap-3">

                <input
                    type="checkbox"
                    id="privacy_consent"
                    name="privacy_consent"
                    value="1"
                    required
                    class="mt-1 h-5 w-5 shrink-0 accent-kp-light-blue"
                >

                <span class="text-sm leading-7 text-kp-muted">

                    <span class="text-kp-orange">*</span>

                    <?php esc_html_e('I agree to the', 'kevinpirnie-v2'); ?>

                    <a
                        href="<?php echo esc_url(home_url('/about-kevin-pirnie/privacy-policy/')); ?>"
                        class="text-kp-light-blue underline underline-offset-4 hover:text-kp-text"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <?php esc_html_e('Privacy Policy', 'kevinpirnie-v2'); ?>
                    </a>.

                    <?php esc_html_e(
                        'By submitting this form, you consent to the collection and use of your contact information to respond to your inquiry and for related business purposes.',
                        'kevinpirnie-v2'
                    ); ?>

                </span>

            </label>

        </div>

        <!-- reCAPTCHA -->
        <?php if ($recaptcha_site_key) : ?>

            <input
                type="hidden"
                name="g-recaptcha-response"
                id="g-recaptcha-response"
            >

            <p class="text-xs leading-6 text-kp-muted">
                <?php esc_html_e('This site is protected by reCAPTCHA and the Google', 'kevinpirnie-v2'); ?>

                <a
                    href="https://policies.google.com/privacy"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-kp-light-blue underline"
                >
                    <?php esc_html_e('Privacy Policy', 'kevinpirnie-v2'); ?>
                </a>

                <?php esc_html_e('and', 'kevinpirnie-v2'); ?>

                <a
                    href="https://policies.google.com/terms"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-kp-light-blue underline"
                >
                    <?php esc_html_e('Terms of Service', 'kevinpirnie-v2'); ?>
                </a>

                <?php esc_html_e('apply.', 'kevinpirnie-v2'); ?>
            </p>

        <?php endif; ?>

        <!-- Submit Button -->
        <button
            type="submit"
            name="kpt_contact_submit"
            value="1"
            id="kpt-contact-submit"
            class="kp-btn kp-btn-primary w-full text-lg"
        >
            <?php esc_html_e('Send Message', 'kevinpirnie-v2'); ?>
            <span aria-hidden="true">&rarr;</span>
        </button>

    </form>

</div>

<?php if ($recaptcha_site_key) : ?>

    <!-- reCAPTCHA Integration -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo esc_attr(rawurlencode($recaptcha_site_key)); ?>" async defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const form = document.getElementById('kpt-contact-form');
            const tokenField = document.getElementById('g-recaptcha-response');

            if (!form || !tokenField) {
                return;
            }

            form.addEventListener('submit', async (event) => {

                if (form.dataset.verified === 'true') {
                    return;
                }

                event.preventDefault();

                const submitButton = document.getElementById('kpt-contact-submit');

                if (typeof grecaptcha === 'undefined') {
                    return;
                }

                submitButton.disabled = true;

                try {

                    await new Promise((resolve) => grecaptcha.ready(resolve));

                    const token = await grecaptcha.execute(
                        <?php echo wp_json_encode($recaptcha_site_key); ?>,
                        { action: 'contact_form' }
                    );

                    tokenField.value = token;
                    form.dataset.verified = 'true';

                    form.requestSubmit(submitButton);

                } catch (error) {

                    submitButton.disabled = false;

                }

            });

        });
    </script>

<?php endif; ?>
