$(document).ready(function(){

//crossbrowser html5 placeholder
	$('[placeholder]').focus(function() {
	  var input = $(this);
	  if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = $(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function() {
	  $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	}); //end crossbrowser html5 placeholder

$('.search-form .tabs').each(function() {
    $(this).find('li').each(function(i) {
      $(this).click(function(){
        $(this).addClass('active').siblings().removeClass('active')
          .parents('.search-form').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
      });
    });
  });
  
//tabs
$('.location-box .tabs').each(function() {
    $(this).find('li').each(function(i) {
      $(this).click(function(){
        $(this).addClass('active').siblings().removeClass('active')
          .parents('.location-box').find('.section').eq(i).fadeIn(150).siblings('.section').hide();
      });
    });
  });
  
$(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true});

$(".info-block .close").click(function(){
	$(this).parent('.info-block').hide();	
});

$(".illness-nav .nav li:last").addClass("last");
$(".illness-nav .sub-nav li:first").addClass("first");
$(".illness-nav .sub-nav li:last").addClass("last");

//search dropdown menu
$(".quick-search-block input.txt").keydown(function(){
	$(".search-info .drop-menu").slideDown();
});

$(".wrap").click(function(){
	$(".search-info .drop-menu, .quick-search .drop-menu, .search-block .drop-menu").slideUp();
});

$(".quick-search input.quick-search-input").keydown(function(){
	$(".quick-search .drop-menu").slideDown();
});

$(".search-block input.txt").keydown(function(){
	$(".search-block .drop-menu").slideDown();
});
 //search dropdown menu end

//gender choose
$(".gender span").click(function(){  
  $(this).toggleClass("selected");
}); //gender choose end

/*$(".btn-bookmark").click(function() {
	$(this).toggleClass("btn-bookmark-added");
});*/


$('.sub-nav li a').on('click', function(e){
 var link = $(this).attr('href'),
  linkTop = $(link).offset().top-$('.illness-nav').height();
  $('html, body').animate({scrollTop : linkTop}, 1000);
  
  var sections = $(".content .section");
var navigation_links = $(".sub-nav li a");
	
	sections.waypoint({
		handler: function(event, direction) {
		
			var active_section;
			active_section = $(this);
			if (direction === "up") active_section = active_section.prev();

			var active_link = $('.sub-nav li a[href="#' + active_section.attr("id") + '"]');
			navigation_links.removeClass("active");
			active_link.addClass("active");
		},
		offset: '20%'
});

	});
	
	
$('.sub-nav-man, .sub-nav-children, .sub-nav-pregnant').hide();
$('.tab-woman').click(function(){
	$('.sub-nav').hide();
	$('.sub-nav-woman').show();
});
$('.tab-man').click(function(){
	$('.sub-nav').hide();
	$('.sub-nav-man').show();
});
$('.tab-children').click(function(){
	$('.sub-nav').hide();
	$('.sub-nav-children').show();
});
$('.tab-pregnant').click(function(){
	$('.sub-nav').hide();
	$('.sub-nav-pregnant').show();
});
	
	
$(".other-links li span").click(function () {
    $(this).parent().children('.drop-box').slideToggle(200);
    $(this).toggleClass("collapsed");}
 );
	

$('.rating-block-clinics').hide();
$('.search-form .tab-clinics').click(function(){
		$('.rating-block-clinics').show();
		$('.rating-block-doctors').hide();
	});
$('.search-form .tab-doctors').click(function(){
		$('.rating-block-clinics').hide();
		$('.rating-block-doctors').show();
	});



//search filter
$(".colright .radioBox").click(function(){
  $(".colright").find(".act").removeClass("act");
  $(this).addClass("act");
  $('.colright .radioBox').find('input[type=hidden]').val(0);
  $(this).find('input[type=hidden]').val(1);

});

$(".colleft .radioBox").click(function(){
  $(".colleft").find(".act").removeClass("act");
  $(this).addClass("act");
  $('.colleft .radioBox').find('input[type=hidden]').val(0);
  $(this).find('input[type=hidden]').val(1);

});

$('.chekBox ,.chekBox-allTime').click(function(){
		$(this).toggleClass('act');

	});

$('.choose-section .chekBox-allTime').click(function(){
		$('.choose-section .chekBox').removeClass('act');
		$(this).addClass('act');
		$('.choose-section .first-label .radioBox').addClass('act');
		$('.choose-section .first-label .radioBox').find('input[type=hidden]').val(1);
	});
	
$('.choose-section .chekBox').click(function(){
		$('.choose-section .chekBox-allTime').removeClass('act');
		$('.choose-section .second-label .radioBox').removeClass('act');
		$('.choose-section .second-label .radioBox').find('input[type=hidden]').val(0);
		$('.choose-section .first-label .radioBox').addClass('act');
		$('.choose-section .first-label .radioBox').find('input[type=hidden]').val(1);
		$('.choose-section .chekBox-allTime').find('input[type=hidden]').val(0);
		$('.choose-section .radio').toggleClass('act');
		if ($(this).hasClass('act')) $(this).find('input[type=hidden]').val(1);
		else  $(this).find('input[type=hidden]').val(0);
});

$('.choose-section .chekBox-allTime').click(function(){
		if ($(this).hasClass('act')) $(this).find('input[type=hidden]').val(1);
		else  $(this).find('input[type=hidden]').val(0);
		$('.choose-section .second-label .radioBox').removeClass('act');
		$('.choose-section .second-label .radioBox').find('input[type=hidden]').val(0);
});

$('.choose-section .second-label').click(function(){
		$('.choose-section .chekBox, .choose-section .chekBox-allTime').removeClass('act');
});

$('.choose-section .first-label, .choose-section .chekBox-allTime').click(function(){
		$('.choose-section .chekBox-allTime').find('input[type=hidden]').val(1);
});

$('.choose-section .first-label').click(function(){
		$('.choose-section .chekBox-allTime').addClass('act');
});
//search filter end



/*var inftop = $(".cards-wrap").clientY;
console.log(inftop)	
$(window).load(function(e){
	var e = $(".cards-wrap");
	console.log(inftop)	
	$(window).on('mousemove', function(e){
		console.log(e)	
		if($(window).scrollTop() >= inftop){
			console.log(inftop)	
		}
		
	});

});*/


});