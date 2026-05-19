var ExampleShowCardsFormController = function (clinic_id) {

    var self = this;

    this.clinic_id = clinic_id;
    this.specialty_id = null;
    this.purpose_of_visit_id = null;
    this.time_of_visit = null;
    this.page = 1;

    this.init = function () {

        $('.header, .footer').remove();

        $('.content a').click(function(){
            return false;
        });

        $('#doctor-container a').click(function(){
            return false;
        });


    };
}