$(function() {

	  // contact form animations
  $('#contact').click(function(event) {
  	event.preventDefault();
    $('#contactForm1').fadeToggle();
    $('body').addClass('popup')
  })

  $('#contact4').click(function(event) {
  	event.preventDefault();
    $('#contactForm1').fadeToggle();
    $('body').addClass('popup')
  })


  $('#contact2').click(function(event) {
  	event.preventDefault();
    $('#contactForm2').fadeToggle();
    $('body').addClass('popup')
  })

  $('#contact3').click(function(event) {
  	event.preventDefault();
    $('#contactForm2').fadeToggle();
    $('body').addClass('popup')
  })


  $('#exit1').click(function(){
  	$(".form").fadeOut();
  	$('body').removeClass('popup');
  });

  $('#exit2').click(function(){
  	$(".form").fadeOut();
  	$('body').removeClass('popup');
  });


  $(document).mouseup(function (e) {
    var container = $(".form");
    var containerInner = $(".form-inner");

    if (!containerInner.is(e.target) // if the target of the click isn't the container...
        && containerInner.has(e.target).length === 0) // ... nor a descendant of the container
    {
        container.fadeOut();
        $('body').removeClass('popup');
    }
  });

});
