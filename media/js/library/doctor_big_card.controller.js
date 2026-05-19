var DoctorBigCardController = function (container, doctor_id, my_doctor, counter) {

    var self = this;
    this.doctor_id = doctor_id;
    this.container = container;
    this.my_doctor = my_doctor;
    this.counter = counter;

    this.is_current_account = null;
    this.is_call_center_operator = null;
    this.is_uncommented_visit = null;
    this.review_block_unique_id = null;
    this.review_unique_id = null;
    this.is_doctor_last_review = null;
    this.is_reviews_count = null;
    this.doctor_link = null;
    this.review_text = null;
    this.authed_user = null;

    this.record_controller = null;

    this.after_init_callbacks = [];

    this.init = function () {

        var review_block = '';
        if (!self.is_current_account || !self.is_call_center_operator) {
            review_block = '<div class="tooltip-block';
            if (!self.is_reviews_count) {
                review_block += ' without-reviews-yet';
            }
            if (self.is_current_account && self.is_uncommented_visit && self.is_reviews_count) {
                review_block += ' my-review-needed"';
            } else {
                review_block += '"';
            }
            if (self.is_uncommented_visit) {
                review_block += ' id="visit-remind-block-'+self.review_block_unique_id+'"';
            }
            review_block += '>';

            if (self.is_current_account && self.is_uncommented_visit) {
                review_block += '<img class="corn" src="/media/images/tooltip_corn.png" alt=""/><a class="review-link remaked-review-link rev-popup-open" id="'+self.review_block_unique_id+'" href="#add-review-popup-'+self.review_block_unique_id+'"><span></span>Оставить отзыв</a>';
                review_block += '<script>';
                review_block += 'block_controller = new VisitRemindBlockController('+self.review_unique_id+', "", '+self.review_block_unique_id+');block_controller.init();';
                review_block += '</script>';
            }
            else if (self.is_doctor_last_review) {
                review_block += '<div class="tooltip"><img class="corn" src="/media/images/tooltip_corn.png" alt=""/>';
                if (!self.authed_user) {
                    review_block += '<p><a style="color: #5D5D5D; text-decoration:none;" data-url="'+self.doctor_link+'#reviews" href="'+self.doctor_link+'#reviews">'+self.review_text+'</a></p>';
                } else {
                    review_block += '<p><a style="color: #5D5D5D; text-decoration:none;" href="'+self.doctor_link+'#reviews">'+self.review_text+'</a></p>';
                }
                review_block += '</div>';
            }
            review_block += '</div>';

            $(self.container+ ' .rating').append(review_block);
        }



        if (self.my_doctor == 1) {
            var button_text = '<i class="icon-add" style="background-position: 0 100%;"></i><span class="txt txt-added" style="display: inline; line-height: 26px;">В закладках</span>';
        }
        else {
            button_text = '<i class="icon-add"></i><span class="txt">Добавить в закладки</span>';
        }
        $(self.container + ' .doctor_bookmark').html(button_text);

        if (self.counter == 1) {
            $(self.container).addClass('fright');
        }
    };

};
