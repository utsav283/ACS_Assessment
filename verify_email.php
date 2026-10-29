<?php
include "db.php";

if(isset($_GET['token'])){

$token=$_GET['token'];

$result=$conn->query("SELECT * FROM users WHERE verification_token='$token'");

if($result->num_rows>0){

$conn->query("UPDATE users 
SET email_verified=1,
verification_token=NULL
WHERE verification_token='$token'");

echo "Email verified. You can login.";

}else{
echo "Invalid token.";
}

}
?>