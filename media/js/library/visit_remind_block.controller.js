var VisitRemindBlockController = function(visit_id, visits_page_flag, unique_el_id){
    var self = this;
    this.visit_id = visit_id;
    this.visits_page_flag = visits_page_flag;
    this.unique_el_id = unique_el_id;

    this.add_review_controller = null;

    this.init = function(){

        $('#'+self.unique_el_id).click(function(){

            $(this).fancybox();

            if (self.add_review_controller == null) {
                self.add_review_controller = new AddReviewBlockController(self.visit_id, self.visits_page_flag, self.unique_el_id);
                self.add_review_controller.init();
            };
        });
    };
};