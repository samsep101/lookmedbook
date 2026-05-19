$(function(){
	
	$(document).on('click', '#menu-opener', function(){
		
		var $nav = $(this).closest('nav');
		$nav.toggleClass('open', !$nav.hasClass('open'));
		
	}).on('blur', '#menu-opener', function(){
		$(this).closest('nav').removeClass('open');
	});
	
});