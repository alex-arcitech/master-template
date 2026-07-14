document.addEventListener('DOMContentLoaded', () => {
    const accordions = document.querySelectorAll('.faq-3-accordion');
    const animatingClass = 'is-animating';

    const setArrowState = (trigger, expanded) => {
        const arrow = trigger.querySelector('.faq-accordion-arrow');

        if (!arrow) {
            return;
        }

        arrow.style.transition = 'transform 220ms ease';
        arrow.style.transform = expanded ? 'rotate(90deg)' : 'rotate(-90deg)';
    };

    const setExpanded = (trigger, content, expanded) => {
        trigger.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        content.classList.remove(animatingClass);
        setArrowState(trigger, expanded);

        if (expanded) {
            content.hidden = false;
            content.style.height = 'auto';
            content.style.opacity = '1';
        } else {
            content.hidden = true;
            content.style.height = '0px';
            content.style.opacity = '0';
        }
    };

    const openTray = (trigger, content) => {
        if (content.classList.contains(animatingClass)) {
            return;
        }

        content.classList.add(animatingClass);
        content.hidden = false;
        content.style.height = '0px';
        content.style.opacity = '0';

        content.offsetHeight;

        const targetHeight = content.scrollHeight;
        content.style.height = `${targetHeight}px`;
        content.style.opacity = '1';
        trigger.setAttribute('aria-expanded', 'true');
        setArrowState(trigger, true);

        const onEnd = (event) => {
            if (event.propertyName !== 'height') {
                return;
            }

            content.style.height = 'auto';
            content.classList.remove(animatingClass);
            content.removeEventListener('transitionend', onEnd);
        };

        content.addEventListener('transitionend', onEnd);
    };

    const closeTray = (trigger, content) => {
        if (content.classList.contains(animatingClass)) {
            return;
        }

        content.classList.add(animatingClass);
        const startHeight = content.scrollHeight;
        content.style.height = `${startHeight}px`;
        content.style.opacity = '1';

        content.offsetHeight;

        content.style.height = '0px';
        content.style.opacity = '0';
        trigger.setAttribute('aria-expanded', 'false');
        setArrowState(trigger, false);

        const onEnd = (event) => {
            if (event.propertyName !== 'height') {
                return;
            }

            content.hidden = true;
            content.classList.remove(animatingClass);
            content.removeEventListener('transitionend', onEnd);
        };

        content.addEventListener('transitionend', onEnd);
    };

    accordions.forEach((accordion) => {
        const triggers = accordion.querySelectorAll('.faq-accordion-trigger');

        triggers.forEach((trigger) => {
            const item = trigger.closest('.faq-accordion-item');
            const content = item ? item.querySelector('.faq-accordion-content') : null;

            if (!item || !content) {
                return;
            }

            content.style.overflow = 'hidden';
            content.style.transition = 'height 260ms ease, opacity 220ms ease';
            setExpanded(trigger, content, false);
        });

        triggers.forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const item = trigger.closest('.faq-accordion-item');
                const content = item ? item.querySelector('.faq-accordion-content') : null;

                if (!item || !content) {
                    return;
                }

                const isExpanded = trigger.getAttribute('aria-expanded') === 'true';

                if (isExpanded) {
                    closeTray(trigger, content);
                } else {
                    triggers.forEach((otherTrigger) => {
                        if (otherTrigger === trigger) {
                            return;
                        }

                        const otherItem = otherTrigger.closest('.faq-accordion-item');
                        const otherContent = otherItem ? otherItem.querySelector('.faq-accordion-content') : null;

                        if (!otherItem || !otherContent) {
                            return;
                        }

                        const otherExpanded = otherTrigger.getAttribute('aria-expanded') === 'true';
                        if (otherExpanded) {
                            closeTray(otherTrigger, otherContent);
                        }
                    });

                    openTray(trigger, content);
                }
            });
        });
    });
});
