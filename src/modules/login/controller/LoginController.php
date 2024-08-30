<?php

namespace src\modules\login\controller;

use core\Controller;
use core\helpers\LoginHelper;
use core\Request;
use src\modules\login\model\UsuarioDAO;

class LoginController extends Controller
{
    # [GET]
    public function login()
    {
        $data = [];

        $data['settings'] = [
            'url_logar' => Request::getBaseUrl() . '/logar'
        ];

        $this->loadView('login/view/login', $data);
    }

    # [POST]
    public function logar()
    {
        $login = $_POST['login'];
        $senha = $_POST['senha'];

        $usuario = (new UsuarioDAO())->getData(
            $login
        );

        sys_info($usuario);
    }

}