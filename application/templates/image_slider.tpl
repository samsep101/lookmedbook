<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo (isset($page_title)) ? $page_title : SITE_NAME; ?></title>

        <script type="text/javascript" src="/media/js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="/media/js/jquery.jcarousel.min.js"></script>
        <script type="text/javascript" src="/media/js/jcarousel.connected-carousels.js"></script>
        <script>
            $(function() {
                $('[data-jcarousel]').each(function() {
                    var el = $(this);
                    el.jcarousel(el.data());
                });

                $('[data-jcarousel-control]').each(function() {
                    var el = $(this);
                    el.jcarouselControl(el.data());
                });
            });
        </script>
    </head>

    <body>
        <?php $this->content(); ?>
    </body>

</html>


<style>
    html, body, h1, h2, h3, h4, h5, h6, p, em, strong, abbr, acronym, blockquote, q, cite, ins, del, dfn, a, div, span, pre, hr, address, br, b, i, sub, sup, big, small, tt, table, tr, caption, thead, tbody, tfoot, col, colgroup, form, input, label, textarea, button, fieldset, legend, select, option, optiongroup, ul, ol, li, dl, dt, dd, code, var, kdb, samp, img, object, param, map, area, bdo, iframe { padding:0; margin:0; }
    ul li { list-style-type:none; }
    img { border:none; }
    :-moz-any-link:focus {outline:none;}
    .connected-carousels { margin:3px 0 0 3px; }
    .connected-carousels .stage {  position:relative; padding:6px; background:#FFF; box-shadow:0 0 3px #CCC; border-radius:6px; position:relative; }
    .connected-carousels .navigation { margin:8px auto 0; position:relative; width:580px; }
    .connected-carousels .carousel { position:relative; overflow:hidden; }
    .connected-carousels .carousel ul { width:20000em; position:absolute; }
    .connected-carousels .carousel li { float:left; }
    .connected-carousels .carousel-stage { height:279px; }
    .connected-carousels .carousel-navigation { height:65px; width:580px; padding-top:3px; }
    .connected-carousels .carousel-navigation li { cursor:pointer; margin-right:11px; padding:2px; border:1px #dadada solid; background:#FFF; box-shadow:0 0 2px #666; border-radius:2px; }
    .connected-carousels .carousel-navigation ul li:firts-child {margin:0;}
    .connected-carousels .carousel-navigation li img { display:block; }
    .connected-carousels .carousel-navigation li.active { border-color:#999; }
    .connected-carousels .prev-navigation, .connected-carousels .next-navigation { display:block; position:absolute; top:20px; width:12px; height:24px; background:url(/media/images/slider_controls.png) no-repeat; }
    .connected-carousels .next-navigation { background-position:100% 0; }
    .connected-carousels .navigation .prev-navigation { left:-25px; }
    .connected-carousels .navigation .next-navigation { left:auto; right:-25px; }
    .connected-carousels .navigation .prev-navigation:hover { background-position:0 100%; }
    .connected-carousels .navigation .next-navigation:hover { background-position:100% 100%; }
    .connected-carousels .prev-navigation.inactive, .connected-carousels .next-navigation.inactive { opacity:.5; cursor:default; }
    .connected-carousels .prev-navigation.inactive:hover, .connected-carousels .next-navigation.inactive:hover { background-position:0 0; }
    .connected-carousels .next-navigation.inactive:hover { background-position:100% 0; }
    .connected-carousels .prev-stage, .connected-carousels .next-stage { display:block; position:absolute; top:0; width:50%; height:100%; }
    .connected-carousels .prev-stage { left:0; }
    .connected-carousels .next-stage { right:0; }
    .connected-carousels .prev-stage.inactive, .connected-carousels .next-stage.inactive { display: none; }
    .connected-carousels .prev-stage span, .connected-carousels .next-stage span { display:none; position:absolute; top:42%; width:52px; height:52px; background:url(/media/images/popup_controls.png) no-repeat; }
    .connected-carousels .prev-stage span { left:20px; }
    .connected-carousels .next-stage span { right:20px; background-position:100% 0; }
    .connected-carousels .prev-stage:hover span, .connected-carousels .next-stage:hover span { display:block; }
</style>