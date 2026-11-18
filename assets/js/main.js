/** Frontend interactions. */
const init = () => {
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-navigation');
    if (menuToggle && mobileMenu) {
        const toggleMenu = (open) => {
            menuToggle.setAttribute('aria-expanded', String(open));
            mobileMenu.hidden = !open;
            mobileMenu.classList.toggle('hidden', !open);
        };
        menuToggle.addEventListener('click', () => toggleMenu(menuToggle.getAttribute('aria-expanded') !== 'true'));
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !mobileMenu.hidden) { toggleMenu(false); menuToggle.focus(); }
        });
        window.matchMedia('(min-width: 1024px)').addEventListener('change', (event) => { if (event.matches) toggleMenu(false); });
    }

    const searchToggle = document.getElementById('header-search-toggle');
    const headerSearch = document.getElementById('header-search');
    const searchInput = document.getElementById('header-search-input');
    if (searchToggle && headerSearch && searchInput) {
        const toggleSearch = (open) => {
            searchToggle.setAttribute('aria-expanded', String(open));
            headerSearch.hidden = !open;
            headerSearch.classList.toggle('hidden', !open);
            if (open) searchInput.focus();
            else if (headerSearch.contains(document.activeElement)) searchToggle.focus();
        };
        searchToggle.addEventListener('click', () => toggleSearch(searchToggle.getAttribute('aria-expanded') !== 'true'));
        document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !headerSearch.hidden) toggleSearch(false); });
        document.addEventListener('click', (event) => {
            if (!headerSearch.hidden && !headerSearch.contains(event.target) && !searchToggle.contains(event.target)) toggleSearch(false);
        });
    }

    const heroCarousel = document.getElementById('hero-carousel');
    if (heroCarousel) {
        const slides = Array.from(heroCarousel.querySelectorAll('.kp-hero-slide'));
        if (slides.length > 1) {
            let currentSlide = 0;
            let paused = false;
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            const showSlide = (index) => {
                const next = (index + slides.length) % slides.length;
                slides.forEach((slide, i) => { slide.hidden = i !== next; slide.classList.toggle('hidden', i !== next); });
                currentSlide = next;
            };
            const next = () => showSlide(currentSlide + 1);
            const prev = () => showSlide(currentSlide - 1);
            document.getElementById('hero-prev')?.addEventListener('click', prev);
            document.getElementById('hero-next')?.addEventListener('click', next);
            heroCarousel.addEventListener('mouseenter', () => { paused = true; });
            heroCarousel.addEventListener('mouseleave', () => { paused = false; });
            heroCarousel.addEventListener('focusin', () => { paused = true; });
            heroCarousel.addEventListener('focusout', (event) => { if (!heroCarousel.contains(event.relatedTarget)) paused = false; });
            heroCarousel.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowLeft') { event.preventDefault(); prev(); }
                if (event.key === 'ArrowRight') { event.preventDefault(); next(); }
            });
            showSlide(0);
            window.setInterval(() => { if (!paused && !reducedMotion.matches && !document.hidden) next(); }, 7000);
        }
    }

    const scrollToTop = document.getElementById('scroll-to-top');
    if (scrollToTop) {
        const update = () => {
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
        window.addEventListener('scroll', update, { passive: true });
        scrollToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'instant' : 'smooth' }));
        update();
    }

    const cookieBanner = document.getElementById('cookie-consent');
    const cookieAccept = document.getElementById('cookie-accept');
    const cookieDecline = document.getElementById('cookie-decline');
    if (cookieBanner && cookieAccept && cookieDecline) {
        const setCookieConsent = (accepted) => {
            const consent = accepted ? 'accepted' : 'declined';
            const state = accepted ? 'granted' : 'denied';
            const secure = window.location.protocol === 'https:' ? '; Secure' : '';
            document.cookie = `kp_cookie_consent=${consent}; Path=/; Max-Age=15552000; SameSite=Lax${secure}`;
            cookieBanner.hidden = true;
            if (typeof window.gtag === 'function') window.gtag('consent', 'update', {
                ad_storage: state, ad_user_data: state, ad_personalization: state, analytics_storage: state
            });
            window.location.reload();
        };
        cookieAccept.addEventListener('click', () => setCookieConsent(true));
        cookieDecline.addEventListener('click', () => setCookieConsent(false));
        cookieBanner.hidden = /(?:^|;\s*)kp_cookie_consent=(?:accepted|declined)(?:;|$)/.test(document.cookie);
    }
    // Contact form: generate reCAPTCHA on submission and measure elapsed interaction time.
    const contactForm = document.getElementById('kpt-contact-form');
    if (contactForm) {
        const started = Date.now();
        const elapsedField = document.getElementById('kpt-form-elapsed');
        const tokenField = document.getElementById('g-recaptcha-response');
        const submitButton = document.getElementById('kpt-contact-submit');
        const errorField = document.getElementById('kpt-form-error');
        const siteKey = contactForm.dataset.recaptchaSitekey;
        let submitting = false;
        contactForm.addEventListener('submit', async (event) => {
            if (elapsedField) elapsedField.value = String(Math.floor((Date.now() - started) / 1000));
            if (!siteKey || submitting) return;
            event.preventDefault();
            submitButton.disabled = true;
            try {
                if (typeof window.grecaptcha === 'undefined') {
                    await new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = 'https://www.google.com/recaptcha/api.js?render=' + encodeURIComponent(siteKey);
                        script.onload = resolve;
                        script.onerror = reject;
                        document.head.appendChild(script);
                    });
                }
                await new Promise(resolve => window.grecaptcha.ready(resolve));
                tokenField.value = await window.grecaptcha.execute(siteKey, { action: 'contact_form' });
                submitting = true;
                contactForm.requestSubmit(submitButton);
            } catch (error) {
                errorField.textContent = 'Verification could not load. Please try again.';
                errorField.classList.remove('hidden');
                submitButton.disabled = false;
            }
        });
    }

    // Resume print action.
    document.getElementById('kpt-print-resume')?.addEventListener('click', () => window.print());

};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
} else {
    init();
}
