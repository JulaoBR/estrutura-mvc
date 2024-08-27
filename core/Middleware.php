<?php

namespace core;

use core\middleware\AuthMiddleware;
use core\middleware\CsrfMiddleware;
use InvalidArgumentException;
use stdClass;
use Closure;

class Middleware {

    private $layers;

    public function __construct(array $layers = [])
    {
        $this->layers = [
            new AuthMiddleware(),
            new CsrfMiddleware()
        ];

        $this->layers = array_merge($this->layers, $layers);
    }

    public function layer($layers)
    {
        if ($layers instanceof Middleware) {
            $layers = $layers->toArray();
        }

        if ($layers instanceof LayerInterface) {
            $layers = [$layers];
        }

        if (!is_array($layers)) {
            throw new InvalidArgumentException(get_class($layers) . " is not a valid middleware layer.");
        }

        return new static(array_merge($this->layers, $layers));
    }

    public function toArray()
    {
        return $this->layers;
    }

    private function createCoreFunction(Closure $core)
    {
        return function($object) use($core) {
            return $core($object);
        };
    }

    private function createLayer($nextLayer, $layer)
    {
        return function($object) use($nextLayer, $layer){
            return $layer->_call($object, $nextLayer);
        };
    }

    public function appendLayer($layer) {
        array_push($this->layers, $layer);
    }

    public function _call($object, Closure $core)
    {
        $coreFunction = $this->createCoreFunction($core);

        $layers = array_reverse($this->layers);

        $complete = array_reduce($layers, function($nextLayer, $layer){
            return $this->createLayer($nextLayer, $layer);
        }, $coreFunction);

        return $complete($object);
    }

    public function runController($route, $security, $controller, $action, $args)
    {
        $object = new stdClass;
        $object->route = $route;
        $object->security = $security;
        $object->controller = $controller;
        $object->action = $action;
        $object->args = $args;

        $this->_call($object,  function($object){
            $controller = $object->controller;
            $action = $object->action;
            $args = $object->args;

            $controller->$action($args);

            return $object;
        });
    }
}

interface LayerInterface {
    public function _call($object, Closure $next);
}

