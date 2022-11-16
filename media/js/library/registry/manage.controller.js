var ManageController = function () {

    this.search_text = '';
    this.search_container = '';
    this.drop_down_container = '';
    this.url_page = '';
    this.page = 1;
    this.registry_user_id = null;
    this.city_id = 0;
    this.page_name = null;

    var controller = this;

    this.init = function () {

        $('.btn-search').click(function () {
            controller.search_text = $(this).parent().children('.txt').val();
            controller.search_container = $(this).attr('data-id');

            if (controller.search_container == 'doctor')
                controller.url_page = 'doctors';
            else if (controller.search_container == 'clinic')
                controller.url_page = 'clinics';
            else {
                controller.url_page = controller.search_container;
                controller.registry_user_id = $('select[name="freelancer-pick"]').val();
                if ($('.txt').val() != $('.txt').attr('placeholder'))
                    controller.search_text = $('.txt').val();
                else
                    controller.search_text = '';
                queryString.set('query', controller.search_text);
                queryString.set('page', 1);
                queryString.load();
            }

            window.location.href = '/registry/manage/' + controller.url_page + '?query=' + encodeURIComponent(controller.search_text);
        });

        $('input.txt').keyup(function (event) {
            if (event.keyCode==13) {
                controller.search_text = $(this).val();
                controller.search_container = $(this).attr('data-id');
                if (controller.search_container == 'doctor')
                    controller.url_page = 'doctors';
                else if (controller.search_container == 'clinic')
                    controller.url_page = 'clinics';
                else {
                    controller.url_page = controller.search_container;
                    controller.registry_user_id = $('select[name="freelancer-pick"]').val();
                    if ($('.txt').val() != $('.txt').attr('placeholder'))
                        controller.search_text = $('.txt').val();
                    else
                        controller.search_text = '';
                    window.location.href = '/registry/manage/' + controller.url_page + '?query=' + encodeURIComponent(controller.search_text) + '&registry_user_id=' + controller.registry_user_id;
                }
                window.location.href = '/registry/manage/' + controller.url_page + '?query=' + encodeURIComponent(controller.search_text);
            }
            else {
                controller.search_text = $(this).val();
                controller.search_container = $(this).attr('data-id');
                controller.drop_down_container = $(this).parent().children('.drop-menu');
                controller.updateDropDownList();
            }
        });

        $('body').click( function(event){
            if( $(event.target).closest(".drop-menu").length )
                return;
            $('.drop-menu').slideUp();
            $('.drop-menu').html('');
        });

        $(document).on('click','.paging-previous, .paging-next',function () {
            if ($(this).hasClass('paging-previous')) controller.page = controller.page-1;
            else if ($(this).hasClass('paging-next')) controller.page = controller.page+1;
            controller.search_text = $('input.txt').val();
            if ($('input.txt').attr('data-id') == 'doctor')
                controller.searchDoctor();
            else if ($('input.txt').attr('data-id') == 'clinic')
                controller.searchClinic();
        });

        $('select[name="city_id"]').change( function(){
            controller.city_id = $('select[name="city_id"]').val();
            controller.page_name = $('select[name="city_id"]').attr('data-page-name');
            if (controller.page_name == 'main_page') {
                Ajax.Post('/registry/ajax/getDoctorAndClinicsListByCityId', {city_id: controller.city_id}, function (data) {
                    if (data.status == 0) {
                        $('.filter-clinic-list').html(data.result.clinics);
                        $('.filter-doctor-list').html(data.result.doctors);
                    }
                });
            } else if (controller.page_name == 'clinics') {
                Ajax.Post('/registry/ajax/getClinicsListByCityId', {city_id: controller.city_id}, function (data) {
                    if (data.status == 0) {
                        $('.filter-clinic-list').html(data.result.clinics);
                    }
                });
            } else if (controller.page_name == 'doctors') {
                Ajax.Post('/registry/ajax/getDoctorsListByCityId', {city_id: controller.city_id}, function (data) {
                    if (data.status == 0) {
                        $('.filter-doctor-list').html(data.result.doctors);
                    }
                });
            }
        });

    };

    this.updateDropDownList = function () {
        if (controller.search_text.length > 0) {
            var url = '';

            if (controller.search_container == 'doctor')
            {
                url = '/registry/ajax/getDoctors';
            } else if (controller.search_container == 'clinic') {
                url = '/registry/ajax/getClinics';
            }

            Ajax.Get(url, {query:controller.search_text, container:controller.search_container}, function (data) {
                if (data.status == 0) {
                    controller.drop_down_container.html(data.result);
                    controller.drop_down_container.slideDown();
                }

                if (data.status == 2) {
                    controller.drop_down_container.slideUp();
                    controller.drop_down_container.html('');
                }
            });
        } else {
                controller.drop_down_container.slideUp();
                controller.drop_down_container.html('');
        }
    };

    this.searchDoctor = function () {
        Ajax.Get('/registry/ajax/searchDoctorByManager', {query : controller.search_text, page: controller.page}, function (data) {
            if (data.status == 0) {
                $('.doctor-list').html(data.result.html);
            }
        });
    };

    this.searchClinic = function () {
        Ajax.Get('/registry/ajax/searchClinicByManager', {query : controller.search_text, page: controller.page}, function (data) {
            if (data.status == 0) {
                $('.clinic-list').html(data.result.html);
            }
        });
    };
};