<?php
session_start();

/* Protect page */
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>

<head>

<title>Dashboard | ACS</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="dashboard.css">

</head>

<body>

<!-- Navbar -->
<div class="navbar">

    <h2>ACS Dashboard</h2>

    <a class="logout-btn" href="logout.php">Logout</a>

</div>


<!-- Main dashboard -->
<div class="dashboard">

    <div class="card">

        <h1>Welcome, <?php echo $user; ?> 👋</h1>

        <p>
        You have successfully logged into the system.
        This is your personal dashboard where you can manage your account.
        </p>

    </div>

</div>

</body>

</html>