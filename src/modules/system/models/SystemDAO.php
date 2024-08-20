<?php

namespace src\modules\system\models;

use core\Model;

class SystemDAO extends Model
{
    public function getData()
    {
        return [
            0 => 'Teste 1',
            1 => 'Teste 2'
        ];
    }
}