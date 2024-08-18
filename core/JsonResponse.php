<?php

namespace core;

use Exception;
use core\helpers\DateTimeHelper;

class JsonResponse
{
    private static $title = "";
    private static $chart = [];
    private static $history = 1;
    private static $modal = [];
    private static $link_redirect = null;
    private static $data = [];
    private static $message = [];
    private static $errors = [];
    private static $status_code = 200;
    private static $status = null;
    private static $headers = [];
    private static $type_view_message = "toast";

    // set in seconds
    private static $time_toast_remove = 5;


    const ERROR = 0;
    const SUCCESS = 1;
    const WARNING = 2;

    private static function call_exception($function)
    {
        throw new Exception("JsonAjax API: Invalid '$function' format");
    }

    private static function json($array)
    {
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function responseSimple($array)
    {
        if (!empty(self::$message)) {
            $array['message'] = self::$message;
        }

        $array['datetime'] = DateTimeHelper::currentDateTime();
        self::json($array);
    }

    private static function responseFile($binary_blob)
    {
        self::sendHeaders();
        echo $binary_blob;
        exit;
    }

    private static function sendHeaders()
    {
        http_response_code(self::$status_code);

        foreach (self::$headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    private static function setContentType($content_type)
    {
        self::addHeader('Content-type', $content_type);
    }

    private static function addHeader($key, $value)
    {
        self::$headers[$key] = $value;
    }

    public static function createMessage($status_cod, $message = null, $title = '')
    {
        if (!empty($message))
            self::appendMessage($message);

        self::$title = $title;
        $r = self::setHeaderResponse($status_cod);

        $r['data'] = self::$data;
        $r['message'] = self::$message;

        return $r;
    }

    public static function response($status, $message = null, $title = '')
    {
        if (!$status && is_null($message))
            self::call_exception(__FUNCTION__);

        if ($status != self::SUCCESS)
            sys_log(L_WARN, $message, true);

        $r = self::createMessage($status, $message, $title);

        if (self::hasModal())
            $r["modal"] = self::$modal;

        $r['responseType'] = __FUNCTION__;
        self::json($r);
    }

    public static function redirect($path)
    {
        $r = [
            'path' => $path,
        ];

        $r['responseType'] = __FUNCTION__;
        self::json($r);
    }

    public static function appendLinkRedirect($link)
    {
        self::$link_redirect = $link;
    }

    public static function appendErrorIfDoesntExist($message, $nameInput = '')
    {
        if (!self::hasError())
            self::appendError($message, $nameInput);
    }

    public static function appendChart(array $dataChart = [])
    {
        self::$chart[] = $dataChart;
    }

    public static function appendError($message, $nameInput = '')
    {
        sys_log(L_WARN, $message, true);
        self::$errors[] = [
            'nameInput' => $nameInput,
            'message' => $message
        ];
    }

    public static function appendMessage($message, $nameInput = '', $status = false)
    {
        if ($status)
            self::setStatus($status);

        self::$message[] = [
            "nameInput" => $nameInput,
            "message" => $message
        ];
    }

    public static function appendData(array $dataAppend = [])
    {
        self::$data = $dataAppend;
    }

    public static function hasLinkRedirect()
    {
        if (empty(self::$link_redirect))
            return false;

        return true;
    }

    public static function hasError()
    {
        if (empty(self::$errors))
            return false;

        return true;
    }

    public static function hasChart()
    {
        if (empty(self::$chart))
            return false;

        return true;
    }

    public static function hasMessage()
    {
        if (empty(self::$message))
            return false;

        return true;
    }

    public static function responseErrorIfExist($title)
    {
        if (!self::hasError())
            return false;

        self::$title = $title;
        $r = self::setHeaderResponse(self::ERROR);

        $r['message'] = self::$errors;

        $r['data'] = self::$data;

        $r['responseType'] = __FUNCTION__;
        self::json($r);
    }

    public static function disableHistory($value = true)
    {
        self::$history = !$value;
    }

    public static function appendModal($options)
    {
        self::$modal = [
            'modal' => 'true',
            'size' => $options['size'] ?? 'modal-xl',
            'action' => $options['action'],
            'redirect' => $options['redirect'],
            'div' => $options['div'],
            'title' => $options['title']
        ];
    }

    public static function hasModal()
    {
        if (empty(self::$modal))
            return false;

        return true;
    }

    public static function responseHTML($HTML)
    {
        // If responseHTML, status must be 1
        $r = self::setHeaderResponse(self::SUCCESS);

        $r["html"] = $HTML;

        if (self::hasChart())
            $r["chart"] = self::$chart;

        if (self::hasMessage())
            $r["message"] = self::$message;

        if (self::hasModal())
            $r["modal"] = self::$modal;

        if (self::hasLinkRedirect())
            $r["link_redirect"] = self::$link_redirect;

        $r["data"] = self::$data;
        $r["type_view_message"] = self::$type_view_message;

        $r['responseType'] = __FUNCTION__;
        self::json($r);
    }

    public static function setTypeViewMessage()
    {
        self::$type_view_message = "modal";
    }

    public static function setTitle($title)
    {
        self::$title = $title;
    }

    public static function setStatus($status)
    {
        self::$status = $status;
    }

    public static function setTimeOutToast(int $value)
    {
        self::$time_toast_remove = $value;
    }

    private static function setHeaderResponse($status_cod)
    {
        if (!empty(self::$status))
            $status_cod = self::$status;

        if ($status_cod == self::ERROR && empty(self::$title))
            $title = "Operação não foi concluida.";

        if ($status_cod == self::SUCCESS && empty(self::$title))
            $title = "Operação concluida com sucesso.";

        if ($status_cod == self::WARNING && empty(self::$title))
            $title = "Operação concluida com observação.";

        if (!empty(self::$title))
            $title = self::$title;

        $r = [
            'title' => $title,
            'dateHour' => DateTimeHelper::currentDateTime(),
            'status' => $status_cod,
            'history' => self::$history,
            'time_alert' => self::$time_toast_remove,
        ];

        return $r;
    }
}
