<?php
namespace Controllers;

use Lib\Pages;
use Utils\Utils;

class CursoController
{
    private Pages $pages;

    public function __construct() {
        $this->pages = new Pages();

    }

    public function index(): void {
        $this->pages->render('index', ['title' => 'Cursos']);
    }

    public function getAll(): void {
        (new ApiCursoController())->getAll();
    }

    public function inscribir(): void {
        (new ApiCursoController())->inscribir();
    }

}