<?php

namespace src\modules\login\model;

use core\Model;

class UsuarioDAO extends Model
{
    public function getData()
    {
        return [
            'id' => 1,
            'login' => 'teste',
            'nome' => 'USER TESTE'
        ];
    }
}