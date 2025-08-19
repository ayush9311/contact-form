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

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* ===== Background with Gradient Animation ===== */
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background: linear-gradient(270deg, #6a11cb, #2575fc, #ff6a00, #ee0979);
        background-size: 800% 800%;
        animation: gradientShift 18s ease infinite;
    }
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* ===== Glassmorphism Form Card ===== */
    form {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 35px;
        max-width: 650px;
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        animation: fadeIn 0.8s ease-in-out;
        color: white;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 30px;
        font-weight: 600;
        color: #fff;
        letter-spacing: 1px;
    }

    /* ===== Input Wrapper with Icons ===== */
    .input-group {
        position: relative;
        margin-top: 15px;
    }

    .input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        opacity: 0.8;
    }

    input, select, textarea {
        width: 100%;
        padding: 14px 14px 14px 45px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        outline: none;
        box-sizing: border-box;
        background: rgba(255,255,255,0.2);
        color: #fff;
        transition: all 0.3s ease;
    }

    input::placeholder, textarea::placeholder {
        color: #ddd;
    }

    input:focus, select:focus, textarea:focus {
        background: rgba(255,255,255,0.3);
        box-shadow: 0 0 10px rgba(255,255,255,0.5);
    }

    button {
        margin-top: 25px;
        padding: 15px;
        width: 100%;
        background: linear-gradient(90deg, #ff512f, #dd2476);
        border: none;
        color: white;
        font-size: 18px;
        font-weight: 600;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s;
    }

    button:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    .status {
        text-align: center;
        margin-bottom: 20px;
        padding: 12px;
        border-radius: 6px;
        font-weight: 500;
    }
    .success {
        background-color: rgba(40, 167, 69, 0.9);
        color: #fff;
    }
    .error {
        background-color: rgba(220, 53, 69, 0.9);
        color: #fff;
    }

    .two-column {
        display: flex;
        gap: 15px;
    }
    .two-column .input-group {
        flex: 1;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 600px) {
        .two-column {
            flex-direction: column;
        }
    }
</style>

<script>
    function validateForm() {
        let firstName = document.getElementById("first_name").value.trim();
        let lastName = document.getElementById("last_name").value.trim();
        let email = document.getElementById("email").value.trim();
        let phone = document.getElementById("phone").value.trim();
        let subject = document.getElementById("subject").value.trim();
        let message = document.getElementById("message").value.trim();

        let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
        let phonePattern = /^[0-9]{7,15}$/;

        if (firstName === "" || lastName === "" || subject === "" || message === "") {
            alert("⚠️ Please fill in all required fields.");
            return false;
        }
        if (!email.match(emailPattern)) {
            alert("⚠️ Please enter a valid email address.");
            return false;
        }
        if (phone !== "" && !phone.match(phonePattern)) {
            alert("⚠️ Please enter a valid phone number (7–15 digits).");
            return false;
        }
        return true;
    }
</script>
</head>
<body>

<?php if (isset($_GET['status'])): ?>
<div class="status <?= $_GET['status'] === 'success' ? 'success' : 'error' ?>">
    <?= $_GET['status'] === 'success' ? '✅ Message sent successfully!' : '❌ Error sending message.' ?>
</div>
<?php endif; ?>

<form action="process_form.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
    <h2>📩 Contact Us</h2>

    <div class="two-column">
        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" id="first_name" name="first_name" placeholder="First Name" required>
        </div>
        <div class="input-group">
            <i class="fa fa-user"></i>
            <input type="text" id="last_name" name="last_name" placeholder="Last Name" required>
        </div>
    </div>

    <div class="input-group">
        <i class="fa fa-envelope"></i>
        <input type="email" id="email" name="email" placeholder="Email Address" required>
    </div>

    <div class="input-group">
        <i class="fa fa-phone"></i>
        <input type="text" id="phone" name="phone" placeholder="Phone Number (Optional)">
    </div>

    <div class="input-group">
        <i class="fa fa-heading"></i>
        <input type="text" id="subject" name="subject" placeholder="Subject" required>
    </div>

    <div class="input-group">
        <i class="fa fa-list"></i>
        <select id="inquiry_type" name="inquiry_type">
            <option value="">-- Select Inquiry Type --</option>
            <option value="General Question">General Question</option>
            <option value="Support">Support</option>
            <option value="Feedback">Feedback</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="input-group">
        <i class="fa fa-comment-dots"></i>
        <textarea id="message" name="message" rows="5" placeholder="Write your message..." required></textarea>
    </div>

    <div class="input-group">
        <i class="fa fa-paperclip"></i>
        <input type="file" id="file" name="file">
    </div>

    <button type="submit"><i class="fa fa-paper-plane"></i> Send Message</button>
</form>

</body>
</html>
