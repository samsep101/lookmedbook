var PersonalRoomDoctorsVisitsPastController = function () {
    var controller = this;

    this.note_type = '';

    this.init = function () {

        $(document).on('click','#close_settings_note', function(){
            controller.note_type = 'doctors_visits_past_note';
            controller.closeNote();
        });
    };

    this.closeNote = function () {
        Ajax.Get('/ajax/closeNote', {note_type: controller.note_type}, function (data) {
            if (data.status == 0)
            {
                $('#close_settings_note').parent().parent().fadeOut();
            }
        });
    };
}