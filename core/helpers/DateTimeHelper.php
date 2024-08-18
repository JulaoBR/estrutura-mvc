<?php 

namespace core\helpers;

class DateTimeHelper
{
    public static function currentDateTime($format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

    public static function currentTime()
    {
        return date('H:i:s');
    }
}