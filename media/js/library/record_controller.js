var RecordController = function () {
    var self = this;
    this.record_form_container = $('#record_form_container');

    this.showForm = function (doctor_id, clinic_id, disease_id) {
        self.popup = new Popup();
        self.popup.show(this.record_form_container.html(), '560px');

        $('.js-hide-on-record-complete').show();
        $('.recordFormSuccess').hide();

        var _form = $('.fancybox-overlay .recordPopupForm');

        _form.find('input[name=doctor_id]').val(doctor_id);
        _form.find('input[name=clinic_id]').val(clinic_id);
        _form.find('input[name=disease_id]').val(disease_id);

        var info = '';
        if (clinic_id && $('#clinic-card-' + clinic_id)) {
            var clinicCard = $('#clinic-card-' + clinic_id);
            var clinicPhone = clinicCard.data('phone');
            var formRowPhone = $('.record-form-info-phone');
            $('.record-form-info-phone .info-phone').text(clinicPhone);
            if (clinicPhone) {
                formRowPhone.removeClass('hidden');
            } else {
                formRowPhone.addClass('hidden');
            }
            info = clinicCard.data('info');
        }
        _form.find('.info-placeholder').html(info);

        if (window.grecaptcha) {
            var _grep_id = Math.round(Math.random() * 1000000) + 'google_captcha';
            _form.find('.g-recaptcha-add_review').empty().attr('id', _grep_id);
            window.grecaptcha.render(_grep_id, {
                'sitekey': window.gRecaptchaSiteKey
            });
        }

        _form.removeClass('lmmarked');
        LinkMapper_remap();

        $(".inputPhone").inputmask("+7 (999) 999-99-99");
    }
};

function onRecordSubmit(form) {
    var $form = $(form);
    if (!$form.find('input[name=full_name]').val()
        || !$form.find('input[name=phone]').val()
    ) {
        alert('Вы не заполнили поля имя или телефон');
        $form.find('.doSubmit').val('false');
    } else {
        $form.find('.doSubmit').val('');
    }
}

function recordComplete() {
    var data = $.parseJSON(linkMapper_answer);

    if (data['result']) {
        $('.recordFormSuccess').show();
        $('.js-hide-on-record-complete').hide();

        if ($.cookie('admitad_uid')) {
            admitad_submit(document, window, $.cookie('admitad_uid'));
        }

        if ($.cookie('utm_campaign') === 'mixuni') {
            alert(data['visit_id']);
            mixmarket_submit(data['visit_id']);
        }
    } else {
        $('.recordFormFail').show();
    }
}

function admitad_submit(d, w, uid) {
    w._admitadPixel = {
        response_type: 'img',
        action_code: '1',
        campaign_code: '0b5698ee5d'
    };
    w._admitadPositions = w._admitadPositions || [];
    w._admitadPositions.push({
        uid: uid,
        order_id: '',
        client_id: '',
        tariff_code: '1',
        currency_code: '',
        payment_type: 'lead'
    });
    var id = '_admitad-pixel';
    if (d.getElementById(id)) {
        return;
    }
    var s = d.createElement('script');
    s.id = id;
    var r = (new Date).getTime();
    var protocol = (d.location.protocol === 'https:' ? 'https:' : 'http:');
    s.src = protocol + '//cdn.asbmit.com/static/js/pixel.min.js?r=' + r;
    d.head.appendChild(s);
}

function mixmarket_submit(visit_id) {
    $('body').append(
        '<img src="http://mixmarket.biz/uni/tev.php?id=1294937486&r='
        + encodeURIComponent(document.referrer)
        + '&t=' + (new Date()).getTime()
        + '&a1=' + visit_id
        + '&a2=' + 0
        + '" width="1" height="1"/>'
    );
}

var recordController;
$(document).ready(function () {
    recordController = new RecordController;
    if (window.location.search.indexOf('sf=1') > 0) {
        recordController.showForm(0, 0, 0);
    }
});
