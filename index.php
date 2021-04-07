<?php
session_start();

function has_error($key)
{
    $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    return isset($errors[$key]) ? true : false;
}

function show_error($key)
{
    $errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    return isset($errors[$key]) ? $errors[$key] : '';
}

function old_data($key)
{
    $data = isset($_SESSION['data']) ? $_SESSION['data'] : [];
    return isset($data[$key]) ? $data[$key] : '';
}



$path = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : '';
$position = strpos($path, '?');

if ($position) {
    $path = substr($path, 0, $position);
}

$url = explode('/', $path);

define('BASE_URL', '/invoice/');

require_once  'controller/CustomerController.php';
require_once  'controller/InvoiceController.php';

if ($url[2] != '') {
    if ($url[2] == 'customers') {
        $controller = new CustomerController();
        if (isset($url[3])) {
            if (isset($url[4])) {
                $controller->{$url[3]}($url[4]);
            } else {
                $controller->{$url[3]}();
            }
        } else {
            $controller->index();
        }
    } else {
        $controller = new InvoiceController();
        $controller->{$url[2]}();
    }
} else {
    $controller = new InvoiceController();
    $controller->mvcHandler();
}
