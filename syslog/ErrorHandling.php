<?php

use core\JsonResponse;
use core\Request;

class ErrorHandling
{
    private $message = '';
    private $file = '';
    private $line = 0;

    function __construct($mensagem, $arquivo, $linha)
    {
        $this->message = $mensagem;
        $this->file = $arquivo;
        $this->line = $linha;
    }

    public static function exception($errno, $errstr, $errfile, $errline)
    {
        $self = new self($errno, $errstr, $errfile, $errline);

        // Theses codes are definied into SystemLog.php file
        $error_type_code = L_UNKNOWN;

        switch($errno)
        {
            case E_USER_ERROR:
            case E_ERROR:
            case E_PARSE:
                JsonResponse::appendError("Ocorreu um Erro Interno no Servidor");
                $error_type_code = L_ERROR;
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $error_type_code = L_WARN;
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                $error_type_code = L_NOTICE;
                break;
        }

        $self->handleSystemLog($error_type_code);
        $self->handleFrontend($error_type_code);

        return true;
    }

    public static function shutdown()
    {
        $error = error_get_last();
        if (isset($error["type"])) {
            restore_error_handler();
            self::exception($error["type"], $error["message"], $error["file"], $error["line"]);
        }
    }

    private function handleSystemLog($type)
    {
        if ($type == L_ERROR) {
            //Remove stack trace
            $this->message = substr($this->message, 0, strpos($this->message, "Stack"));
        }

        $str = $this->file . ":" . $this->line .  " " . $this->message;
        SystemLog::write($type, $str);
    }

    private function handleFrontend($type)
    {
        if ($type == L_ERROR) {
            if (Request::isXmlHttpRequest()) {
                JsonResponse::responseErrorIfExist("ERRO CRITICO");
            } else {
                Request::redirect('/server-error');
            }
        }
    }
}
