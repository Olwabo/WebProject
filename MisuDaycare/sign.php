<?php
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName   = mysqli_real_escape_string($conn, $_POST['full_name']);
    $userRole   = mysqli_real_escape_string($conn, $_POST['user_role']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $pwd        = $_POST['pwd'];
    $confirmPassword = $_POST['confirmPassword'];
    $idNumber   = mysqli_real_escape_string($conn, $_POST['id_number']);

    // Passwords match check
    if ($pwd !== $confirmPassword) {
        $error = "Passwords do not match!";
    } else {
        // Check for duplicates
        $checkQuery = "SELECT * FROM app_users WHERE email='$email' OR phone_number='$phone' OR id_number='$idNumber'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['phone_number'] === $phone) {
                    $error = "Phone number already exists!";
                } elseif ($row['email'] === $email) {
                    $error = "Email already exists!";
                } elseif ($row['id_number'] === $idNumber) {
                    $error = "ID number already exists!";
                }
            }
        } else {
            // Hash password for security
            //$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

            $sql = "INSERT INTO app_users (full_name, user_role, phone_number, email, id_number, pwd) 
                    VALUES ('$fullName', '$userRole', '$phone', '$email', '$idNumber', '$pwd')";

            if (mysqli_query($conn, $sql)) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account - Misukukhanya DayCare</title>
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body, html {
        min-height: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        justify-content: center;
        padding: 20px 0;
    }
    
    .container {
        width: 100%;
        max-width: 480px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin: auto;
    }
    
    .header {
        background: #4a6fa5;
        color: white;
        padding: 25px 20px;
        text-align: center;
    }
    
    .logo {
        font-size: 2.2rem;
        margin-bottom: 10px;
        color: #ffd166;
    }
    
    .header h1 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .header p {
        opacity: 0.9;
        font-size: 0.95rem;
    }
    
    .form-container {
        padding: 30px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.95rem;
    }
    
    .required::after {
        content: " *";
        color: #e53e3e;
    }
    
    input[type="text"], 
    input[type="email"], 
    input[type="password"], 
    select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }
    
    input[type="text"]:focus, 
    input[type="email"]:focus, 
    input[type="password"]:focus, 
    select:focus {
        outline: none;
        border-color: #4a6fa5;
        box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.1);
        background-color: white;
    }
    
    .password-strength {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        margin-top: 5px;
        overflow: hidden;
    }
    
    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: width 0.3s ease, background-color 0.3s ease;
    }
    
    .password-weak { background-color: #e53e3e; width: 33%; }
    .password-medium { background-color: #d69e2e; width: 66%; }
    .password-strong { background-color: #38a169; width: 100%; }
    
    .password-hints {
        font-size: 0.8rem;
        color: #718096;
        margin-top: 5px;
    }
    
    .error {
        color: #e53e3e;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 15px;
        padding: 10px;
        background-color: #fed7d7;
        border-radius: 6px;
        border-left: 4px solid #e53e3e;
    }
    
    .success {
        color: #38a169;
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f0fff4;
        border-radius: 6px;
        border-left: 4px solid #38a169;
    }
    
    .submit-btn {
        width: 100%;
        padding: 14px;
        background: #4a6fa5;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
    }
    
    .submit-btn:hover {
        background: #3a5a8a;
    }
    
    .login-link {
        text-align: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
    }
    
    .login-link a {
        color: #4a6fa5;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .login-link a:hover {
        color: #3a5a8a;
        text-decoration: underline;
    }
    
    .footer {
        text-align: center;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #e2e8f0;
        color: #718096;
        font-size: 0.85rem;
    }
    
    @media (max-width: 480px) {
        body, html {
            padding: 10px 0;
        }
        
        .container {
            margin: 0;
        }
        
        .form-container {
            padding: 20px;
        }
        
        .header {
            padding: 20px;
        }
        
        .header h1 {
            font-size: 1.3rem;
        }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
        <div class="logo">🧸</div>
        <h1>Create Your Account</h1>
        <p>Misukukhanya DayCare Management System</p>
    </div>
    
    <div class="form-container">
        <?php if (!empty($error)) { echo "<div class='error'>$error</div>"; } ?>
        <?php if (!empty($success)) { echo "<div class='success'>$success</div>"; } ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="fullName" class="required">Full Name</label>
                <input type="text" id="fullName" name="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required placeholder="Enter your full name">
            </div>

            <div class="form-group">
                <label for="userRole" class="required">Register As</label>
                <select id="userRole" name="user_role" required>
                    <option value="">-- Select Role --</option>
                    <option value="Admin" <?php echo (isset($_POST['user_role']) && $_POST['user_role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="Staff" <?php echo (isset($_POST['user_role']) && $_POST['user_role'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                    <option value="Guardian" <?php echo (isset($_POST['user_role']) && $_POST['user_role'] == 'Guardian') ? 'selected' : ''; ?>>Guardian</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phoneNumber" class="required">Phone Number</label>
                <input type="text" id="phoneNumber" name="phone_number" value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" required placeholder="Enter your phone number">
            </div>

            <div class="form-group">
                <label for="email" class="required">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required placeholder="Enter your email address">
            </div>

            <div class="form-group">
                <label for="idNumber" class="required">ID Number</label>
                <input type="text" id="idNumber" name="id_number" value="<?php echo isset($_POST['id_number']) ? htmlspecialchars($_POST['id_number']) : ''; ?>" required placeholder="Enter your ID number">
            </div>

            <div class="form-group">
                <label for="password" class="required">Create Password</label>
                <input type="password" id="password" name="pwd" required placeholder="Create a secure password">
                <div class="password-strength">
                    <div class="password-strength-bar" id="passwordStrengthBar"></div>
                </div>
                <div class="password-hints" id="passwordHints">Use 8+ characters with letters, numbers, and symbols</div>
            </div>

            <div class="form-group">
                <label for="confirmPassword" class="required">Confirm Password</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required placeholder="Confirm your password">
                <div id="passwordMatch" style="font-size: 0.8rem; margin-top: 5px;"></div>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
  </div>

  <script>
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const passwordHints = document.getElementById('passwordHints');
    const confirmPasswordInput = document.getElementById('confirmPassword');
    const passwordMatch = document.getElementById('passwordMatch');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let hints = [];

        // Length check
        if (password.length >= 8) strength++;
        else hints.push('Use at least 8 characters');

        // Lowercase check
        if (/[a-z]/.test(password)) strength++;
        else hints.push('Include lowercase letters');

        // Uppercase check
        if (/[A-Z]/.test(password)) strength++;
        else hints.push('Include uppercase letters');

        // Numbers check
        if (/[0-9]/.test(password)) strength++;
        else hints.push('Include numbers');

        // Special characters check
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        else hints.push('Include special characters');

        // Update strength bar
        strengthBar.className = 'password-strength-bar';
        if (password.length === 0) {
            strengthBar.style.width = '0%';
            passwordHints.textContent = 'Use 8+ characters with letters, numbers, and symbols';
        } else if (strength <= 2) {
            strengthBar.classList.add('password-weak');
            passwordHints.textContent = hints.length > 0 ? hints.join(', ') : 'Weak password';
        } else if (strength <= 4) {
            strengthBar.classList.add('password-medium');
            passwordHints.textContent = 'Medium strength password';
        } else {
            strengthBar.classList.add('password-strong');
            passwordHints.textContent = 'Strong password!';
        }
    });

    // Password confirmation check
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;

        if (confirmPassword.length === 0) {
            passwordMatch.textContent = '';
            passwordMatch.style.color = '';
        } else if (password === confirmPassword) {
            passwordMatch.textContent = '✓ Passwords match';
            passwordMatch.style.color = '#38a169';
        } else {
            passwordMatch.textContent = '✗ Passwords do not match';
            passwordMatch.style.color = '#e53e3e';
        }
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password !== confirmPassword) {
            e.preventDefault();
            passwordMatch.textContent = 'Please make sure passwords match before submitting';
            passwordMatch.style.color = '#e53e3e';
            confirmPasswordInput.focus();
        }

        if (password.length < 8) {
            e.preventDefault();
            passwordHints.textContent = 'Password must be at least 8 characters long';
            passwordHints.style.color = '#e53e3e';
            passwordInput.focus();
        }
    });
  </script>
</body>
</html>