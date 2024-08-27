<?php

namespace core\middleware;

use core\Request;

class AuthMiddleware extends Middleware
{
    public function _call($request, $next)
    {
        if (!isset($_SESSION['user'])) {
            Request::redirect('/login');
        }

        return $next($request);
    }
}