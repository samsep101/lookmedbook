"use strict";
'use strict';

(function () {
    var items = document.querySelectorAll('.faq__item');
    Array.from(items).forEach(function (item) {
        item.addEventListener('click', function (e) {
            e.preventDefault();
            if (item.classList.contains('active')) {
                item.classList.remove('active');
            } else {
                item.classList.add('active');
            }
        });
    });
})();
'use strict';

(function () {
    var forms = document.querySelectorAll('form');
    function submitForm(e) {
        e.preventDefault();
        var _this = this;
        var xhr = new XMLHttpRequest();
        xhr.onload = function () {
            return console.log(xhr.responseText);
        };
        xhr.open(_this.method, _this.action, true);
        xhr.send(new FormData(_this));
        return false;
    }
    Array.from(forms).forEach(function (form) {
        form.addEventListener('submit', submitForm);
    });
})();
"use strict";
"use strict";
"use strict";
"use strict";
'use strict';

(function () {
    var partners = document.querySelectorAll('.partners__item');
    var modal = document.getElementById('modal');
    var overlay = document.getElementById('overlay');
    var body = document.querySelector('body');
    var close = document.getElementById('modalClose');

    Array.from(partners).forEach(function (partner) {
        partner.addEventListener('click', openModal);
    });

    function openModal() {
        modal.classList.add('open');
        overlay.classList.add('open');
        overlay.addEventListener('click', closeModal);
        close.addEventListener('click', closeModal);
        body.classList.add('fixed');
    }

    function closeModal() {
        modal.classList.remove('open');
        overlay.classList.remove('open');
        body.classList.remove('fixed');
        overlay.removeEventListener('click', closeModal);
        close.removeEventListener('click', closeModal);
    }
})();
"use strict";