var EqualElementsBlockController = function () {
    var self = this;

    this.init = function() {
        $(".equal-elements .all-elements .show-all").click(function() {
            $(this).toggleClass('active');
            $(".equal-elements-container").slideToggle(150);
        });
    };
};