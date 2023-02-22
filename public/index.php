<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Controllers\CursoController;
use Controllers\TallerController;
use Controllers\UsuarioController;
use Controllers\PonenteController;
use Dotenv\Dotenv;
use Lib\Router;

$dotenv = Dotenv::createImmutable(__DIR__); // Accede al archivo .env
$dotenv->safeLoad();

Router::add('GET', '/', function () {
    (new CursoController())->index();
});

Router::add('GET', 'ponentes', function () {
    (new PonenteController())->getAll();
});

Router::add('GET', 'ponente/:id', function ($id) {
    (new PonenteController())->getPonente($id);
});

Router::add('DELETE', 'ponente/:id', function ($id) {
    (new PonenteController())->deletePonente($id);
});

Router::add('POST', 'ponente/new', function () {
    (new PonenteController())->newPonente();
});

Router::add('PUT', 'ponente/update/:id', function ($id) {
    (new PonenteController())->updatePonente($id);
});

Router::add('POST', 'usuarios/register', function () {
    (new UsuarioController())->register();
});

Router::add('GET', 'usuarios/register', function () {
    (new UsuarioController())->register();
});

Router::add('POST', 'usuarios/login', function () {
    (new UsuarioController())->login();
});

Router::add('GET', 'usuarios/login', function () {
    (new UsuarioController())->login();
});

Router::add('GET', 'confirmarCuenta/:id', function ($token) {
    (new UsuarioController())->confirmarCuenta($token);
});

Router::add('GET', 'cursos', function () {
    (new CursoController())->getAll();
});

Router::add('GET', 'talleres', function () {
    (new TallerController())->getAll();
});

Router::add('POST', 'curso/inscibir', function () {
    (new CursoController())->inscribir();
});

Router::add('POST', 'taller/inscibir', function () {
    (new TallerController())->inscribir();
});

Router::dispatch();