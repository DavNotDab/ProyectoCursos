<?php
namespace Controllers;

use Lib\Pages;
use Models\Usuario;

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

    public function logout(): void
    {
        (new Usuario())->logout();
    }

    public function confirmarCuenta($token): void {
        (new ApiUsuarioController())->confirmarCuenta($token);
    }

    public function verPerfil(): void
    {
        session_start();
        if (isset($_SESSION['user'])) {
            $this->pages->render('usuario/perfil', ['title' => 'Perfil']);
        }
        else {
            header('Location: http://localhost/ProyectoCursos/public/usuarios/login');
        }
    }

}