document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.accordion-block').forEach((block) => {
        block.querySelectorAll('.accordion-item').forEach((item) => {
            const trigger = item.querySelector('.accordion-trigger');
            const panel = item.querySelector('.accordion-panel');
            const arrow = item.querySelector('.accordion-arrow');

            if (!trigger || !panel) {
                return;
            }

            panel.hidden = true;
            panel.style.overflow = 'hidden';
            panel.style.height = '0px';
            panel.style.transition = 'height 260ms ease';

            if (arrow) {
                arrow.style.transition = 'transform 220ms ease';
                arrow.style.transform = 'rotate(270deg)';
            }

            trigger.addEventListener('click', () => {
                const expanded = trigger.getAttribute('aria-expanded') === 'true';

                trigger.setAttribute('aria-expanded', expanded ? 'false' : 'true');

                if (arrow) {
                    arrow.style.transform = expanded ? 'rotate(270deg)' : 'rotate(90deg)';
                }

                if (expanded) {
                    const startHeight = panel.scrollHeight;
                    panel.style.height = `${startHeight}px`;
                    panel.offsetHeight;
                    panel.style.height = '0px';

                    panel.addEventListener('transitionend', function onEnd(event) {
                        if (event.propertyName !== 'height') {
                            return;
                        }

                        panel.hidden = true;
                        panel.removeEventListener('transitionend', onEnd);
                    });

                    return;
                }

                panel.hidden = false;
                panel.style.height = '0px';
                panel.offsetHeight;
                panel.style.height = `${panel.scrollHeight}px`;

                panel.addEventListener('transitionend', function onEnd(event) {
                    if (event.propertyName !== 'height') {
                        return;
                    }

                    panel.style.height = 'auto';
                    panel.removeEventListener('transitionend', onEnd);
                });
            });
        });
    });
});
