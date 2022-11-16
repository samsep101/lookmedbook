var DiseaseSearchPageController = function (label_for_counters) {

    var self = this;
    this.label_for_counters = label_for_counters;

    this.form_controller = null;

    this.init = function () {

        self.form_controller = new DiseaseQuickSearchFormController(0,0, self.label_for_counters);

        self.form_controller.input_element = $('.search-block .txt');
        self.form_controller.drop_down_container = $('.search-block .drop-menu');
        self.form_controller.submit_element = $('.search-block .btn-1');
        self.form_controller.init();
    };
};