<?php

use core\Router;

$router = new Router();

$router->get('/login', 'login@LoginController@login', Router::WITHOUT_AUTH);
$router->post('/logar', 'login@LoginController@logar', Router::WITHOUT_AUTH);

$router->get('/', 'system@SystemController@index', Router::AUTH);