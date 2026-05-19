var Elements = {};

Elements.AddFormTemplate = function (template_container, el, func) {
    el.before(template_container.html());

    if (func != undefined) {
        func();
    }
};

$(document).ready(function () {
  $(document).on('click', '.gender-select .man', function () {
    $(this).addClass('selected');
    $(this).parent().find('.woman').removeClass('selected');
    $(this).parent().find('input[type="hidden"]').val(1);
  });

  $(document).on('click', '.gender-select .woman', function () {
    $(this).addClass('selected');
    $(this).parent().find('.man').removeClass('selected');
    $(this).parent().find('input[type="hidden"]').val(2);
  });


    window.ajaxLoadCallbacks.push(function(){
        var fixHelper = function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        };


        $('.sortable tbody').sortable({
            helper : fixHelper,
            update : function(event, ui){
                var result = [];
                var model_name = ui.item.parent().data('model');

                ui.item.parent().find('tr').each(function(){
                    result.push($(this).data('id'));
                });

                var data = {
                    'model_name' : model_name,
                    'order' : result
                };

                Ajax.Post('/admin/ajax/saveItemsOrder', data, function(result){

                });
            }
        });
    });

    var doctor_pick_controller = new DoctorPickQuickSearchController();

    doctor_pick_controller.setInputElement($('#doctor-pick-quick-search .doctor-pick-input'));
    doctor_pick_controller.setDrowDownContainer($('#doctor-pick-quick-search .drop-menu'));
    doctor_pick_controller.init();

    $('span.highlight_color').each(function(){
        var color = $(this).data('color');

        if (color)
        {
            $(this).parent().parent().css('background-color', color);
        }
    });


    $(document).on('click', '.ajax-checkbox', function () {
        var value = $(this).attr('checked') ? 1 : 0;

        var table = $(this).data('model_name');
        var field_name = $(this).data('field_name');
        var id = $(this).data('id');

        var data = {
            table:table,
            field_name:field_name,
            id:id,
            value:value
        };

        var el = $(this);
        Ajax.Post('/admin/ajax/setCheckbox', data, function (data) {
            if(data.result)
            {
                el.parent().parent().find('.field-image_find_status_name span').html('Готово');
            }
        });
    });
});

var DoctorPickQuickSearchController = function () {
    this.input_element = null;
    this.drop_down_container = null;

    var controller = this;
    this.setInputElement = function (el) {
        this.input_element = el;

    };

    this.setDrowDownContainer = function (container) {
        this.drop_down_container = container;
    };

    this.init = function () {
        var self = this;

        self.input_element.keyup(function () {
            self.updateDropDownList();
        });
    };

    this.updateDropDownList = function () {
        var self = this;

        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Get('/ajax/getDoctorsPick', {query: text}, function (data) {
                if (data.status == 0) {
                    self.drop_down_container.html(data.result);
                    self.drop_down_container.slideDown();
                }

                if (data.status == 2) {
                    self.drop_down_container.slideUp();
                    self.drop_down_container.html('');
                }
            });
        } else {
            self.drop_down_container.slideUp();
            self.drop_down_container.html('');
        }
    };

    $(document).on('click', '#doctor-pick-quick-search .drop-menu li', function () {
        controller.input_element.val($(this).text());
        controller.drop_down_container.slideUp();
        $('input[name="form[doctor_id]"]').val($(this).attr('data-id'));
        controller.drop_down_container.html('');
    });

    $(document).on('change', 'select[name="form[clinic_id]"]', function () {
        $('.doctor-pick-input').val('');
        $('input[name="form[doctor_id]"]').val('');
        controller.drop_down_container.css('display', 'none');
        controller.drop_down_container.html('');
    });

    $('body').click(function (event) {
        if ($(event.target).closest(".drop-menu").length)
            return;
        controller.drop_down_container.slideUp();
        controller.drop_down_container.html('');
    });

};


