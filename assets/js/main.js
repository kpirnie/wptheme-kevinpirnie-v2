
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

    // Homepage hero carousel
    const heroCarousel = document.getElementById('hero-carousel');

    if (heroCarousel) {

        const slides = Array.from(heroCarousel.querySelectorAll('.kp-hero-slide'));
        const prevButton = document.getElementById('hero-prev');
        const nextButton = document.getElementById('hero-next');

        if (slides.length > 1) {

            let currentSlide = 0;
            let isPaused = false;

            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

            // show the requested slide
            const showSlide = (index) => {

                const nextSlide = (index + slides.length) % slides.length;

                slides.forEach((slide, slideIndex) => {

                    const active = slideIndex === nextSlide;

                    slide.hidden = !active;
                    slide.classList.toggle('hidden', !active);

                });

                currentSlide = nextSlide;

            };

            // display the next slide
            const nextSlide = () => {
                showSlide(currentSlide + 1);
            };

            // display the previous slide
            const prevSlide = () => {
                showSlide(currentSlide - 1);
            };

            // handle navigation buttons
            prevButton?.addEventListener('click', prevSlide);
            nextButton?.addEventListener('click', nextSlide);

            // pause automatic rotation on interaction
            heroCarousel.addEventListener('mouseenter', () => {
                isPaused = true;
            });

            heroCarousel.addEventListener('mouseleave', () => {
                isPaused = false;
            });

            heroCarousel.addEventListener('focusin', () => {
                isPaused = true;
            });

            heroCarousel.addEventListener('focusout', (event) => {

                if (! heroCarousel.contains(event.relatedTarget)) {
                    isPaused = false;
                }

            });

            // allow keyboard navigation
            heroCarousel.addEventListener('keydown', (event) => {

                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    prevSlide();
                }

                if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    nextSlide();
                }

            });

            // initialize the carousel
            showSlide(0);

            // automatically rotate slides
            window.setInterval(() => {

                if (
                    isPaused ||
                    reducedMotion.matches ||
                    document.hidden
                ) {
                    return;
                }

                nextSlide();

            }, 7000);

        }

    }

    // Header search
    const searchToggle = document.getElementById('header-search-toggle');
    const headerSearch = document.getElementById('header-search');
    const searchInput = document.getElementById('header-search-input');

    if (searchToggle && headerSearch && searchInput) {

        // toggle the search interface
        const toggleSearch = (open) => {

            searchToggle.setAttribute('aria-expanded', String(open));
            headerSearch.hidden = !open;
            headerSearch.classList.toggle('hidden', !open);

            if (open) {
                searchInput.focus();
            } else if (headerSearch.contains(document.activeElement)) {
                searchToggle.focus();
            }

        };

        // handle search toggle clicks
        searchToggle.addEventListener('click', () => {

            const isOpen = searchToggle.getAttribute('aria-expanded') === 'true';

            toggleSearch(!isOpen);

        });

        // close the search interface with Escape
        document.addEventListener('keydown', (event) => {

            if (event.key === 'Escape' && !headerSearch.hidden) {

                toggleSearch(false);
                searchToggle.focus();

            }

        });

        // close when clicking outside the search interface
        document.addEventListener('click', (event) => {

            if (
                !headerSearch.hidden &&
                !headerSearch.contains(event.target) &&
                !searchToggle.contains(event.target)
            ) {
                toggleSearch(false);
            }

        });

    }

    // Scroll to top
    const scrollToTop = document.getElementById('scroll-to-top');

    if (scrollToTop) {

        // show the button after scrolling 150px
        const updateScrollButton = () => {

            const visible = window.scrollY > 150;

            scrollToTop.classList.toggle('opacity-0', !visible);
            scrollToTop.classList.toggle('translate-y-4', !visible);
            scrollToTop.classList.toggle('pointer-events-none', !visible);

            scrollToTop.classList.toggle('opacity-100', visible);
            scrollToTop.classList.toggle('translate-y-0', visible);
            scrollToTop.classList.toggle('pointer-events-auto', visible);

            scrollToTop.setAttribute('aria-hidden', String(!visible));
            scrollToTop.tabIndex = visible ? 0 : -1;

        };

        // handle scroll events
        window.addEventListener('scroll', updateScrollButton, {
            passive: true,
        });

        // scroll to the top when clicked
        scrollToTop.addEventListener('click', () => {

            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            window.scrollTo({
                top: 0,
                behavior: reducedMotion ? 'instant' : 'smooth',
            });

        });

        // initialize the button state
        updateScrollButton();

    }

});
