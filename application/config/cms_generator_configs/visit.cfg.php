<?php
    $visit = array(
        'table' => DB_PREFIX . 'visit', /*имя таблицы*/
        'title' => 'Список визитов', /*меняется "ролей"*/
        'fields' => array(
            'id' => 'index', /*всегда*/
            'account' => array(
                'type' => 'reference',
                'table' => 'account',
                'field' => 'full_name'
            ),
            'account.open_visit' => 'just_text',
            'account.closed_visit' => 'just_text',
            'widget_site.name' => 'just_text',
            'doctor_name' => 'just_text',
            'clinic_name' => 'just_text',
            'full_name' => 'just_text',
            'processed_user' => array(
                'type' => 'dataauthuser',
                'cross_name' => 'email',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'account'
            ),
            'schedule_id' => array(
                'type' => 'category',
                'cross_name' => 'dt_start',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'schedule',
                'first' => array(
                    '0' => '',
                ),
                'filter' => 'true',
                'sort_by' => 'dt_end',
            ),
            'target_call_id' => array(
                'type' => 'category',
                'cross_name' => 'name_with_phone',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'target_call',
                'first' => array(
                    '0' => '-',
                ),
                'sort_by' => 'id',
                'short' => '1',
            ),
            'clinic_filter' => 'input',
            'disease_id' => array(
                'type'        => 'category',
                'cross_name'  => 'title',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'disease',
                'first'       => array(
                    '' => '',
                ),
                'sort_by'     => 'title',
            ),
            'clinic_id' => array(
                'type' => 'category',
                'cross_name' => 'name_with_address',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'clinic',
                'first' => array(
                    '0' => '',
                ),
                'filter' => 'true',
                'sort_by' => 'id',
                'sort_by' => 'name',
            ),
            'clinic_phone' => array(
                'type' => 'phone_text',
                'script' => '
                    $(document).ready(function(){
                        $(\'select[name="form[clinic_id]"]\').change(function(){
                            $(\'#clinic_phone\').html("");
                            var data = {
                                clinic_id : $(this).find(\'option:selected\').val()
                            };
                            Ajax.Post("/ajax/getPhoneByClinicId", data, function(data){
                                if (data.status == 0){
                                    $(\'#clinic_phone\').html(data.result);
                                }
                            });
                        });
                    });
                ',
            ),
            'doctor_id' => array(
                'type' => 'category',
                'cross_name' => 'full_name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'doctor',
                'first' => array(
                    '0' => '',
                ),
                'search_params' => array(
                    'is_virtual = ' => 'is_virtual',
                    'join' => 'doctor_specialty_to_clinic',
                    'doctor_specialty_to_clinic.clinic_id' => 'clinic_id',
                ),
                'filter' => 'true',
                'short' => true,
                'sort_by' => 'full_lower_name',
                'script' => '
                    $(document).ready(function(){
                        $(\'input[name="form[clinic_id]"]\').change(function(){
                            var prevValue = $(".value.form__doctor_id").html();
                            $(\'select[name="form[doctor_id]"]\').html("");
                            $(\'select[name="form[doctor_id]"]\').append(\'<option value="0"></option>\');
                            var data = {
                                clinic_id : $(this).val()
                            };
                            Ajax.Post("/ajax/getDoctorsByClinicId", data, function(data){
                                if (data.status == 0){
                                    var option = \'<option selected="" value="0"></option>\';

                                    for (var i in data.result)
                                    {
                                        var $option = $(\'<option value="\'+data.result[i].id+\'">\' + data.result[i].name + \'</option>\');
                                        if ($option.val() == prevValue) {
                                          $option.prop("selected", true);
                                        }
                                        $(\'select[name="form[doctor_id]"]\').append($option);
                                    }
                                     $(\'select[name="form[specialty_id]"]\').html("");

                                     var data1 = {
                                        doctor_id : $(\'[name="form[doctor_id]"]\').val(),
                                        clinic_id : $(\'[name="[clinic_id]"]\').val()
                                     };
                                     var prevSpecValue = $(".value.form__specialty_id").html();
                                     Ajax.Post("/ajax/getSpecialtiesByDoctorId", data1, function(data1){
                                        if (data1.status == 0){
                                        $(\'select[name="form[specialty_id]"]\').append(\'<option value="0"></option>\');
                                            for (var j in data1.result.specialties)
                                            {
                                                var selected = "";
                                                if (data1.result.specialties[j].id == prevSpecValue) {
                                                  selected = "selected";
                                                }
                                                var option = \'<option value="\'+data1.result.specialties[j].id+\'"\' + selected + \'>\' + data1.result.specialties[j].name + \'</option>\';
                                                $(\'select[name="form[specialty_id]"]\').append(option);
                                            }
                                        }
                                     });
                                }
                            });
                        });
                    });
                ',
            ),
            'specialty_id' => array(
                'type' => 'category',
                'cross_name' => 'name',
                'cross_index' => 'id',
                'cross_table' => DB_PREFIX . 'specialty',
                'first' => array(
                    '0' => '',
                ),
                'filter' => 'true',
                'sort_by' => 'name',
                'short' => true,
                'script' => '
                    $(document).ready(function(){
                        var prevValue = $(".value.form__specialty_id").html();
                        //$(\'select[name="form[specialty_id]"]\').html("");
                        var data = {
                            doctor_id : $(\'[name="form[doctor_id]"]\').val(),
                            clinic_id : $(\'[name="form[clinic_id]"]\').val(),
                            visit_id : $(\'input[name="form[id]"]\').val()
                        };
                        Ajax.Post("/ajax/getSpecialtiesByDoctorId", data, function(data){
                            if (data.status == 0){
                                $(\'select[name="form[specialty_id]"]\').append(\'<option value="0"></option>\');
                                for (var i in data.result.specialties)
                                {
                                    var selected = "";
                                    if (data.result.specialties[i].id == prevValue) selected = "selected";
                                    var option = \'<option value="\'+data.result.specialties[i].id+\'" \'+ selected +\' >\' + data.result.specialties[i].name + \'</option>\';
                                    $(\'select[name="form[specialty_id]"]\').append(option);
                                }
                            }
                        });

                        $(\'select[name="form[doctor_id]"]\').change(function(){
                            $(\'select[name="form[specialty_id]"]\').html("");
                            $(\'select[name="form[specialty_id]"]\').append(\'<option value="0"></option>\');
                            var data = {
                                doctor_id : $(this).val(),
                                clinic_id : $(\'[name="form[clinic_id]"]\').val()
                            };
                            if(data.doctor_id == "0") {
                                Ajax.Post("/ajax/getSpecialtiesByClinicId", data, function(data){
                                    if (data.status == 0){
                                        for (var i in data.result.specialties)
                                        {
                                            var selected = "";
                                            if (data.result.specialties[i].id == prevValue) {
                                              selected = "selected";
                                            }
                                            var option = \'<option value="\'+data.result.specialties[i].id+\'" \' + selected + \'>\' + data.result.specialties[i].name + \'</option>\';
                                            $(\'select[name="form[specialty_id]"]\').append(option);
                                        }
                                    }
                                });
                            } else {
                                Ajax.Post("/ajax/getSpecialtiesByDoctorId", data, function(data){
                                    if (data.status == 0){
                                        for (var i in data.result.specialties)
                                        {
                                            var selected = "";
                                            if (data.result.specialties[i].id == prevValue) {
                                              selected = "selected";
                                            }
                                            var option = \'<option value="\'+data.result.specialties[i].id+\'" \' + selected + \'>\' + data.result.specialties[i].name + \'</option>\';
                                            $(\'select[name="form[specialty_id]"]\').append(option);
                                        }
                                    }
                                });
                            }
                        });
                    });
                ',
            ),
            'phone' => 'phone_text',
            'formatted_phone' => 'just_text',
            'status_id' => array(
                'type' => 'radio',
                'values' => array(
                    '9' => '<b>К заполнению</b>',
                    '1' => '<b>Новая заявка</b>',
                    '5' => '<b>Звонок в клинику</b>',
                    '3' => 'Пациент записан',
                    '6' => '<b>Обратная связь</b>',
                    '7' => 'Был у врача',
                    '8' => 'Не был у врача',
                    '2' => 'Отменено',

                ),
                'text' => '
                    <script type="text/javascript">
                        $(document).ready(function(){
                            var active_status_id = $(\'input[name="form[status_id]"]:checked\').val();

                            var selected_statuses = [];
                            if (active_status_id == 1)
                            {
                                selected_statuses.push(5);
                            }

                            if (active_status_id == 5)
                            {
                                selected_statuses.push(2);
                                selected_statuses.push(3);
                            }

                            if (active_status_id == 6)
                            {
                                selected_statuses.push(7);
                                selected_statuses.push(8);
                            }

                            if(active_status_id == 9)
                            {
                                selected_statuses.push(5);
                            }

                            for(var i in selected_statuses)
                            {
                                $(\'span[name="status_id_\' +selected_statuses[i] + \'"]\').css(\'text-decoration\', \'underline\');
                            }
                        });
                    </script>
                ',
            ),
            'confirm_code' => 'input',
            'confirm_dt' => array('type' => 'date', 'show_time' => TRUE),
            'price' => 'input',
            'is_first_visit' => 'checkbox',

            'visit_range' => 'just_text',
            'visit_start_time' => array(
                'type' => 'date',
                'show_time' => TRUE
            ),
            'after_work' => array(
                'type'  => 'checkbox',
                'label' => 'Да/Нет',
            ),

            'comment' => array(
                'type' => 'text',
                'style' => 'width: 400px;'
            ),
            'admin_comment' => array(
                'type' => 'text',
                'style' => 'width: 400px;'
            ),
            'visit_number' => 'just_text',
            'yandex_id' => 'just_text',
            'create_time' => array(
                'type' => 'date',
                'show_time' => true
            ),
            'city_id' => array(
                'type' => 'category',
                'cross_index' => 'id',
                'cross_name' => 'name',
                'cross_table' => DB_PREFIX . 'city',
                'first' => array(
                    '0' => '',
                ),
                'filter' => 'true',
                'sort_by' => 'name',
            ),
            'appeal' => array(
                'type' => 'reference',
                'table' => 'appeal',
                'field' => 'id'
            ),
        ),
        'extra' => array(
            'yandex_log' => array(
                'table' => 'yandex_log',
                'title' => 'Переписка с Яндексом',
                'field' => 'visit_id'
            ),
        ),
        'generator' => array(
            'fields' => array(
                'id' => 'ID',
                'doctor_name' => 'Врач',
                'clinic_name' => 'Клиника',
                'disease_id' => 'Заболевание',
                'doctor_id' => 'Врач',
                'specialty_id' => 'Специализация врача',
                'clinic_id' => 'Клиника',
                'account' => 'Аккаунт пользователя',
                'account.open_visit' => 'Количество открытых заявок',
                'account.closed_visit' => 'Количество закрытых заявок',
                'full_name' => 'Имя пользователя',
                'processed_user' => 'Обработана пользователем',
                'schedule_id' => 'Время начала визита',
                'phone' => 'Номер телефона',
                'formatted_phone' => 'Номер телефона',
                'status_id' => 'Статус',
                'confirm_code' => 'Код подтверждения',
                'confirm_dt' => 'Дата подтверждения',
                'price' => 'Цена визита',
                'is_first_visit' => 'Флаг первого визита',
                'visit_start_time' => 'Записан на',
                'after_work' => 'После работы',
                'visit_number' => 'Номер заявки',
                'visit_range' => 'Желаемое время',
                'comment' => 'Комментарий пользователя',
                'admin_comment' => 'Комментарий '.SITE_NAME,
                'yandex_id' => 'ID яндекса',
                'create_time' => 'Дата и время создания',
                'clinic_filter' => 'Фильтр клиник',
                'clinic_phone' => 'Телефоны клиники',
                'city_id' => 'Город',
                'appeal' => 'Номер обращения',
                'target_call_id' => 'Целевой звонок',
                'widget_site.name' => 'Сайт'
            ),
            'list' => array(
                'fields' => array(
                    'visit_number',
                    'widget_site.name',
                    'full_name',
                    'processed_user',
                    'formatted_phone',
                    'status_id',
                    //'schedule_id',
                    'visit_start_time',
                    'create_time',
                    'city_id'
                ), /*поля кот. отображаются в списке "суперадминистратор"*/
                'title' => 'Список визитов',
                'join' => array(
                    array(
                        'table' => 'schedule',
                        'join_field' => 'visit.schedule_id',
                        'joined_field' => 'schedule.id',
                        'join_type' => 'LEFT OUTER JOIN'
                    ),
                ),
                'sort_by' => array(
                    array(
                        'field' => 'status_id',
                        'desc' => array(
                            VisitStatusModel::FEEDBACK,
                            VisitStatusModel::CALL_TO_CLINIC,
                            VisitStatusModel::TO_FILL,
                            VisitStatusModel::CHECKING,
                        ),
                        'field_order' => 'DESC'
                    ),
                    array(
                        'field' => 'id',
                        'desc' => 'DESC'
                    ),
                )
            ),
            'edit' => array(
                'fields' => array(
                    'Визит' => array(
                        'visit_number',
                        'city_id',
                        'widget_site.name',
                        'time_create',
                        'yandex_id',
                        'appeal',
                        'target_call_id',
                        //'clinic_name',
                        //'doctor_name',
                        'clinic_filter',
                        'disease_id',
                        'clinic_id',
                        'clinic_phone',
                        'doctor_id',
                        'specialty_id',
                        'full_name',
                        'processed_user',
                        'account',
                        'account.open_visit',
                        'account.closed_visit',
                        //'schedule_id',
                        'phone',
                        'status_id',
                        //'confirm_code',
                        //'confirm_dt',
                        'visit_range',
                        'visit_start_time',
                        'after_work',
                        'comment',
                        'admin_comment',
                    ),
                ),
                'title' => 'Редактирование',
                'submit' => 'Сохранить',
            ),
            'add' => array(
                'fields' => array(
                    'Визит' => array(
                        //'schedule_id',
                        'phone',
                        'status_id',
                        //'confirm_code',
                        //'confirm_dt',

                    ),
                ),
                'title' => 'Добавить',
                'submit' => 'Добавить',
            ),
        ),
    );

    CmsGeneratorConfigRegister::add('visit', $visit);