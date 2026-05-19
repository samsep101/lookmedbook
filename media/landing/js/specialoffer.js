$(document).ready(function () {
  $('#subscribe-form').submit(function () {
    var $form = $(this);
    if (!$form.find('[name="specialty"]:checked').length) {
      $form.find('.js--form-info').html('<span class="error">Не выбрана спецальность врача</span>');
      return false;
    }
    if (!$form.find('[name="phone"]').val()) {
      $form.find('.js--form-info').html('<span class="error">Не указан номер телефона</span>');
      return false;
    }
    if (window.yaCounterLookmedbook) {
      window.yaCounterLookmedbook.reachGoal('specialOfferMail');
    }
    $form.find('button').prop('disabled', true);
  });
});