<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Lib\ResponseHttp;
use Lib\Security;
use Models\Curso;
use Models\Usuario;

class ApiCursoController
{
    private Curso $curso;
    private Usuario $usuario;

    public function __construct()
    {
        $this->curso = new Curso();
        $this->usuario = new Usuario();
    }

    public function getAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $cursos = $this->curso->getAll();

            if (!empty($cursos)) {
                echo json_encode($cursos);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontraron cursos");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }
    }

    public function getCurso($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $curso = $this->curso->getCurso($id);

            if ($curso !== false) {
                echo json_encode($curso);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontró el curso");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }

    }

    public function deleteCurso($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $curso = $this->curso->getCurso($id);

            if ($curso !== false) {
                $this->curso->deleteCurso($id);
                echo ResponseHttp::statusMessage(202, "Curso borrado correctamente");
            }
            else {
                echo ResponseHttp::statusMessage(404, "Error. No se encontró el curso");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use DELETE");
        }
    }

    public function inscribir(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tokenData = Security::validateToken();
            if (gettype($tokenData) == "object") {
                echo $tokenData->message;
            }
            else if ($tokenData !== false && $this->usuario->getUserByEmail($tokenData[0]) !== false) {

                $data = json_decode(file_get_contents("php://input"));

                if (!empty($data->id_curso) && !empty($data->id_usuario)) {
                    $curso = $this->curso->getCurso($data->id_curso);

                    if ($curso !== false) {
                        $this->curso->inscribir($data->id_curso, $data->id_usuario);
                        echo ResponseHttp::statusMessage(201, "Inscripción realizada correctamente");
                    }
                    else {
                        echo ResponseHttp::statusMessage(404, "Error. No se encontró el curso");
                    }
                }
                else {
                    echo ResponseHttp::statusMessage(400, "Error. Datos incompletos");
                }
            }
            else {
                echo ResponseHttp::statusMessage(401, "Error. No autorizado");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use POST");
        }
    }


}