var ImageJCropController = function () {

    var self = this;
    this.container = null;

    this.width = null;
    this.height = null;

    this.button_selector = null;

    this.success_callback = null;

    this.sizes = [];

    this.preview_container_selector = null;

    this.setSuccessCallback = function (callback) {
        self.success_callback = callback;
    };


    this.init = function () {
        var self = this;

        var uploader = new plupload.Uploader({
            runtimes : 'gears,html5,browserplus,flash',
            browse_button : self.button_selector,
            //container: 'container',
            max_file_size : '10mb',
            multi_selection: false,
            url : '/registry/ajax/uploadImage',
            resize : {width : 1000, height : 800, quality : 90},
            flash_swf_url : '/media/js/plupload/plupload.flash.swf',
            silverlight_xap_url : '/media/js/plupload/plupload.silverlight.xap',
            filters : [
                {title : "Изображения", extensions : "jpg,gif,png"}
            ],
            multipart_params : {
                'csrf' : SessionInfo.csrf
            }
        });

        uploader.bind('Init', function(up, params) {
            //$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
        });

        var el = uploader;
        uploader.bind('FilesAdded', function(up, files) {
            for (var i in files) {
                $(self.preview_container_selector).append('<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>');
            }
            setTimeout(function(){
                uploader.start();
            }, 100);
        });

        uploader.bind('UploadProgress', function(up, file) {
            $('#'+file.id).find('b').html('<span>' + file.percent + "%</span>");
        });

        uploader.bind('FileUploaded', function(up, file, response) {
            setTimeout(function(){
                $('#'+file.id).remove();
            }, 1000);

            response = JSON.parse(response.response);
            var img_count = $('.one_doctor_img').length;
            if (img_count >= 10){
                var message = new PopupMessage();
                message.show('Количество фотографий не должно превышать 10');
                return;
            };

            if (response.status == 0) {
                var resize_ratio = response.result.original_image.width / response.result.resized_image.width;

                if((response.result.original_image.width < self.width ) || (response.result.original_image.height < self.height))
                {
                    var message = new PopupMessage();
                    message.show('Загруженная фотография меньше допустимых минимальных размеров');
                } else {
                    var crop_block = new CropBlockController();
                    crop_block.image_id = response.result.image_id;
                    crop_block.image_path = response.result.resized_image.path;
                    crop_block.width = self.width;
                    crop_block.height = self.height;
                    crop_block.resize_ratio = resize_ratio;

                    if (self.sizes.length)
                    {
                        for (var i in self.sizes)
                        {
                            self.sizes[i].min_size =  [self.sizes[i].width / resize_ratio, self.sizes[i].height / resize_ratio];
                        }
                    }

                    crop_block.sizes = self.sizes;
                    crop_block.min_size = [self.width / resize_ratio, self.height / resize_ratio];
                    crop_block.setSuccessCallback(self.success_callback);
                    crop_block.init();
                }
            }
        });


        $('#start').click(function(){
            uploader.start();
        });

        uploader.init();

/*
        $(self.button_selector).uploadify({
            'buttonText': 'Добавить фотографию',
            'buttonClass' : 'longest-button',
            'swf': '/media/js/uploadify/uploadify.swf',
            'uploader': '/registry/ajax/uploadImage',
            'fileTypeDesc': 'jpg,bmp,png,gif',
            'fileTypeExts': '*.jpg;*.bmp;*.png;*.gif',
            'multi': false,
            'onUploadSuccess': function (file, data, response) {
                var json = JSON.parse(data);

                var img_count = $('.one_doctor_img').length;
                if (img_count >= 10){
                    var message = new PopupMessage();
                    message.show('Количество фотографий не должно превышать 10');
                    return;
                };

                console.log(json);
                if (json.status == 0) {
                    var resize_ratio = json.result.original_image.width / json.result.resized_image.width;

                    if((json.result.original_image.width < self.width ) || (json.result.original_image.height < self.height))
                    {
                        var message = new PopupMessage();
                        message.show('Загруженная фотография меньше допустимых минимальных размеров');
                    } else {
                        var crop_block = new CropBlockController();
                        crop_block.image_id = json.result.image_id;
                        crop_block.image_path = json.result.resized_image.path;
                        crop_block.width = self.width;
                        crop_block.height = self.height;
                        crop_block.resize_ratio = resize_ratio;

                        if (self.sizes.length)
                        {
                            for (var i in self.sizes)
                            {
                                self.sizes[i].min_size =  [self.sizes[i].width / resize_ratio, self.sizes[i].height / resize_ratio];
                                console.log(self.sizes[i].min_size);
                            }
                        }

                        crop_block.sizes = self.sizes;
                        crop_block.min_size = [self.width / resize_ratio, self.height / resize_ratio];
                        crop_block.setSuccessCallback(self.success_callback);
                        crop_block.init();
                    }
                } else {
                    console.log('error');
                }
            }
        });*/
    };
};

