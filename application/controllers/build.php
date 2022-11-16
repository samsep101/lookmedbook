<?php

use MatthiasMullie\Minify;

class BuildController extends Controller
{
    public function beforeRender()
    {
        exit(0);
    }

    public function index()
    {
        $this->buildCss();
        $this->buildJs();
    }

    public function buildCss()
    {
        $minifier = new Minify\CSS();
        $minifier->add($this->getCssFile('../../responsive/css/bootstrap.css'));
        $minifier->add($this->getCssFile('styles.css'));
        $minifier->add($this->getCssFile('../chosen.css'));
        $minifier->add($this->getCssFile('my.css'));
        $minifier->minify($this->getCssFile('../min/top.css'));

        $minifier = new Minify\CSS();
        $minifier->add($this->getCssFile('../../responsive/css/bootstrap.css'));
        $minifier->add($this->getCssFile('styles.css'));
        $minifier->add($this->getCssFile('../chosen.css'));
        $minifier->add($this->getCssFile('my.css'));
        $minifier->minify($this->getCssFile('../min/top-lite.css'));

        $minifier = new Minify\CSS();
        $minifier->add($this->getCssFile('../fonts.css'));
        $minifier->add($this->getCssFile('../jquery-ui.min.css'));
        $minifier->add($this->getCssFile('../jquery.fancybox.css'));
        $minifier->add($this->getCssFile('../jquery.jscrollpane.css'));
        $minifier->add($this->getCssFile('../../js/jquery-rating/styles/jquery.rating.css'));
        $minifier->minify($this->getCssFile('../min/lazy.css'));

        $minifier = new Minify\CSS();
        $minifier->add($this->getCssFile('media.css'));
        $minifier->add($this->getCssFile('../../responsive/css/main2.css'));
        $minifier->add($this->getCssFile('responsive-header.css'));
        $minifier->add($this->getCssFile('responsive-body.css'));
        $minifier->add($this->getCssFile('slick.css'));
        $minifier->add($this->getCssFile('slick-theme.css'));
        $minifier->add($this->getCssFile('../../responsive/css/responsive-nav.css'));
        $minifier->minify($this->getCssFile('../min/home.css'));

        $minifier = new Minify\CSS();
        $minifier->add($this->getCssFile('clinic/new.css'));
        $minifier->minify($this->getCssFile('../min/clinic-new.css'));

        $minifier = new Minify\CSS();
        $minifier->add($this->getCssFile('second_opinion/styles.css'));
        $minifier->minify($this->getCssFile('../min/second_opinion.css'));
    }

    public function buildJs()
    {
        $minifier = new Minify\JS();
        $minifier->add($this->getJsFile('jquery-1.8.3.min.js'));
        $minifier->add($this->getJsFile('chosen.jquery.js'));
        $minifier->add($this->getJsFile('jquery.fancybox.pack.js'));
        $minifier->add($this->getJsFile('docdoc-async.js'));
        $minifier->add($this->getJsFile('popup.js'));
        $minifier->add($this->getJsFile('popup_message.js'));
        $minifier->add($this->getJsFile('init.js'));
        $minifier->add($this->getJsFile('history.js'));
        $minifier->add($this->getJsFile('jquery.form.validation.js'));
        $minifier->minify($this->getJsFile('min/top-lite.js'));

        $minifier = new Minify\JS();
        $minifier->add($this->getJsFile('jquery-1.8.3.min.js'));
        $minifier->add($this->getJsFile('jquery-ui-1.10.2.custom.min.js'));
        $minifier->add($this->getJsFile('chosen.jquery.js'));
        $minifier->add($this->getJsFile('jquery.fancybox.pack.js'));
        $minifier->add($this->getJsFile('docdoc-async.js'));
        $minifier->add($this->getJsFile('popup.js'));
        $minifier->add($this->getJsFile('popup_message.js'));
        $minifier->add($this->getJsFile('init.js'));
        $minifier->add($this->getJsFile('history.js'));
        $minifier->add($this->getJsFile('jquery.form.validation.js'));
        $minifier->minify($this->getJsFile('min/top.js'));

        $minifier = new Minify\JS();
        $minifier->add($this->getJsFile('responsive-switch.js'));
        $minifier->add($this->getJsFile('../responsive/js/slick.min.js'));
        $minifier->add($this->getJsFile('../responsive/js/hide-elements.js'));
        $minifier->add($this->getJsFile('../responsive/js/custom.js'));
        $minifier->add($this->getJsFile('../responsive/js/responsive-nav.min.js'));
        $minifier->minify($this->getJsFile('min/home.js'));

        // Validation rules to file
        file_put_contents($this->getJsFile('min/validation-rules.js'), $this->getValidationRules());

        $minifier = new Minify\JS();
        //$minifier->add($this->getJsFile('min/validation-rules.js'));
        $minifier->add($this->getJsFile('bootstrap-affix.js'));
        $minifier->add($this->getJsFile('inputmask/jquery.inputmask.js'));
        $minifier->add($this->getJsFile('jquery.carouFredSel-6.2.0-packed.js'));
        $minifier->add($this->getJsFile('jquery.jcarousel.min.js'));
        $minifier->add($this->getJsFile('jcarousel.connected-carousels.js'));
        $minifier->add($this->getJsFile('jquery.jscrollpane.js'));
        $minifier->add($this->getJsFile('jquery.raty.min.js'));
        $minifier->add($this->getJsFile('jquery-rating/js/jquery.rating-2.0.js'));
        $minifier->add($this->getJsFile('waypoints.min.js'));
        $minifier->add($this->getJsFile('jquery.event.move.js'));
        $minifier->add($this->getJsFile('citymap.js'));
        $minifier->add($this->getJsFile('cookies.js'));
        $minifier->add($this->getJsFile('image_preview.js'));
        $minifier->add($this->getJsFile('actions.js'));
        $minifier->add($this->getJsFile('modal_window.js'));
        $minifier->add($this->getJsFile('simple-timer.js'));
        $minifier->add($this->getJsFile('jquery.lazy.min.js'));
        $minifier->add($this->getJsFile('lazy.js'));
        $minifier->minify($this->getJsFile('min/main.js'));

        $minifier = new Minify\JS();
        $minifier->add($this->getJsFile('jquery-ui-1.10.2.custom.min.js'));
        $minifier->add($this->getJsFile('min/main.js'));
        $minifier->minify($this->getJsFile('min/main-new.js'));
    }

