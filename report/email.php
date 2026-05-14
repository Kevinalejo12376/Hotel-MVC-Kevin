<?php
require_once 'lib/email/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      // Desactivar salida de depuración (usar SMTP::DEBUG_SERVER para pruebas)
    $mail->isSMTP();                                         // Enviar usando SMTP
    $mail->Host = 'smtp.gmail.com';                    // Servidor SMTP de Gmail
    $mail->SMTPAuth = true;                                // Habilitar autenticación SMTP
    $mail->Username = 'kalejodv@gmail.com';                // Nombre de usuario SMTP
    $mail->Password = 'uxrw uumz fdav gudr';                // Contraseña SMTP (App Password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Habilitar cifrado TLS implícito
    $mail->Port = 587;                                 // Puerto TCP para conectarse

    // Configuración del correo
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    // Obtener datos de la sesión (del registro reciente)
    $email = $_SESSION['email_temp'];
    $nombre = $_SESSION['nombre_temp'];

    if (empty($email)) {
        throw new Exception("No hay un correo destino definido.");
    }

    // Remitente
    $mail->setFrom('no-reply@Hotel.mvc.com', 'Hotel VILLA MARINA');

    // Destinatario
    $mail->addAddress($email, $nombre);

    // Contenido
    $mail->Subject = 'Confirmación de Registro en el Hotel Villa Marina';

    // Cuerpo del email con diseño profesional y elegante
    $mail->Body = "
    <div style='margin: 0; padding: 0; background-color: #f4f7f6; font-family: \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif;'>
        <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse; background-color: #ffffff; margin-top: 30px; margin-bottom: 30px; border-radius: 20px; overflow: hidden; box-shadow: 0 15px 45px rgba(0,0,0,0.08);'>
            
            <!-- Hero Section with Background Image -->
            <tr>
                <td align='center' style='background: url(\"https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=1000&auto=format&fit=crop\") no-repeat center center; background-size: cover; padding: 80px 40px;'>
                    <div style='background-color: rgba(255, 255, 255, 0.9); padding: 30px; border-radius: 15px; display: inline-block; backdrop-filter: blur(5px);'>
                        <h1 style='color: #1a5f7a; margin: 0; font-size: 26px; text-transform: uppercase; letter-spacing: 4px; font-weight: 700;'>¡Bienvenido!</h1>
                        <p style='color: #00a8cc; margin: 5px 0 0 0; font-size: 14px; letter-spacing: 2px;'>HOTEL VILLA MARINA</p>
                    </div>
                </td>
            </tr>

            <!-- Main Content -->
            <tr>
                <td style='padding: 50px 40px;'>
                    <h2 style='color: #2c3e50; margin-top: 0; font-size: 28px; font-weight: 600; text-align: center;'>Tu aventura comienza aquí</h2>
                    
                    <p style='color: #5d6d7e; line-height: 1.8; font-size: 17px; text-align: center; margin-top: 20px;'>
                        Estimado(a) <strong>$nombre</strong>, es un placer darte la bienvenida a nuestra exclusiva comunidad. Hemos creado este espacio pensando en tu confort y tranquilidad.
                    </p>

                    <!-- User Info Card -->
                    <div style='background: linear-gradient(145deg, #ffffff, #f0f4f8); border-radius: 15px; border: 1px solid #e1e8ed; padding: 30px; margin: 40px 0; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);'>
                        <h3 style='margin: 0 0 20px 0; color: #1a5f7a; font-size: 14px; text-transform: uppercase; letter-spacing: 2px; text-align: center;'>Detalles de tu cuenta</h3>
                        <table width='100%' cellpadding='0' cellspacing='0'>
                            <tr>
                                <td style='color: #7f8c8d; padding: 12px 0; font-size: 15px;'>Nombre:</td>
                                <td style='color: #2c3e50; font-weight: 600; text-align: right; font-size: 15px;'>$nombre</td>
                            </tr>
                            <tr>
                                <td style='color: #7f8c8d; padding: 12px 0; font-size: 15px; border-top: 1px solid #edf2f7;'>Email:</td>
                                <td style='color: #2c3e50; font-weight: 600; text-align: right; font-size: 15px; border-top: 1px solid #edf2f7;'>$email</td>
                            </tr>
                            <tr>
                                <td style='color: #7f8c8d; padding: 12px 0; font-size: 15px; border-top: 1px solid #edf2f7;'>Estado:</td>
                                <td style='color: #27ae60; font-weight: 600; text-align: right; font-size: 15px; border-top: 1px solid #edf2f7;'>✓ CUENTA ACTIVADA</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Call to Action -->
                    <div style='text-align: center; margin-top: 40px;'>
                        <p style='color: #7f8c8d; font-size: 15px; margin-bottom: 25px;'>¿Listo para tu próxima escapada de lujo?</p>
                        <a href='http://localhost/Hotel-MVC-Kevin/' 
                           style='background: linear-gradient(135deg, #1a5f7a 0%, #00a8cc 100%); color: #ffffff; padding: 18px 45px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 16px; display: inline-block; box-shadow: 0 10px 25px rgba(26, 95, 122, 0.3); transition: transform 0.2s;'>
                           Explorar Habitaciones
                        </a>
                    </div>
                </td>
            </tr>

            <!-- Modern Footer -->
            <tr>
                <td style='background-color: #f8fafc; padding: 40px; text-align: center; border-top: 1px solid #f1f5f9;'>
                    <div style='margin-bottom: 20px;'>
                        <img src='https://cdn-icons-png.flaticon.com/512/25/25231.png' width='24' style='margin: 0 10px;' alt='Social'>
                        <img src='https://cdn-icons-png.flaticon.com/512/174/174855.png' width='24' style='margin: 0 10px;' alt='Social'>
                        <img src='https://cdn-icons-png.flaticon.com/512/174/174848.png' width='24' style='margin: 0 10px;' alt='Social'>
                    </div>
                    <p style='color: #94a3b8; font-size: 13px; margin: 0; line-height: 1.6;'>
                        © 2026 Hotel Villa Marina. Todos los derechos reservados.<br>
                        <span style='color: #cbd5e1;'>Ubicación: Av. Costanera, Suite 101 | Tel: +57 300 000 0000</span>
                    </p>
                </td>
            </tr>
        </table>
        <div style='text-align: center; padding-bottom: 30px;'>
            <p style='color: #94a3b8; font-size: 11px;'>Recibiste este correo porque te registraste en nuestro portal.<br>Si no fuiste tú, por favor ignora este mensaje.</p>
        </div>
    </div>
    ";


    $mail->AltBody = "Hola $nombre, gracias por registrarte en el Hotel Villa Marina. Tu registro con el email $email ha sido exitoso.";

    // Enviar el correo
    $mail->send();

} catch (Exception $e) {
    echo "Error al enviar el email: {$mail->ErrorInfo}";
}


