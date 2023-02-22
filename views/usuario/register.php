<link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" async defer type="module"></script>

<main>
    <section class="pb-4">
        <div class="col-8 container">
            <div class="jumbotron">
                <h1 class="display-5 p-4">Registro:</h1>
            </div>
        </div>
        <div class="bg-white ">

            <section class="w-100 p-4 d-flex justify-content-center pb-4">
                <div style="width: 26rem;">
                    <form action="">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre">
                        </div>
                        <div class="mb-3">
                            <label for="apellidos" class="form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contrase&ntilde;a</label>
                            <input type="password" class="form-control" id="password" aria-describedby="passwordHelp">
                            <div id="passwordHelp" class="form-text">La contrase&ntilde;a debe tener n&uacute;meros, letras, y un s&iacute;mbolo</div>

                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terminos">
                            <label class="form-check-label" for="terminos">Acepto los t&eacute;rminos</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
            </section>
        </div>
    </section>

</main>