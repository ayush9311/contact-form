<?php
// Show errors for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use MongoDB\Client as MongoClient;

// Include PHPMailer (correct paths)
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

// Include Composer autoload if you installed MongoDB library via Composer
require __DIR__ . '/vendor/autoload.php';

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

    // ----------- Store in MongoDB -----------
    try {
        $mongo = new MongoClient("mongodb://127.0.0.1:27017"); // MongoDB URI
        $db = $mongo->contact_form_db;                           // Database
        $collection = $db->submissions;                          // Collection

        $collection->insertOne([
            'first_name'  => $first_name,
            'last_name'   => $last_name,
            'email'       => $email,
            'phone'       => $phone,
            'subject'     => $subject_input,
            'inquiry_type'=> $inquiry_type,
            'message'     => $message,
            'date'        => new MongoDB\BSON\UTCDateTime()
        ]);
    } catch (Exception $e) {
        error_log("MongoDB Error: " . $e->getMessage());
    }

    // ----------- Send Email via PHPMailer -----------
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = '';
        $mail->Password   = '';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('ayushgnida@gmail.com', 'Website Contact Form');
        $mail->addAddress('akgnida@gmail.com');
        $mail->addReplyTo($email, "$first_name $last_name");

        if (!empty($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            $mail->addAttachment($_FILES['file']['tmp_name'], $_FILES['file']['name']);
        }

        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission - " . $subject_input;
        $mail->Body    = "
            <p><strong>Name:</strong> {$first_name} {$last_name}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p><strong>Phone:</strong> {$phone}</p>
            <p><strong>Inquiry Type:</strong> {$inquiry_type}</p>
            <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
        ";

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
