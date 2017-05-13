<?php

use \App\Middleware\Auth;

$app->get('/', 'App\Controllers\Home:getHome')->setName('home');
$app->get('/login', 'App\Controllers\Home:getLogin')->setName('home');
$app->get('/logout', 'App\Controllers\Home:getLogout')->setName('logout');
$app->post('/login', 'App\Controllers\Home:postLogin')->setName('login');

$app->group('', function () {

    $this->get('/admin', 'App\Controllers\Home:getAdmin')->setName('admin');
    $this->post('/admin/adduser', 'App\Controllers\Home:postAddUser')->setName('adduser');

})->add(new Auth($container)); // adding auth middleware to all routes inside this group
