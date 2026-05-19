$(function(){
	
	var controllers = {};
	
	$.getScript('/media/js/library/header.controller.js', function(){
		controllers.header = new HeaderController();
        controllers.header.init();
	});
	
	$.getScript('/media/js/library/footer_block.controller.js', function(){
		controllers.footer = new FooterBlockController();
        controllers.footer.init();
	});
	
	$.getScript('/media/js/library/login_form.controller.js');
	$.getScript('/media/js/library/registration_form.controller.js');
	$.getScript('/media/js/library/password_recovery.controller.js');
	
	$.getScript('/media/js/jquery-ui-1.10.2.custom.min.js', function(){
		$(".datepicker").datepicker();
	});
	
	$.ajaxSetup({
  cache: true
});
	
	$.each([
		'/media/js/validation-rules.js',
		'/media/js/jquery.fancybox.pack.js',
		'/media/js/jquery.jscrollpane.js',
		'/media/js/popup.js',
		'/media/js/modal_window.js',
		'/media/js/popup_message.js',
		'/media/js/jquery.form.js',
		'/media/js/jquery.form.validation.js',
		'/media/js/actions.js'
	], function(){
		var URL = this;
		$.ajax({
			url: URL,
			dataType: "script",
			cache: true
		});
	});
	
	if($.fn.inputmask){
		$(".inputPhone").inputmask("+7 (999) 999-99-99");
	}
	
	
});