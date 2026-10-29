<?php
session_start();
include "db.php";

$message = "";

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){

$row = $result->fetch_assoc();

/* Check email verification */
if($row['is_verified'] == 0){
$message = "Please verify your email first.";
}

else{

if(password_verify($password,$row['password'])){

$_SESSION['user'] = $row['fullname'];

/* redirect after login */
header("Location: dashboard.php");
exit();

}else{
$message = "Wrong password.";
}

}

}else{
$message = "User not found.";
}

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Login | ACS</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="login.css">

</head>

<body>

<form method="POST" class="login-box">

<h2>Login</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<input type="submit" name="login" value="Login">

<div class="links">

<a href="forgot_password.php">Forgot Password?</a>

<a href="register.php">Don't have an account? Register</a>

</div>

</form>

</body>

</html>