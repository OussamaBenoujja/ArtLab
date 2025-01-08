<?php
// mailer.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

function sendEmail($toEmail, $toName, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 2; 
        $mail->isSMTP();
        $mail->Host       = 'mail.privateemail.com'; //
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username   = 'dev@oussamabenoujja.me'; // Your email address
        $mail->Password   = 'oussamaBNJ13101310@'; // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS encryption
        $mail->Port       = 587; // Port for TLS

        // Recipients
        $mail->setFrom('dev@oussamabenoujja.me', 'Oussama Benoujja'); // Sender
        $mail->addAddress($toEmail, $toName); // Recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject; // Email subject
        $mail->Body    = nl2br($message); // Email body (plain text converted to HTML)

        // Send email
        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return "Mailer Error: {$mail->ErrorInfo}"; // Return error message if sending fails
    }
}
?>