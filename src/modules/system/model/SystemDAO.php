<?php

namespace src\modules\system\model;

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