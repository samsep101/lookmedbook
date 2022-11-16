var DiseasePageController = function (id, is_login, already_registred_account, first_tab, label_for_counters) {
    this.disease_id = id;
    this.specialty_id = null;
    var controller1 = this;
    this.already_registred_account = already_registred_account;
    this.url_page = null;
    this.specialty_text = null;
    this.is_login = is_login;
    this.label_for_counters = label_for_counters;
    var current_top = null;
    var self = this;

    this.tab_name = first_tab;
    this.recording = 0;
    this.sections_coordinates = [];

    this.highlight_tab_block_flag = false;
    this.highlight_tab_block_timer = null;

    this.block_title = null;
    this.doctor_icon_text = null;
    this.counter_number = '';

    this.active_section = {
        tab : null,
        section : null
    };
    
    this.pediatr_wrap = $('.pediatr-banner-wrapper');
    this.pediatr_cont = $('.pediatr-banner-container');
    this.pediatr_flow_offset = 0;
    this.show_flow_banner = true;

    this.init = function () {

        $('.des-page').click(function(){
            setNewCounters(self.counter_number, 'DesPage/Page', $(this).data('action'), 'Push', $(this).data('position'));
        });

        if (self.block_title == null)
            self.block_title = 'Лучшие врачи Москвы';

        if (self.doctor_icon_text == null)
            self.doctor_icon_text = 'В нашей базе лучшие врачи Москвы';

        // вещаем обработчики на кнопки

        this.checkTabsInUrl();

        setInterval(self.checkActiveTab, 100);

        $('.section ul').addClass('list');

        $('.like_p ul').addClass('list-description');
        $('.like_p ul li ul').removeClass('list-description');
        $('.like_p ul li ul').addClass('list-description-2');

        var offset = $('.illness-description').outerHeight() + 220;
        $('.illness-nav').attr('data-offset-top', offset);

        self.getUnderstandBlock();

        $(document).on('click', '.btn-bookmark-illness', function () {
            self.getBookmarkBlock();
        });

        $(document).on('click', '.btn', function () {
            if ($(this).text() == 'Да')
                self.opinion = 1;
            else
                self.opinion = 0;
            Ajax.Post('/disease/ajaxAddUnderstandOpinion', {disease_id: self.disease_id, opinion: self.opinion}, function (data) {
                if (data.result)
                    $('.info-buttons').html(data.result.understand_text);
            });
        });

        $('.btn-find-doctor, .disease-doctor').click(function(){
            var action_for_counters = $(this).data('action-for-counters');
            setCounters('find-doctor', action_for_counters, '', SessionInfo.email);
        });



        $('.we-good .btn-reg').click(function () {
            var landing_registration_page_controller = new LandingRegistrationPageController(self.url_page, 'врачи','');
            landing_registration_page_controller.action_for_counters = 'disease-right-reg';
            landing_registration_page_controller.block_title = self.block_title;
            landing_registration_page_controller.init();
        });

        $(window).scroll(function (e) {
            if ($('#cards-wrap').offset()) {
                var topBl = $('#cards-wrap').offset().top - $(window).scrollTop(),
                    fixHei = $('.illness-nav').height();
                var doctor_action_banner = $(".third-version-buttons-container");
                if (topBl <= fixHei + 45) {
                    $('.illness-nav, .doing-box:not(.not-hide)').fadeOut();
                    if($(".affix-top").length == 0) {
                        doctor_action_banner.show();
                    } else {
                        doctor_action_banner.hide();
                    }
                } else {
                    $('.illness-nav').fadeIn();
                    if($(".affix-top").length == 0) {
                        doctor_action_banner.show();
                    } else {
                        doctor_action_banner.hide();
                    }
                    if (!$('.disease-doctor').hasClass(controller1.tab_name + '-block'))
                        $('.doing-box').fadeOut();
                    else
                        $('.doing-box').fadeIn();
                    $('.disease-doctor').removeClass('visible-specialty');
                    $('.disease-doctor').hide();
                    if (controller1.tab_name == 'male' || controller1.tab_name == 'female') {
                        $('.doing-box .adult-block').fadeIn();
                    }
                    $('.doing-box .' + controller1.tab_name + '-block.disease-doctor').addClass('visible-specialty');
                    $('.doing-box .' + controller1.tab_name + '-block').show();
                    var plural_sp = ($('.visible-specialty').data('text')) ? $('.visible-specialty').data('text') : '';
                    if (plural_sp) {
                        $('.heading-line').fadeIn();
                        $('.heading-line span').html('Врачи '+plural_sp);
                    } else {
                        $('.heading-line').fadeOut();
                    }
                }
                var fixHei2 = $('.we-good').height();
                if (topBl <= fixHei2 + fixHei + 90) {
                    $('.we-good').fadeOut();
                } else {
                    $('.we-good').fadeIn();
                }
                var fixHei3 = $('.disease-banner').height();
                if (topBl <= fixHei3 + fixHei + 90) {
                    $('.disease-banner').fadeOut();
                } else {
                    $('.disease-banner').fadeIn();
                }
            }
        });

        self.getSimilarDiseases();

        $('.sub-nav ul a').click(function (e) {
            e.preventDefault();
            if (self.highlight_tab_block_timer)
                clearTimeout(self.highlight_tab_block_timer);

            self.highlight_tab_block_flag = true;

            $('.sub-nav ul a').removeClass('active');
            $(this).addClass('active');

            $('.sub-nav ul li').removeClass('active');
            $(this).parent().addClass('active');

            var w = $(this).attr('data-t');
            $('html, body').animate({
                scrollTop: ($('#' + w).offset().top - 100)
            }, 1000);

            self.highlight_tab_block_timer = setTimeout(function () {
                self.highlight_tab_block_flag = false;
            }, 1300);

        });
        $(window).scroll(function () {
        	if (self.pediatr_wrap.length) {
	        	if (self.pediatr_wrap.offset().top > self.pediatr_cont.offset().top + self.pediatr_flow_offset) {
	        		if (self.show_flow_banner) {
		        		self.pediatr_cont.append(self.pediatr_wrap.html());
		        		self.pediatr_wrap.html('');
		        		self.show_flow_banner = false;
		        		self.pediatr_flow_offset = self.pediatr_cont.height();
	        		}
	        	}
	        	else if (!self.show_flow_banner) {
	        		self.pediatr_wrap.append(self.pediatr_cont.html());
	        		self.pediatr_cont.html('');
	        		self.show_flow_banner = true;
	        		self.pediatr_flow_offset = 0;
	        	}
        	}
            if (self.highlight_tab_block_flag)
                return;

            current_top = $(window).scrollTop() + $('.illness-nav').height();

            var prev = null;

            for (var i in self.sections_coordinates) {

                if (prev != null) {

                    if ((prev.top < current_top)
                        && (self.sections_coordinates[i].top > current_top)) {
                        $('.sub-nav ul a').removeClass('active');
                        $('.sub-nav ul li').removeClass('active');
                        $('.sub-nav ul a[data-t="' + prev.id + '"]').addClass('active');
                        $('.sub-nav ul a[data-t="' + prev.id + '"]').parent().addClass('active');

                        break;
                    }
                    if (prev.top < current_top) {
                        $('.sub-nav ul a').removeClass('active');
                        $('.sub-nav ul li').removeClass('active');
                        $('.sub-nav ul a[data-t="' + self.sections_coordinates[i].id + '"]').addClass('active');
                        $('.sub-nav ul a[data-t="' + self.sections_coordinates[i].id + '"]').parent().addClass('active');
                    }

                }

                prev = self.sections_coordinates[i];
            }
        });

    };

    this.getUnderstandBlock = function () {
        Ajax.Get('/disease/ajaxGetUnderstandBlock', {disease_id: controller1.disease_id}, function (data) {
            if (data.result) {
                $('.info-buttons').html(data.result.understand_text);
            }
        });
    };

    this.getBookmarkBlock = function () {
        Ajax.Post('/disease/ajaxAddToMyDiseaseList', {disease_id: controller1.disease_id}, function (data) {
            var isItAddOrKick = data.result.my_disease;

            if (isItAddOrKick) {
                $('.btn-bookmark-illness').addClass("btn-bookmark-added");
                $('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
            }
            else {
                $('.btn-bookmark-illness').removeClass("btn-bookmark-added");
                $('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
            }
        });
    };

    this.changeSpecialtyBlock = function (tab_name) {
        //$('.doing-box').hide();
        if (!$('.disease-doctor').hasClass(tab_name + '-block'))
            $('.doing-box').hide();
        else
            $('.doing-box').fadeIn();
        $('.disease-doctor').hide();
        $('.disease-doctor').removeClass('visible-specialty');
        if (tab_name == 'male' || tab_name == 'female') {
            $('.doing-box .adult-block').show();
        }
        $('.doing-box .' + tab_name + '-block.disease-doctor').addClass('visible-specialty');
        $('.doing-box .' + tab_name + '-block').show();
    };

    this.getSimilarDiseases = function() {
        var disease = $('#disease-title'),
            disease_id = parseInt(disease.data('id')),
            disease_title = disease.data('title');

        if(disease_id > 0) {
            var specialty_id = parseInt($('.visible-specialty').data('id')),
                data = {
                disease_id: disease_id,
                disease_title: disease_title,
                specialty_id: specialty_id
            };

            if(data.disease_id) {
                Ajax.Get('/ajax/getSimilarDiseases', data, function (data) {
                    if(data.status == 0) {
                        $('#disease-similar').html(data.result.html);
                    }
                });
            }
        }
    };

    this.getSectionsCoordinates = function () {

        self.sections_coordinates = [];
        $('.read .section').each(function () {
            $('.section ul').addClass('list');
            var id = $(this).attr('id');
            var of = $(this).offset().top;
            self.sections_coordinates.push({id: id, top: of});
        });

        self.sections_coordinates.sort(function (a, b) {
            return a.top - b.top;
        });
    };

    this.checkTabsInUrl = function () {
        var url = location.href;
        var tabs = ['/male', 'female', 'adult', 'newborn', 'pregnant', 'children'];
        var tab = false;
        var i = 0;

        while (i < tabs.length && !tab) {

             if(url.match(new RegExp(tabs[i]))){
                 if(tabs[i]=='/male')
                    tabs[i]='male';
                 $('.tab-people').removeClass('ui-state-active');
                 $('#'+tabs[i]).parent().addClass('ui-state-active');
                 tab = true;
                 $('.sub-nav').hide();
                 $('.sub-nav-'+tabs[i]).show();
             }
            i++;
        }
        self.getSectionsCoordinates();
    };

    this.checkActiveTab = function(){
        // получаем активную вкладку
        var active_tab_name = $('.tab-people.ui-state-active').data('tab-name');

        // получаем активный раздел
        var active_section_name = $('.section-name.active').data('section-name');

        if (!active_section_name ||self.highlight_tab_block_flag)
            return;

        // если они отличаются от предыдущего активного - генерируем событие
        // новый активный таб сохраняем в self.active_section
        if ((active_tab_name != self.active_section.tab) || (active_section_name != self.active_section.section)){
            setCounters(active_section_name, active_tab_name, '', SessionInfo.email);
            self.active_section.tab =  active_tab_name;
            self.active_section.section = active_section_name;
        }
    };

}
