<?php
namespace Controllers;

use Lib\Pages;

class UsuarioController
{
    private Pages $pages;

    public function __construct() {
        $this->pages = new Pages();
    }

    public function index(): void {
        $this->pages->render('index', ['title' => 'Bienvenido']);
    }

    public function getAll(): void {
        (new ApiUsuarioController())->getAll();
    }

    public function getUser($id): void {
        (new ApiUsuarioController())->getUser($id);
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->pages->render('usuario/register', ['title' => 'Registro']);
        }
        else {
            (new ApiUsuarioController())->register();
        }
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->pages->render('usuario/login', ['title' => 'Login']);
        }
        else {
            (new ApiUsuarioController())->login();
        }
    }

    public function confirmarCuenta($token): void {
        (new ApiUsuarioController())->confirmarCuenta($token);
    }

}