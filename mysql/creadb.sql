CREATE DATABASE IF NOT EXISTS proyectoCursos;
USE proyectoCursos;
DROP TABLE IF EXISTS ponentes;

CREATE TABLE ponentes (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(40) DEFAULT NULL,
    apellidos VARCHAR(40) DEFAULT NULL,
    imagen VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
    tags VARCHAR(120) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
    redes text,
    CONSTRAINT pk_ponentes PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios (
    id BIGINT(20) UNSIGNED NOT NULL,
    nombre VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL,
    apellidos VARCHAR(40) COLLATE utf8_unicode_ci NOT NULL,
    email VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
    password VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL,
    rol VARCHAR(10) COLLATE utf8_unicode_ci NOT NULL,
    confirmado varchar(2) COLLATE utf8_unicode_ci NOT NULL,
    token VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
    token_exp TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT pk_usuarios PRIMARY KEY (id),
    CONSTRAINT uq_email UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

