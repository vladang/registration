<?php

error_reporting(E_ALL);

session_start();

require_once 'model/db.php';
require_once 'model/user.php';
require_once 'model/orders.php';

$user = new user();
$view = (object)[];
$post = (object)$_POST;
$ajax = false;
$view->is_auth = $user->checkAuth();

switch ($_GET['r'] ?? false) {
    case 'auth':
        $view->include = 'form-auth';
        if (isset($post->login)) $ajax = $user->authorization($post);
        break;
    case 'reg':
        $view->include = 'form-reg';
        if (isset($post->login)) $ajax = $user->registration($post);
        break;
    case 'edit':
        if ($view->is_auth) {
            if (isset($post->email))
                $ajax = $user->editUser($post);
            else
                $view->data = $user->getUserInfo();
                $view->include = 'form-edit';
        } else {
            echo 'Ошибка, данный раздел доступен только зарегистрированным пользователям!';
        }
        break;
    case 'repeat-email':
        $orders = new orders();
        $view->include = 'table';
        $view->data = $orders->getRepeatEmail();
        break;
    case 'no-orders':
        $orders = new orders();
        $view->include = 'table';
        $view->data = $orders->getLoginNotOrders();
        break;
    case 'two-orders':
        $orders = new orders();
        $view->include = 'table';
        $view->data = $orders->getLoginMoreTwoOrders();
        break;
    case 'logout':
        $view->is_auth = false;
        $user->logout();
        break;
}

if ($ajax)
    echo $ajax;
else
    require_once 'view/main.php';