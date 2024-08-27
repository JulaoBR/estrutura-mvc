<?php

namespace src\modules\system\model;

use core\Entity;

class SystemEntity extends Entity
{
    const db_table = '';
    const pk_field = '';

    private $key;

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }
}