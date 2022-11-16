/*
 * Url preview script 
 * powered by jQuery (http://www.jquery.com)
 * 
 * written by Alen Grakalic (http://cssglobe.com)
 * 
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */
 
this.screenshotPreview = function(){	
	/* CONFIG */
		
		xOffset = -10;
		yOffset = -250;

        var self = this;
		// these 2 variable determine popup's distance from the cursor
		// you might want to adjust to get the right result
		
	/* END CONFIG */
	$(document).on('mouseenter', ".screenshot", function(e){
        this.t = this.title;
        this.title = "";
        var c = (this.t != "") ? "<br/>" + this.t : "";
        $("body").append("<p id='screenshot'><img src='"+ this.rel +"' alt='url preview'  />"+ c +"</p>");
        $("#screenshot")
            .css("top",(e.pageY - xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px")
            .fadeIn("fast");
    });

    $(document).on('mouseleave', ".screenshot", function(e) {
        $("#screenshot").remove();
    });

    $(document).on('mousemove', ".screenshot", function(e){
        $("#screenshot")
            .css("top",(e.pageY - xOffset) + "px")
            .css("left",(e.pageX + yOffset) + "px");
	});
};

// starting the script on page load
$(document).ready(function(){
	screenshotPreview();
});