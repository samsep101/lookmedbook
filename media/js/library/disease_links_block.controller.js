var DiseaseLinksBlockController = function () {
    this.init = function() {
        var container = $(".third-version-buttons-container");
        var height = container.height();
        var anchor_offset = $(".anchor").offset().top - height;

        $(window).scroll(function() {
            if(container.css('display') != 'none') {
                if ($(window).scrollTop() >= anchor_offset) {
                    container.css({'position': 'relative', 'border-radius': '6px'});
                }
                else {
                    container.css({'position': 'fixed', 'border-radius': '6px 6px 0 0'});
                }
            }
        });
    }
};