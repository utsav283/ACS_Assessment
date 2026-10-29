ACS USER REGISTRATION SYSTEM
README FILE


1. PROJECT OVERVIEW
This project is a secure web-based user registration and authentication system. It includes features such as password strength validation, Google reCAPTCHA, email OTP verification, login authentication, and password reset functionality.

The system is developed using PHP, MySQL, HTML, CSS, and JavaScript, following basic cybersecurity best practices.


2. FEATURES
* User Registration with password strength checker
* Google reCAPTCHA integration (bot protection)
* Email OTP verification
* Secure password hashing (bcrypt)
* Login system with authentication
* Forgot Password and Reset Password functionality
* SQL Injection protection using prepared statements


3. REQUIREMENTS
Make sure the following are installed:
* XAMPP / WAMP / MAMP (Apache + MySQL)
* PHP 7.4 or higher
* MySQL Database
* Web Browser (Chrome, Edge, etc.)
* Internet connection (for Google reCAPTCHA)


4. PROJECT SETUP INSTRUCTIONS
Step 1: Move Project Folder
* Copy the project folder "ACS_Assignment" into:
  C:\xampp\htdocs\

Step 2: Start Server
* Open XAMPP Control Panel
* Start Apache
* Start MySQL

Step 3: Create Database
* Open phpMyAdmin (http://localhost/phpmyadmin)
* Create a new database named:
  acs_assignment

Step 4: Create Users Table
Run the following SQL:
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
fullname VARCHAR(100),
email VARCHAR(100) UNIQUE,
password VARCHAR(255),
otp VARCHAR(10),
otp_expiry DATETIME,
is_verified INT DEFAULT 0,
reset_token TEXT,
token_expiry DATETIME
);

Step 5: Configure Database Connection
* Open db.php
* Update if needed:

$host = "localhost";
$username = "root";
$password = "";
$database = "acs_assignment";

Step 6: Setup PHPMailer
* Download PHPMailer
* Place it inside your project folder:
  ACS_Assignment/PHPMailer/

Step 7: Configure Email (Gmail)
* Open register.php and forgot_password.php
* Update:

$mail->Username = "[your-email@gmail.com](mailto:your-email@gmail.com)";
$mail->Password = "your-app-password";

NOTE:
Use Gmail App Password (not your normal password)


5. RUN THE PROJECT
Open browser and go to:
http://localhost/ACS_Assignment/register.php

From here you can:
* Register new user
* Verify OTP via email
* Login to system
* Reset password


6. SECURITY FEATURES IMPLEMENTED
* Password hashing using bcrypt
* SQL Injection prevention using prepared statements
* Google reCAPTCHA for bot prevention
* OTP email verification
* Secure password reset using token


7. NOTES
* Ensure internet connection for reCAPTCHA
* OTP expires in 10 minutes
* Reset password link expires in 1 hour
* Use correct Gmail App Password for email sending
