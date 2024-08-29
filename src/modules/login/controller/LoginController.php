<?php

namespace src\modules\login\controller;

use core\Controller;

class LoginController extends Controller
{
    public function login()
    {
        $data = [];

        $this->loadView('login/view/login', $data);
    }

}