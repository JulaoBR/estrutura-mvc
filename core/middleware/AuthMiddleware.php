<?php

namespace core\middleware;

use core\Request;
use core\Router;

class AuthMiddleware extends Middleware
{
    public function _call($object, $next)
    {
        if ($object->security == Router::WITHOUT_AUTH) {
            return $next($object);
        }

        if (!isset($_SESSION['user'])) {
            Request::redirect('/login');
        }

        return $next($object);
    }
}