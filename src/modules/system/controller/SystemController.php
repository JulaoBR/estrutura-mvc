<?php

namespace src\modules\system\controller;

use core\Controller;
use src\modules\system\model\SystemDAO;

class SystemController extends Controller
{
    public function index()
    {
        $dao = new SystemDAO();

        $data = [
            'list' => $dao->getData()
        ];

        $this->loadView('system/view/system', $data);
    }
}