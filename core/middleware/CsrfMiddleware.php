<?php

namespace core\middleware;

use core\Request;

class CsrfMiddleware extends Middleware
{
    public function _call($object, $next)
    {
        if (Request::isPostRequest()) {
            $token = $_POST['csrf_token'] ?? '';
        }

        return $next($object);
    }
}