var Popup = function(){
    var self = this;
    this.popup_block = null;
    this.close_callback = null;

    this.container = null;

    this.show = function(html, width, height, is_closed){
        Popup.locks++;

        if (self.popup_block)
        {
            self.popup_block.css('display', 'block');
            self.popup_block.css('visibility', 'visible');
            return;
        }

        if (width == undefined) width = '639px';
        if (height == undefined) height = 'auto';
        if (is_closed == undefined) is_closed = true;
        //if (top == undefined) top = '20px';

        var popup_block = $('<div></div>');

        popup_block.append($('<div class="fancybox-placeholder" style="display: none;"></div>'));
        var fancybox_overlay = $('<div class="fancybox-overlay fancybox-overlay-fixed" style="width: auto; height: auto; display: block;"></div>');
        popup_block.append(fancybox_overlay);

        var fancybox_wrap = $('<div class="fancybox-wrap fancybox-desktop fancybox-type-inline fancybox-opened" tabindex="-1" style="width: ' + width + '; height: ' + height + '; position: absolute; top: ' + top +'; opacity: 1; overflow: visible;"></div>');
        fancybox_overlay.append(fancybox_wrap);

        var fancybox_skin = $('<div class="fancybox-skin" style="padding: 0px; width: auto; height: auto;"></div>');
        fancybox_wrap.append(fancybox_skin);

        var fancybox_outer = $('<div class="fancybox-outer"></div>');
        fancybox_skin.append(fancybox_outer);

        var fancybox_inner = $('<div class="fancybox-inner" style="overflow: hidden; width: '+width+';"></div>');
        fancybox_outer.append(fancybox_inner);

        if (is_closed){
            var close_button = $('<a class="fancybox-item fancybox-close" href="javascript:;" title="Close"></a>');
            fancybox_outer.append(close_button);

            close_button.click(function(){
                self.close();
                $('.error_span').css('display','none');
            });
        }

        fancybox_inner.append(html);

        self.container = fancybox_inner;


        $('body').addClass('fancybox-lock');
        $('body').append(popup_block);

        $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);

        //var top = ($(window).height() < fancybox_inner.height()) ? 10 : (($(window).height() -  fancybox_inner.height())/2);

        var top = ($(window).height() < fancybox_inner.height()) ? 10 : (($(window).height() -  fancybox_inner.height())/2);
        fancybox_wrap.css('top', top);
        if ($(window).width() - $('.fancybox-wrap').width() > 0) {
            $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);
        }
        else
            $('.fancybox-wrap').css('left', 20);

        fancybox_wrap.css('top', top);

        self.popup_block = popup_block;

        $(window).on('resize', function () {
            //$('.fancybox-inner').css('height', $(window).height() - 40);
            var top = ($(window).height() < fancybox_inner.height()) ? 10 : (($(window).height() -  fancybox_inner.height())/2);
            fancybox_wrap.css('top', top);
            if ($(this).width() - $('.fancybox-wrap').width() > 0) {
                $('.fancybox-wrap').css('left', ($(this).width() - $('.fancybox-wrap').width()) / 2);
            }
            else
                $('.fancybox-wrap').css('left', 20);
        });

        $('.fallback').click(function(){
            self.close();
        });

//        $('[placeholder]').each(function () {
//            var input = $(this);
//            if (input.val() == '' || input.val() == input.attr('placeholder')) {
//                input.addClass('placeholder');
//                if( $.browser.msie && $.browser.version == 9.0 )
//                {
//                    input.val(input.attr('placeholder'));
//                }
//            }
//        });
//
//        $('[placeholder]').blur(function () {
//            var input = $(this);
//            if (input.val() == '' || input.val() == input.attr('placeholder')) {
//                input.addClass('placeholder');
//                if( $.browser.msie && $.browser.version == 9.0 )
//                {
//                    input.val(input.attr('placeholder'));
//                }
//            }
//        });
    };

    this.hide = function(){
        self.popup_block.css('visibility','hidden');

        Popup.locks--;
        if (Popup.locks <= 0)
            $('body').removeClass('fancybox-lock');

        if (self.close_callback != null)
            self.close_callback();
    };


    this.close = function(){
        self.popup_block.remove();

        Popup.locks--;
        if (Popup.locks <= 0)
            $('body').removeClass('fancybox-lock');

        if (self.close_callback != null)
            self.close_callback();
    };

    this.setCloseCallback = function(callbak){
        self.close_callback = callbak;
    };


    this.setContent = function(data)
    {
        self.container.html(data);
        self.fit();
    };

    this.getContent = function()
    {
        return self.container.html();
    }

    this.getElement = function(selector)
    {
        return self.popup_block.find(selector);
    };

    this.fit = function()
    {
        var top = ($(window).height() < $('.fancybox-inner').height()) ? 10 : (($(window).height() -  $('.fancybox-inner').height())/2);
        $('.fancybox-wrap').css('top', top);
        if ($(window).width() - $('.fancybox-wrap').width() > 0) {
            $('.fancybox-wrap').css('left', ($(window).width() - $('.fancybox-wrap').width()) / 2);
        }
        else
            $('.fancybox-wrap').css('left', 20);
    }

};

Popup.locks = 0;