(function () {
    var galleries = document.querySelectorAll('.pm-product-gallery .woocommerce-product-gallery__image');
    if (!galleries.length) {
        return;
    }

    galleries.forEach(function (item) {
        var img = item.querySelector('img');
        if (!img) {
            return;
        }

        item.classList.add('pm-zoom-ready');

        item.addEventListener('mousemove', function (event) {
            var rect = item.getBoundingClientRect();
            var x = ((event.clientX - rect.left) / rect.width) * 100;
            var y = ((event.clientY - rect.top) / rect.height) * 100;

            x = Math.max(0, Math.min(100, x));
            y = Math.max(0, Math.min(100, y));

            item.style.setProperty('--pm-zoom-x', x + '%');
            item.style.setProperty('--pm-zoom-y', y + '%');
            item.classList.add('is-zooming');

            img.style.transformOrigin = x + '% ' + y + '%';
        });

        item.addEventListener('mouseleave', function () {
            item.classList.remove('is-zooming');
            item.style.removeProperty('--pm-zoom-x');
            item.style.removeProperty('--pm-zoom-y');
            img.style.transformOrigin = '50% 50%';
        });
    });
})();
