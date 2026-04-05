import './bootstrap';
import '../css/app.css';

document.addEventListener('DOMContentLoaded', () => {
    initRevealOnScroll();
    initDoctorCardTilt();
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