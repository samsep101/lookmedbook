<?php
    /**
     * @var View $this
     * @var SpecialtyModel $specialty
     * @var bool $home_page
     * @var bool $daytime
     */
?>
<!DOCTYPE HTML>
<html>
<head>
    <?php if (isset($doctor_experiment) && $doctor_experiment == 'oftalmolog'): ?>
        <!-- Google Analytics Content Experiment code for doctor fast record LP switch (oftalmolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-0',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code-->
    <?php elseif (isset($doctor_experiment) && $doctor_experiment == 'allergolog-immunolog'): ?>
        <!-- Google Analytics Content Experiment code  for doctor fast record LP switch (allergolog-immunolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-3',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code-->
    <?php elseif (isset($doctor_experiment) && $doctor_experiment == 'otolaringolog'): ?>
        <!-- Google Analytics Content Experiment code for doctor fast record LP switch (otolaringolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-4',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code-->
    <?php elseif (isset($doctor_experiment) && $doctor_experiment == 'nevrolog'): ?>
        <!-- Google Analytics Content Experiment code for doctor fast record LP switch (nevrolog)-->
        <script>function utmx_section(){}function utmx(){}(function(){var
                k='72608629-5',d=document,l=d.location,c=d.cookie;
            if(l.search.indexOf('utm_expid='+k)>0)return;
            function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
                    indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
                    length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
                    '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
                            '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
                            '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
                            valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
                            '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code -->
    <?php endif; ?>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?php echo (isset($page_title)) ? $page_title : SITE_NAME; ?></title>
    <meta name="description" content="<?php echo (isset($page_description)) ? $page_description : SITE_NAME.' - поиск врача и запись на прием, информация обо всех известных заболеваниях.'; ?>">
    <link rel="icon" href="/media/images/home_page/<?php echo CSS_DIR; ?>/favicon.png" type="image/png">
    <?php $this->block('blocks/head'); ?>
    <?php if (isset($home_page)):?>
    <link rel="stylesheet" href="/media/css/<?php echo CSS_DIR; ?>/home_style.css?<?php echo RELEASE_NUMBER?>" type="text/css" media="screen, projection" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" href="/media/css/home_page/ie/ie-7.css?<?php echo RELEASE_NUMBER?>" type="text/css" media="screen, projection" />
    <![endif]-->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="/media/css/home_page/ie/ie.css?<?php echo RELEASE_NUMBER?>" type="text/css" media="screen, projection" />
    <![endif]-->
    <?php endif?>

    <meta name='yandex-verification' content='76535cc7dd5d586f' />

</head>
<body>
    <?php if (isset($specialty) && iconv_strlen($specialty->lp_dative_name, 'UTF-8')>17):?>
        <?php $addicted_class = 'longer-specialty';?>
    <?php else:?>
        <?php $addicted_class = '';?>
    <?php endif;?>
    <div class="wrap landing">
        <?php $this->block('landing/blocks/header'); ?>
        <div class="content <?php echo $addicted_class;?>">
            <?php $this->content(); ?>
        </div>
    </div>

    <?php $this->block('landing/blocks/footer'); ?>

    <noindex>
        <?php $this->block('blocks/counters'); ?>
    </noindex>
    <!--[if IE]><script type="text/javascript" src="http://www.xiper.net/examples/js-plugins/html5-and-css3/explorer-canvas/excanvas.js"></script><![endif]-->
</body>
</html>




