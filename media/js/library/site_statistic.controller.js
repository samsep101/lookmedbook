var SiteStatisticController = function(docx_error) {
    var self = this;

    this.city_value = null;
    this.docx_error = docx_error;
    self.doctors_without_clinics_click = null;

    this.init = function(){
        if (self.docx_error) {
            $('.docx-error').css('display','inline-block');
            $('.docx-error').delay(2000).fadeOut(2000);
        }

        if ($('#clinic-visits').hasClass('active')){
            $('#datepicker').css('display', 'block');
            $('.visits-info').css('display', 'block');
        } else {
            $('#datepicker').css('display', 'none');
            $('.visits-info').css('display', 'none');
        }

        $('.etabs').click(function(){
            if ($('#clinic-visits').hasClass('active')){
                $('#datepicker').css('display', 'block');
                $('.visits-info').css('display', 'block');
            } else {
                $('#datepicker').css('display', 'none');
                $('.visits-info').css('display', 'none');
            }
        });

        self.city_value = $('.select-city select option:selected').val();

        $( "#datepicker" ).datepicker({
            monthNames: [ "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь" ]
        });

        self.getStatistic();

        $(document).on('click', 'a.ui-corner-all', function () {
            self.getVisitInformation();
        });

        $('.select-city select').change(function(){
            self.city_value = $('.select-city select option:selected').val();
            if (self.city_value){
                self.getStatistic();
            }
        });

        $('li.tab.docotor_tab').click(function(){
            $(this).toggleClass('act');

            if ($(this).hasClass('act')){
                $('#doctors_without_clinic').css('display','block');
            } else {
                $('#doctors_without_clinic').css('display','none');
            }
        });

        $('#visit-statistic select').change(function(){
            self.formVisitStatisticHref();
        });
    }

    this.getStatistic = function(){
        Ajax.Get('/system/ajaxGetStatistics', {city_value: self.city_value}, function(data){
            if (data.status == 0){
                $('#clinic-doctor-specialty').html(data.result.clinic_doctor_specialty);
                $('#specialties-doctor-clinic').html(data.result.specialties_doctor_clinic);
                $('#specialties').html(data.result.specialties);

                self.getVisitInformation();
                self.formVisitStatisticHref();
            }
        });
    }

    this.getVisitInformation = function(){
        Ajax.Get('/system/ajaxGetVisitInformationByMonth', {city_value: self.city_value, month: $('.ui-datepicker-month').html().toLowerCase(), year: $('.ui-datepicker-year').html() }, function(data){
            if (data.status == 0){
                $('#clinic-visits').html(data.result.html);

                var visits = data.result.information;
                $('.visit-information').html('');
                $('.clinic-name').html('');

                var visit_information = null;
                var visit_month = null;
                var visit_year = null;
                var clinic_name = null;
                var clinic_id = null;
                var head = null;
                var count_all_visit_by_clinic = 0;
                var count_end_visit = 0;

                var all_visits = 0;
                var visited_visits = 0;
                var not_visited_visits = 0;
                var canceled_visits = 0;
                var appeals = 0;

                if (visits){
                    appeals = visits[0];

                    for (var i = 2; i < visits.length; i++){
                        for (var j = 0; j < visits[i].length; j++){
                            if (visits[i][j].visit.status == 7)
                                visited_visits++;

                            if (visits[i][j].visit.status == 8)
                                not_visited_visits++;

                            if (visits[i][j].visit.status == 2)
                                canceled_visits++;

                            count_all_visit_by_clinic++;
                            head = '<tr><td width="75">№ заявки</td>' +
                                '<td width="160">Дата заявки</td>' +
                                '<td width="270">Комментарий call-центра</td>' +
                                '<td width="270">Комментарий по заявке</td>' +
                                '<td width="160">Дата посещения</td>' +
                                '<td width="200">К какому специалисту (специализация)</td>' +
                                '<td width="300">ФИО врача</td>' +
                                '<td width="300">ФИО пациента</td></tr>';

                            html = '<tr><td>'+visits[i][j].visit.id+'</td>' +
                                        '<td>'+ ((visits[i][j].visit.create_time) ? visits[i][j].visit.create_time : '') +'</td>' +
                                        '<td>'+ ((visits[i][j].visit.admin_comment) ? visits[i][j].visit.admin_comment : '') +'</td>' +
                                        '<td>'+ ((visits[i][j].visit.comment) ? visits[i][j].visit.comment : '') +'</td>';
                            if (visits[i][j].visit.status == 7){
                                count_end_visit++
                                html += '<td>'+ ((visits[i][j].visit.visit_time) ? visits[i][j].visit.visit_time: '') +'</td>' +
                                    '<td>'+ ((visits[i][j].visit.specialty_name) ? visits[i][j].visit.specialty_name : '')+'</td>' +
                                    '<td>'+((visits[i][j].visit.doctor_last_name) ? visits[i][j].visit.doctor_last_name : '') +' '+ ((visits[i][j].visit.doctor_first_name) ? visits[i][j].visit.doctor_first_name : '') +' '+((visits[i][j].visit.doctor_second_name) ? visits[i][j].visit.doctor_second_name : '') +'</td>' +
                                    '<td>'+visits[i][j].visit.account+'</td></tr>';
                            } else {
                                html += '<td></td>' +
                                    '<td></td>' +
                                    '<td></td>' +
                                    '<td></td>';
                            }

                            visit_information += html;
                            clinic_name = ((visits[i][j].visit.clinic_name) ? visits[i][j].visit.clinic_name : 'Визиты без клиники')+ ' ' + count_all_visit_by_clinic + ' / ' + count_end_visit;
                            clinic_id = visits[i][j].visit.clinic_id;
                        }

                        if (count_end_visit == 0){
                            $('.docx-href-'+i).css('display', 'none');
                        } else {
                            $('.docx-href-'+i).css('display', 'inline');
                        }

                        if (visit_information){
                            $('.visits-table-'+i).css('display', '');
                        } else {
                            $('.visits-table-'+i).css('display', 'none');
                        }
                        $('.visit-information-'+ i).append(visit_information);
                        visit_information = null;
                        $('.clinic-name-'+ i).html(clinic_name);
                        clinic_name = null;
                        $('.visits-head-' + i).html(head);
                        head = null;
                        //$('.docx-button-' + i).attr('data-clinic', clinic_id);
                        //clinic_id = null;

                        $('.docx-href-' + i).attr('href','/system/generateDocx?clinic_id='+clinic_id+'&visit_month='+$('.ui-datepicker-month').html().toLowerCase()+'&visit_year='+$('.ui-datepicker-year').html());
                        clinic_id = null;

                        all_visits += count_all_visit_by_clinic;

                        count_all_visit_by_clinic = 0;
                        count_end_visit = 0;
                    }

                    $('.visits-table-'+visits.length).css('display', 'none');
                }
/*
                $('.docx-button').click(function(){

                    clinic_id = $(this).data('clinic');
                    visit_month = $('.ui-datepicker-month').html().toLowerCase();
                    visit_year = $('.ui-datepicker-year').html();
                    Ajax.Post('/system/ajaxGenerateDocx', {clinic_id: clinic_id, visit_month:visit_month, visit_year:visit_year}, function(data){

                    });
                });
*/
                $('.visits-info #appeal').html(appeals);
                $('.visits-info #all').html(all_visits);
                $('.visits-info #visited').html(visited_visits);
                $('.visits-info #not_visited').html(not_visited_visits);
                $('.visits-info #visited_and_not_visited').html(visited_visits + not_visited_visits);
                $('.visits-info #canceled').html(canceled_visits);
            }
        });
    }

    this.formVisitStatisticHref = function(){
        $('.visit-statistic-xls').attr('href', '/system/generateVisitStatisticXls?month_from='+$('.visit-month-from').val()+'&year_from='+$('.visit-year-from').val()+'&month_to='+$('.visit-month-to').val()+'&year_to='+$('.visit-year-to').val());
    }
};