<?php
namespace Controllers;

use Utils\Utils;

class TallerController
{
    public function getAll(): void {
        (new ApiTallerController())->getAll();
    }

    public function inscribir(): void {
        (new ApiTallerController())->inscribir();
    }

}