$(function(){

	$('[data-ac]').each(function(){
		var _ = $(this);
		var method = _.data('ac');
		_.devbridgeAutocomplete({
			lookup: function (query, done) {
				$.post('/admin/autocomplete/'+method, {search: query}, function(response){
					console.log(response);
					done(response);
				});
			},
			onSelect: function (suggestion) {
				if(suggestion.code){
					_.data('selected', suggestion.code);
				}
			}
		});
	});

	function postRelation(action, data, __callback){

		data.action = action;
		$.post('/admin/relations', data, function(response){
			if(response.success){
				show_message(response.success, 'success');
			}
			if(response.error){
				return show_message(response.error, 'error');
			}
			if(response.do && response.do == 'reload'){
				if(response.success){
					setTimeout(function(){
						return window.location.reload();
					}, 1000);
				} else {
					return window.location.reload();
				}
			}
			if(__callback) {
				return __callback(response);
			}
		});
	}

	function getSelectedCheckboxes(container){

		var $checkboxes = $(container).find('.__selected:checked');
		var selected = [];
		$checkboxes.each(function(){
			selected.push($(this).val());
		});
		return selected;
	}

	$(document).on('click', '[data-trigger]', function(e){
		e.preventDefault();
		$(document).trigger($(this).data('trigger'), this);
	}).on('change', '[data-onchange]', function(e){
		e.preventDefault();
		$(document).trigger($(this).data('onchange'), $(this));
	}).on('service_to_clinic', function(e, _this){
		var $container = $(_this).closest('.tools-panel');
		var $input = $container.find('[name=autocomplete_clinic]');
		postRelation('service_to_clinic', {
			linkto : $input.data('linkto'),
			selected : $input.data('selected')
		});
	}).on('service_to_clinic_delete', function(e, _this){
		var $row = $(_this).closest('tr');
		postRelation('service_to_clinic_delete', $row.data());
	}).on('service_to_clinic_auto_relations', function(e, _this){
		var specID = $(_this).val();
		if(specID !== '_init'){
			var data = $(_this).data();
			data.specID = specID;
			if(confirm('Будет выполнен автоматический поиск связей для всех клиник по выбранной специализации. Продолжить?')){
				postRelation('service_to_clinic_auto_relations', data);
			}
		}
	}).on('services_categories_clear', function(e, _this){

		if(confirm('Будут удалены все связи текущей услуги с клиниками и врачами!')){
			postRelation('services_categories_clear', {linkto: $('#current').val()});
		}

	}).on('services_to_doctor', function(e, _this){
		var selected = getSelectedCheckboxes('#doctors-table');
		if(selected.length){
			postRelation('services_to_doctor', {
				selected : selected,
				linkto: $('#doctors-table').data('linkto')
			});
		} else {
			postRelation('services_to_doctor', {
				unlinkAll : true,
				linkto: $('#doctors-table').data('linkto')
			});
		}
	}).on('services_to_doctor_delete', function(e, _this){
		var $row = $(_this).closest('tr');
		postRelation('services_to_doctor_delete', {
				doctorId : $row.data('docId'),
				linkto: $('#doctors-table').data('linkto')
			});
	});



});

function show_message(message, type){
	var $container = $('#message-box');
	var $message = $('<div>').addClass('alert').addClass(type).text(message).hide();
	$container.append($message.fadeIn(200, function(){
		setTimeout(function(){
			$message.fadeOut(500, function(){
				$message.remove();
			});
		}, 4000);
	}));
}