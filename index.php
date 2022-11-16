<?php

if (php_sapi_name() !== 'cli') {
    die();
}

// по константе проще
define('SERVER_NAME', 'lookmedbook.ru');

// абсолютный путь до корня сайта
define('ABS_ROOT', realpath(dirname(__FILE__)));
// публичный корень сайта
define('PUBLIC_ROOT', realpath(__DIR__ . '/public/'));
// оставлено
define('BENCHMARKS', false);
require(ABS_ROOT . '/application/config/site.cfg.php');

if (!debug) {
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

    define('CURRENT_HOST', '');
    require(ABS_ROOT . '/application/config/init.php');

    $controller = new Dispatcher();
    $controller->process($uri);
} catch (Exception $exception) {
    if (debug) {
        echo $exception->getFile() . ":" . $exception->getLine() . " " . $exception->getMessage();
        echo "<pre>";
        print_r($exception->getTraceAsString());
        echo "</pre>";
    }
    display_cli_error($exception);
}

if (debug) {
    echo "<br><br><br><hr>
			Peak memory usage: " . number_format(memory_get_peak_usage()) . "
			";
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