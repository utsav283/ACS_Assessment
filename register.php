<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include "db.php";

date_default_timezone_set('Asia/Kathmandu');

$message = "";

if(isset($_POST['register'])){

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    $secretKey = "6LdyWoMsAAAAAJNpi4shWcIZSzr4Km899T4d8NT9";
    $responseKey = $_POST['g-recaptcha-response'];
    $userIP = $_SERVER['REMOTE_ADDR'];

    $verify = file_get_contents(
        "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP"
    );

    $response = json_decode($verify);

    if(!$response->success){
        $message = "Please verify that you are not a robot.";
    }
    else {

        // Password match check
        if($password != $confirm){
            $message = "Passwords do not match!";
        }
        else {
            // Password strength check
            $strength = "Weak";
            $tips = [];
            if(strlen($password) < 8){
                $tips[] = "At least 8 characters.";
            }
            if(!preg_match("/[A-Z]/", $password)){
                $tips[] = "Include at least 1 uppercase letter.";
            }
            if(!preg_match("/[a-z]/", $password)){
                $tips[] = "Include at least 1 lowercase letter.";
            }
            if(!preg_match("/[0-9]/", $password)){
                $tips[] = "Include at least 1 number.";
            }
            if(!preg_match("/[\W]/", $password)){
                $tips[] = "Include at least 1 symbol.";
            }

            if(empty($tips)){
                $strength = "Strong";
            } elseif(count($tips) <= 2){
                $strength = "Medium";
            }

            if(!empty($tips)){
                $message = "Password Strength: $strength. Tips: " . implode(" ", $tips);
            }

            if($strength === "Weak"){
                // Do not allow weak passwords
            } else {

                // Check duplicate email
                $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $check = $stmt->get_result();
                if($check->num_rows > 0){
                    $message = "Email already registered!";
                }
                else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $otp = rand(100000,999999);

                    $conn->query("INSERT INTO users (fullname,email,password,otp,otp_expiry)
                                  VALUES ('$fullname','$email','$hashed_password','$otp', NOW() + INTERVAL 10 MINUTE)");

                    // Send OTP Email
                    $mail = new PHPMailer(true);
                    try{
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'utsavbudhathoki11@gmail.com';
                        $mail->Password = 'etju ofol lxjm slvv';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        $mail->setFrom('utsavbudhathoki11@gmail.com', 'ACS Project');
                        $mail->addAddress($email);

                        $mail->isHTML(true);
                        $mail->Subject = 'Your OTP Verification Code';
                        $mail->Body = "Hello $fullname,<br><br>Your OTP is: <b>$otp</b><br>It expires in 10 minutes.";

                        $mail->send();

                        header("Location: verify_otp.php?email=$email");
                        exit();
                    } catch (Exception $e){
                        $message = "Mailer Error: " . $mail->ErrorInfo;
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Register | ACS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="register.css">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
// Live password strength feedback
function checkPasswordStrength(){
    const password = document.getElementById("password").value;
    let strength = "Weak";
    let tips = [];

    if(password.length < 8) tips.push("At least 8 characters.");
    if(!/[A-Z]/.test(password)) tips.push("1 uppercase letter.");
    if(!/[a-z]/.test(password)) tips.push("1 lowercase letter.");
    if(!/[0-9]/.test(password)) tips.push("1 number.");
    if(!/[\W]/.test(password)) tips.push("1 symbol.");

    if(tips.length === 0) strength = "Strong";
    else if(tips.length <=2) strength = "Medium";

    const strengthBox = document.getElementById("strength");
    strengthBox.textContent = "Strength: " + strength + (tips.length ? ". Tips: " + tips.join(" ") : "");
}
</script>
</head>

<body>
<div class="box">
<h2>User Registration</h2>
<?php if($message) echo "<p class='message'>$message</p>"; ?>
<form method="POST">
<input type="text" name="fullname" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<input type="password" id="password" name="password" placeholder="Password" required oninput="checkPasswordStrength()">
<input type="password" name="confirm_password" placeholder="Confirm Password" required>
<div id="strength" style="margin-bottom:10px;color:#ff5722;font-weight:bold;"></div>

<div class="g-recaptcha" data-sitekey="6LdyWoMsAAAAAJ8GF2dmcQ-dVopNqIH6CDdtUmch"></div>
<br>
<button type="submit" name="register">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>