<?php
namespace Models;

use Lib\BaseDatos;
use PDOException;
use MVC\Utils\Utils;

// Modelo de la tabla cursos
// Accede a la base de datos para ver, modificar y borrar datos de los cursos.
class Curso
{
    private string $id;
    private string $nombre;
    private string $descripcion;
    private string $duracion;
    private string $ponente;

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

    public function getDescripcion(): string {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void {
        $this->descripcion = $descripcion;
    }

    public function getDuracion(): string {
        return $this->duracion;
    }

    public function setDuracion(string $duracion): void {
        $this->duracion = $duracion;
    }

    public function getPonente(): string {
        return $this->ponente;
    }

    public function setPonente(string $ponente): void {
        $this->ponente = $ponente;
    }

    public function getAll(): array {
        $sql = "SELECT * FROM cursos";
        $this->bd->consulta($sql);
        return $this->bd->extraer_todos();
    }

    public function getCurso($id): array|false {
        $sql = "SELECT * FROM cursos WHERE id = $id";
        $this->bd->consulta($sql);
        return $this->bd->extraer_registro();
    }

    public function deleteCurso($id): bool
    {
        $sql = "DELETE FROM cursos WHERE id = $id";
        try {
            $this->bd->consulta($sql);
            return true;
        }
        catch (PDOException) {
            return false;
        }
    }

    public function inscribir($id_curso, $id_usuario): void
    {
        $sql = "INSERT INTO inscripciones (id_curso, id_usuario) VALUES ($id_curso, $id_usuario)";
        $this->bd->consulta($sql);

        $sql = "UPDATE cursos SET alumnos = alumnos + 1 WHERE id = $id_curso";
        $this->bd->consulta($sql);
    }

    public static function fromArray(array $data): self
    {
        $curso = new self();
        $curso->setId($data['id']);
        $curso->setNombre($data['nombre']);
        $curso->setDescripcion($data['descripcion']);
        $curso->setDuracion($data['duracion']);
        $curso->setPonente($data['ponente']);
        return $curso;
    }

    public function validarData(mixed $data): bool|string
    {
        try {
            set_error_handler(function (){throw new PDOException();}, E_WARNING);
            $nombre = Utils::validarNombre($data->nombre);
            $descripcion = Utils::validarTexto($data->descripcion);
            $duracion = Utils::validarNumero($data->duracion);
            $ponente = Utils::validarNombre($data->ponente);
            restore_error_handler();
        }
        catch (PDOException) {
            return "Se ha producido un error, comprueba que los datos están escritos correctamente";
        }

        if ($nombre === true) {
            if ($descripcion === true) {
                if ($duracion === true) {
                    if ($ponente === true) {
                        return true;
                    }
                    else return $ponente;
                }
                else return $duracion;
            }
            else return $descripcion;
        }
        else return $nombre;

    }


}