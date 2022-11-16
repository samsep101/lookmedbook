<script src="/media/js/jquery-1.8.3.min.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/js/validation"></script>
<link rel="stylesheet" href="/media/css/fonts.css?<?php echo RELEASE_NUMBER?>" type="text/css"/>
<link rel="stylesheet" type="text/css" href="/media/responsive/css/bootstrap.css?<?php echo RELEASE_NUMBER?>" />
<link rel="stylesheet" type="text/css" href="/media/css/<?php echo CSS_DIR; ?>/styles.css?<?php echo RELEASE_NUMBER?>" />
<link rel="stylesheet" type="text/css" href="/media/css/jquery.fancybox.css?<?php echo RELEASE_NUMBER?>"/>
<link rel="stylesheet" type="text/css" href="/media/css/<?php echo CSS_DIR; ?>/my.css?<?php echo RELEASE_NUMBER?>" />
<link rel="stylesheet" type="text/css" href="/media/css/chosen.min.css?<?php echo RELEASE_NUMBER?>" />

<script type="text/javascript" src="/media/js/datepicker/js/datepicker.js?<?php echo RELEASE_NUMBER?>"></script>
<link rel="stylesheet" href="/media/js/datepicker/css/datepicker.css?<?php echo RELEASE_NUMBER?>"/>
<link rel="stylesheet" href="/media/css/lookmedregistry/new_styles.css?<?php echo RELEASE_NUMBER?>"/>
<script type="text/javascript" src="/media/js/jquery-ui-1.10.2.custom.min.js?<?php echo RELEASE_NUMBER?>"></script>
<link rel="stylesheet" href="/media/js/lightbox/lightbox.css?<?php echo RELEASE_NUMBER?>"/>
<script type="text/javascript" src="/media/js/lightbox/lightbox.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/lightbox/jquery.lightbox-0.5.js?<?php echo RELEASE_NUMBER?>"></script>
<link rel="stylesheet" href="/media/js/lightbox/jquery.lightbox-0.5.css?<?php echo RELEASE_NUMBER?>"/>

<script src="/media/js/init.js?<?php echo RELEASE_NUMBER?>"></script>
<script src="/media/js/modal_window.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/popup.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/popup_message.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/new_actions.js?<?php echo RELEASE_NUMBER?>"></script>

<script type="text/javascript" src="/media/js/jquery.form.validation.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.form.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jqDynaForm/jqDynaForm.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jquery.jcarousel.min.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/jcarousel.connected-carousels.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/chosen.jquery.min.js?<?php echo RELEASE_NUMBER?>"></script>

<script type="text/javascript" src="/media/js/inputmask/jquery.inputmask.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/uploadify/jquery.uploadify.min.js?<?php echo RELEASE_NUMBER?>" ></script>
<link rel="stylesheet" type="text/css" href="/media/js/uploadify/uploadify.css?<?php echo RELEASE_NUMBER?>">
<script type="text/javascript" src="/media/js/jcrop/jquery.Jcrop.min.js?<?php echo RELEASE_NUMBER?>" ></script>
<link rel="stylesheet" type="text/css" href="/media/js/jcrop/jquery.Jcrop.min.css?<?php echo RELEASE_NUMBER?>">
<script type="text/javascript" src="/media/js/plupload/plupload.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/plupload/plupload.gears.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/plupload/plupload.silverlight.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/plupload/plupload.flash.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/plupload/plupload.browserplus.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/plupload/plupload.html4.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/plupload/plupload.html5.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/calendar/calendar.min.js?<?php echo RELEASE_NUMBER?>"></script>
<link rel="stylesheet" href="/media/js/calendar/calendar-win2k-cold-1.css?<?php echo RELEASE_NUMBER?>"/>
<script type="text/javascript" src="/media/js/calendar/lang/calendar-ru.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/calendar/calendar-setup.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/ckeditor/ckeditor.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/ckeditor/ckfinder.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js?<?php echo RELEASE_NUMBER?>"></script>
<!--for calendar-->
<link rel='stylesheet' type='text/css' href='/media/js/fullcalendar/fullcalendar.css?<?php echo RELEASE_NUMBER?>'/>
<link rel='stylesheet' type='text/css' href='/media/js/fullcalendar/fullcalendar.print.css?<?php echo RELEASE_NUMBER?>' media='print' />

<script type='text/javascript' src='/media/js/fullcalendar/fullcalendar.js?<?php echo RELEASE_NUMBER?>'></script>
<script type='text/javascript' src='/media/js/querystring.js?<?php echo RELEASE_NUMBER?>'></script>

<script type="text/javascript" src="/media/js/gritter/js/jquery.gritter.min.js?<?php echo RELEASE_NUMBER?>"></script>
<link rel="stylesheet" href="/media/js/gritter/css/jquery.gritter.css?<?php echo RELEASE_NUMBER?>" />
<script type="text/javascript" src="/media/js/library/link_mapper.js"></script>


<?php $this->block('registry/blocks/js-library'); ?>

<script type="text/javascript">
    SessionInfo.csrf = <?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>;
</script>