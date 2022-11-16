<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LookMedBook</title>
<link type="text/css" href="/media/css/main.css" rel="Stylesheet" />
<script type="text/javascript" src="/media/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="/media/js/jquery.tooltip.js"></script>
<link rel="shortcut icon" href="/media/img/favicon.png"/>
<script type='text/javascript' src='/media/js/jquery.corner.min.js'></script>
<meta name="apple-mobile-web-app-capable" content="yes" />
<link rel="apple-touch-icon-precomposed" href="/media/img/ipad-iphone-logo-goradel.png" /></head>
<body>
	<center>
	<div>
		<div id="login_item" style="display:none;">
	<div class="logo"><img src="/media/img/eyes_owl_line_tr.png" width="200px" border="0" /></div>
	<div id="frm" class="content" style="height:auto; min-height:40px; width:294px;">
	<form method="POST">
	<br/>
	<p class="errormsg"></p>
	<table id="loginForm" class="form">
		<tr>
			<td align="center">
				Введите эл. почту:
				<input type="text" name="login" value=""/>
			</td>
		</tr>
		<tr>
			<td align="center">
				Введите пароль:
				<input type="password" name="password" value=""/>
			</td>
		</tr>
	</table>
	<input type="submit" value="Войти" id="signin"/>
	<br/>
	<br/>
	</form>
	</div>
</div>

<script>
$(document).ready(function() {
    centering();
});
function centering(){
	var id = "login_item";
	var main = $("#"+id).height();
	var h = $(window).height();
	if (h >= main) {
		var new_h = (parseInt(h)-main)/2 - 60;
		$("#"+id).css('margin-top',new_h);
		$("#"+id).fadeIn(250);
		$("#frm").corner("5px");
	}
}
</script>
	</div>
	</center>
	<div id="action_loading"><img src="/media/images/ajaxLoader.gif"/></div>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28568864-1']);
  _gaq.push(['_setDomainName', 'goradel.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>