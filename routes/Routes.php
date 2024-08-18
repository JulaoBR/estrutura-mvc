<?php

use core\Router;

$router = new Router();

$router->get('/', 'system@SystemController@index');