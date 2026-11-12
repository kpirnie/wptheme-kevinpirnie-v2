<?php
/** Contact form fields and client-side reCAPTCHA handling. */
defined('ABSPATH') || die('No direct script access allowed');
$options = get_option('kptheme_settings', array());
$options = is_array($options) ? $options : array();
$site_key = $options['kpt_recaptcha_site_key'] ?? '';
$success = isset($_GET['contact_status']) && 'received' === $_GET['contact_status'];
$input = 'w-full rounded-lg border border-kp-border bg-kp-background px-4 py-3 text-kp-text focus:border-kp-light-blue focus:outline-none';
?>
<div class="kpt-contact-form-wrapper mx-auto w-full max-w-3xl">
<?php if ($success) : ?><div role="status" class="mb-8 rounded-lg border border-green-700 bg-green-950 p-6 text-green-100"><strong>Thank you!</strong> Your message has been received.</div><?php endif; ?>
<form id="kpt-contact-form" method="post" action="" class="space-y-6" data-recaptcha-sitekey="<?php echo esc_attr($site_key); ?>">
<?php wp_nonce_field('kpt_contact_form', 'kpt_contact_nonce'); ?>
<div class="absolute -left-[9999px]" aria-hidden="true"><input type="text" name="website_url" tabindex="-1" autocomplete="off"><input type="text" name="company_name" tabindex="-1" autocomplete="off"></div>
<input type="hidden" name="form_elapsed" id="kpt-form-elapsed" value="0">
<div class="grid gap-6 md:grid-cols-2"><div><label class="mb-2 block font-semibold" for="first_name">First Name *</label><input class="<?php echo esc_attr($input); ?>" type="text" id="first_name" name="first_name" autocomplete="given-name" required maxlength="100"></div>
<div><label class="mb-2 block font-semibold" for="last_name">Last Name *</label><input class="<?php echo esc_attr($input); ?>" type="text" id="last_name" name="last_name" autocomplete="family-name" required maxlength="100"></div></div>
<div class="grid gap-6 md:grid-cols-2"><div><label class="mb-2 block font-semibold" for="email">Email Address *</label><input class="<?php echo esc_attr($input); ?>" type="email" id="email" name="email" autocomplete="email" required maxlength="254"></div>
<div><label class="mb-2 block font-semibold" for="phone">Phone</label><input class="<?php echo esc_attr($input); ?>" type="tel" id="phone" name="phone" autocomplete="tel" maxlength="30"></div></div>
<div><label class="mb-2 block font-semibold" for="url">Website URL</label><input class="<?php echo esc_attr($input); ?>" type="url" id="url" name="url" placeholder="https://" maxlength="2048"></div>
<div><label class="mb-2 block font-semibold" for="message">Message *</label><textarea class="<?php echo esc_attr($input); ?>" id="message" name="message" rows="7" maxlength="10000" required></textarea></div>
<div class="rounded-lg border border-kp-border bg-kp-surface p-5"><label class="flex items-start gap-3"><input type="checkbox" name="privacy_consent" required class="mt-1 h-5 w-5 shrink-0 accent-kp-light-blue"><span class="text-sm leading-7 text-kp-muted">I agree to the <a class="text-kp-light-blue underline" href="<?php echo esc_url(home_url('/about-kevin-pirnie/privacy-policy/')); ?>">Privacy Policy</a> and consent to the use of my contact details to respond to this inquiry. *</span></label></div>
<?php if ($site_key) : ?><input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response"><p class="text-xs text-kp-muted">Protected by reCAPTCHA. Google <a href="https://policies.google.com/privacy" class="underline">Privacy</a> and <a href="https://policies.google.com/terms" class="underline">Terms</a> apply.</p><?php endif; ?>
<button class="kp-btn kp-btn-primary w-full" id="kpt-contact-submit" type="submit" name="kpt_contact_submit" value="1">Send Message &rarr;</button>
<p id="kpt-form-error" role="alert" class="hidden text-sm text-kp-orange"></p>
</form></div>
