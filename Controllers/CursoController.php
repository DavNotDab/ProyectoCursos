<?php
namespace Controllers;

use Lib\Pages;

class CursoController
{
    private Pages $pages;

    public function __construct() {
        $this->pages = new Pages();
    }

    public function index(): void {
        $this->pages->render('index');
    }

    public function getAll(): void {
        (new ApiCursoController())->getAll();
    }

    public function inscribir(): void {
        session_start();
        if (isset($_SESSION['user'])) {
            (new ApiCursoController())->inscribir();
        }
        else {
            $this->pages->render('usuarios/login');
        }
    }

}