# Kevin Pirnie V2 WordPress Theme

Dark, responsive WordPress classic theme built with PHP 8.4, Tailwind CSS 4, and esbuild.

## Local setup

1. Run `composer install` (required for the custom field framework).
2. Run `npm install`.
3. Run `npm run build` to generate `assets/css/theme.min.css` and `assets/js/theme.min.js`.
4. Install or copy this directory into `wp-content/themes/` and activate it in WordPress.

The ZIP contains **source only**; vendor modules, lock files, and compiled assets are omitted.
Do not activate the theme before building the CSS/JS and installing Composer dependencies.

## WordPress configuration

- Existing hero IDs, portfolio entries, CTA metadata, contact submissions, and menu location identifiers are retained.
- Assign a static homepage and a Posts page under **Settings > Reading**.
- Existing hero assignments use the `page_assignment` page metadata; featured images supply inner-page headers.
- The logo is the original provided SVG in `assets/kevinpirnie-logo-color.svg`, not altered.
- Custom Gutenberg blocks: `kpt/cta-block`, `kpt/portfolio-block`, `kpt/contact-form-block`.
- If WordPress displays a 404 for existing portfolio permalinks after switching themes, resave **Settings > Permalinks** once.
- The contact form uses the configured reCAPTCHA v3 keys in Theme Options; review consent handling and your privacy policy before production use.
- The resume template displays existing WordPress page content and offers a Print action; it does not recreate custom fields or markup from the old resume page.

## Existing URL compatibility

Portfolio CPT slug: `about-kevin-pirnie/enterprise-web-solutions-and-portfolio`.
Contact page: `/about-kevin-pirnie/lets-talk/`.

## Notes

- The portfolio Gutenberg block now renders a grid, although old content may call it a slideshow.
- Theme-specific custom fields remain implemented in the theme, consistent with the original project, so switching away disables their editor controls.
- Google Consent Mode defaults to denied; external scripts added by plugins require separate consent gating where legally required.
