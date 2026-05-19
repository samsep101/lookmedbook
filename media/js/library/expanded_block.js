var ExpandedBlock = function(){

    this.block_container = null;

    this.more_button_text = 'узнать больше';
    this.hide_text = 'Скрыть';

    this.max_height = 200;

    this.more_button = null;

    this.not_apply_onload = null;

    var self = this;

    this.init = function(){
        if ($(self.block_container).height() < self.max_height){
            $(self.block_container).parent().find(".more-link").hide();
        } else {
            self.more_button = $('<a class="more-link">'+self.more_button_text+'</a>');
            $(self.block_container).after(self.more_button);
        }

        $(self.block_container).css('margin-bottom', '15px');

        if(self.not_apply_onload) {
            $(".more-link").addClass('expanded').text('Скрыть');
        } else {
            $(self.block_container).css('max-height', self.max_height);
            $(".more-link").text('Показать все');
        }

        if (self.more_button) {
            self.more_button.click(function () {
                if (!$(this).hasClass('expanded'))
                {
                    $(this).addClass('expanded');
                    $(this).html('Скрыть');
                    $(self.block_container).css('max-height', '');
                } else {
                    $(this).html('Показать все');
                    $(this).removeClass('expanded');
                    $(self.block_container).css('max-height', self.max_height);
                }
            });

        }
    };
};