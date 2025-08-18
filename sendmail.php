<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Sanitize input
$name = htmlspecialchars(trim($_POST['name']));
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

// Gmail SMTP credentials
$gmailUsername = 'your_email@gmail.com';          // ⬅️ Your Gmail
$gmailAppPassword = 'xxxxxxxxxxxxxxxx';           // ⬅️ 16-digit App Password

$mail = new PHPMailer(true);

try {
    // SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $gmailUsername;
    $mail->Password = $gmailAppPassword;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender and recipient
    $mail->setFrom('noreply@yourdomain.com', 'Contact Form');
    $mail->addAddress($gmailUsername); // send to your Gmail

    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';
    $mail->Body    = "
        <strong>Name:</strong> $name <br>
        <strong>Email:</strong> $email <br>
        <strong>Message:</strong> $message
    ";

    $mail->send();
    echo '✅ Message sent successfully!';
} catch (Exception $e) {
    echo "❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
