<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\ApiPonenteController;
use Controllers\ApiUsuarioController;
use Dotenv\Dotenv;
use Lib\Router;
use Lib\Pages;

$dotenv = Dotenv::createImmutable(__DIR__); // Accede al archivo .env
$dotenv->safeLoad();

Router::add('GET', '/', function () {
    (new Pages())->render('index.php');
});

Router::add('GET', 'ponente', function () {
    (new ApiPonenteController())->getAll();
});

Router::add('GET', 'ponente/:id', function ($id) {
    (new ApiPonenteController())->getPonente($id);
});

Router::add('DELETE', 'ponente/:id', function ($id) {
    (new ApiPonenteController())->deletePonente($id);
});

Router::add('GET', 'ponente/new', function () {
    (new ApiPonenteController())->newPonente();
});

Router::add('PUT', 'ponente/update/:id', function ($id) {
    (new ApiPonenteController())->updatePonente($id);
});

Router::add('GET', 'auth', function () {require "../views/auth.php";}); // Solo para pruebas

Router::add('POST', 'usuarios/register', function () {
    (new ApiUsuarioController())->register();
});

Router::add('POST', 'usuarios/login', function () {
    (new ApiUsuarioController())->login();
});

Router::add('GET', 'confirmarCuenta/:id', function ($token) {
    (new ApiUsuarioController())->confirmarCuenta($token);
});

Router::dispatch();