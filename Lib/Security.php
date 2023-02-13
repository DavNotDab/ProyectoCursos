<?php
namespace Lib;

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Models\Usuario;
use PDOException;

class Security {

    final public static function claveSecreta(): string
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->load();
        return $_ENV['SECRET_KEY'];
    }

    final public static function encrypt(string $clave): string
    {
        return password_hash($clave, PASSWORD_BCRYPT);
    }

    final public static function validPass(string $clave, string $hash): bool
    {
        if (password_verify($clave, $hash)) {
            return true;
        } else {
            echo "Contraseña incorrecta";
            return false;
        }
    }

    final public static function crearToken(string $key, array $data): array
    {
        $time = strtotime("now");
        return array(
            "iat" => $time,
            "exp" => $time + 3600,
            "data" => $data
        );
    }

    /*final public static function validarToken(array $token, string $key): bool
    {
        try {
            $decoded = JWT::decode($token, $key, array('HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }*/

    final public static function getToken()
    {
        $headers = apache_request_headers();// recoger las cabeceras en el servidor Apache
        if (!isset($headers['Authorization'])) { // comprobamos que existe la cabecera authoritation
            return $response['message'] = json_decode(ResponseHttp::statusMessage(403, 'Acceso denegado'));
        }
        try {
            $authorizationArr = explode(' ', $headers['Authorization']);
            $token = $authorizationArr[1];
            $decodeToken = JWT::decode($token, new Key(Security::clavesecreta(), 'HS256'));
            return $decodeToken;
        } catch (PDOException $exception) {
            return $response['message'] = json_encode(ResponseHttp::statusMessage(401, 'Token expirado o invalido'));
        }
    }

    final public static function validateToken(): bool|array
    {
        $info = self::getToken();

        return $info->data ?? false;



        // En $info->data tenemos el id del usuario
        //podemos acceder a la base de datos y comprobar que existe un usuario con ese identificador en nuestra BD
     // también podemos verificar si coinciden las fechas de expiración de token.
     //Podemos devolver verdadero o falso
}

}