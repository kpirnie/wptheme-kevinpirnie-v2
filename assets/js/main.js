
/**
 * Theme JavaScript
 *
 * Handles frontend interactions.
 *
 * @since 1.0.0
 */

document.addEventListener('DOMContentLoaded', () => {

    // Mobile navigation
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-navigation');

    if (menuToggle && mobileMenu) {

        // toggle the mobile navigation
        const toggleMenu = (open) => {

            menuToggle.setAttribute('aria-expanded', String(open));
            mobileMenu.hidden = !open;
            mobileMenu.classList.toggle('hidden', !open);

        };

        // handle menu toggle clicks
        menuToggle.addEventListener('click', () => {

            const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';

            toggleMenu(!isOpen);

        });

        // close the menu when Escape is pressed
        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape' && !mobileMenu.hidden) {

                toggleMenu(false);
                menuToggle.focus();

            }

        });

        // close the menu when switching to desktop layout
        const desktopBreakpoint = window.matchMedia('(min-width: 1024px)');

        desktopBreakpoint.addEventListener('change', (event) => {

            if (event.matches) {
                toggleMenu(false);
            }

        });

    }

});
