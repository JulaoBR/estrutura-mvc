<?php

namespace core;

use core\Middleware;
class Router 
{
    public $routes;

    const ERROR_CONTROLLER = '';
    const DEFAULT_ACTION = '';

    private static $entry_func = null;
    private static $security_routes = null;

    public function run($routes)
    {
        $middleware = new Middleware();

        $method = Request::getMethod();
        $route = Request::getRoute();

        $module = "error";
        $controller = self::ERROR_CONTROLLER;
        $entry_func = self::DEFAULT_ACTION;
        $security = null;

        $packing = $routes[$method][$route] ?? null;
        if (!is_null($packing)) {
            $callback = $packing[0];
            $security = $packing[1];

            self::$security_routes = $security;

            $c = explode('@', $callback);
            $module     = $c[0];
            $controller = $c[1];
            $entry_func = $c[2];
        }

        $controller = "\src\\modules\\$module\\controller\\$controller";
        
        $defined_controller = new $controller();

        static::$entry_func = $entry_func;

        $middleware->runController(
            $route, $security, $defined_controller, $entry_func, $method
        );
    }

    public static function getSecurityRoutes() 
    {
        return self::$security_routes;
    }

    public static function getEntryFunction()
    {
        return static::$entry_func;
    }

    private static function get_class_constants()
    {
        $reflect = new \ReflectionClass(__CLASS__);
        return $reflect->getConstants();
    }

    public function getSecurityActionsByRoute($route)
    {
        $SECURITY_INDEX = 1;

        $packing = $this->routes['GET']['/' . $route] ??
                   $this->routes['GET'][$route] ?? null;

        if (!is_null($packing)) {
            return $packing[$SECURITY_INDEX];
        } else {
            return null;
        }
    }

    public static function isAValidAction(string|bool $action)
    {
        if (is_bool($action)) {
            return true;
        }

        $action_var = strtoupper($action);
        if (self::get_class_constants()[$action_var] == $action) {
            return true;
        } else {
            return false;
        }
    }

    public function get(string $uri, $action, $security_action = null)
    {
        if (isset($this->routes['GET'][$uri])) {
            sys_log(L_ERROR, "GET Route $uri defined multiple times");
        }

        $this->routes['GET'][$uri] = [$action, $security_action];
    }

    public function post(string $uri, $action, $security_action = null)
    {
        if (isset($this->routes['POST'][$uri])) {
           sys_log(L_ERROR, "POST Route $uri defined multiple times");
        }

        $this->routes['POST'][$uri] = [$action, $security_action];
    }

    public function put(string $uri, $action)
    {
        if (isset($this->routes['PUT'][$uri])) {
            sys_log(L_ERROR, "PUT Route $uri defined multiple times");
        }

        $this->routes['PUT'][$uri] = $action;
    }

    public function delete(string $uri, $action)
    {
        if (isset($this->routes['DELETE'][$uri])) {
            sys_log(L_ERROR, "DELETE Route $uri defined multiple times");
        }

        $this->routes['DELETE'][$uri] = $action;
    }
}