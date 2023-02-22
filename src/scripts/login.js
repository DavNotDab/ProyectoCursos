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
            localStorage.setItem('token', data.token);
            localStorage.setItem('idUser', data.id);
            window.location.href = "http://localhost/ProyectoCursos/public/";
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
    $('#error').empty().append(errorMsg);
}