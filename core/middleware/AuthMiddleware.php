<?php

namespace core\middleware;

class AuthMiddleware extends Middleware
{
    public function _call($request, $next)
    {
        return $next($request);
    }
}