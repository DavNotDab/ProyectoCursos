
<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://code.jquery.com/color/jquery.color-2.2.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <title><?=$title ?? "Bienvenido"?></title>
</head>
<body class="container-fluid p-0">

<header>
    <nav class="navbar navbar-expand-md p-2" style="background-color: #e3f2fd; font-size: 1.2rem;">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">
                <i class="bi bi-egg-fried"></i>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="<?=$_ENV["BASE_URL"]?>" class="nav-item nav-link active">Home</a>
                    <a href="<?=$_ENV["BASE_URL"]?>#cursos" class="nav-item nav-link">Cursos</a>
                    <a href="<?=$_ENV["BASE_URL"]?>#talleres" class="nav-item nav-link">Talleres</a>
                    <a href="<?=$_ENV["BASE_URL"]?>#ponentes" class="nav-item nav-link">Nuestros Ponentes</a>
                    <a href="<?=$_ENV["BASE_URL"]?>#about" class="nav-item nav-link">Acerca de</a>
                </div>
                <div class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user'])): ?>
                        <a href="<?=$_ENV["BASE_URL"]?>usuarios/perfil" class="nav-item nav-link">Perfil</a>
                        <a href="<?=$_ENV["BASE_URL"]?>usuarios/logout" class="nav-item nav-link">Cerrar Sesi&oacute;n</a>
                    <?php else: ?>
                    <a href="<?=$_ENV["BASE_URL"]?>usuarios/login" class="nav-item nav-link">Inicia Sesi&oacute;n</a>
                    <a href="<?=$_ENV["BASE_URL"]?>usuarios/register" class="nav-item nav-link">Reg&iacute;strate</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
</header>