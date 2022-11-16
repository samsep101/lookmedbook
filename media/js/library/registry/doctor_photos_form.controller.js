var DoctorPhotosFormController = function (container) {
    var self = this;
    self.list_names = ['image_to_doctor'];
};

extend(DoctorPhotosFormController, ModerateFormController);

DoctorPhotosFormController.prototype.initFields = function()
{
    var self = this;

    self.initMainDoctorPhoto();
    self.initDoctorPhotos();

    self.addSetMainPhotoButton();

    $(document).on('click', '.set-main-photo', function(){
        var container = $(this).parent();
        $('div[data-holder-for="image_to_doctor"]').find('div[data-name="image_to_doctor"]').first().before(container);
        self.addSetMainPhotoButton();
    });

    //$('.carousel-stage ul li').append('<input class="set-main-photo-button" type="button" value="Сделать главной"  /><span class="delete-photo-button"></span>');
    $('.carousel-stage ul li').each(function(){
        var img = $(this).find('img');

        if (img.data('width') < img.data('height'))
        {
            $(this).append('<input class="set-main-photo-button" type="button" value="Сделать главной"  />');
        }

        $(this).append('<span class="delete-photo-button"></span>');
    });

    $(document).on('click', '.delete-photo-button', function(){
        var li = $(this).parent();
        var image_index = $('.carousel-stage ul li').index(li);


        var navigation_li = $('.carousel-navigation ul li').eq(image_index);
        navigation_li.remove();

        li.remove();

        connectCarousel();
    });

    $(document).on('click', '.set-main-photo-button', function(){
        var li = $(this).parent();
        var image_index = $('.carousel-stage ul li').index(li);
        $('.carousel-stage ul li').first().before(li);

        var navigation_li = $('.carousel-navigation ul li').eq(image_index);
        $('.carousel-navigation ul li').first().before(navigation_li);
        connectCarousel();
    });

    $('.doctor_images .one_doctor_img .remove-feature').click(function(){
        $(this).parent().remove();
    });

    $(document).on('click','.new_remove', function(){
        $(this).parent().remove();
    });


    $('.location-box .tabs').each(function () {
        $('.location-box .tabs li:first-child').addClass('active');
        $(this).find('li').each(function (i) {
            $(this).click(function () {
                $('.day').removeClass('active');
                var id = $(this).data('id');

                $(this).addClass('active').siblings().removeClass('active')
                    .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
                $('.time-clinic-' + id).addClass('active');
            });
        });
    });
    $('.location-box .tabs_schedule').each(function () {
        $(this).find('li').each(function (i) {
            $(this).click(function () {
                var id = $(this).data('id');

                $(this).addClass('active').siblings().removeClass('active')
                    .parents('.location-box_schedule').find('.section-in').eq(i).fadeIn(150).siblings('.section-in').hide();
            });
        });
    });

    $('.section.visible.flo').next('div.section.visible.flo').css('display', 'none');
    $('.section-in.visible.flo').next('div.section-in.visible.flo').css('display', 'none');


};

DoctorPhotosFormController.prototype.initMainDoctorPhoto = function(){
    var self = this;

    var image_upload_controller = new ImageJCropController();
    image_upload_controller.width = 74;
    image_upload_controller.height = 111;
    image_upload_controller.button_selector = 'add_main_doctor_photo';
    image_upload_controller.preview_container_selector = '#avatar-progress-block';
    image_upload_controller.setSuccessCallback(function(data){
        self.setMainDoctorPhoto(data.original_image.image_id, data.resized_image.path);
    });
    image_upload_controller.init();
};

DoctorPhotosFormController.prototype.setMainDoctorPhoto = function(image_id, image_path){
    $('.doctor-big-card').find('.avatar img').attr('src', image_path);
    $('input[name="form[card_image_id]"]').val(image_id);
    $('.images-block .image').html('<img src="'+image_path+'" />');
};

DoctorPhotosFormController.prototype.initDoctorPhotos = function(){

    var self = this;

    var image_upload_controller = new ImageJCropController();
    image_upload_controller.width = 298;
    image_upload_controller.height = 450;
    image_upload_controller.sizes = [{width: 675, height: 450}, {width: 298, height: 450}];
    image_upload_controller.button_selector = 'add_doctor_photos';
    image_upload_controller.preview_container_selector = '#photo-progress-block';
    image_upload_controller.setSuccessCallback(function(data){
        var html = '<li data-name="image_to_doctor" style="width: 660px;"><img src="'+data.resized_image.path+'" />';
        html += '<input type="hidden" name="image_id" value="'+data.resized_image.image_id+'" />';
        html += '</li>';
        $('.carousel-stage ul').append(html);


        if (data.original_image.width < data.original_image.height)
        {
            $('.carousel-stage ul li').last().append('<input class="set-main-photo-button" type="button" value="Сделать главной"  />');
        }
        $('.carousel-stage ul li').last().append('<span class="delete-photo-button"></span>');

        var navigation_li = '<li data-jcarouselcontrol="true"><img src="'+data.resized_image.path+'" /></li>';
        $('.carousel-navigation ul').append(navigation_li);
        $('.carousel-stage ul li.delete-when-add-new').remove();
        connectCarousel();
    });
    image_upload_controller.init();

};

DoctorPhotosFormController.prototype.addSetMainPhotoButton = function()
{
    var i = 1;
    $('div[data-name="image_to_doctor"]').each(function(){
        if (i == 1)
        {
            $(this).find('.set-main-photo').remove();
        } else {
            if ($(this).find('.set-main-photo').length == 0)
            {
                var img = $(this).find('img').first();
                if (img.data('is-main'))
                {
                    $(this).find('.remove-feature').after('<input type="button" style="position:relative; top: 30px" class="set-main-photo" value="Сделать главной" />');
                }
            }
        }
        i++;
    });
};

DoctorPhotosFormController.prototype.setDoctorPhotos = function(image_id, image_path, original_image){
    var self = this;

    var container = $('<div data-name="image_to_doctor" class="one_doctor_img" style="clear:both"></div>');
    var input = $('<input type="hidden" name="image_id" value="'+image_id+'">');
    var link = $('<a href="'+original_image.path+'" rel="lightbox" style="float:left"></a>');
    var image = $('<img width="148" src="'+image_path+'" />');

    if (original_image.width < original_image.height)
    {
        image.data('is-main', 1);
    }

    var remove = $('<span class="remove-feature new_remove">X</span>');

    link.append(image);
    var block = container.append(input).append(link).append(remove);

    $('.doctor_images').append(block);

    link.click(function(){
        showLightbox(this);
        return false;
    });

    self.addSetMainPhotoButton();
};

DoctorPhotosFormController.prototype.lock = function (locked_before) {
    var self = this;
    lock_div = $('<div class="lock-div"></div>');
    var isChrome = window.chrome;
    if(isChrome && locked_before) {
        lock_div.css({
            position: 'absolute',
            left: 0,
            top: 170,
            width: $(self.container).width() + 300,
            height: $(self.container).height() + 260
        });
    } else {
        lock_div.css({
            position: 'absolute',
            left: 0,
            top: 170,
            width: $(self.container).width() + 300,
            height: $(self.container).height() + 50
        });
    }

    $(this.container).append(lock_div);
    $('.swfupload').css('z-index', '0');
};