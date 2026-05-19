$(function(){
	
	$.getScript('/media/js/chosen.jquery.js', function(){
		$('.chosen-select').chosen();
	});
	
	var $citydata = $('.__citydata');
	if($citydata.lenght){
		window.city_controller = new CityController(
			$citydata.data('id'),
			$citydata.data('lat'),
			$citydata.data('lng')
		);
	}
	
	$('header').each(function(){
		var controller = $(this).data('active');
		$(this).find('[data-controller]').each(function(){
			if($(this).data('controller') === controller){
				$(this).addClass('active');
			}
		});
	});
	
});