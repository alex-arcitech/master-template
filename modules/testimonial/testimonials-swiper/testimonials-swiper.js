(function () {
    function init(scope) {
        if (typeof window.Swiper !== 'function') return;

        var root = scope || document;
        var sections = root.querySelectorAll('.testimonials-swiper');

        sections.forEach(function (section) {
            var slider = section.querySelector('.swiper');
            var next = section.querySelector('.testimonial-next');
            var prev = section.querySelector('.testimonial-prev');

            if (!slider || !next || !prev) return;

            if (slider.swiper && typeof slider.swiper.destroy === 'function') {
                slider.swiper.destroy(true, true);
            }

            new window.Swiper(slider, {
                slidesPerView: 'auto',
                spaceBetween: 20,
                loop: true,
                speed: 700,
                watchOverflow: false,
                observer: true,
                observeParents: true,
                navigation: {
                    nextEl: next,
                    prevEl: prev,
                },
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        init(document);
    });

    if (window.acf && typeof window.acf.addAction === 'function') {
        window.acf.addAction('render_block_preview/type=testimonials-swiper', function ($el) {
            init($el && $el[0] ? $el[0] : document);
        });
    }
})();
