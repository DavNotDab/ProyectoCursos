<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Lib\ResponseHttp;
use Lib\Security;
use Models\Ponente;
use Models\Usuario;
use Lib\Pages;

class ApiPonenteController
{
    private Ponente $ponente;
    private Usuario $usuario;
    private Pages $pages;

    public function __construct()
    {
        $this->ponente = new Ponente();
        $this->usuario = new Usuario();
        $this->pages = new Pages();
    }

    public function getAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $tokenData = Security::validateToken();
            if (gettype($tokenData) == "object") {
                $response = $tokenData->message;
            }
            else if ($tokenData !== false && $this->usuario->getUserByEmail($tokenData[0]) !== false) {

                $ponentes = $this->ponente->getAll();
                $ponenteArr = [];

                if (!empty($ponentes)) {
                    $ponenteArr["message"] = json_decode(ResponseHttp::statusMessage(202, "OK"));
                    $ponenteArr["ponentes"] = [];
                    foreach ($ponentes as $ponente) {
                        $ponenteArr["ponentes"][] = $ponente;
                    }
                }
                else {
                    $ponenteArr["message"] = json_decode(ResponseHttp::statusMessage(404, "No se encontraron ponentes"));
                    $ponenteArr["ponentes"] = [];
                }

                if ($ponenteArr == []) {
                    $response = json_encode($ponenteArr["message"]);
                }
                else {
                    $response = json_encode($ponenteArr);
                }
            }
            else {
                $response = json_decode(ResponseHttp::statusMessage(401, "No autorizado"))->message;
            }
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido, use GET"));
        }

        $this->pages->render('read', ['response' => $response]);
    }

    public function getPonente($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $ponente = $this->ponente->getPonente($id);

            if ($ponente !== false) {
                $response["message"] = json_decode(ResponseHttp::statusMessage(202, "OK"));
                $response["ponente"] = $ponente;
            }
            else {
                $response["message"] = json_decode(ResponseHttp::statusMessage(404, "No se encontró el ponente"));
            }
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido, use GET"));
        }

        $this->pages->render('read', ['response' => json_encode($response)]);
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