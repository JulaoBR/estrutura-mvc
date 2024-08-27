<?php

namespace src\modules\login\controller;

use core\Controller;

class LoginController extends Controller
{
    public function login()
    {
        sys_info($_GET);
    }

}