var CropBlockController = function () {

    var self = this;

    this.container = null;
    this.image_id = null;
    this.width = null;
    this.height = null;

    this.image_path = null;

    this.min_size = null;

    this.aspect_ratio = null;

    this.resize_ratio = null;

    this.send_data_block_flag = false;

    this.x = null;
    this.y = null;

    this.w = null;
    this.h = null;

    this.sizes = [];

    this.success_callback = null;

    this.popup = null;

    this.jcrop_api = null;

    this.setSuccessCallback = function (callback) {
        self.success_callback = callback;
    };


    this.init = function () {
        var popup = new Popup();

        self.aspect_ratio = self.width / self.height;


        var rand = Math.floor(Math.random() * (10000 - 1 + 1)) + 1;


        self.container = '#crop-block' + rand;

        var html = '<div class="crop-block" id="crop-block' + rand + '">';
        html += '<div class="image_block"><img id="jcrop_target" src="' + self.image_path + '" /></div>';
        html += '<div id="preview-block" style="width:' + (self.aspect_ratio * 100) + 'px;height:100px;overflow:hidden;margin-left:5px;">';
        html += '<img id="preview" src="' + self.image_path + '" />';




        html += '</div>';

        if (self.sizes.length)
        {
            html += '<br /><br /><div id="change-size">';
            for (var i in self.sizes)
            {
                 html += '<div style="margin-top:10px"><input type="submit" data-min-width="'+self.sizes[i].min_size[0]+'" data-min-height="'+self.sizes[i].min_size[1]+'" data-width="' + self.sizes[i].width + '" data-height="' + self.sizes[i].height
                        + '" value="Размеры ' +self.sizes[i].width+ 'x' + self.sizes[i].height + '" /> </div>';
            }

            html += '</div>';
        }
        html += '<div class="buttons" style="clear: both; padding-top: 20px">' +
            '<input class="btn-appoint" type="submit" name="cancel" value="Отменить" style="display: inline;"/>' +
            ' <input class="btn-1" type="submit" name="add" value="Добавить" style="height: 46px;"/> ' +
            '</div>';
        html += '</div>';

        popup.show('<div style="padding: 20px">' + html + '</div>', '1000px', '590px');


        self.popup = popup;

        $(self.container + ' #change-size input').click(function(){

            self.aspect_ratio = $(this).data('width')/$(this).data('height');
            var min_size1 = [$(this).data('min-width'), $(this).data('min-height')];
            self.jcrop_api.setOptions(
            {
                aspectRatio: self.aspect_ratio,
                minSize: min_size1
            });

            $('#preview-block').css('width', self.aspect_ratio * 100);
            self.width = $(this).data('width');
            self.height = $(this).data('height');
        });

        $(self.container + ' input[name="cancel"]').click(function () {
            self.popup.close();
        });

        $(self.container + ' input[name="add"]').click(function () {
            self.sendData();
        });

        self.x = 0;
        self.y = 0;
        self.w = self.width;
        self.h = self.height;

        $(self.container + ' #jcrop_target').Jcrop({
            onChange: self.showPreview,
            onSelect: self.showPreview,
            aspectRatio: self.aspect_ratio,
            minSize: self.min_size,
            setSelect: [self.x, self.y, self.w, self.h]
        },function(){
            //Store the API in the jcrop_api variable
            self.jcrop_api = this;
        });
    };

    this.showPreview = function (coords) {

        var rx = self.aspect_ratio * 100 / coords.w;
        var ry = 100 / coords.h;

        self.x = (coords.x * self.resize_ratio);
        self.y = coords.y * self.resize_ratio;
        self.w = coords.w * self.resize_ratio;
        self.h = coords.h * self.resize_ratio;

        $('#preview').css({
            width: Math.round(rx * $('#jcrop_target').width()) + 'px',
            height: Math.round(ry * $('#jcrop_target').height()) + 'px',
            marginLeft: '-' + Math.round(rx * coords.x) + 'px',
            marginTop: '-' + Math.round(ry * coords.y) + 'px'
        });
    };

    this.sendData = function () {
        if(self.send_data_block_flag)
            return;

        self.send_data_block_flag = true;

        var data = {
            x: self.x,
            y: self.y,
            w: self.w,
            h: self.h,
            width: self.width,
            height: self.height,
            image_id: self.image_id
        };

        Ajax.Post('/registry/ajax/cropImage', data, function (data) {
            if (data.status == 0)
            {
                if (self.success_callback)
                    self.success_callback(data.result);

                self.popup.close();
            }

            self.send_data_block_flag = false;
        });
    };
};

var HtmlViewHelper = function(){

};

