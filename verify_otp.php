<?php
include "db.php";
$message = "";

if(!isset($_GET['email'])){
    die("Invalid request!");
}
$email = $_GET['email'];

if(isset($_POST['verify'])){
    $otp = implode('', $_POST['otp']);

    $result = $conn->query("SELECT * FROM users 
    WHERE email='$email' AND otp='$otp' AND otp_expiry > NOW()");

    if($result->num_rows > 0){

        $conn->query("UPDATE users 
        SET is_verified=1, otp=NULL, otp_expiry=NULL 
        WHERE email='$email'");

        header("Location: login.php?verified=success");
        exit();

    } else {
        $message = "Invalid or expired OTP!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Verify OTP</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- USE SAME CSS AS REGISTER -->
<link rel="stylesheet" href="register.css">

</head>

<body>

<div class="box">

<h2>Email Verification</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<form method="POST">

<div class="otp-container">

<input type="text" name="otp[]" maxlength="1" required>
<input type="text" name="otp[]" maxlength="1" required>
<input type="text" name="otp[]" maxlength="1" required>
<input type="text" name="otp[]" maxlength="1" required>
<input type="text" name="otp[]" maxlength="1" required>
<input type="text" name="otp[]" maxlength="1" required>

</div>

<button type="submit" name="verify">Verify OTP</button>

</form>

<p>
<a href="login.php">Back to Login</a>
</p>

</div>

<script>

/* Auto move cursor */
const inputs = document.querySelectorAll(".otp-container input");

inputs.forEach((input,index)=>{

input.addEventListener("input",()=>{

if(input.value.length==1 && index < inputs.length-1){
inputs[index+1].focus();
}

});

input.addEventListener("keydown",(e)=>{

if(e.key==="Backspace" && input.value=="" && index>0){
inputs[index-1].focus();
}

});

});

</script>

</body>
</html>