<?php
// Show errors for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require __DIR__ . '/../PHPMailer/src/Exception.php';
require __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize inputs
    $first_name    = htmlspecialchars(trim($_POST['first_name'] ?? ''));
    $last_name     = htmlspecialchars(trim($_POST['last_name'] ?? ''));
    $email         = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone         = htmlspecialchars(trim($_POST['phone'] ?? ''));
    $subject_input = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $inquiry_type  = htmlspecialchars(trim($_POST['inquiry_type'] ?? ''));
    $message       = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate required fields
    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($email) ||
        empty($subject_input) ||
        empty($message) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        header("Location: contact.php?status=error");
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';     // your Gmail
        $mail->Password   = '';         // Gmail App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender & recipient
        $mail->setFrom('ayushgnida@gmail.com', 'Website Contact Form'); // MUST match your Gmail
        $mail->addAddress('akgnida@gmail.com');                         // Receiver (your second email)
        $mail->addReplyTo($email, "$first_name $last_name");            // Reply to user

        // Attach file if uploaded
        if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $mail->addAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
        }

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission - " . $subject_input;
        $mail->Body    = "
            <p><strong>Name:</strong> {$first_name} {$last_name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Inquiry Type:</strong> {$inquiry_type}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        ";

        // Send the email
        $mail->send();
        header("Location: contact.php?status=success");
        exit;

    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo . " Exception: " . $e->getMessage());
        header("Location: contact.php?status=error");
        exit;
    }
} else {
    echo "Access denied.";
}