HtmlViewHelper.getOptions = function(list, id_field_name, name_field_name){
    var str = '';

    var container = $('<div></div>');
    container.append('<option value="0"> </option>');
    for (var i in list)
    {
        container.append('<option value="' + list[i][id_field_name] + '">' + list[i][name_field_name] + '</option>');
    }

    return container.html();
};

var ListFilterController = function(){
    var self = this;

    this.init = function(){
        $('#filter-block  input.submit').click(function(){
            $('#filter-block .filter-element').each(function(){
                if ($(this).hasClass('filter-checkbox'))
                {
                    if ($(this).attr('checked') == 'checked')
                    {
                        queryString.set($(this).attr('name'), 1);
                    } else {
                        queryString.set($(this).attr('name'), 0);
                    }
                } else {
                queryString.set($(this).attr('name'), $(this).val());
                }
            });

            queryString.loadFilter();
        });

        $('#filter-block  input.cancel').click(function(){
            queryString.clear();
            queryString.load();
        });
    };
};

var VidalSearchPopupController = function()
{
    var self = this;

    this.product_id = null;
    this.block = null;

    this.blocked_flag = null;

    this.search_query = null;

    this.list_cache = null;

    this.items_cache = {};

    this.button = null;

    this.init = function()
    {
        self.button.click(function(){
            self.showPopup();
        });

        $(document).on('click', 'img.show-vidal-product', function(){
            self.loadItem($(this).data('id'));
        });

        $(document).on('click', 'img.import-vidal-product', function(){
            self.importData($(this).data('id'));
        });
    };

    this.showPopup = function()
    {
        if(self.blocked_flag)
            return;

        self.button.val('...загружается...');
        self.blocked_flag = true;

        var url = '/admin/vidal_product?ajax=1&filter[rus_name]='+encodeURIComponent(self.search_query);
        //var url = '/admin/manufacturer/edit/?id=1415&ajax=1';
        Ajax.Get(url, {}, function(data){
            self.button.val('Поиск в базе VIDAL');
            self.popup = new Popup();
            self.popup.show(data, '850px');
            self.popup.getElement('.actionBar').html('<div style="color:white; font-weight: bold; font-size: 12px; padding-top: 7px; padding-left: 3px;">Поиск в базе лекарств VIDAL</div>');
            self.popup.getElement('.edit-image').css('display', 'none');
            self.popup.close_callback = function(){
                self.blocked_flag = false;
            };

            self.addSearchForm();
        }, false);
    };

    this.addSearchForm = function()
    {
        var form = $('<div class="search-form"></div>');
        var text_input = $('<input type="text" value="'+self.search_query+'" />');
        form.append(text_input);

        text_input.keypress(function (e) {
            if (e.keyCode == 13) {
                self.search();
            }
        });

        var button = $('<button>Найти</button>');

        form.html('<b>Поиск:</b> ');
        form.append(text_input);
        form.append(button);

        button.click(function(){
            var text = text_input.val();

            if(text.length < 4)
            {
                alert('Поисковая фраза должна включать минимум 3 символа!');
                return;
            }

            self.search_query = text;
            self.search();
        });
        self.popup.getElement('.actionBar').after(form);
    };

    this.search = function()
    {
        var url = '/admin/vidal_product?ajax=1&filter[rus_name]='+encodeURIComponent(self.search_query);

        Ajax.Get(url, {}, function(data){
            self.popup.setContent(data);
            self.popup.getElement('.actionBar').html('<div style="color:white; font-weight: bold; font-size: 12px; padding-top: 7px; padding-left: 3px;">Поиск в базе лекарств VIDAL</div>') ;
            self.popup.getElement('.edit-image').css('display', 'none');
            self.addSearchForm();
        }, false);
    };

    this.goToList = function()
    {
        self.popup.setContent(self.list_cache);
    };

    this.importData = function(item_id)
    {
        var popup_message = new PopupMessage();

        popup_message.show('Выполняется импорт данных. Ждите!');
        self.popup.close();

        var data = {
            product_id : self.product_id,
            vidal_product_id : item_id
        };
        Ajax.Post('/admin/vidal/ajaxImportProduct', data, function(data){
            if(data.status == 0)
            {
                window.location.reload();
            }
        });
    }

    this.loadItem = function(item_id)
    {
        self.list_cache = self.popup.getContent();

        var url = '/admin/vidal_product/edit?id='+item_id+'&ajax=1';

        Ajax.Get(url, {}, function(data){
            self.popup.setContent(data);

            self.items_cache[item_id] = data;

            var submit_button = self.popup.getElement('input#submit_action');
            submit_button.val('Импортировать данные');
            submit_button.removeAttr('onclick');
            submit_button.click(function(){
                self.importData(item_id);
                return false;
            });


            var cancel_button = self.popup.getElement('input#cancel_action');
            cancel_button.val('Вернуться к списку');
            cancel_button.removeAttr('onclick');
            cancel_button.click(function(){
                self.goToList();
                return false;
            });
        }, false);


    };
}

