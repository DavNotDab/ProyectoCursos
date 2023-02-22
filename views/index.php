<link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
<script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" async defer type="module"></script>

<main style="padding: 20px">
    <div class="container-fluid" id="home">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron">
                    <h1 class="display-3">Bienvenido a la p&aacute;gina de inicio de Hosteler&iacute;a</h1>
                    <p class="lead">La mejor escuela de hosteler&iacute;a</p>
                    <hr class="my-4">
                    <p>Aqu&iacute; encontrar&aacute;s los mejores cursos y talleres de hosteler&iacute;a y pasteler&iacute;a. Impartidos por los mejores chefs y reposteros del mundo.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid p-4" id="cursos">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron">
                    <h1 class="display-5">Nuestros cursos:</h1>
                    <p class="lead">Ofrecemos una variedad de cursos profesionales para que te especialices en lo que <b>t&uacute;</b> quieras.</p>
                    <hr class="my-4">
                </div>
            </div>
        </div>

        <div class="row" id="cursos-cards" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">

        </div>
    </div>

    <div class="container-fluid p-4" id="talleres">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron">
                    <h1 class="display-5">Nuestros talleres:</h1>
                    <p class="lead">Ofrecemos una variedad de talleres profesionales para que te especialices en lo que <b>t&uacute;</b> quieras.</p>
                    <hr class="my-4">
                </div>
            </div>
        </div>

        <div class="row" id="talleres-cards" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">

        </div>
    </div>

    <div class="container-fluid p-4" id="ponentes">
        <div class="row">
            <div class="col-12">
                <div class="jumbotron">
                    <h1 class="display-5">Nuestros ponentes:</h1>
                    <p class="lead">Nuestros cursos son impartidos por los mejores chefs. Aqu&iacute; podr&aacute;s aprender un poco m&aacute;s sobre ellos.</p>
                    <hr class="my-4">
                </div>
            </div>
        </div>
        <div class="carousel slide container" data-bs-ride="carousel" id="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../src/media/chef.jpg" class="d-block w-100" alt="ponente-img">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Conoce a Pablo Ortega</h5>
                        <p>Un gran apasionado de la hosteler&iacute;a. Aprendi&oacute; a cocinar con tan solo 7 a&ntilde;os.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <script src="../src/scripts/ajax.js"></script>

</main>
