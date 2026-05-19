$(document).ready(function(){
	var filling_select = $('select.filling_select');
	var curr_select_id = filling_select.length;

	function fill_options(select, answ, val) {
		if(!answ.result){
			return;
		}

		for(var key in answ.result) {
			var selected = (key == val) ? 'selected="selected"': '';
			var option = '<option value="' + key + '" ' + selected + '>' + answ.result[key] + '</option>';
			select.append(option);
		}
	}



	function fill_edit_selects() {
		if(!curr_select_id){
			return;
		}
		var select = $(filling_select[curr_select_id-1]);
		curr_select_id--;

		var select_id = select.attr('class').replace('filling_select ','');

		var data = {};
		var json_line = $('.cross_name.'+select_id).html();
		data.cross_name = json_line?JSON.parse(json_line):'';
    var json_line = $('.cross_index.'+select_id).html();
    data.cross_index = json_line?JSON.parse(json_line):'';
		var json_line = $('.cross_table.'+select_id).html();
		data.cross_table = json_line?JSON.parse(json_line):'';
		var json_line = $('.sort_param.'+select_id).html();
		data.sort_param = json_line?JSON.parse(json_line):'';
		var json_line = $('.search_param.'+select_id).html();
		data.search_param = json_line?JSON.parse(json_line):'';
		var json_line = $('.value.'+select_id).html();
		var value = json_line?JSON.parse(json_line):'';

		var rand = Math.random();
		$.ajax({
			url: '/admin/ajax/getSelectList?rand='+rand,
			type: 'POST',
			dataType: 'json',
			data: data,
			success: function(answ){
				fill_options(select, answ, value);
				fill_edit_selects();
			}
		});
	}

	fill_edit_selects();


	//=====================================================================


	var filling_input = $('input.filling_input');
	var curr_input_i = filling_input.length;
	var page_inputs = {};


	function fill_value(input_id, value, label) {
		page_inputs[input_id].visible.val(label);
		page_inputs[input_id].hiddn.val(value);
    page_inputs[input_id].visible.trigger('change');
    page_inputs[input_id].hiddn.trigger('change');
	}


	function fill_input_ajax_search(senddata, func_exec) {
		$.ajax({
			url: '/admin/ajax/getSelectList',
			type: 'POST',
			dataType: 'json',
			data: senddata.data,
			success: function (answ) {
				eval(func_exec);
			}
		});
	}

	function fill_first_value(answ, input_id, val) {
		if(!answ.result){
			return false;
		}
		for(var key in answ.result) {
			if(val == key) {
				var title = answ.result[key];
				fill_value(input_id, key, title);
				return false;
				break;
			}
		}
	}

	var timers = [];

	function fill_avail_values(answ, suggest) {
		var suggestions = [];
		if(answ.result) {
			for (var key in answ.result) {
				var label = answ.result[key];
				suggestions.push([key, label]);
			}
		}
		suggest(suggestions);
	}


	function fill_input_prepare() {
		if(!curr_input_i){
			return;
		}
		var input = $(filling_input[curr_input_i-1]);
		curr_input_i--;

		var input_id = input.attr('class').replace('filling_input ','');

		page_inputs[input_id] = {'visible':input, 'hiddn':$('input.filling_value.'+input_id)};

		var data = {};
		var json_line = $('.cross_name.'+input_id).html();
		data.cross_name = json_line?JSON.parse(json_line):'';
    var json_line = $('.cross_index.'+input_id).html();
    data.cross_index = json_line?JSON.parse(json_line):'';
		var json_line = $('.cross_table.'+input_id).html();
		data.cross_table = json_line?JSON.parse(json_line):'';
		var json_line = $('.sort_param.'+input_id).html();
		data.sort_param = json_line?JSON.parse(json_line):'';
		var json_line = $('.search_param.'+input_id).html();
		data.search_param = json_line?JSON.parse(json_line):'';
		var json_line = $('.value.'+input_id).html();
		data.value = json_line?JSON.parse(json_line):'';
		page_inputs[input_id].data = data;

		if(data.value && data.value != '0') {
			var senddata = {data:data, input_id:input_id, inp_val:data.value};
			fill_input_ajax_search(senddata, 'fill_first_value(answ, senddata.input_id, senddata.inp_val)');
		}
		page_inputs[input_id].data.value = '';

		page_inputs[input_id].visible.autoComplete({
			minChars: 4,
			source: function(term, suggest){
				if(timers[input_id]){
					clearTimeout(timers[input_id]);
				}
				page_inputs[input_id].data.title = term;
				var senddata = {data:page_inputs[input_id].data, suggest:suggest, input_id:input_id};

				timers[input_id] = setTimeout(function(){ fill_input_ajax_search(senddata, 'fill_avail_values(answ, senddata.suggest)'); }, 1500);
			},
			renderItem: function (item, search){
				return '<div class="autocomplete-suggestion" data-val="'+item[1]+'" data-key="'+item[0]+'" data-input-id="'+input_id+'">'+item[1]+'</div>';

			},
			onSelect: function(e, term, item){
				page_inputs[input_id].hiddn.val(item.data('key'));
        page_inputs[input_id].hiddn.trigger('change');
			}
		});
		fill_input_prepare();
	}

	fill_input_prepare();

});

