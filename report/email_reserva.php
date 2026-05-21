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
    $mail->Subject = 'Confirmación de su Reserva - Hotel Villa Marina';

    // Diseño Premium
    $mail->Body = "
    <div style='margin: 0; padding: 0; background-color: #f0f2f5; font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif;'>
        <table align='center' border='0' cellpadding='0' cellspacing='0' width='600' style='border-collapse: collapse; background-color: #ffffff; margin-top: 20px; margin-bottom: 20px; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);'>
            <!-- Header with Background Image -->
            <tr>
                <td align='center' style='background: url(\"https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1000&auto=format&fit=crop\") no-repeat center center; background-size: cover; padding: 60px 40px;'>
                    <div style='background-color: rgba(0, 0, 0, 0.4); padding: 20px; border-radius: 8px; display: inline-block;'>
                        <h1 style='color: #ffffff; margin: 0; font-size: 28px; text-transform: uppercase; letter-spacing: 3px; font-weight: 300;'>Reserva Confirmada</h1>
                    </div>
                </td>
            </tr>
            
            <!-- Welcome Text -->
            <tr>
                <td style='padding: 40px;'>
                    <h2 style='color: #1a5f7a; font-size: 22px; margin-top: 0;'>¡Hola, $nombre_completo!</h2>
                    <p style='color: #555555; font-size: 16px; line-height: 1.6;'>
                        Estamos encantados de confirmar su estancia con nosotros. En el <strong>Hotel Villa Marina</strong>, nos estamos preparando para brindarle una experiencia excepcional llena de lujo y relajación.
                    </p>
                </td>
            </tr>

            <!-- Information Sections -->
            <tr>
                <td style='padding: 0 40px;'>
                    <!-- Client Details -->
                    <div style='background-color: #f9fbfc; border: 1px solid #e1e8ed; border-radius: 12px; padding: 25px; margin-bottom: 20px;'>
                        <h3 style='color: #1a5f7a; font-size: 18px; margin-top: 0; border-bottom: 2px solid #1a5f7a; padding-bottom: 10px; display: inline-block;'>Datos del Huésped</h3>
                        <table width='100%' style='margin-top: 15px;'>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Nombre:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>$nombre_completo</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Documento:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$userData['tipo_documento_nombre']} - {$userData['documento']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Teléfono:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$userData['telefono']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Email:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$userData['email']}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Reservation Details -->
                    <div style='background-color: #f9fbfc; border: 1px solid #e1e8ed; border-radius: 12px; padding: 25px;'>
                        <h3 style='color: #1a5f7a; font-size: 18px; margin-top: 0; border-bottom: 2px solid #1a5f7a; padding-bottom: 10px; display: inline-block;'>Detalles de su Estadía</h3>
                        <table width='100%' style='margin-top: 15px;'>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Habitación:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$reservaData['habitacion']} ({$reservaData['tipo']})</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Fecha de Entrada:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$reservaData['entrada']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Fecha de Salida:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$reservaData['salida']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 8px 0; color: #777777;'>Personas:</td>
                                <td style='padding: 8px 0; color: #333333; font-weight: bold; text-align: right;'>{$reservaData['personas']}</td>
                            </tr>
                            <tr>
                                <td style='padding: 20px 0 8px 0; color: #1a5f7a; font-size: 18px; font-weight: bold;'>Total:</td>
                                <td style='padding: 20px 0 8px 0; color: #1a5f7a; font-size: 20px; font-weight: bold; text-align: right;'>$ " . number_format($reservaData['total'], 0, ',', '.') . "</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

            <!-- Information Section -->
            <tr>
                <td style='padding: 40px;'>
                    <table width='100%'>
                        <tr>
                            <td width='30%' valign='top'>
                                <div style='text-align: center; padding: 10px;'>
                                    <img src='https://cdn-icons-png.flaticon.com/512/3009/3009489.png' width='40' alt='Check-in'>
                                    <p style='font-size: 12px; color: #777777; margin-top: 5px;'>Check-in<br><strong>3:00 PM</strong></p>
                                </div>
                            </td>
                            <td width='30%' valign='top'>
                                <div style='text-align: center; padding: 10px;'>
                                    <img src='https://cdn-icons-png.flaticon.com/512/3009/3009488.png' width='40' alt='Check-out'>
                                    <p style='font-size: 12px; color: #777777; margin-top: 5px;'>Check-out<br><strong>11:00 AM</strong></p>
                                </div>
                            </td>
                            <td width='30%' valign='top'>
                                <div style='text-align: center; padding: 10px;'>
                                    <img src='https://cdn-icons-png.flaticon.com/512/483/483497.png' width='40' alt='Location'>
                                    <p style='font-size: 12px; color: #777777; margin-top: 5px;'>Zona Costera<br><strong>Ver Mapa</strong></p>
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                    <div style='text-align: center; margin-top: 30px;'>
                        <a href='http://localhost/Hotel-MVC-Kevin/' style='background-color: #1a5f7a; color: #ffffff; text-decoration: none; padding: 15px 30px; border-radius: 50px; font-weight: bold; display: inline-block; box-shadow: 0 5px 15px rgba(26, 95, 122, 0.3);'>Gestionar mi Reserva</a>
                    </div>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style='background-color: #1a5f7a; padding: 30px; text-align: center;'>
                    <p style='color: #ffffff; margin: 0; font-size: 14px;'>&copy; 2026 Hotel Villa Marina - El Lujo que Mereces</p>
                    <p style='color: rgba(255,255,255,0.6); margin-top: 10px; font-size: 12px;'>Si tiene alguna pregunta, contáctenos al +57 300 000 0000 o responda a este correo.</p>
                </td>
            </tr>
        </table>
    </div>
    ";

    $mail->AltBody = "Hola $nombre_completo, su reserva en el Hotel Villa Marina ha sido confirmada. Habitación: {$reservaData['habitacion']}, Fecha: {$reservaData['entrada']} al {$reservaData['salida']}. Total: {$reservaData['total']}";

    $mail->send();

} catch (Exception $e) {
    // Log error or handle it as needed
    error_log("Error al enviar email de reserva: " . $mail->ErrorInfo);
}
