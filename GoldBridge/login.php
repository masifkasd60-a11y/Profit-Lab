<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Registration Form</title>
    <link rel="stylesheet" href="css\loginpage.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<?php
session_start(); // start session
// MySQL connection
$host = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "logedin_users"; // change this to your database name

$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $username = $_POST['username'] ?? '';
    $password = trim($_POST['password'] ?? '');
   
    // Prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check user
    $sql = "SELECT Pasword FROM users_list WHERE Name = '$username'";
    $result = mysqli_query($conn, $sql);
 
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Compare entered password with database password
        if ($password === $row['Pasword']) {
            // Password matched → Redirect
                  $_SESSION['username'] = $username; // 
            header("Location: myaccount.php");
            exit();
        } else {
            echo "<script>alert('Your username or password does not match');</script>";
        }
    } else {
        echo "<script>alert('Your username or password does not match');</script>";
    }
}
?>

<body>
    <div class="form-container mx-4 px-8 py-10">
        <h1 class="text-3xl font-bold text-white mb-8 text-center">LOG IN</h1>
        <form id="registrationForm" method="POST">
            <div class="mb-6">
                <div class="mb-6">
                <label for="username" class="block text-white text-sm font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" value="" 
                       class="w-full px-4 py-3 rounded-lg input-field" required>
            </div>
                <label for="password" class="block text-white text-sm font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-3 rounded-lg input-field">
            </div>
           <div class="flex flex-col sm:flex-row gap-4 justify-between">
            <button type="submit" id="submitBtn" class="submit-btn text-white px-6 py-3 rounded-lg font-medium transition-colors">
                   Log IN
                </button>
                <a href="" class="text-center" style="color:white; pointer:cursor;"><u>Forget Password</u></a>
                 <a href="index.php" class="text-center" style="color:white; pointer:cursor;"><u>Create Accoutn</u></a>
</div>

        </form>
        </body>
</html>

        