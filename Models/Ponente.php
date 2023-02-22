<?php
namespace Models;

use Lib\BaseDatos;
use PDOException;
use MVC\Utils\Utils;

class Ponente
{
    private string $id;
    private string $nombre;
    private string $apellidos;
    private string $imagen;
    private string $tags;
    private string $redes;

    private BaseDatos $bd;

    public function __construct() {
        $this->bd = new BaseDatos();
    }

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void {
        $this->nombre = $nombre;
    }

    public function getApellidos(): string {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void {
        $this->apellidos = $apellidos;
    }

    public function getImagen(): string {
        return $this->imagen;
    }

    public function setImagen(string $imagen): void {
        $this->imagen = $imagen;
    }

    public function getTags(): string {
        return $this->tags;
    }

    public function setTags(string $tags): void {
        $this->tags = $tags;
    }

    public function getRedes(): string {
        return $this->redes;
    }

    public function setRedes(string $redes): void {
        $this->redes = $redes;
    }


    public function getAll(): array {
        $sql = "SELECT * FROM ponentes";
        $this->bd->consulta($sql);
        return $this->bd->extraer_todos();
    }

    public function getPonente($id): array|false {
        $sql = "SELECT * FROM ponentes WHERE id = $id";
        $this->bd->consulta($sql);
        return $this->bd->extraer_registro();
    }

    public function deletePonente($id): bool
    {
        $sql = "DELETE FROM ponentes WHERE id = $id";
        try {
            $this->bd->consulta($sql);
            return true;
        }
        catch (PDOException $e) {
            return false;
        }
    }

    public function insertPonente(): bool
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $sql = "INSERT INTO ponentes (nombre, apellidos, imagen, tags, redes) VALUES (:nombre, :apellidos, :imagen, :tags, :redes)";
            $this->bd->consultaPrep($sql, [
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'imagen' => $this->imagen,
                'tags' => $this->tags,
                'redes' => $this->redes
            ]);

            return true;
        }
        return false;
    }

    public function updatePonente($id): bool
    {
        if ($_SERVER["REQUEST_METHOD"] == "PUT") {
            $sql = "UPDATE ponentes SET nombre = :nombre, apellidos = :apellidos, imagen = :imagen, tags = :tags, redes = :redes WHERE id = $id";
            $this->bd->consultaPrep($sql, [
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'imagen' => $this->imagen,
                'tags' => $this->tags,
                'redes' => $this->redes
            ]);

            return true;
        }
        return false;
    }

    public static function fromArray(array $data): self
    {
        $ponente = new self();
        $ponente->setId($data['id']);
        $ponente->setNombre($data['nombre']);
        $ponente->setApellidos($data['apellidos']);
        $ponente->setImagen($data['imagen']);
        $ponente->setTags($data['tags']);
        $ponente->setRedes($data['redes']);
        return $ponente;
    }

    public function validarData(mixed $data): bool|string
    {
        try {
            set_error_handler(function (){throw new PDOException();}, E_WARNING);
            $nombre = Utils::validarNombre($data->nombre);
            $apellidos = Utils::validarTexto($data->apellidos);
            $redes = Utils::validarTexto($data->redes);
            $tags = Utils::validarTexto($data->tags);
            //$imagen = Utils::validarImagen($data['imagen']);
            restore_error_handler();
        }
        catch (PDOException) {
            return "Se ha producido un error, comprueba que los datos están escritos correctamente";
        }

        if ($nombre === true) {
            if ($apellidos === true) {
                if ($redes === true) {
                    if ($tags === true) {
                        /*if ($imagen === true) {
                            return true;
                        } else {
                            return $imagen;
                        }*/
                        return true; // Este se quita
                    } else {
                        return $tags;
                    }
                } else {
                    return $redes;
                }
            } else {
                return $apellidos;
            }
        } else {
            return $nombre;
        }

    }


}