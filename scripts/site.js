document.addEventListener('DOMContentLoaded', () => {
    animateMenuButton();
    initMobileSubmenus();
    initHeaderShrink();
    initScrollAnimations();
    initHeroAnims();
    initPhoneFieldValidation();
});

const initMobileSubmenus = () => {
    const mobileNav = document.querySelector('nav.mobile');
    if (!mobileNav) return;

    const parentItems = mobileNav.querySelectorAll('.menu-item-has-children');

    parentItems.forEach((item) => {
        const trigger = item.querySelector(':scope > a');
        const subMenu = item.querySelector(':scope > .sub-menu');

        if (!trigger || !subMenu) return;

        subMenu.style.overflow = 'hidden';
        subMenu.style.maxHeight = '0px';
        subMenu.style.transition = 'max-height 250ms ease';
        trigger.setAttribute('aria-expanded', 'false');

        trigger.addEventListener('click', (event) => {
            if (window.innerWidth > 767) return;

            event.preventDefault();

            const isOpen = trigger.getAttribute('aria-expanded') === 'true';

            if (isOpen) {
                subMenu.style.maxHeight = '0px';
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                subMenu.style.maxHeight = `${subMenu.scrollHeight}px`;
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });
};

const animateMenuButton = () => {
    const menuButton = document.getElementById('menu-button');
    const mobileNav = document.getElementById('mobile-nav-section');
    const navOverlay = document.getElementById('mobile-nav-overlay');

    if (!menuButton || !mobileNav || !navOverlay) return;

    const bars = menuButton.querySelector('.bars');
    const barEls = menuButton.querySelectorAll('.bar');
    const isLightMobileNav = mobileNav.classList.contains('light') || !!mobileNav.closest('.light') || !!mobileNav.querySelector('.light');
    let closeColorTimer;

    const setMenuButtonColor = (isOpen) => {
        if (!isLightMobileNav) return;

        const color = isOpen ? '#0D181C' : '#FFFFFF';
        barEls.forEach((bar) => {
            bar.style.background = color;
        });
    };

    const closeMenu = () => {
        bars.classList.remove('is-cross');
        navOverlay.classList.add('invisible', 'opacity-0');
        mobileNav.classList.add('translate-x-full');
        document.body.style.overflow = '';
        clearTimeout(closeColorTimer);
        closeColorTimer = setTimeout(() => {
            setMenuButtonColor(false);
        }, 300);
    };

    const openMenu = () => {
        clearTimeout(closeColorTimer);
        bars.classList.add('is-cross');
        navOverlay.classList.remove('invisible', 'opacity-0');
        mobileNav.classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
        setMenuButtonColor(true);
    };

    menuButton.addEventListener('click', () => {
        if (mobileNav.classList.contains('translate-x-full')) {
            openMenu();
        } else {
            closeMenu();
        }
    });

    navOverlay.addEventListener('click', closeMenu);

    window.addEventListener('resize', () => {
        if (window.innerWidth > 767 && !mobileNav.classList.contains('translate-x-full')) {
            closeMenu();
        }
    });
};


const initHeaderShrink = () => {
    const header = document.getElementById('site-header');
    if (!header) return;

    const root = document.documentElement;
    const isSeoTemplate = document.querySelector('.seo-page-template') !== null;
    let ticking = false;

    const syncHeaderHeightVar = () => {
        const headerHeight = Math.max(0, Math.round(header.getBoundingClientRect().height));
        root.style.setProperty('--site-header-height', `${headerHeight}px`);
    };

    const update = () => {
        if (isSeoTemplate) {
            header.classList.remove('is-compact');
        } else if (window.scrollY > 0) {
            header.classList.add('is-compact');
        } else {
            header.classList.remove('is-compact');
        }

        syncHeaderHeightVar();
    };

    const onScrollOrResize = () => {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(() => {
            update();
            ticking = false;
        });
    };

    // Initial state and listeners
    update();
    if (!isSeoTemplate) {
        window.addEventListener('scroll', onScrollOrResize, { passive: true });
    }
    window.addEventListener('resize', onScrollOrResize, { passive: true });
    header.addEventListener('transitionend', syncHeaderHeightVar);
};

const initScrollAnimations = () => {
    // Single-direction fade animations
    const simpleAnimElements = document.querySelectorAll('.anim-up, .anim-left, .anim-right');

    simpleAnimElements.forEach(el => {
        el.style.opacity = 0;

        if (el.classList.contains('anim-up')) el.style.transform = 'translateY(100px)';
        else if (el.classList.contains('anim-left')) el.style.transform = 'translateX(-20px)';
        else if (el.classList.contains('anim-right')) el.style.transform = 'translateX(20px)';

        el.style.transition = 'opacity 1s ease, transform 1s ease';
    });

    // Staggered elements
    const staggerElements = document.querySelectorAll('.anim-stagger');
    staggerElements.forEach(el => {
        el.style.opacity = 0;
        el.style.transform = 'translateX(-50px)';
        el.style.transition = 'opacity 0.5s ease, transform 1s ease';
    });

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;

            if (el.classList.contains('anim-stagger')) {
                const index = [...staggerElements].indexOf(el);
                setTimeout(() => {
                    el.style.opacity = 1;
                    el.style.transform = 'translateX(0)';
                }, index * 150);
            } else {
                // simple fade anims
                el.style.opacity = 1;
                el.style.transform = 'translateX(0) translateY(0)';
            }

            obs.unobserve(el);
        });
    }, { threshold: 0.1 });

    // Observe all elements
    [...simpleAnimElements, ...staggerElements].forEach(el => observer.observe(el));
};

const initHeroAnims = () => {
    const elements = document.querySelectorAll('.reveal');

    elements.forEach((el, i) => {
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateX(0) translateY(0)';
        }, 100);
    });

};

const initPhoneFieldValidation = () => {
    const phoneInputs = document.querySelectorAll('.phone-field');
    if (!phoneInputs.length) return;

    const phonePattern = /^\d{10,15}$/;

    const validatePhoneInput = (input) => {
        const value = input.value.trim();
        const isValid = phonePattern.test(value);

        if (!isValid) {
            input.setCustomValidity('Enter a valid phone number.');
        } else {
            input.setCustomValidity('');
        }

        return isValid;
    };

    phoneInputs.forEach((input) => {
        input.addEventListener('input', () => {
            validatePhoneInput(input);
        });

        const form = input.closest('form');
        if (!form) return;

        form.addEventListener('submit', (event) => {
            if (validatePhoneInput(input)) return;
            event.preventDefault();
            input.reportValidity();
            input.focus();
        });
    });
};

