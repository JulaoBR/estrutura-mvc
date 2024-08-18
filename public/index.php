<?php

define('APP_START', microtime(true));

$session_name = session_name();

require '../vendor/autoload.php';

session_name($session_name);
session_start();

$php_self = filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_URL);
$basedir = str_replace("/index.php", "", (string)$php_self);

define("__BASEDIR__", $basedir);
define("__ROOT__", __DIR__ . "/../");
define("__SRC__", __DIR__  . "/../src/");

require __DIR__ . '/../syslog/SystemLog.php';
require __DIR__ . '/../syslog/ErrorHandling.php';
require __DIR__ . '/../routes/Routes.php';

date_default_timezone_set('America/Sao_Paulo');

ini_set('display_errors', 0);
set_error_handler(['ErrorHandling', 'exception']);
register_shutdown_function(['ErrorHandling', 'shutdown']);

$router->run($router->routes);
