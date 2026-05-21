<?php
require_once 'lib/email/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'kalejodv@gmail.com';
    $mail->Password = 'uxrw uumz fdav gudr';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Configuración del correo
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(true);

    // Datos recibidos
    $email_dest = $userData['email'];
    $nombre_completo = $userData['nombre'] . ' ' . $userData['apellido'];

    // Remitente
    $mail->setFrom('no-reply@Hotel.mvc.com', 'Hotel Villa Marina');
    $mail->addAddress($email_dest, $nombre_completo);

    // Contenido
    $mail->Subject = 'Cancelación de su Reserva - Hotel Villa Marina';

    // Diseño Premium
    $mail->Body = "
    <div style='margin: 0; padding: 0; background-color: #f0f2f5; font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;'>
        <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse; background-color: #ffffff; margin-top: 20px; margin-bottom: 20px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);'>
            <!-- Header with Red Accent -->
            <tr>
                <td align='center' style='background-color: #e74c3c; padding: 50px 40px;'>
                    <h1 style='color: #ffffff; margin: 0; font-size: 28px; text-transform: uppercase; letter-spacing: 3px; font-weight: 300;'>Reserva Cancelada</h1>
                </td>
            </tr>
            
            <!-- Notification Text -->
            <tr>
                <td style='padding: 40px;'>
                    <h2 style='color: #2c3e50; font-size: 22px; margin-top: 0;'>¡Hola, $nombre_completo!</h2>
                    <p style='color: #555555; font-size: 16px; line-height: 1.6;'>
                        Le confirmamos que su reserva en el <strong>Hotel Villa Marina</strong> ha sido cancelada exitosamente como usted lo solicitó. Lamentamos que no pueda acompañarnos en esta ocasión.
                    </p>
                </td>
            </tr>

            <!-- Reservation Details -->
            <tr>
                <td style='padding: 0 40px;'>
                    <div style='background-color: #fdf2f2; border: 1px solid #f5c6cb; border-radius: 12px; padding: 25px;'>
                        <h3 style='color: #c0392b; font-size: 18px; margin-top: 0; border-bottom: 2px solid #c0392b; padding-bottom: 10px; display: inline-block;'>Datos de la Reserva Cancelada</h3>
                        <table width='100%' style='margin-top: 15px;'>
                            <tr>
                                <td style='padding: 8px 0; color: #7f8c8d;'>Habitación:</td>
                                <td style='padding: 8px 0; color: #2c3e50; font-weight: bold; text-align: right;'>{$reservaData['habitacion']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #7f8c8d;'>Fechas:</td>
                                <td style='padding: 8px 0; color: #2c3e50; font-weight: bold; text-align: right;'>{$reservaData['entrada']} al {$reservaData['salida']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #7f8c8d;'>Valor Reembolsado:</td>
                                <td style='padding: 8px 0; color: #c0392b; font-weight: bold; text-align: right; font-size: 18px;'>$ " . number_format($reservaData['total'], 0, ',', '.') . "</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Information Section -->
            <tr>
                <td style='padding: 40px;'>
                    <div style='text-align: center;'>
                        <p style='color: #7f8c8d; font-size: 15px;'>Esperamos verle pronto nuevamente. ¡Nuestras puertas siempre estarán abiertas para usted!</p>
                        <a href='http://localhost/Hotel-MVC-Kevin/' style='background-color: #2c3e50; color: #ffffff; text-decoration: none; padding: 15px 30px; border-radius: 50px; font-weight: bold; display: inline-block; margin-top: 20px;'>Volver al Inicio</a>
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style='background-color: #2c3e50; padding: 30px; text-align: center;'>
                    <p style='color: #ffffff; margin: 0; font-size: 14px;'>© 2026 Hotel Villa Marina - El Lujo que Mereces</p>
                </td>
            </tr>
        </table>
    </div>
    ";

    $mail->AltBody = "Hola $nombre_completo, su reserva en el Hotel Villa Marina ha sido cancelada exitosamente.";

    $mail->send();

} catch (Exception $e) {
    error_log("Error al enviar email de cancelación: " . $mail->ErrorInfo);
}
