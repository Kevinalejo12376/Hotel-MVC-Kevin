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
    <div style='background-color: #f4f7f6; padding: 40px 0; font-family: \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; margin: 0;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);'>
            
            <!-- Encabezado con Degradado -->
            <div style='background: linear-gradient(135deg, #1a5f7a 0%, #00a8cc 100%); padding: 50px 40px; text-align: center;'>
                <h1 style='color: #ffffff; margin: 0; font-size: 32px; font-weight: 300; letter-spacing: 2px; text-transform: uppercase;'>Hotel Villa Marina</h1>
                <p style='color: rgba(255,255,255,0.8); margin-top: 10px; font-size: 14px; letter-spacing: 1px;'>EL LUJO QUE MERECES</p>
            </div>

            <!-- Imagen Destacada -->
            <div style='width: 100%; height: 250px; overflow: hidden;'>
                <img src='https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=1000&auto=format&fit=crop' alt='Hotel Villa Marina' style='width: 100%; height: 100%; object-fit: cover; display: block;'>
            </div>

            <!-- Contenido Principal -->
            <div style='padding: 50px 40px;'>
                <h2 style='color: #2c3e50; margin-top: 0; font-size: 24px; font-weight: 600;'>¡Bienvenido a la Experiencia Villa Marina!</h2>
                
                <p style='color: #5d6d7e; line-height: 1.8; font-size: 16px;'>
                    Estimado(a) <strong>$nombre</strong>,<br><br>
                    Es un honor para nosotros darte la bienvenida a nuestra exclusiva comunidad. En el <strong>Hotel Villa Marina</strong>, cada detalle está diseñado para ofrecerte una estancia inolvidable marcada por el confort y la elegancia.
                </p>

                <!-- Tarjeta de Datos -->
                <div style='background-color: #f9fbfc; border-radius: 12px; border: 1px solid #e1e8ed; padding: 25px; margin: 35px 0;'>
                    <h3 style='margin: 0 0 15px 0; color: #1a5f7a; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;'>Confirmación de Registro</h3>
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='color: #7f8c8d; padding: 8px 0; font-size: 14px;'>Nombre del Huésped:</td>
                            <td style='color: #2c3e50; font-weight: 600; text-align: right; font-size: 14px;'>$nombre</td>
                        </tr>
                        <tr>
                            <td style='color: #7f8c8d; padding: 8px 0; font-size: 14px;'>Correo Electrónico:</td>
                            <td style='color: #2c3e50; font-weight: 600; text-align: right; font-size: 14px;'>$email</td>
                        </tr>
                        <tr>
                            <td style='color: #7f8c8d; padding: 8px 0; font-size: 14px;'>Estado de Cuenta:</td>
                            <td style='color: #27ae60; font-weight: 600; text-align: right; font-size: 14px;'>ACTIVA</td>
                        </tr>
                    </table>
                </div>

                <p style='color: #5d6d7e; line-height: 1.8; font-size: 16px;'>
                    A partir de ahora, tendrás acceso preferencial a nuestras promociones de temporada y eventos especiales.
                </p>

                <!-- Botón de Acción -->
                <div style='text-align: center; margin-top: 45px;'>
                    <a href='http://localhost/Hotel-MVC-Kevin/' 
                       style='background-color: #1a5f7a; color: #ffffff; padding: 18px 35px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 16px; display: inline-block; box-shadow: 0 10px 20px rgba(26, 95, 122, 0.2); transition: all 0.3s;'>
                       Explorar el Hotel
                    </a>
                </div>
            </div>

            <!-- Pie de Página -->
            <div style='background-color: #f8f9fa; padding: 30px 40px; text-align: center; border-top: 1px solid #eeeeee;'>
                <p style='color: #bdc3c7; font-size: 13px; margin: 0;'>
                    © 2026 Hotel Villa Marina. Todos los derechos reservados.<br>
                    <span style='color: #dcdde1;'>Ubicación: Av. Costanera, Suite 101 | Tel: +57 300 000 0000</span>
                </p>
                <div style='margin-top: 15px;'>
                    <p style='color: #bdc3c7; font-size: 11px; margin: 0;'>Este es un correo automático, por favor no respondas directamente.</p>
                </div>
            </div>
        </div>
    </div>
    ";


    $mail->AltBody = "Hola $nombre, gracias por registrarte en el Hotel Villa Marina. Tu registro con el email $email ha sido exitoso.";

    // Enviar el correo
    $mail->send();

} catch (Exception $e) {
    echo "Error al enviar el email: {$mail->ErrorInfo}";
}


