<?php

namespace core\middleware;

abstract class Middleware
{
    abstract public function _call($object, $next);
}