import './bootstrap';
import '../css/app.css';

document.addEventListener('DOMContentLoaded', () => {
    initRevealOnScroll();
    initDoctorCardTilt();
    initMobileNavbar();
});

/**
 * Reveal animation when elements enter viewport
 */
function initRevealOnScroll() {
    const revealElements = document.querySelectorAll('[data-reveal]');

    if (!revealElements.length) return;

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const el = entry.target;
                const delay = el.dataset.delay || '0';
                el.style.transitionDelay = `${delay}ms`;
                el.classList.add('is-visible');

                obs.unobserve(el);
            });
        },
        {
            threshold: 0.14,
            rootMargin: '0px 0px -60px 0px',
        }
    );

    revealElements.forEach((el) => {
        observer.observe(el);
    });
}

/**
 * Subtle mouse tilt for doctor cards
 */
function initDoctorCardTilt() {
    const cards = document.querySelectorAll('.doctor-card');

    cards.forEach((card) => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * -4;
            const rotateY = ((x - centerX) / centerX) * 4;

            card.style.transform = `
                perspective(1000px)
                rotateX(${rotateX}deg)
                rotateY(${rotateY}deg)
                translateY(-10px)
                scale(1.04)
            `;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
}

/**
 * Mobile navbar toggle
 */
function initMobileNavbar() {
    const navbars = document.querySelectorAll('[data-mobile-nav]');

    if (!navbars.length) return;

    const syncBodyState = () => {
        const hasOpenMenu = Array.from(navbars).some((navbar) => navbar.classList.contains('is-open'));
        document.body.classList.toggle('mobile-nav-open', hasOpenMenu && window.innerWidth <= 992);
    };

    navbars.forEach((navbar) => {
        const toggle = navbar.querySelector('[data-nav-toggle]');
        const menu = navbar.querySelector('[data-nav-menu]');

        if (!toggle || !menu) return;

        const closeMenu = () => {
            navbar.classList.remove('is-open');
            toggle.setAttribute('aria-expanded', 'false');
            syncBodyState();
        };

        const openMenu = () => {
            navbars.forEach((item) => {
                if (item !== navbar) {
                    item.classList.remove('is-open');
                    const itemToggle = item.querySelector('[data-nav-toggle]');
                    if (itemToggle) itemToggle.setAttribute('aria-expanded', 'false');
                }
            });

            navbar.classList.add('is-open');
            toggle.setAttribute('aria-expanded', 'true');
            syncBodyState();
        };

        toggle.addEventListener('click', () => {
            const isOpen = navbar.classList.contains('is-open');
            isOpen ? closeMenu() : openMenu();
        });

        menu.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('click', (event) => {
            if (!navbar.contains(event.target)) {
                closeMenu();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeMenu();
            }
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                closeMenu();
            } else {
                syncBodyState();
            }
        });
    });
}
