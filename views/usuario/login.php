<link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../node_modules/bootstrap-icons/font/bootstrap-icons.css">
<script src="../../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js" async defer type="module"></script>

<main>
    <section class="pb-4">
        <div class="col-8 container">
            <div class="jumbotron">
                <h1 class="display-5 p-4">Login:</h1>
            </div>
        </div>
        <div class="bg-white ">

            <section class="w-100 p-4 d-flex justify-content-center pb-4">
                <div style="width: 26rem;">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="data[email]" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contrase&ntilde;a</label>
                        <input type="password" class="form-control" id="password" name="data[password]" required>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="login()">Enviar</button>
                    <div id="error"></div>
                    <script>
                        function login() {
                            let email = document.getElementById('email').value;
                            let password = document.getElementById('password').value;
                            let data = {
                                email: email,
                                password: password
                            };
                            let url = 'http://localhost/ProyectoCursos/public/usuarios/login';
                            let body = JSON.stringify(data);
                            $.ajax({
                                url: url,
                                type: "POST",
                                dataType: 'json',
                                data: body,
                                contentType: 'application/json',
                                success: function (data) {
                                    console.log(data);
                                },
                                error: function (error) {
                                    tratarError(error);
                                }
                            });
                        }

                        function tratarError(error) {
                            error = JSON.parse(error.responseText)
                            console.log(error);
                            let errorMsg = $('<p class="text-danger"></p>');
                            errorMsg.text("ERROR: " + error.message);
                            $('#error').empty().append(errorMsg);
                        }
                    </script>
                </div>
            </section>
        </div>
    </section>
</main>