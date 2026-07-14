const initInfoImageSwiper = (scope = document) => {
    if (typeof window.Swiper !== 'function') {
        return;
    }

    const swiperEls = scope.querySelectorAll('[data-list-detailed-swiper]');

    swiperEls.forEach((swiperEl) => {
        if (swiperEl.dataset.swiperInit === '1') {
            return;
        }

        const nextEl = swiperEl.querySelector('.list-detailed-swiper-next');
        const prevEl = swiperEl.querySelector('.list-detailed-swiper-prev');

        if (!nextEl || !prevEl) {
            return;
        }

        swiperEl.dataset.swiperInit = '1';

        const swiperInstance = new window.Swiper(swiperEl, {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 750,
            breakpoints: {
                0: {
                    spaceBetween: 20,
                },
                768: {
                    spaceBetween: 0,
                },
            },
            navigation: {
                nextEl,
                prevEl,
            },
        });

        swiperEl.addEventListener('click', (event) => {
            const prevTrigger = event.target.closest('.list-detailed-swiper-prev-inline');
            const nextTrigger = event.target.closest('.list-detailed-swiper-next-inline');

            if (prevTrigger) {
                event.preventDefault();
                swiperInstance.slidePrev();
            }

            if (nextTrigger) {
                event.preventDefault();
                swiperInstance.slideNext();
            }
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initInfoImageSwiper(document);
});

if (window.acf && typeof window.acf.addAction === 'function') {
    window.acf.addAction('render_block_preview/type=list-detailed-swiper', ($el) => {
        const element = $el && $el[0] ? $el[0] : document;
        initInfoImageSwiper(element);
    });
}
