<?php
namespace Controllers;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Lib\Email;
use Lib\ResponseHttp;
use Lib\Security;
use Models\Usuario;

// Controlador de la api para los usuarios.
// Devuelve los datos de los usuarios en formato JSON.
class ApiUsuarioController
{
    private Usuario $usuario;

    public function __construct()
    {
        $this->usuario = new Usuario();
    }

    // Devuelve todos los usuarios.
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

    // Devuelve los datos de un usuario dada su id.
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

    // Registra un usuario en la base de datos.
    // Recoge los datos desde una petiocion POST.
    // También envía un correo de confirmación al usuario.
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario = new Usuario();
            $data = json_decode(file_get_contents("php://input"));
            if (!$data->terminos) {
                echo ResponseHttp::statusMessage(400, "Debe aceptar los términos y condiciones");
                return;
            }
            $valido = $usuario->validarData($data);

            if ($valido === true) {
                if ($usuario->getUserByEmail($data->email) !== false) {
                    echo ResponseHttp::statusMessage(400, "El email ya está registrado. Inicie sesión");
                    return;
                }
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
                echo ResponseHttp::statusMessage(400, $valido);
            }
        } else {
            echo ResponseHttp::statusMessage(405, "Método no permitido. Use POST");
        }
    }

    // Inicia sesión en la aplicación.
    // Recoge los datos desde una petición POST.
    // Además, crea un token JWT y lo devuelve en la respuesta.
    // También crea una sesión de usuario con sus datos.
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

    // Crea un token JWT y lo devuelve en la respuesta.
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

    // Confirma la cuenta de un usuario.
    // Recibe el token JWT por parámetro.
    // Si el token es válido, confirma la cuenta y devuelve un mensaje de confirmación.
    public function confirmarCuenta($token): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $key = Security::claveSecreta();
            $usuario = new Usuario();
            try {
                $decoded = JWT::decode($token, new Key($key, 'HS256'));
                $usuario->setEmail($decoded->data[0]);
                if ($usuario->checkToken($token) === false) {
                    echo ResponseHttp::statusMessage(401, "Token inválido");
                }
                $usuario->setConfirmado(true);
                $usuario->confirmarCuenta();
                echo json_encode([$usuario, ResponseHttp::statusMessage(200, "Cuenta confirmada correctamente. Ya puede iniciar sesión")]);
                header("Refresh:2; url=".$_ENV["BASE_URL"]."usuarios/login");
            }
            catch (Exception) {
                echo ResponseHttp::statusMessage(401, "Token inválido o expirado. Regístrese de nuevo.");
                $email = JWT::decode($token, new Key($key, 'HS256'), true)->data[0];
                if ($usuario->getUserByEmail($email) !== false) {
                    $usuario->deleteUser($email);
                }
                header("Refresh:2; url=".$_ENV["BASE_URL"]."usuarios/register");
            }
        }
        else {
            echo ResponseHttp::statusMessage(405, "Método no permitido. Use GET");
        }
    }

}