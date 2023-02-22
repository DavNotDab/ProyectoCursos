<?php
namespace Controllers;

class TallerController
{
    public function getAll(): void {
        (new ApiTallerController())->getAll();
    }

    public function inscribir(): void {
        (new ApiTallerController())->inscribir();
    }

}