    protected function getCssFile($file)
    {
        return __DIR__ . '/../../media/css/' . CSS_DIR .'/' . $file;
    }

    protected function getGlobalCssFile($file)
    {
        return __DIR__ . '/../../media/css/' . $file;
    }

    protected function getJsFile($file)
    {
        return __DIR__ . '/../../media/js/' . $file;
    }

    // TODO TEMP Удалить после применения редиректов
    public function generateRedirects()
    {
        $file = fopen('/tmp/medbook-redirects.php', 'w');
        /**
         * @var DoctorManager $doctorManager
         */
        $doctorManager = ModelManagerFactory::getByName('doctor');
        /**
         * @var DoctorModel $doctor
         */
        $redirects = [];
        foreach ($doctorManager->getList() as $doctor) {
            if ($doctor->old_alias) {
                if (!preg_match('/^[a-zA-Z0-9\-\_]+$/i', $doctor->old_alias)) {
                    echo "Bad doctor old alias: {$doctor->old_alias}\n";
                    continue;
                }
                if (!preg_match('/^[a-zA-Z0-9\-\_]+$/i', $doctor->alias)) {
                    echo "Bad doctor alias: {$doctor->alias}\n";
                    continue;
                }
                $redirects["/doctor/{$doctor->old_alias}"] = "/doctor/{$doctor->alias}";
            }
        }

        /**
         * @var ClinicManager $clinicManager
         */
        $clinicManager = ModelManagerFactory::getByName('clinic');
        /**
         * @var ClinicModel $clinic
         */
        foreach ($clinicManager->getList() as $clinic) {
            if ($clinic->old_alias) {
                if (!preg_match('/^[a-zA-Z0-9\-\_\/]+$/i', $clinic->old_alias)) {
                    echo "Bad clinic old alias: {$clinic->old_alias}\n";
                    continue;
                }
                if (!preg_match('/^[a-zA-Z0-9\-\_\/]+$/i', $clinic->alias)) {
                    echo "Bad clinic alias: {$clinic->alias}\n";
                    continue;
                }
                $redirects["/clinic/{$clinic->old_alias}"] = "/clinic/{$clinic->alias}";
            }
        }
        $newRedirects = $redirects;
        foreach ($redirects as $redirect) {
            unset($newRedirects[$redirect]);
        }
        $str = <<<STR
<?php
return 
STR;
        fwrite($file, $str);
        fwrite($file, var_export($newRedirects, true));
        fwrite($file, ';');
        fclose($file);
    }

    private function getValidationRules()
    {
        $rules = Register::get('validation_rules');
        $rules = $rules->getValidationRules();
        return 'var validation_rules = '.json_encode($rules).';';
    }
}