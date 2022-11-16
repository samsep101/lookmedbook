var ClinicPhotosController = function () {
    $('.avatar a').removeAttr('href');
    var self = this;

    self.list_names = ['image_to_clinic'];
};

extend(ClinicPhotosController, ModerateFormController);


    ClinicPhotosController.prototype.initFields = function () {

        var self = this;
        var image_upload_controller = new ImageJCropController();
        image_upload_controller.width = 74;
        image_upload_controller.height = 31;
        image_upload_controller.image_type = 'clinic_image';
        image_upload_controller.button_selector = 'add_main_doctor_photo';
        image_upload_controller.preview_container_selector = '#avatar-progress-block';
        image_upload_controller.setSuccessCallback(function(data){
            self.refreshClinicAvatar(data.resized_image.path, data.resized_image.image_id);
        });
        image_upload_controller.init();

        var image_upload_controller_2 = new ImageJCropController();
        image_upload_controller_2.width = 660;
        image_upload_controller_2.height = 360;
        image_upload_controller_2.image_type = 'clinic_image';
        image_upload_controller_2.button_selector = 'add_clinic_photo';
        image_upload_controller_2.preview_container_selector = '#photo-progress-block';
        image_upload_controller_2.setSuccessCallback(function(data){
            var html = '<li data-name="image_to_clinic"><img src="'+data.resized_image.path+'" />';
            html += '<input type="hidden" name="image_id" value="'+data.resized_image.image_id+'" />';
            html += '<input class="set-main-photo-button" type="button" value="Сделать главной"  /><span class="delete-photo-button"></span>';
            html += '</li>';
            $('.carousel-stage ul').append(html);

            var navigation_li = '<li data-jcarouselcontrol="true"><img src="'+data.resized_image.path+'" /></li>';

            $('.carousel-navigation ul').append(navigation_li);

            $('.delete-when-add-new').remove();
            connectCarousel();
        });
        image_upload_controller_2.init();

        $(document).on('click','.remove-image',function(){
            $(this).parent().remove();
        });

        $('.carousel-stage ul li').append('<input class="set-main-photo-button" type="button" value="Сделать главной"  /><span class="delete-photo-button"></span>');

        $(document).on('click', '.set-main-photo-button', function(){
            var li = $(this).parent();
            var image_index = $('.carousel-stage ul li').index(li);
            $('.carousel-stage ul li').first().before(li);
            connectCarousel();
        });

        $(document).on('click', '.delete-photo-button', function(){
            var li = $(this).parent();
            var image_index = $('.carousel-stage ul li').index(li);
            li.remove();
        });
    };

    ClinicPhotosController.prototype.refreshClinicAvatar = function (image_path, image_id) {
        $('.avatar img').attr('src', image_path);
        $('.clinic-avatar-image img').remove();
        $('.card-image .image-block').html('<img src="'+image_path+'" />');
        $('input[name="form[card_image_id]"]').attr('value', image_id);
    };

ClinicPhotosController.prototype.refreshClinicPhotos = function (image_path, image_id) {

    var li = $('<li></li>');
    var data = $('<div data-name="image_to_clinic"></div>');
    var hidden_input = $('<input type="hidden" value="'+image_id+'" name="image_id" />');
    var link = $('<a href="'+image_path+'" rel="lightbox"></a>');
    var image = $('<img src="'+image_path+'" />');
    link.append(image);

    var x_mark = $('<span class="remove-image">X</span>');
    data.append(hidden_input);
    data.append(link);
    data.append(x_mark);
    li.append(data);

    link.click(function(){
        showLightbox(this);
        return false;
    });

    $('.clinic-images > ul').append(li);
};
