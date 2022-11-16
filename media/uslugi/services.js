$(function($){
	
	$('.services-events').on('click', '.view-more-services', function(e){
		
		e.preventDefault();
		var _ = $(this);
		if(_.hasClass('open')){
			_.removeClass('open').text('Показать все услуги');
			$('#pricepage').addClass('collapsed');
		} else {
			_.addClass('open').text('Скрыть список услуг');
			$('#pricepage').removeClass('collapsed');
		}
	});
	
	$(document).on('change', '#service_name', function(){
		var _ = $(this);
		if(_.val()){
			window.location.href = _.val();
		}
	});
	
});