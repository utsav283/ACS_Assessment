<?php
include "db.php";

if(!isset($_GET['token'])){
    die("Invalid request. Token missing.");
}

$token = $_GET['token'];

// Check token validity
$stmt = $conn->prepare("UPDATE users SET password=? WHERE reset_token=?");
$stmt->bind_param("ss",$hashed,$token);
$stmt->execute();
if($result->num_rows == 0){
    die("Invalid or expired link.");
}

$error = "";

if(isset($_POST['reset'])){
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if($password != $confirm){
        $error = "Passwords do not match!";
    } else {
        $hashed = password_hash($password,PASSWORD_DEFAULT);
        $update = $conn->query("UPDATE users SET password='$hashed', reset_token=NULL, token_expiry=NULL WHERE reset_token='$token'");
        if($update){
            header("Location: login.php?reset=success");
            exit();
        } else {
            $error = "Database update failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="reset_password.css">
</head>
<body>
<div class="box">
<h2>Reset Password</h2>
<?php if($error) echo "<p class='message'>$error</p>"; ?>

<form method="POST">
<input type="password" name="password" placeholder="New Password" required>
<input type="password" name="confirm" placeholder="Confirm Password" required>
<button type="submit" name="reset">Update Password</button>
</form>
</div>
</body>
</html>