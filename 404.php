
<?php

/**
 * 404 Template
 *
 * Displays the page not found error.
 *
 * @since 1.0.0
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package Kevin Pirnie V2
 */

// We don't want to allow direct access to this
defined('ABSPATH') || die('No direct script access allowed');

// get the header
get_header();

?>

<!-- 404 Content -->
<section class="flex min-h-[65vh] items-center py-20 md:py-28">

    <div class="kp-container">

        <div class="mx-auto max-w-2xl text-center">

            <!-- Error Code -->
            <p class="mb-6 text-7xl font-bold tracking-tight text-kp-orange md:text-9xl">
                404
            </p>

            <!-- Page Heading -->
            <h1 class="mb-6 text-3xl font-bold tracking-tight text-kp-text md:text-5xl">
                <?php esc_html_e('Page Not Found', 'kevinpirnie-v2'); ?>
            </h1>

            <!-- Description -->
            <p class="mx-auto mb-10 max-w-xl text-lg leading-8 text-kp-muted">
                <?php esc_html_e(
                    'The page you are looking for may have been moved, removed, or no longer exists.',
                    'kevinpirnie-v2'
                ); ?>
            </p>

            <!-- Search Form -->
            <form
                role="search"
                method="get"
                action="<?php echo esc_url(home_url('/')); ?>"
                class="mx-auto mb-10 flex max-w-lg flex-col gap-3 sm:flex-row"
            >

                <label for="404-search" class="sr-only">
                    <?php esc_html_e('Search the website', 'kevinpirnie-v2'); ?>
                </label>

                <input
                    id="404-search"
                    type="search"
                    name="s"
                    value="<?php echo esc_attr(get_search_query()); ?>"
                    placeholder="<?php esc_attr_e('Search the website...', 'kevinpirnie-v2'); ?>"
                    class="min-h-12 flex-1 rounded-lg border border-kp-border
                           bg-kp-surface px-4 py-3 text-kp-text
                           placeholder:text-kp-muted
                           focus:border-kp-light-blue focus:outline-none"
                >

                <button type="submit" class="kp-btn kp-btn-primary">
                    <?php esc_html_e('Search', 'kevinpirnie-v2'); ?>
                </button>

            </form>

            <!-- Home Link -->
            <a
                href="<?php echo esc_url(home_url('/')); ?>"
                class="inline-flex items-center gap-2 font-semibold
                       text-kp-light-blue hover:text-kp-text"
            >
                <span aria-hidden="true">&larr;</span>
                <?php esc_html_e('Return to Homepage', 'kevinpirnie-v2'); ?>
            </a>

        </div>

    </div>

</section>

<?php

// get the footer
get_footer();
