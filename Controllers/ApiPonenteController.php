<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Lib\ResponseHttp;
use Models\Ponente;
use Lib\Pages;

class ApiPonenteController
{
    private Ponente $ponente;
    private Pages $pages;

    public function __construct()
    {
        $this->ponente = new Ponente();
        $this->pages = new Pages();
    }

    public function getAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $ponentes = $this->ponente->getAll();

            if (!empty($ponentes)) {
                echo json_encode($ponentes);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontraron ponentes");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }
    }

    public function getPonente($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $ponente = $this->ponente->getPonente($id);

            if ($ponente !== false) {
                echo json_encode($ponente);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontró el ponente");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }

    }

    public function deletePonente($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $ponente = $this->ponente->getPonente($id);

            if ($ponente !== false) {
                $this->ponente->deletePonente($id);
                $response = json_decode(ResponseHttp::statusMessage(202, "Ponente borrado correctamente"));
            }
            else {
                $response = json_decode(ResponseHttp::statusMessage(404, "Error. No se encontró el ponente"));
            }
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido, use DELETE"));
        }

        $this->pages->render('read', ['response' => json_encode($response)]);
    }

    public function newPonente(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ponente = new Ponente();
            $data = json_decode(file_get_contents("php://input"));
            $valido = $ponente->validarData($data);

            if ($valido === true) {
                $ponente->setNombre($data->nombre);
                $ponente->setApellidos($data->apellidos);
                $ponente->setImagen($data->imagen);
                $ponente->setTags($data->tags);
                $ponente->setRedes($data->redes);

                if ($ponente->insertPonente()) {
                    http_response_code(201);
                    $response = json_decode(ResponseHttp::statusMessage(201, "Ponente creado correctamente"));
                }
                else {
                    http_response_code(503);
                    $response = json_decode(ResponseHttp::statusMessage(503, "Error al crear el ponente"));
                }
            }
            else {
                http_response_code(400);
                $response = json_decode(ResponseHttp::statusMessage(400, "ERROR. $valido"));
            }
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido. Use POST"));
        }

        $this->pages->render('read', ['response' => json_encode($response)]);
    }

    public function updatePonente($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $dataPonente = $this->ponente->getPonente($id);

            if ($dataPonente !== false) {
                $ponente = Ponente::fromArray($dataPonente);
                $data = json_decode(file_get_contents("php://input"));
                $valido = $ponente->validarData($data);

                if ($valido === true) {
                    $ponente->setNombre($data->nombre);
                    $ponente->setApellidos($data->apellidos);
                    $ponente->setImagen($data->imagen);
                    $ponente->setTags($data->tags);
                    $ponente->setRedes($data->redes);

                    if ($ponente->updatePonente($id)) {
                        http_response_code(201);
                        $response = json_decode(ResponseHttp::statusMessage(201, "Ponente actualizado correctamente"));
                    }
                    else {
                        http_response_code(503);
                        $response = json_decode(ResponseHttp::statusMessage(503, "Error al actualizar el ponente"));
                    }
                }
                else {
                    http_response_code(400);
                    $response = json_decode(ResponseHttp::statusMessage(400, "ERROR. $valido"));
                }
            }
            else {
                http_response_code(404);
                $response = json_decode(ResponseHttp::statusMessage(404, "Error. No se encontró el ponente"));
            }
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido. Use PUT"));
        }

        $this->pages->render('read', ['response' => json_encode($response)]);
    }

}