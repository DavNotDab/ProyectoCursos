<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Lib\Email;
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
                        $token = $this->createToken($usuario, $data->email);
                        $email = new Email($data->email, $token);
                        $email->sendConfirmation();

                        http_response_code(201);
                        $response = json_decode(ResponseHttp::statusMessage(201, "Usuario creado correctamente. Confirme su email para poder iniciar sesión"));
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

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            $data = json_decode(file_get_contents("php://input"));
            $valido = $usuario->validarLogin($data);

            if ($valido === true) {
                $datos = $usuario->login($data->email);
                if ($datos !== false && $datos["confirmado"] == 1) {
                    $hash = $datos["password"];
                    if (password_verify($data->password, $hash)) {
                        $this->createToken($usuario, $data->email);
                        http_response_code(200);
                        $user = $usuario->getUser($datos["id"]);
                        unset($user["password"]);
                        echo json_encode($user);
                    }
                    else {
                        echo ResponseHttp::statusMessage(401, "Contraseña incorrecta");
                    }
                }
                else {
                    echo ResponseHttp::statusMessage(400, "El usuario no existe o no está confirmado");
                }

            }
            else {
                echo ResponseHttp::statusMessage(400, "ERROR. $valido");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido. Use POST");
        }
    }

    private function createToken($usuario, $email): string
    {
        $key = Security::claveSecreta();
        $token = Security::crearToken($key, [$email]);
        $encodedToken = JWT::encode($token, $key, 'HS256');
        $usuario->setToken($encodedToken);
        $usuario->setEmail($email);
        $usuario->updateToken($token["exp"]);
            return $encodedToken;
    }

    public function confirmarCuenta($token): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $key = Security::claveSecreta();
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $usuario = new Usuario();
            $usuario->setEmail($decoded->data[0]);
            if ($usuario->checkToken($token) === false) {
                $response = json_decode(ResponseHttp::statusMessage(401, "Token inválido"));
                $this->pages->render('read', ['response' => json_encode($response)]);
                return;
            }
            $usuario->setConfirmado(true);
            $usuario->confirmarCuenta();
            $this->pages->render('confirmarCuenta');
        }
        else {
            $response = json_decode(ResponseHttp::statusMessage(405, "Método no permitido. Use GET"));
            $this->pages->render('read', ['response' => json_encode($response)]);
        }
    }

}