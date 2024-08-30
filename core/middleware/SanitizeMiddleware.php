<?php

namespace core\middleware;

use core\Request;

class SanitizeMiddleware extends Middleware
{
    public function _call($object, $next)
    {
        $_GET = $this->sanitizeArray($_GET);

        $_POST = $this->sanitizeArray($_POST);

        return $next($object);
    }

    private function sanitizeArray($data)
    {
        $cleanedData = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Limpa arrays recursivamente
                $cleanedData[$key] = $this->sanitizeArray($value);
            } else {
                $cleanedData[$key] = htmlspecialchars(
                    trim($value), ENT_QUOTES, 'UTF-8'
                );
            }
        }
        return $cleanedData;
    }
}