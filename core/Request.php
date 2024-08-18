<?php

namespace core;

class Request
{
    private static $route = null;
    private static $route_referer = null;

    public static function redirect($url)
    {
        $url = Request::getBaseUrl() . $url;
        echo '<script type="text/javascript">';
        echo 'window.location.href="' . $url . '";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
        echo '</noscript>';
        exit;
    }

    public static function getDomain()
    {
        return strtolower($_SERVER['SERVER_NAME']);
    }

    public static function getBaseUrl()
    {
        $base = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $base .= $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $base .= ':' . $_SERVER['SERVER_PORT'];
        }

        $base .= __BASEDIR__;

        return $base;
    }

    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getRoute()
    {
        if (is_null(self::$route)) {
            $url = filter_input(INPUT_GET, 'request');
            $url = str_replace(__BASEDIR__, '', $url ?? '');
            self::$route = "/" . $url;
        }

        return self::$route;
    }

    public static function getRouteReferer()
    {
        if (is_null(self::$route_referer)) {
            $referer = $_SERVER['HTTP_REFERER'];
            $url = explode("?", $referer);
            $url = str_replace(__BASEDIR__, '', $url[0] ?? '');
            $url = str_replace(self::getDomain(), '', $url ?? '');
            $url = str_replace(['https', 'http', ':', '/'], '', $url ?? '');
            self::$route_referer =  $url;
        }

        return self::$route_referer;
    }

    public static function isXmlHttpRequest() : bool
    {
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
        ) {
            return true;
        }
        return false;
    }

    public static function isPostRequest() : bool
    {
        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') {
            return true;
        }
        return false;
    }

    public static function getIPAdress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}