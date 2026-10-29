<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include "db.php";

// Set timezone
date_default_timezone_set('Asia/Kathmandu');

$message = "";

if(isset($_POST['forgot'])) {
    $email = trim($_POST['email']);

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 0){
        $message = "Email not found!";
    } else {
        // Generate token
        $token = bin2hex(random_bytes(32)); // 64-character token
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // valid 10 minutes

        // Save token in DB
        $conn->query("UPDATE users SET reset_token='$token', token_expiry='$expiry' WHERE email='$email'");

        // Send email via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = ''; // your Gmail
            $mail->Password = '';    // Gmail App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('Your_Gmail.com', 'ACS Project');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $link = "http://localhost/ACS_Assingment/reset_password.php?token=$token";
            $mail->Body = "Hello,<br><br>Click this link to reset your password:<br>
            <a href='$link'>$link</a><br><br>This link expires in 10 minutes.";

            $mail->send();
            $message = "Reset link has been sent to your email!";
        } catch (Exception $e) {
            $message = "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="forgot_password.css">
</head>
<body>
<div class="box">
<h2>Forgot Password</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<form method="POST">
<input type="email" name="email" placeholder="Enter your email" required>
<button type="submit" name="forgot">Send Reset Link</button>
</form>
<br>
<a href="login.php">Back to Login</a>
</div>
</body>
</html>