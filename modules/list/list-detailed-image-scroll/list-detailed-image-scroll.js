const initInfoImageScroll = (scope = document) => {
    const modules = scope.querySelectorAll('[data-list-detailed-image-scroll]');

    modules.forEach((moduleEl) => {
        if (moduleEl.dataset.iisInit === '1') {
            return;
        }

        const trackEl = moduleEl.querySelector('[data-iis-track]');
        const itemEls = Array.from(moduleEl.querySelectorAll('[data-iis-item]'));

        if (!trackEl || itemEls.length < 2) {
            return;
        }

        moduleEl.dataset.iisInit = '1';

        let ticking = false;
        let animating = false;
        let lastTs = 0;
        let currentTranslate = 0;
        let targetTranslate = 0;
        const MAX_TRANSLATE_SPEED = 160;
        const EPSILON = 0.1;
        const TRIGGER_VIEWPORT_RATIO = 0.7;

        const clamp = (value, min, max) => {
            if (value < min) {
                return min;
            }

            if (value > max) {
                return max;
            }

            return value;
        };

        const readTargetTranslate = () => {
            if (window.innerWidth < 768) {
                return 0;
            }

            const viewportHeight = window.innerHeight;
            const burstDistance = viewportHeight * 0.2;
            const scrollY = window.scrollY;
            const viewportCenter = viewportHeight * TRIGGER_VIEWPORT_RATIO;
            let indexProgress = 0;

            for (let i = 0; i < itemEls.length - 1; i += 1) {
                const nextRect = itemEls[i + 1].getBoundingClientRect();
                const nextCenterDoc = scrollY + nextRect.top + nextRect.height * 0.5;
                const centerTriggerScroll = nextCenterDoc - viewportCenter;
                const burstStart = centerTriggerScroll - burstDistance * 0.5;
                const burstEnd = centerTriggerScroll + burstDistance * 0.5;

                if (scrollY < burstStart) {
                    indexProgress = i;
                    break;
                }

                if (scrollY >= burstEnd) {
                    indexProgress = i + 1;
                    continue;
                }

                const local = clamp((scrollY - burstStart) / burstDistance, 0, 1);
                indexProgress = i + local;
                break;
            }

            const maxShift = itemEls.length - 1;
            const cappedProgress = clamp(indexProgress, 0, maxShift);
            return -100 * cappedProgress;
        };

        const setTrackTranslate = (value) => {
            trackEl.style.transform = `translate3d(0, ${value}%, 0)`;
        };

        const animateToTarget = (ts) => {
            if (!lastTs) {
                lastTs = ts;
            }

            const deltaSeconds = (ts - lastTs) / 1000;
            lastTs = ts;

            const maxStep = MAX_TRANSLATE_SPEED * deltaSeconds;
            const diff = targetTranslate - currentTranslate;

            if (Math.abs(diff) <= Math.max(maxStep, EPSILON)) {
                currentTranslate = targetTranslate;
                setTrackTranslate(currentTranslate);
                animating = false;
                lastTs = 0;
                return;
            }

            currentTranslate += Math.sign(diff) * maxStep;
            setTrackTranslate(currentTranslate);
            window.requestAnimationFrame(animateToTarget);
        };

        const update = () => {
            ticking = false;

            targetTranslate = readTargetTranslate();

            if (window.innerWidth < 768) {
                currentTranslate = 0;
                setTrackTranslate(0);
                animating = false;
                lastTs = 0;
                return;
            }

            if (!animating) {
                animating = true;
                window.requestAnimationFrame(animateToTarget);
            }
        };

        const requestUpdate = () => {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(update);
        };

        window.addEventListener('scroll', requestUpdate, { passive: true });
        window.addEventListener('resize', requestUpdate);
        requestUpdate();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initInfoImageScroll(document);
});

if (window.acf && typeof window.acf.addAction === 'function') {
    window.acf.addAction('render_block_preview/type=list-detailed-image-scroll', ($el) => {
        const element = $el && $el[0] ? $el[0] : document;
        initInfoImageScroll(element);
    });
}
