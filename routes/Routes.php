<?php

use core\Router;

$router = new Router();

$router->get('/login', 'login@LoginController@login');
$router->get('/', 'system@SystemController@index');