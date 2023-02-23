<link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" async defer type="module"></script>

<?php $user = $_SESSION['user']; ?>

<main>
    <h1 class="display-6 p-5">Tus datos:</h1>

    <table class="table table-striped container">
        <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Apellidos</th>
            <th scope="col">Email</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $user["nombre"] ?></td>
                <td><?= $user["apellidos"] ?></td>
                <td><?= $user["email"] ?></td>
            </tr>

        </tbody>
    </table>

    <h1 class="display-6 p-5">Tus cursos:</h1>

    <table class="table table-striped container">
        <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Descripci&oacute;n</th>
            <th scope="col">Horas</th>
            <th scope="col">Ponente</th>
        </tr>
        </thead>
        <tbody>
        <tr>

        </tr>

        </tbody>
    </table>


</main>