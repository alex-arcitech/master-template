const initSnippetScroll = (scope = document) => {
    const modules = scope.querySelectorAll('[data-list-detailed-text-scroll]');

    modules.forEach((moduleEl) => {
        if (moduleEl.dataset.snippetScrollInit === '1') {
            return;
        }

        const cards = Array.from(moduleEl.querySelectorAll('[data-list-detailed-text-scroll-card]'));

        if (!cards.length) {
            return;
        }

        moduleEl.dataset.snippetScrollInit = '1';

        let ticking = false;

        const applyFocusState = () => {
            const viewportCenter = window.innerHeight * 0.5;
            let focusedIndex = 0;
            let closestDistance = Number.POSITIVE_INFINITY;

            cards.forEach((card, index) => {
                const rect = card.getBoundingClientRect();
                const cardCenter = rect.top + rect.height * 0.5;
                const distance = Math.abs(cardCenter - viewportCenter);

                if (distance < closestDistance) {
                    closestDistance = distance;
                    focusedIndex = index;
                }
            });

            cards.forEach((card, index) => {
                const isFocused = index === focusedIndex;
                card.classList.toggle('is-focused', isFocused);
            });
        };

        const onScrollOrResize = () => {
            if (ticking) {
                return;
            }

            ticking = true;
            window.requestAnimationFrame(() => {
                applyFocusState();
                ticking = false;
            });
        };

        window.addEventListener('scroll', onScrollOrResize, { passive: true });
        window.addEventListener('resize', onScrollOrResize);

        applyFocusState();
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initSnippetScroll(document);
});

if (window.acf && typeof window.acf.addAction === 'function') {
    window.acf.addAction('render_block_preview/type=list-detailed-text-scroll', ($el) => {
        const element = $el && $el[0] ? $el[0] : document;
        initSnippetScroll(element);
    });
}
