<?php
namespace Lib;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    private string $email;
    private string $token;

    public function __construct(string $email, string $token) {
        $this->email = $email;
        $this->token = $token;
    }

    public function sendConfirmation(): void
    {
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = '6272f53951b1d4';
        $phpmailer->Password = '0185c50697eb54';

        try {
            $phpmailer->setFrom('noreply.cursos@freecontent.com');
            $phpmailer->addAddress($this->email);
            $phpmailer->Subject = 'Confirmación de cuenta';

            $phpmailer->isHTML(true);
            $phpmailer->CharSet = 'UTF-8';

            $contenido = '<html lang="es">
            <head>
                <title>Confirmación de cuenta</title>
            </head>
            <body>
                <h1>Confirmación de cuenta</h1>
                <p>Hola '.$this->email.'</p>
                <p>Para confirmar tu cuenta, haz click en el siguiente enlace:</p>
                <a href="http://localhost/ProyectoCursos/public/confirmarCuenta/' . $this->token . '">Confirmar cuenta</a>
            </body>
        </html>';

            $phpmailer->Body = $contenido;
            $phpmailer->send();
        }
        catch (Exception) {
            echo 'Error al enviar el correo: ' . $phpmailer->ErrorInfo;
            var_dump($this->email); die();
        }
    }

}