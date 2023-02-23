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

    // Devuelve todos los ponentes con una llamada a la api
    public function getAll(): void {
        (new ApiPonenteController())->getAll();
    }

    // Devuelve un ponente con una llamada a la api
    public function getPonente($id): void {
        if(isset($_SESSION['user']) && $_SESSION['user']['rol'] == 'admin')
            (new ApiPonenteController())->getPonente($id);
        else
            header('Location: '.$_ENV['BASE_URL']);
    }

    // Crea un nuevo ponente con una llamada a la api
    public function newPonente(): void {
        if(isset($_SESSION['user']) && $_SESSION['user']['rol'] == 'admin')
            (new ApiPonenteController())->newPonente();
        else
            header('Location: '.$_ENV['BASE_URL']);
    }

    // Actualiza un ponente con una llamada a la api
    public function updatePonente($id): void {
        if(isset($_SESSION['user']) && $_SESSION['user']['rol'] == 'admin')
            (new ApiPonenteController())->updatePonente($id);
        else
            header('Location: '.$_ENV['BASE_URL']);
    }

    // Borra un ponente con una llamada a la api
    public function deletePonente($id): void {
        if(isset($_SESSION['user']) && $_SESSION['user']['rol'] == 'admin')
            (new ApiPonenteController())->deletePonente($id);
        else
            header('Location: '.$_ENV['BASE_URL']);
    }


}