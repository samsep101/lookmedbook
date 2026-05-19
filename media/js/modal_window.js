var ModalWindow = function () {

    var self = this;

    this.yes_action = null;
    this.no_action = null;

    this.yes_button_text = 'Сохранить';
    this.no_button_text = 'Уйти без сохранения';

    this.popup = null;

    this.setYesAction = function (f) {
        this.yes_action = f;
    };

    this.setNoAction = function (f) {
        this.no_action = f;
    };


    this.show = function (text) {
        var text = $('<p>' + text + '</p>');
        var buttons_block = $('<span class="buttons ask-section btns"></span>');

        var yes_button = $('<input type="button" value="'+self.yes_button_text+'" class="btn-1" id="ask_section_save_butoon" />');
        var no_button = $('<a href="javascript:void(0)" id="ask_section_no_butoon">'+self.no_button_text+'.<input type="button" value="" class="btn-1" style="display: none" /></a>');

        buttons_block.append(yes_button);
        buttons_block.append(no_button);

        popup_info = $('<div id="modal_window_popup" class="modal-popup"></div>').append(text).append(buttons_block);

        self.popup = new Popup();
        self.popup.show(popup_info, '430px', '150px', false, '300px');

        yes_button.click(function () {
            self.popup.close();

            if (self.yes_action != undefined)
                self.yes_action();

        });

        no_button.click(function () {
            self.popup.close();
            if (self.no_action != undefined)
                self.no_action();
        });
    };

    this.showDefaultPopup = function (text) {
        popup_info = $('<div class="reg-popup" id="success-popup" style="display: block;"><img alt="" src="/media/images/main_logo.png" class="logo"><p class="success-txt">' + text + '</p></div>');

        self.popup = new Popup();
        self.popup.show(popup_info, '670px', '270px', true, '250px');
    };
};