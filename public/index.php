<?php
if (php_sapi_name()!='cli') {
    header("Content-Type: text/html; charset=UTF-8");
}

// по константе проще
if(!empty($_SERVER['SERVER_NAME'])){
    define('SERVER_NAME', $_SERVER['SERVER_NAME']);
} else {
    define('SERVER_NAME', 'lookmedbook.ru');
}

//define(debug, true);
// абсолютный путь до корня сайта
define('ABS_ROOT', realpath(__DIR__ . '/../'));
define('PUBLIC_ROOT', realpath(__DIR__));

require(ABS_ROOT . '/application/config/site.cfg.php');


if ( !debug) {
    ini_set('display_errors', 'Off');
} else {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
}

require_once(ABS_ROOT . '/vendor/sentry/sentry/lib/Raven/Autoloader.php');
Raven_Autoloader::register();
$client = new Raven_Client('https://3eddb6b698414aa28519bd1b864ef789:92e01d140f5c448c81348fd7834e201c@sentry.io/157050');

try {
    if (!empty($argc)) {
        chdir(dirname(__FILE__));
        unset($argv[0]);
        $uri = '/' . join('/', $argv);
    } else {
        $uri = '';
    }

    define('CURRENT_HOST', isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    // бенчмарк запросов
    define('BENCHMARKS', (bool)(mb_strpos(CURRENT_HOST, '.citrus.one') > 0));

    require(ABS_ROOT . '/application/config/init.php');

    $redirect_domen = $redirect_uri = '';
    if (mb_strlen(SERVER_NAME)) {
        $excluded_subdomens = [
            'account',
            'test',
            'sankt-peterburg',
            'novosibirsk',
            'chelyabinsk',
            'samara',
            'kazan',
            'nizhniy-novgorod',
            'ekaterinburg',
            'perm',
            'krasnodar',
            'voronezh',
            'rostov-na-donu',
            'ufa',
            'izhevsk',
            'krasnoyarsk',
            'volgograd',
            'sochi',
            'tyumen',
            'yaroslavl'
        ];
        $m = [];
        if (preg_match('|^(www\.)?(([a-z0-9-]+)\.)?\w+\.\w+$|', SERVER_NAME, $m)) {
            if (!empty($m[1])) {
                $redirect_domen = str_replace($m[1], '', SERVER_NAME);
            }
            if (!empty($m[2]) and ! in_array($m[3], $excluded_subdomens)) {
                $redirect_domen = str_replace($m[1] . $m[2], '', SERVER_NAME);
            }
        }
    }

    // редирект со страницы со слешем на конце на страницу без слеша на конце
    if (isset($_SERVER['REQUEST_URI']) and preg_match('/^(.+)\/$/ims', $_SERVER['REQUEST_URI'], $matches)) {
        $redirect_uri = $matches[1];
    }

    if ($redirect_domen or $redirect_uri) {
        if (!$redirect_domen) {
            $redirect_domen = $_SERVER['SERVER_NAME'];
        }
        if (!$redirect_uri) {
            $redirect_uri = $_SERVER['REQUEST_URI'];
        }
        RedirectManager::redirect301(SITE_SCHEME . '://' . $redirect_domen . $redirect_uri);
    }

    $controller = new Dispatcher();
    $controller->process($uri);
} catch (Exception $exception) {
    if (debug) {
        echo $exception->getFile() . ":" . $exception->getLine() . " " . $exception->getMessage();
        echo "<pre>";
        print_r($exception->getTraceAsString());
        echo "</pre>";
    }
    if (!debug && (!in_array(php_sapi_name(), array('cgi-fcgi', 'cli')))) {
        error404($exception);
    }
    if (in_array(php_sapi_name(), array('cli'))) {
        display_cli_error($exception);
    }
}

if (debug) {
    echo "<br><br><br><hr>
			Peak memory usage: " . number_format(memory_get_peak_usage()) . "
			";
}

function error404($exception = null)
{
    if (debug) {
        echo '
    <html>
    <head>
    <style>
    * { font-family: Verdana, Tahoma, Arial; font-size: 13px; }
    body {}
    .error {width: 860px;text-align: left;}
    .error h1 {font-size: 18px;margin-left: 36px;}
    .error-num {float: left;width: 30px;background-color: #fff;padding: 3px;text-align: left;}
    .error-descr {float: left;background-color: #f5f5f5;margin-bottom: 10px;padding: 3px;width: 800px;text-align: left;}
    .error-descr span {font-weight: bold;}
    </style>
    </head>
    </body>
    ';
        echo '<center><div class="error"><h1>' . $exception->getMessage() . '</h1>';
        foreach ($exception->getTrace() as $key => $item) {
            echo '<div class="error-item"><div class="error-num">' . ($key + 1) . '.</div>';
            echo '<div class="error-descr">';
            echo '<span>' . $item['class'] . ' ' . $item['type'] . ' ' . $item['function'] . ' ' . '( ' . join(', ', $item['args']) . ' )' . '</span><br>';
            echo $item['file'] . ' (Line: ' . $item['line'] . ')<br>';
            echo '</div><br clear="all" /></div>';
        }
        echo '</div></center>';
        echo '</body></html>';
    } else {
        ErrorPageViewHelper::page404('404');
        exit();
    }
}

function display_cli_error($exception = null)
{
    if (!$exception) {
        return;
    }
    echo 'Error! ' . $exception->getMessage() . "\n\n";
    echo 'File: ' . $exception->getFile() . "\n\n";
    echo 'Line: ' . $exception->getLine() . "\n\n";
    //echo 'Trace: '.print_r($exception->getTrace())."\n\n\n";
    //echo 'Exception: '.print_r($exception, true)."\n\n\n";

}
