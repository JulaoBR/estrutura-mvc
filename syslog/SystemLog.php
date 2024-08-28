<?php

use core\Request;
use config\Config;

// Log types
define('L_ERROR', E_ERROR);
define('L_WARN', E_WARNING);
define('L_NOTICE', E_NOTICE);
define('L_INFO', 4000);
define('L_UNKNOWN', 5000);

class SystemLog
{
    private $message_type;
    private $destination;
    private $filename;

    private function __construct()
    {
        // type 3 appends the message to the file destination.
        $this->message_type = 3;
        $this->destination = Config::LOGS['path'];
        $this->filename = Config::LOGS['name_file'] . ".log";
    }

    private static function getTypeString($type)
    {
        $ret = "";

        switch($type) {
        case L_ERROR:
            $ret = "[ERROR]";
            break;
        case L_WARN:
            $ret = "[WARN]";
            break;
        case L_NOTICE:
            $ret = "[NOTICE]";
            break;
        case L_INFO:
            $ret = "[INFO]";
            break;
        default:
            $ret = "[UNKNOWN]";
            break;
        }

        return $ret;
    }

    private static function getInstance()
    {
        static $instance = null;

        if (is_null($instance)) {
            $instance = new SystemLog();
        }

        return $instance;
    }

    public static function write($type, $message, $caller = null, $backtrace = null)
    {
        $sysobj = self::getInstance();

        $path_destination = rtrim($sysobj->destination, '/');

        if (!file_exists($path_destination . '/logs')) {
            mkdir($path_destination . '/logs', 0777, true);
        }

        $date = date('Y-m-d H:i:s');
        $type_str = self::getTypeString($type);

        $method = Request::getMethod();
        $route  = Request::getRoute();
        if ($route != "/") {
            $type_str .= " [$method $route]";
        }

        $file = '';
        if ($caller) {
            $file = $caller['file'] . ':' . $caller['line'] . ' ';
        }

        $bt_str = '';
        if ($backtrace) {
            $bt_str = "\n" . $backtrace;
        }

        $full_msg = $date . " " . $file . $type_str . " " . print_r($message, TRUE) . $bt_str;

        $path = "$path_destination/logs/$sysobj->filename";

        error_log($full_msg . "\n", $sysobj->message_type, $path);
    }
}

function get_backtrace(bool $fulltrace = false) {
    $e = new Exception();
    $msg = $e->getTraceAsString();

    if (!$fulltrace) {
        $new_trace = [];
        foreach (explode("\n", $msg) as $i => $v) {
            if (str_contains($v, 'Middleware->{closure}()')) {
                break;
            }

            $new_trace[] = $v;
        }

        $msg = implode("\n", array_slice($new_trace, 2));
    }

    return $msg;
}

function get_caller_function($index = 1) {
    $e = new Exception();
    $msg = $e->getTraceAsString();

    $array_msg = explode("\n", $msg);
    return $array_msg[$index];
}

function sys_log($type, $message, $backtrace = false) {
    $debug_bt = debug_backtrace();
    $caller = array_shift($debug_bt);

    $bt = null;
    if ($backtrace) {
        $bt = get_backtrace();
    }

    SystemLog::write($type, $message, $caller, $bt);
}

function sys_info($message, $backtrace = false) {
    $debug_bt = debug_backtrace();
    $caller = array_shift($debug_bt);

    $bt = null;
    if ($backtrace) {
        $bt = get_backtrace();
    }

    SystemLog::write(L_INFO, $message, $caller, $bt);
}

function sys_log_backtrace() {
    $message = get_backtrace(true);
    SystemLog::write(L_INFO, $message);
}
