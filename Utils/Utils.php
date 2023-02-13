<?php
namespace MVC\Utils;

use Exception;

class Utils {

    public static function deleteSession(string $name) : void {
        if (isset($_SESSION[$name])) {
            $_SESSION[$name] = null;
            unset($_SESSION[$name]);
        }
    }


    ////////////////////////////////////////////////////
    ///                 VALIDACIONES                 ///
    ////////////////////////////////////////////////////


    public static function validarTexto(string $texto) : bool|string {
        try {
            if (!preg_match("/^[a-zA-Z0-9\s\-_\/º°!¡?¿.,áéíóúÁÉÍÓÚñÑ]*$/", $texto)) {
                throw new Exception("El texto no puede contener caracteres especiales");
            }
            if (strlen($texto) < 3) {
                throw new Exception("El texto debe tener al menos 3 caracteres");
            }
            if (strlen($texto) > 200) {
                throw new Exception("El texto no puede tener más de 200 caracteres");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarNumero(int $numero) : bool|string {
        try {
            if ($numero < 0) {
                throw new Exception("El desnivel no puede ser negativo");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarNotas(string $notas) : bool|string {
        try {
            if (!preg_match("/^[a-zA-Z0-9\s\-_!¡?¿.,áéíóúÁÉÍÓÚñÑ]*$/", $notas)) {
                throw new Exception("Las notas no pueden contener caracteres especiales");
            }
            if (strlen($notas) > 200) {
                throw new Exception("Las notas no pueden tener más de 500 caracteres");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarNombre($nombre) : bool|string {
        try {
            if (strlen($nombre) < 3) {
                throw new Exception("El nombre debe ser de al menos 3 caracteres");
            }
            if (!preg_match("/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ]*$/", $nombre)) {
                throw new Exception("El nombre solo puede contener letras y números");
            }
            if (strlen($nombre) > 50) {
                throw new Exception("El nombre no puede ser de más de 50 caracteres");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarEmail($email) : bool|string {
        try {
            if (!preg_match("/^[\w.]+@([\w-]+\.)+[\w-]{2,4}$/", $email)) {
                throw new Exception("Formato de email incorrecto");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarPassword($password) : bool|string {
        try {
            if (strlen($password) < 8) {
                throw new Exception("La contraseña debe tener al menos 8 caracteres");
            }
            if (!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[-_!¿?¡])[a-zA-Z0-9-_!¿?¡]{8,}$/", $password)) {
                throw new Exception("La contraseña debe tener mayusculas, minusculas, números y algún caracter entre -_!¿?¡");
            }
            if (strlen($password) > 50) {
                throw new Exception("La contraseña no puede ser de más de 50 caracteres");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarImagen($imagen) : bool|string {
        try {
            if ($imagen["type"] != "image/jpeg" && $imagen["type"] != "image/png") {
                throw new Exception("La imagen debe ser jpg o png");
            }
            if ($imagen["size"] > 4000000) {
                throw new Exception("La imagen no puede ser de más de 4 MB");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function guardarImagen($imagen) : bool|string {
        try {
            $nombre = $imagen["name"];

            if (!is_dir("./images"))
                mkdir("./images", 0777);

            move_uploaded_file($imagen["tmp_name"], "./images/".$nombre);

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function validarDireccion(string $texto, bool $checkLength = true) : bool|string {
        try {
            if (!preg_match("/^[a-zA-Z0-9\s\-_\/º°!¡?¿.,áéíóúÁÉÍÓÚñÑ]*$/", $texto)) {
                throw new Exception("La direccion no puede contener caracteres especiales");
            }
            if ($checkLength === true) {
                if (strlen($texto) < 3) {
                    throw new Exception("La direccion debe tener al menos 3 caracteres");
                }
            }

            if (strlen($texto) > 200) {
                throw new Exception("La direccion no puede tener más de 200 caracteres");
            }
            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}