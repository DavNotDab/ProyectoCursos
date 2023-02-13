<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Firebase\JWT\JWT;
use Lib\ResponseHttp;
use Lib\Security;
use Models\Usuario;
use Lib\Pages;

class ApiUsuarioController
{
    private Usuario $usuario;
    private Pages $pages;

    public function __construct()
    {
        $this->usuario = new Usuario();
        $this->pages = new Pages();
    }

    public function getAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $usuarios = $this->usuario->getAll();
            $usuarioArr = [];

            if (!empty($usuarios)) {
                $usuarioArr["message"] = json_decode(ResponseHttp::statusMessage(202, "OK"));
                $usuarioArr["usuarios"] = [];
                foreach ($usuarios as $usuario) {
                    $usuarioArr["usuarios"][] = $usuario;
                }
            }
            else {
                $usuarioArr["message"] = json_decode(ResponseHttp::statusMessage(404, "No se encontraron usuarios"));
                $usuarioArr["usuarios"] = [];
            }

            if ($usuarioArr == []) {
                $response = json_encode($usuarioArr["message"]);
            }
            else {
                $response = json_encode($usuarioArr);
            }

        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido, use GET"));
        }
        $this->pages->render('read', ['response' => $response]);
    }

    public function getUser($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $usuario = $this->usuario->getUser($id);

            if ($usuario !== false) {
                $response["message"] = json_decode(ResponseHttp::statusMessage(202, "OK"));
                $response["usuario"] = $usuario;
            }
            else {
                $response["message"] = json_decode(ResponseHttp::statusMessage(404, "No se encontró el usuario"));
            }
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido, use GET"));
        }

        $this->pages->render('read', ['response' => json_encode($response)]);
    }

    public function register(): void
    {
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $usuario = new Usuario();
                $data = json_decode(file_get_contents("php://input"));
                $valido = $usuario->validarData($data);

                if ($valido === true) {
                    $usuario->setNombre($data->nombre);
                    $usuario->setApellidos($data->apellidos);
                    $usuario->setEmail($data->email);
                    $usuario->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
                    $usuario->setRol("usuario");
                    $usuario->setConfirmado(false);

                    if ($usuario->register()) {
                        http_response_code(201);
                        $response = json_decode(ResponseHttp::statusMessage(201, "Usuario creado correctamente"));
                    } else {
                        http_response_code(503);
                        $response = json_decode(ResponseHttp::statusMessage(503, "Error al crear el usuario"));
                    }
                } else {
                    http_response_code(400);
                    $response = json_decode(ResponseHttp::statusMessage(400, "ERROR. $valido"));
                }
            } else {
                $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido. Use POST"));
            }

            $this->pages->render('read', ['response' => json_encode($response)]);
        }
    }

    public function login(): ?string
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            $data = json_decode(file_get_contents("php://input"));
            $valido = $usuario->validarLogin($data);

            if ($valido === true) {
                $datos = $usuario->login($data->email);
                $hash = $datos["password"];
                if ($hash !== false) {
                    if (password_verify($data->password, $hash)) {
                        $key = Security::claveSecreta();
                        $token = Security::crearToken($key, [$data->email]);
                        $encodedToken = JWT::encode($token, $key, 'HS256');
                        $usuario->setToken($encodedToken);
                        $usuario->setEmail($data->email);
                        $usuario->updateToken($token["exp"]);
                        http_response_code(200);
                        $response["message"] = json_decode(ResponseHttp::statusMessage(200, "OK"));
                        $user = $usuario->getUser($datos["id"]);
                        unset($user["password"]);
                        $resultado = ["response" => $response, "user" => $user];
                        $this->pages->render('read', ['response' => json_encode($resultado)]);
                        return null;
                    }
                    else {
                        http_response_code(401);
                        $response = json_decode(ResponseHttp::statusMessage(401, "Contraseña incorrecta"));
                    }
                }
                else {
                    http_response_code(401);
                    $response = json_decode(ResponseHttp::statusMessage(400, "El usuario no existe"));
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
        return null;
    }

}