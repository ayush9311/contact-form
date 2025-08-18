<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Contact Form</title>
<style>
body {
    font-family: Arial, sans-serif;
    padding: 40px;
    background-color: #f4f4f4;
}
form {
    background: white;
    padding: 20px;
    max-width: 600px;
    margin: auto;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
label {
    margin-top: 10px;
    font-weight: bold;
    display: block;
}
input, select, textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
button {
    margin-top: 15px;
    padding: 10px;
    width: 100%;
    background-color: #28a745;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
}
.status {
    text-align: center;
    max-width: 600px;
    margin: 10px auto;
    padding: 10px;
    border-radius: 6px;
}
.success {
    background-color: #d4edda;
    color: #155724;
}
.error {
    background-color: #f8d7da;
    color: #721c24;
}
.two-column {
    display: flex;
    gap: 10px;
}
.two-column div {
    flex: 1;
}
</style>
</head>
<body>

<h2 style="text-align:center;">Contact Us</h2>

<?php if (isset($_GET['status'])): ?>
<div class="status <?= $_GET['status'] === 'success' ? 'success' : 'error' ?>">
    <?= $_GET['status'] === 'success' ? '✅ Message sent successfully!' : '❌ Error sending message.' ?>
</div>
<?php endif; ?>

<form action="process_form.php" method="POST" enctype="multipart/form-data">

    <div class="two-column">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
    </div>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="phone">Phone Number:</label>
    <input type="text" id="phone" name="phone">

    <label for="subject">Subject:</label>
    <input type="text" id="subject" name="subject" required>

    <label for="inquiry_type">Inquiry Type:</label>
    <select id="inquiry_type" name="inquiry_type">
        <option value="">-- Select --</option>
        <option value="General Question">General Question</option>
        <option value="Support">Support</option>
        <option value="Feedback">Feedback</option>
        <option value="Other">Other</option>
    </select>

    <label for="message">Message:</label>
    <textarea id="message" name="message" rows="5" required></textarea>

    <label for="file">Attach a file (optional):</label>
    <input type="file" id="file" name="file">

    <button type="submit">Send</button>
</form>

</body>
</html>
