CREATE DATABASE IF NOT EXISTS proyectoCursos;
USE proyectoCursos;

--
-- Base de datos: `proyectocursos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `horas` int(11) NOT NULL,
  `ponente` varchar(255) NOT NULL,
  `alumnos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id`, `nombre`, `descripcion`, `horas`, `ponente`, `alumnos`) VALUES
(1, 'Introducción a la Hostelería', 'En este curso aprenderas todos los conceptos necesarios para introducirte en el mundo de la hostelería.', 200, 'Sergio Álbez', NULL),
(3, 'Introducción a la Repostería', 'Este curso de introducción te dará los conocimientos necesarios para iniciarte en el mundo de la repostería.', 200, 'Alberto Chicote', NULL),
(4, 'Panadería avanzada', 'Si ya tienes conocimientos previos de panadería y quieres convertirte en el mejor panadero, este curso es para tí.', 600, 'Miguel Ortiz', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_curso` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id_curso`, `id_usuario`) VALUES
(3, 3),
(4, 26),
(4, 26),
(4, 26);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ponentes`
--

CREATE TABLE `ponentes` (
  `id` int(11) NOT NULL,
  `DNI` varchar(9) NOT NULL,
  `nombre` varchar(40) DEFAULT NULL,
  `apellidos` varchar(40) DEFAULT NULL,
  `imagen` varchar(32) DEFAULT NULL,
  `tags` varchar(120) DEFAULT NULL,
  `redes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `ponentes`
--

INSERT INTO `ponentes` (`id`, `DNI`, `nombre`, `apellidos`, `imagen`, `tags`, `redes`) VALUES
(21, '87654321S', 'Juan Carlos', 'Martínez', 'chef2.jpg', 'Nacido en México, le encanta el picante... y el fuego.', 'Instagram'),
(22, '13455689K', 'Alberto', 'Chicote', 'chicote.jpg', 'Poco más se puede contar de él que no sepas, es chicote.', 'Instagram'),
(23, '98564321B', 'Dabiz', 'Muñoz', 'dabiz.jpg', 'Una nueva estrella en el panorama cocinero español. Te enseñará como nadie.', 'Instagram'),
(24, '65428912L', 'Pepe', 'Hernández', 'pepe.jpg', 'El más meticuloso. Si quieres lograr la perfección, él es el indicado.', 'Instagram');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `talleres`
--

CREATE TABLE `talleres` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `horas` int(11) NOT NULL,
  `ponente` varchar(255) NOT NULL,
  `alumnos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `talleres`
--

INSERT INTO `talleres` (`id`, `nombre`, `descripcion`, `horas`, `ponente`, `alumnos`) VALUES
(1, 'Taller De Ramen', 'Aprende a cocinar el mejor ramen y sorprende a tus amigos y familiares!', 4, 'David López', NULL),
(2, 'Taller De cocina francesa', 'Aprende a cocinar la mejor cocina francesa y sorprende a tus amigos y familiares!', 15, 'Maria Belén Callejón', NULL),
(3, 'Taller de cocina para niños', 'Si quieres pasar una tarde divertida con los peques, este taller es para ti!', 8, 'Abel Persi', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `confirmado` tinyint(1) NOT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_exp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `rol`, `confirmado`, `token`, `token_exp`) VALUES
(1, 'Pepe', 'González Pérez', 'pepepepito@gmail.com', 'pepethebest123', 'user', 0, NULL, NULL),
(2, 'Pepito', 'Martinez', 'Pepe@marti.com', 'pepethebest123', 'usuario', 0, '787d25479a36499f3d91a6ca11092431', NULL),
(3, 'Pablo', 'Martinez', 'pablito@gmail.com', '$2y$10$bkeb1MoiTOr4YZSD8kwmreYM7lAKcPfq.P95zRURvoJUNK5cEWyOi', 'usuario', 0, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzYzODA4NzMsImV4cCI6MTY3NjM4NDQ3MywiZGF0YSI6WyJwYWJsaXRvQGdtYWlsLmNvbSJdfQ.LcpWf7i4poqjTKB4g1KZt1yeKl-Rh9HWgvBnHow6Ulc', '0000-00-00 00:00:00'),
(20, 'David', 'Ballesteros', 'david13ballesteros@gmail.com', '$2y$10$Q3ri01h.RSmic9Wdhu7QsOdgIDrBMb9y8qGij8NjAy/Zikv7OKsrW', 'usuario', 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzcwNzIwNjIsImV4cCI6MTY3NzA3NTY2MiwiZGF0YSI6WyJkYXZpZDEzYmFsbGVzdGVyb3NAZ21haWwuY29tIl19.8UDi5ITEtpKNKWb7qnTzCNiMsn4s6EgDymoRtsVr_G8', '0000-00-00 00:00:00'),
(26, 'David', 'ortiz', 'david15ballesteros@gmail.com', '$2y$10$AX3izruzbJAQ9dk6cMFXS.Gc71SdaNT35CYGmJE0pm/NQQEDtNlKG', 'usuario', 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2NzcwODY1NjUsImV4cCI6MTY3NzA5MDE2NSwiZGF0YSI6WyJkYXZpZDE1YmFsbGVzdGVyb3NAZ21haWwuY29tIl19.qux5tnxZgesdgAJ42_TLrNzOY6-PrHJOnz5jJL6-LvU', '0000-00-00 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ponentes`
--
ALTER TABLE `ponentes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `talleres`
--
ALTER TABLE `talleres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ponentes`
--
ALTER TABLE `ponentes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `talleres`
--
ALTER TABLE `talleres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
COMMIT;