<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once ROOT_PATH . '/vendor/autoload.php'; 

function sendWelcomeEmail($to, $username) {
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'c0ab8992416424';
        $mail->Password = '0d0496111c260f';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;


        $mail->setFrom('no-reply@mywebsite.com', 'MyWebSite');
        $mail->addAddress($to, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Bienvenue sur MyWebSite';
        $mail->Body    = "<h1>Bonjour $username !</h1><p>Merci pour votre inscription. 😊</p>";
        $mail->AltBody = "Bonjour $username,\nMerci pour votre inscription.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur Mailtrap : " . $mail->ErrorInfo);
        return false;
    }
}
