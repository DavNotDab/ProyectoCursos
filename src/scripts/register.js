function register() {
    let nombre = document.getElementById('nombre').value;
    let apellidos = document.getElementById('apellidos').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let terminos = document.getElementById('terminos').checked;
    let data = {
        nombre: nombre,
        apellidos: apellidos,
        email: email,
        password: password,
        terminos: terminos
    };
    let url = 'http://localhost/ProyectoCursos/public/usuarios/register';
    let body = JSON.stringify(data);
    $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: body,
        contentType: 'application/json',
        success: function (data) {
            console.log(data);
            let successMsg = $('<p class="text-success"></p>');
            successMsg.text(data.message);
            $('#message').empty().append(successMsg);
            setTimeout(function () {
                window.location.href = "http://localhost/ProyectoCursos/public/usuarios/login";
            }, 3000);
        },
        error: function (error) {
            console.log(error);
            tratarError(error);
        }
    });
}

function tratarError(error) {
    error = JSON.parse(error.responseText)
    console.log(error);
    let errorMsg = $('<p class="text-danger"></p>');
    errorMsg.text("ERROR: " + error.message);
    $('#message').empty().append(errorMsg);
}