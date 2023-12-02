<?php
$suitable_specialty = array(
	'table'     => DB_PREFIX . 'suitable_specialty',
	'title'     => 'Подходящие специализации',
	'fields'    => array(
		'id'               => 'index',
		'specialty_id'          => array(
			'type'        => 'category',
			'cross_name'  => 'name',
			'cross_index' => 'id',
			'cross_table' => DB_PREFIX . 'specialty',
			'first'       => array(
				'0' => '',
			),
			'filter'      => 'true',
			'sort_by'     => 'name',
		),
		'suitable_specialty_id'          => array(
			'type'        => 'category',
			'cross_name'  => 'name',
			'cross_index' => 'id',
			'cross_table' => DB_PREFIX . 'specialty',
			'first'       => array(
				'0' => '',
			),
			'filter'      => 'true',
			'sort_by'     => 'name',
			'script' => '
					$(document).ready(function(){
						function loadSpecializationsList(){
							var purpose_of_visit_id = $(\'select[name="form[purpose_of_visit_id]"] :selected\').val();
							var suitable_specialty_id = $(\'select[name="form[suitable_specialty_id]"] :selected\').val();
							var specialty_id = $(\'select[name="form[specialty_id]"] :selected\').val();
							var data = {
								purpose_of_visit_id : purpose_of_visit_id
							};
							Ajax.Post(\'/ajax/getSpecialtyListByPurposeOfVisitId\', data, function(data){
								if (data.status == 0)
								{
									var options = HtmlViewHelper.getOptions(data.result, \'id\', \'name\');
									$(\'select[name="form[suitable_specialty_id]"]\').html(options);
									$(\'select[name="form[suitable_specialty_id]"] option[value="\' + specialty_id + \'"]\').remove();
									$(\'select[name="form[suitable_specialty_id]"]\').val(suitable_specialty_id);
								}
							});
						};

						$(\'select[name="form[purpose_of_visit_id]"]\').change(function(){
							loadSpecializationsList();
						});

						loadSpecializationsList();
					});
				'
		),
		'purpose_of_visit_id'          => array(
			'type'        => 'category',
			'cross_name'  => 'name',
			'cross_index' => 'id',
			'cross_table' => DB_PREFIX . 'purpose_of_visit',
			'first'       => array(
				'0' => '',
			),
			'filter'      => 'true',
			'sort_by'     => 'name',
			'script' 	=> '
				$(document).ready(function(){
					function setPurposes(){
						var val = $(\'select[name="form[purpose_of_visit_id]"] :selected\').val();
						var specialty_id = $(\'select[name="form[specialty_id]"] :selected\').val();

						var data = {
							specialty_id : specialty_id
						};

						Ajax.Post(\'/admin/ajax/getPurposesOfVisitBySpecialtyId\', data, function(data){
							if (data.status == 0){
								var block = $(\'<div></div>\');

								block.append(\'<option value="0"> </option>\');
								for (var i in data.result)
								{
									var option = data.result[i];
									block.append(\'<option value="\'+option.id+\'">\'+option.name+\'</option>\');
								}

								$(\'select[name="form[purpose_of_visit_id]"]\').html(block.html());
								$(\'select[name="form[purpose_of_visit_id]"]\').val(val);
							}
						});
					};

					setPurposes();

					$(\'select[name="form[specialty_id]"]\').change(function(){
						setPurposes();
					});
				});
			'
		)
	),
	'generator' => array(
		'fields' => array(
			'id'               => 'ID',
			'specialty_id'       => 'Специализация',
			'purpose_of_visit_id'      => 'Цель визита',
			'suitable_specialty_id'    => 'Подходящая специализация',
		),
		'list'   => array(
			'fields'  => array(
				'specialty_id',
				'purpose_of_visit_id',
				'suitable_specialty_id',
			),
			'title'   => 'Список',
			'sort_by' => array(
				array(
					'field' => 'id',
					'desc'  => 'DESC'
				),
			)
		),
		'edit'   => array(
			'fields'  => array(
				'Информация' => array(
					'specialty_id',
					'purpose_of_visit_id',
					'suitable_specialty_id',
				),
			),
			'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
			'title'   => 'Редактирование',
			'submit'  => 'Сохранить',
		),
		'add'    => array(
			'fields'  => array(
				'Информация' => array(
					'specialty_id',
					'purpose_of_visit_id',
					'suitable_specialty_id',
				),
			),
			'tooltip' => '<p>Для того, чтобы <b>изменения вступили в силу</b>, необходимо выйти и войти в систему.</p>',
			'title'   => 'Добавить',
			'submit'  => 'Добавить',
		),
	),
);

CmsGeneratorConfigRegister::add('suitable_specialty', $suitable_specialty);