var BingSearchPopupController = function()
{
    var self = this;

    this.product_id = null;
    this.clean_name = null;
    this.block = null;

    this.blocked_flag = null;
    this.search_query = null;
    this.list_cache = null;
    this.items_cache = {};
    this.button = null;

    this.init = function()
    {
        self.button.click(function(){
            self.showPopup();
        });
    };

    this.showPopup = function()
    {
        var form = $('<div class="search-form"></div>');
        form.css('margin', '20px 0 0 20px');
        var text_input = $('<input type="text" value="'+self.search_query+'" />');
        text_input.val(self.clean_name);
        text_input.width('400px');

        var button = $('<button id="search">Найти</button>');

        form.html('<b>Поиск:</b> ');
        form.append(text_input);
        form.append(button);

        var result_box = $("<div class='result-box' style='min-height: 450px'></div>");
        form.append(result_box);

        self.popup = new Popup();
        self.popup.show(form, '1000px');
        self.search_query = text_input.val();
        button.text('Идет загрузка...');
        self.blocked_flag = true;
        self.searchImages();

        button.click(function(){
            if(self.blocked_flag) {
                return;
            }

            self.blocked_flag = true;
            button.text('Идет загрузка...');
            var text = text_input.val();

            if(text.length < 4)
            {
                alert('Поисковая фраза должна включать минимум 3 символа!');
                return;
            }

            self.search_query = text;
            self.searchImages();
        });
    };

    this.searchImages = function()
    {
        var url = '/admin/bing/ajaxDownloadImages?query=' + self.search_query;

        Ajax.Get(url, {}, function(data){
            if(data.status == 0) {
                $(".result-box").html('');

                var images_block = $("<ul></ul>", {'class': "images-list"});
                images_block.css({'float': 'left', 'clear': 'both', 'margin-top': '10px'});

                for(var i = 0 ; i < data.result.length; i++) {
                    if(i == 5) {
                        var images_block = $("<ul></ul>", {'class': "images-list"});
                        images_block.css({'float': 'left', 'clear': 'both', 'margin-top': '10px', 'overflow': 'hidden'});
                    }

                    var li = $("<li></li>");
                    li.css('padding', '10px');
                    var image_link = $("<a></a>", {'class': 'screenshot', 'href': data.result[i]});
                    image_link.attr('rel', data.result[i]);
                    var image = $("<img>", {src: data.result[i], width: '150px'});
                    image_link.append(image);
                    li.append(image_link);
                    images_block.append(li);

                    if(i == 4) {
                        $(".result-box").append(images_block);
                    }
                }

                $(".result-box").append(images_block);

                var padding = $("<div class='padding'></div>");
                padding.css({'min-height': '300px', 'float': 'left', 'overflow': 'hidden', 'clear': 'both'});
                $(".result-box").append(padding);

                $(".images-list li").css({'list-style-type': 'none', 'float': 'left'});

                var expanded_block = new ExpandedBlock();
                expanded_block.block_container = $(".fancybox-inner");
                expanded_block.more_button_text = 'Показать все';
                expanded_block.max_height = 400;
                expanded_block.init();
                var more_link = $(".more-link");

                self.blocked_flag = false;
                $("#search").text('Найти');
            } else {
                if(data.status == 3) {
                    $(".result-box").html('По запросу ничего не найдено');
                    self.blocked_flag = false;
                    $("#search").text('Найти');
                } else {
                    $(".result-box").html('При поиске изображений возникла ошибка');
                    self.blocked_flag = false;
                    $("#search").text('Найти');
            }
            }
        }, 'json');

        $(document).on('click', ".images-list li a", function() {
            var src = $(this).attr('href');
            var url = '/admin/bing/ajaxSaveImage?src=' + src + '&product_id=' + self.product_id;

            Ajax.Get(url, {}, function(data){
                if(data.status == 0) {
                    location.reload();
                }
            }, 'json');

            return false;
        });
    };
}