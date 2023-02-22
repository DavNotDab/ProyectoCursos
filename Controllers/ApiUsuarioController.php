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

class ApiUsuarioController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    public function getAll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $usuarios = $this->usuario->getAll();

            if (!empty($usuarios)) {
                echo json_encode($usuarios);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontraron usuarios");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }
    }

    public function getUser($id): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $usuario = $this->usuario->getUser($id);

            if ($usuario !== false) {
                echo json_encode($usuario);
            }
            else {
                echo ResponseHttp::statusMessage(404, "No se encontró el usuario");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido, use GET");
        }
    }

    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            $data = json_decode(file_get_contents("php://input"));
            if (!$data->terminos) {
                echo ResponseHttp::statusMessage(400, "Debe aceptar los términos y condiciones");
                return;
            }
            if ($usuario->getUserByEmail($data->email) !== false) {
                echo ResponseHttp::statusMessage(400, "El email ya está registrado. Inicie sesión");
                return;
            }
            $valido = $usuario->validarData($data);
            if ($valido === true) {
                $usuario->setNombre($data->nombre);
                $usuario->setApellidos($data->apellidos);
                $usuario->setEmail($data->email);
                $usuario->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
                $usuario->setRol("usuario");
                $usuario->setConfirmado(false);

                $correcto = $usuario->register();
                if ($correcto === true) {
                    $token = $this->createToken($usuario, $data->email);
                    $email = new Email($data->email, $token);
                    $email->sendConfirmation();
                    echo ResponseHttp::statusMessage(201, "Usuario creado correctamente. Confirme su email para poder iniciar sesión");
                } else {
                    echo ResponseHttp::statusMessage(503, "Error: $correcto");
                }
            } else {
                echo ResponseHttp::statusMessage(400, "ERROR. $valido");
            }
        } else {
            echo ResponseHttp::statusMessage(405, "Método no permitido. Use POST");
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
                        $user = $usuario->getUser($datos["id"]);
                        unset($user["password"]);
                        $usuario->createSession($user);
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
                echo ResponseHttp::statusMessage(401, "Token inválido");
            }
            $usuario->setConfirmado(true);
            $usuario->confirmarCuenta();
            echo json_encode([$usuario, ResponseHttp::statusMessage(200, "Cuenta confirmada correctamente")]);
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido. Use GET");
        }
    }

}