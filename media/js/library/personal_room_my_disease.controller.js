var PersonalRoomMyDiseaseController = function (letter) {

    var self = this;

    self.my_disease_id = null;
    self.read_disease_id = null;

    this.init = function () {

        $('.all-letters').addClass('current');

        if (letter)
        {
            $('.letters').children().removeClass('current');
            $('.letters .letter-'+letter).addClass('current');
        }

        $('.archive_button').click(function () {
            self.my_disease_id = $(this).data('my_disease_id');
            if (self.my_disease_id){
                self.addToArchive();
            }


        });

        $('.btn-4').click(function () {
            self.read_disease_id = $(this).attr('data-id');
            Ajax.Get('/ajax/readAboutDisease', {read_disease_id: self.read_disease_id}, function (data) {
                if (data.result.ready_disease) {
                    window.location ='/disease/get?id='+data.result.ready_disease;
                }
                else {
                    self.showPopup('Этот текст правят наши редакторы');
                }
            });
        });
    }

    this.addToArchive = function () {
        Ajax.Post('/disease/ajaxAddToArchive', {my_disease_id: self.my_disease_id}, function (data) {
            if (data.status == 0) {
                $('#archive_buttons_' + self.my_disease_id).css('display', 'none');
                $('#archive_buttons_' + self.my_disease_id).parent().parent().remove();
            }
        });
    };

    this.showPopup = function (text) {
        $('#success-popup .success-txt').html(text);
        $('#success-popup-link').click();
    }
}
