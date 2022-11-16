$(function(){
	
	$(document).on('click', '.__showmore', function(){
		
		var _ = $(this);
		var $container = _.closest('.__container');
		var _text = _.text();

    if(_.hasClass('open')){
			$container.find('.remain').slideUp(400);
			_.removeClass('open');
			_.text(_.data('switch'));
			_.data('switch', _text);
    } else {
			$container.find('.remain').slideDown(400);
			_.addClass('open');
			_.text(_.data('switch'));
			_.data('switch', _text);
    }

    $('html, body').animate({
      scrollTop: $container.find(".short").offset().top - 100
    });
	});
});