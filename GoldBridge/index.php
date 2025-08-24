<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Registration Form</title>
    <link rel="stylesheet" href="css\loginpage.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
</head>
<body>
    <div class="form-container px-8 py-10">
        <h1 class="text-3xl font-bold text-white mb-8 text-center">Create Account</h1>
        
<?php
function generateCode($conn) {
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $digits = '0123456789';

    do {
        // Generate random letters
        $randomLetters = '';
        for ($i = 0; $i < 2; $i++) {
            $randomLetters .= $letters[rand(0, strlen($letters) - 1)];
        }

        // Generate random digits
        $randomDigits = '';
        for ($i = 0; $i < 3; $i++) {
            $randomDigits .= $digits[rand(0, strlen($digits) - 1)];
        }

        // Merge and shuffle
        $code = str_shuffle($randomLetters . $randomDigits);

        // ✅ Check uniqueness in DB
        $sql = "SELECT 1 FROM users_list WHERE joiningcode = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $exists = mysqli_stmt_num_rows($stmt) > 0;

        mysqli_stmt_close($stmt);

    } while ($exists); // Keep looping until code is unique

    return $code;
}

// ✅ Function to check if username already exists
function usernameExists($conn, $username) {
    $sql = "SELECT 1 FROM users_list WHERE Name = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    return mysqli_stmt_num_rows($stmt) > 0;
}

// DB connection
$host = "localhost";      
$usernamed = "root";       
$password = "";           
$conn = mysqli_connect($host, $usernamed, $password, "logedin_users");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $referral_code = $_POST['referral_code'] ?? '';
    $joiningcode = generateCode($conn);

    $errors = [];

    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (usernameExists($conn, $username)) {   // ✅ Check if username already exists
        $errors[] = "The username '$username' is already taken. Please choose another one.";
    } elseif (strpos($username, ' ') !== false) {  // ✅ Check for space character
    $errors[] = "Username should not contain spaces.";
}

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        $sql = "INSERT INTO users_list (`sr No`, `Name`, `Email`, `Pasword`, `ref_code`, `joiningcode`) 
                VALUES (NULL, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $password, $referral_code, $joiningcode);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: myaccount.php");
            exit();
        } else {
            echo "Database error: " . mysqli_error($conn);
        }
    } else {
        echo '<div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">';
        echo '<p class="font-bold">Please correct the following errors:</p>';
        echo '<ul class="list-disc pl-5">';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
    }
}
?>

        
        <form id="registrationForm" method="POST">
            <div class="mb-6">
                <label for="username" class="block text-white text-sm font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" 
                       class="w-full px-4 py-3 rounded-lg input-field" required>
            </div>
            
            <div class="mb-6">
                <label for="email" class="block text-white text-sm font-medium mb-2">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                       class="w-full px-4 py-3 rounded-lg input-field" required>
            </div>
            
            <div class="mb-6">
                <label for="password" class="block text-white text-sm font-medium mb-2">Password</label>
                <input type="password" id="password" name="password" 
                       class="w-full px-4 py-3 rounded-lg input-field" required
                       onkeyup="validatePassword()">
                <p id="passwordHelp" class="mt-1 text-sm text-white opacity-80">
                    Password must be at least 8 characters with 1 number and 1 special character.
                </p>
            </div>
            
            <div class="mb-6">
                <label for="confirm_password" class="block text-white text-sm font-medium mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       class="w-full px-4 py-3 rounded-lg input-field" required
                       onkeyup="checkPasswordMatch()">
                <p id="confirmHelp" class="mt-1 text-sm text-white opacity-80"></p>
            </div>
            
            <div class="mb-6">
                <label for="referral_code" class="block text-white text-sm font-medium mb-2">Referral Code (optional)</label>
                <input type="text" id="referral_code" name="referral_code" value="<?php echo htmlspecialchars($referral_code ?? ''); ?>" 
                       class="w-full px-4 py-3 rounded-lg input-field">
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
               <span class="btnstyle"> <button type="submit" id="submitBtn" class="submit-btn text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Register
                </button>
                <button type="reset" class="reset-btn text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    Clear Form
                </button></span>
                
            </div><a href="login.php" class="loginlink">Already Have a Account (Log_IN)</a>
           
        </form>
    </div>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const passwordHelp = document.getElementById('passwordHelp');
            const submitBtn = document.getElementById('submitBtn');
            
            // Password requirements:
            const minLength = 8;
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            let message = '';
            
            if (password.length < minLength) {
                message = 'Password must be at least ' + minLength + ' characters long. ';
            }
            
            if (!hasNumber) {
                message += 'Include at least one number. ';
            }
            
            if (!hasSpecialChar) {
                message += 'Include at least one special character.';
            }
            
            if (password.length >= minLength && hasNumber && hasSpecialChar) {
                passwordHelp.textContent = 'Password meets requirements ✔';
                passwordHelp.style.color = '#4ade80';
            } else {
                passwordHelp.textContent = message;
                passwordHelp.style.color = '#f87171';
            }
        }
        
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const confirmHelp = document.getElementById('confirmHelp');
            
            if (password && confirmPassword) {
                if (password === confirmPassword) {
                    confirmHelp.textContent = 'Passwords match ✔';
                    confirmHelp.style.color = '#4ade80';
                } else {
                    confirmHelp.textContent = 'Passwords do not match ✖';
                    confirmHelp.style.color = '#f87171';
                }
            } else {
                confirmHelp.textContent = '';
            }
        }
        
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Validate password strength
            const minLength = 8;
            const hasNumber = /\d/.test(password);
            const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            if (password.length < minLength || !hasNumber || !hasSpecialChar) {
                event.preventDefault();
                alert('Please ensure your password meets all requirements.');
                return false;
            }
            
            // Check password match
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Passwords do not match.');
                return false;
            }
        });
    </script>
</body>
</html>
