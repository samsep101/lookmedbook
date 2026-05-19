var LearnController = function () {
    var self = this;
    this.learn_form_container = $('#learn_form_container');

    this.showForm = function (doctor_id, clinic_id, disease_id) {
        self.popup = new Popup();
        self.popup.show(this.learn_form_container.html(), '560px');

        $('.js-hide-on-record-complete').show();
        $('.recordFormSuccess').hide();

        var _form = $('.fancybox-overlay .learnPopupForm');

        _form.find('input[name=doctor_id]').val(doctor_id);
        _form.find('input[name=clinic_id]').val(clinic_id);
        _form.find('input[name=disease_id]').val(disease_id);

        var _grep_id = Math.round(Math.random() * 1000000) + 'google_captcha';
        _form.find('.g-recaptcha-add_review').empty().attr('id', _grep_id);
        grecaptcha.render(_grep_id, {
            'sitekey': window.gRecaptchaSiteKey
        });

        _form.removeClass('lmmarked');
        LinkMapper_remap();

        $(".inputPhone").inputmask("+7 (999) 999-99-99");
    }
};

var learnController;
$(document).ready(function () {
    learnController = new LearnController();
    if (window.location.search.indexOf('sl=1') > 0) {
        learnController.showForm(0, 0, 0);
    }
});
