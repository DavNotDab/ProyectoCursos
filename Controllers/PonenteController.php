<?php
namespace Controllers;

use Lib\Pages;

class PonenteController
{
    private Pages $pages;

    public function __construct() {
        $this->pages = new Pages();
    }

    public function index(): void {
        $this->pages->render('index', ['title' => 'Cursos']);
    }

    public function getAll(): void {
        (new ApiPonenteController())->getAll();
    }

    public function getPonente($id): void {
        (new ApiPonenteController())->getPonente($id);
    }

    public function newPonente(): void {
        (new ApiPonenteController())->newPonente();
    }

    public function updatePonente($id): void {
        (new ApiPonenteController())->updatePonente($id);
    }

    public function deletePonente($id): void {
        (new ApiPonenteController())->deletePonente($id);
    }


}