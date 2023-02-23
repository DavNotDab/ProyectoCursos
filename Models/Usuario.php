<?php
namespace Models;

use Lib\BaseDatos;
use PDOException;
use MVC\Utils\Utils;

// Modelo de la tabla usuarios
// Accede a la base de datos para ver, modificar y borrar datos de los usuarios.
class Usuario
{
    private string $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private string $password;
    private string $rol;
    private bool $confirmado;
    private string $token;
    private BaseDatos $bd;

    public function __construct()
    {
        $this->bd = new BaseDatos();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        $this->rol = $rol;
    }

    public function getConfirmado(): bool
    {
        return $this->confirmado;
    }

    public function setConfirmado(bool $confirmado): void
    {
        $this->confirmado = $confirmado;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getAll(): array {
        $sql = "SELECT * FROM usuarios";
        $this->bd->consulta($sql);
        return $this->bd->extraer_todos();
    }

    public function getUser($id): array|false {
        $sql = "SELECT * FROM usuarios WHERE id = $id";
        $this->bd->consulta($sql);
        return $this->bd->extraer_registro();
    }

    public function getUserByEmail($email): array|false {
        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $this->bd->consulta($sql);
        return $this->bd->extraer_registro();
    }

    public function register(): bool|string {
        try {
            $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, rol, confirmado) VALUES ('$this->nombre', '$this->apellidos', '$this->email', '$this->password', '$this->rol', '$this->confirmado')";
            $this->bd->consulta($sql);
            return $this->bd->filasAfectadas() > 0;

        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function login($email): bool|array {
        $sql = "SELECT id, password, confirmado FROM usuarios WHERE email = '$email'";
        $this->bd->consulta($sql);
        if ($this->bd->filasAfectadas() > 0) {
            return $this->bd->extraer_todos()[0];
        }
        return false;
    }

    public function deleteUser($email): bool {
        $user = $this->getUserByEmail($email);
        if ($user["confirmado"] == 1) {
            $sql = "DELETE FROM usuarios WHERE email = '$email'";
            $this->bd->consulta($sql);
            return $this->bd->filasAfectadas() > 0;
        }
        return false;
    }

    public function updateToken($exp): bool
    {
        $sql = "UPDATE usuarios SET token = '$this->token' WHERE email = '$this->email'";
        $this->bd->consulta($sql);
        $sql = "UPDATE usuarios SET token_exp = '$exp' WHERE email = '$this->email'";
        $this->bd->consulta($sql);
        return $this->bd->filasAfectadas() > 0;
    }

    public function confirmarCuenta(): bool
    {
        $sql = "UPDATE usuarios SET confirmado = 1 WHERE email = '$this->email'";
        $this->bd->consulta($sql);
        return $this->bd->filasAfectadas() > 0;
    }

    public function getDatabaseToken(): array
    {
        $sql = "SELECT token FROM usuarios WHERE email = '$this->email'";
        $this->bd->consulta($sql);
        return $this->bd->extraer_registro();
    }

    public function getDatabaseTokenExp(): bool
    {
        $sql = "SELECT token_exp FROM usuarios WHERE email = '$this->email'";
        $this->bd->consulta($sql);
        return $this->bd->extraer_todos()[0];
    }

    public function checkToken($token): bool
    {
        return $this->getDatabaseToken()["token"] == $token;
    }

    public function createSession(array $user): void
    {
        session_start();
        $_SESSION["user"] = $user;
    }

    public function destroySession(): void
    {
        session_start();
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
            session_destroy();
        }
    }

    public function logout(): void
    {
        $this->destroySession();
        header("Location: http://localhost/ProyectoCursos/public/");
    }

    public function validarData(object $data): bool|string
    {
        $nombre = Utils::validarNombre($data->nombre);
        $apellidos = Utils::validarApellidos($data->apellidos);
        $email = Utils::validarEmail($data->email);
        $password = Utils::validarPassword($data->password);

        if ($nombre === true) {
            if ($apellidos === true) {
                if ($email === true) {
                    if ($password === true) {
                        return true;
                    }
                    else return $password;
                }
                else return $email;
            }
            else return $apellidos;
        }
        else return $nombre;
    }

    public function validarLogin(mixed $data): bool
    {
        $email = Utils::validarEmail($data->email);
        $password = Utils::validarPassword($data->password);

        if ($email) {
            if ($password) return true;
            else return $password;
        }
        else return $email;
    }


}