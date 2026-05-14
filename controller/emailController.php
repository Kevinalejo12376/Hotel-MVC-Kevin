<?php
class EmailController {
    public function sendEmail(){
        require_once "report/email.php";
    }

    public function sendReservationEmail($userData, $reservaData){
        require_once "report/email_reserva.php";
    }
}
