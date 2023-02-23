<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Lib\ResponseHttp;
use Lib\Security;
use Models\Taller;
use Models\Usuario;

// Controlador de la api para los talleres.
// Devuelve los datos de los talleres en formato JSON.
class ApiTallerController
{
    private Taller $taller;
    private Usuario $usuario;

    public function __construct()
    {
        $this->taller = new Taller();
        $this->usuario = new Usuario();
    }

    // Devuelve todos los talleres.
    public function getAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $talleres = $this->taller->getAll();

            if (!empty($talleres)) {
                echo json_encode($talleres);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontraron talleres");
            }

        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }
    }

    // Devuelve los datos de un taller dado su id.
    public function getTaller($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $talleres = $this->taller->getTaller($id);

            if ($talleres !== false) {
                echo json_encode($talleres);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontró el taller");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }

    }

    // Borra un taller dado su id.
    public function deleteTaller($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $talleres = $this->taller->getTaller($id);

            if ($talleres !== false) {
                $this->taller->deleteTaller($id);
                echo ResponseHttp::statusMessage(202, "Taller borrado correctamente");
            }
            else {
                echo ResponseHttp::statusMessage(404, "Error. No se encontró el taller");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use DELETE");
        }
    }

    // Inscribe a un usuario en un taller.
    // Recibe el id del taller y el id del usuario por JSON.
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
                    $taller = $this->taller->getTaller($data->id_curso);

                    if ($taller !== false) {
                        $this->taller->inscribir($data->id_curso, $data->id_usuario);
                        echo ResponseHttp::statusMessage(201, "Inscripción realizada correctamente");
                    }
                    else {
                        echo ResponseHttp::statusMessage(404, "Error. No se encontró el taller");
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