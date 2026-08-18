<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function envoyerMail($destinataire, $sujet, $message, $from = "sales@pb-cars.com", $fromName = "PBCars") {
    $mail = new PHPMailer(true);
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'mail.biakuuza.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sales@pb-cars.com'; // Remplace par ton adresse e-mail
        $mail->Password = 'tY9$ae!N-SP_5HU'; // Remplace par ton mot de passe
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Utilise ENCRYPTION_STARTTLS pour TLS
        $mail->Port = 465; // Utilise 587 si tu choisis TLS

        // Destinataire et expéditeur
        $mail->setFrom($from, $fromName);
        $mail->addAddress($destinataire);

        // Contenu du message
        $mail->isHTML(false);
        $mail->Subject = $sujet;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Erreur envoi mail : {$mail->ErrorInfo}";
        return false;
    }
}

