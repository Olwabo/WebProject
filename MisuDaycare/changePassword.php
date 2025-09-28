<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_number = mysqli_real_escape_string($conn, $_POST['id_number']);
    $currentPwd = mysqli_real_escape_string($conn, $_POST['pwd']);
    $newPwd = mysqli_real_escape_string($conn, $_POST['newPassword']);
    $confirmPwd = mysqli_real_escape_string($conn, $_POST['confirmPassword']);

    // Step 1: Check if user exists
    $query = "SELECT * FROM app_users WHERE id_number='$id_number' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Step 2: Verify current password (assuming passwords are hashed in DB)
        if ($currentPwd === $user['pwd']) {
    if ($newPwd === $confirmPwd) {
        // Step 3: Update password directly (no hashing)
        $update = "UPDATE app_users SET pwd='$newPwd' WHERE id_number='$id_number'";
        if (mysqli_query($conn, $update)) {
            $successMsg = "Password updated successfully.";
        } else {
            $errorMsg = "Error updating password: " . mysqli_error($conn);
        }
    } else {
        $errorMsg = "New passwords do not match.";
    }
} else {
    $errorMsg = "Current password is incorrect.";
}

    } else {
        $errorMsg = "No user found with that ID number.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change Password - Misukukhanya DayCare</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4a6fa5;
      --primary-dark: #3a5780;
      --secondary-color: #6d98cb;
      --accent-color: #f8b500;
      --light-color: #f5f7fa;
      --dark-color: #2c3e50;
      --text-color: #333;
      --border-radius: 8px;
      --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --success-color: #28a745;
      --error-color: #dc3545;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      min-height: 100%;
      font-family: 'Roboto', sans-serif;
      background-color: #f0f2f5;
      display: flex;
      justify-content: center;
      padding: 20px 0;
    }

    .container {
      width: 100%;
      max-width: 480px;
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      margin: auto;
    }

    .header {
      background-color: var(--primary-color);
      color: white;
      padding: 1.5rem 1rem;
      text-align: center;
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.5rem;
      flex-wrap: wrap;
    }
    
    .logo {
      width: 50px;
      height: 50px;
      background-color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      color: var(--primary-color);
      font-size: 1.5rem;
      flex-shrink: 0;
    }

    .header h1 {
      font-weight: 600;
      font-size: 1.6rem;
      margin: 0;
      line-height: 1.2;
    }

    .subtitle {
      font-size: 1rem;
      margin-top: 0.3rem;
      font-weight: 400;
      opacity: 0.9;
    }

    .form-title {
      background-color: var(--light-color);
      color: var(--dark-color);
      padding: 1rem;
      text-align: center;
      font-weight: 500;
      font-size: 1.1rem;
      border-bottom: 1px solid #eaeaea;
    }

    .form-content {
      padding: 1.5rem 1rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--dark-color);
    }

    .input-with-icon {
      position: relative;
    }

    .input-with-icon i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #777;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 12px 12px 12px 40px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
    }

    .password-strength {
      margin-top: 5px;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
    }

    .password-strength.weak {
      color: var(--error-color);
    }

    .password-strength.medium {
      color: #ff9800;
    }

    .password-strength.strong {
      color: var(--success-color);
    }

    .buttons {
      display: flex;
      gap: 10px;
      margin-top: 1.5rem;
    }

    .btn {
      flex: 1;
      padding: 12px;
      border: none;
      border-radius: var(--border-radius);
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .btn-primary {
      background-color: var(--primary-color);
      color: white;
    }

    .btn-primary:hover {
      background-color: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-secondary {
      background-color: #6c757d;
      color: white;
    }

    .btn-secondary:hover {
      background-color: #5a6268;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn i {
      margin-right: 8px;
    }

    .note {
      font-size: 0.9rem;
      color: #777;
      margin-top: 1rem;
      text-align: center;
      line-height: 1.5;
    }

    .requirements {
      background-color: var(--light-color);
      border-radius: var(--border-radius);
      padding: 1rem;
      margin-top: 1rem;
      font-size: 0.85rem;
    }

    .requirements h4 {
      margin-bottom: 0.5rem;
      color: var(--dark-color);
    }

    .requirements ul {
      padding-left: 1.2rem;
      color: #555;
    }

    .requirements li {
      margin-bottom: 0.3rem;
    }

    .password-match {
      font-size: 0.85rem;
      margin-top: 5px;
      display: flex;
      align-items: center;
    }

    .password-match.valid {
      color: var(--success-color);
    }

    .password-match.invalid {
      color: var(--error-color);
    }

    .message {
      padding: 10px;
      border-radius: var(--border-radius);
      margin-bottom: 1rem;
      text-align: center;
      font-weight: 500;
    }

    .success-message {
      background-color: #d4edda;
      color: var(--success-color);
      border: 1px solid #c3e6cb;
    }

    .error-message {
      background-color: #f8d7da;
      color: var(--error-color);
      border: 1px solid #f5c6cb;
    }

    @media (max-width: 480px) {
      body, html {
        padding: 10px 0;
      }
      
      .container {
        width: 100%;
        max-width: 100%;
        margin: 0 10px;
      }
      
      .buttons {
        flex-direction: column;
      }
      
      .header h1 {
        font-size: 1.4rem;
      }
      
      .logo {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
      }
    }

    @media (max-width: 350px) {
      .logo-container {
        flex-direction: column;
        text-align: center;
      }
      
      .logo {
        margin-right: 0;
        margin-bottom: 10px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <div class="logo-container">
        <div class="logo">
          <i class="fas fa-child"></i>
        </div>
        <h1>Misukukhanya Daycare</h1>
      </div>
      <div class="subtitle">Change Password</div>
    </div>

    <div class="form-title">
      <i class="fas fa-key"></i> Update Your Password
    </div>

    <div class="form-content">
      <?php if(isset($successMsg)) { ?>
        <div class="message success-message"><?php echo $successMsg; ?></div>
      <?php } ?>
      <?php if(isset($errorMsg)) { ?>
        <div class="message error-message"><?php echo $errorMsg; ?></div>
      <?php } ?>

      <form action="" method="POST" id="passwordForm">
        <div class="form-group">
          <label for="Identity_no">Identification Number</label>
          <div class="input-with-icon">
            <i class="fas fa-id-card"></i>
            <input type="text" id="Identity_no" name="id_number" placeholder="Enter your ID number" required>
          </div>
        </div>

        <div class="form-group">
          <label for="currentPassword">Current Password</label>
          <div class="input-with-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="currentPassword" name="pwd" placeholder="Enter current password" required>
          </div>
        </div>

        <div class="form-group">
          <label for="newPassword">New Password</label>
          <div class="input-with-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required>
          </div>
          <div class="password-strength" id="passwordStrength"></div>
        </div>

        <div class="form-group">
          <label for="confirmPassword">Confirm New Password</label>
          <div class="input-with-icon">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>
          </div>
          <div class="password-match" id="passwordMatch"></div>
        </div>

        <div class="requirements">
          <h4>Password Requirements:</h4>
          <ul>
            <li>At least 8 characters long</li>
            <li>Contains at least one uppercase letter</li>
            <li>Contains at least one number</li>
            <li>Contains at least one special character</li>
          </ul>
        </div>

        <div class="buttons">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Password
          </button>
          <button type="button" class="btn btn-secondary" onclick="window.location.href='login.php'">
            <i class="fas fa-arrow-left"></i> Back to Login
          </button>
        </div>

        <div class="note">Make sure your new password is strong and secure. Keep it confidential.</div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const newPassword = document.getElementById('newPassword');
      const confirmPassword = document.getElementById('confirmPassword');
      const passwordStrength = document.getElementById('passwordStrength');
      const passwordMatch = document.getElementById('passwordMatch');
      
      newPassword.addEventListener('input', checkPasswordStrength);
      confirmPassword.addEventListener('input', checkPasswordMatch);
      
      function checkPasswordStrength() {
        const password = newPassword.value;
        let strength = 'Weak';
        let colorClass = 'weak';
        
        // Check password strength
        if (password.length >= 8) {
          strength = 'Medium';
          colorClass = 'medium';
          
          if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/) && password.match(/[^a-zA-Z0-9]/)) {
            strength = 'Strong';
            colorClass = 'strong';
          }
        }
        
        if (password.length === 0) {
          passwordStrength.innerHTML = '';
          passwordStrength.className = 'password-strength';
        } else {
          passwordStrength.innerHTML = '<i class="fas fa-shield-alt"></i> Password Strength: <span class="${colorClass}">${strength}</span>';
          passwordStrength.className = 'password-strength ${colorClass}';
        }
      }
      
      function checkPasswordMatch() {
        if (confirmPassword.value.length === 0) {
          passwordMatch.innerHTML = '';
          passwordMatch.className = 'password-match';
          return;
        }
        
        if (newPassword.value === confirmPassword.value) {
          passwordMatch.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
          passwordMatch.className = 'password-match valid';
        } else {
          passwordMatch.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
          passwordMatch.className = 'password-match invalid';
        }
      }
      
      // Form validation
      document.getElementById('passwordForm').addEventListener('submit', function(e) {
        if (newPassword.value !== confirmPassword.value) {
          e.preventDefault();
          alert('Passwords do not match. Please correct and try again.');
          confirmPassword.focus();
          return false;
        }
        
        // Check if password meets requirements
        if (newPassword.value.length < 8 || 
            !newPassword.value.match(/[A-Z]/) || 
            !newPassword.value.match(/[0-9]/) || 
            !newPassword.value.match(/[^a-zA-Z0-9]/)) {
          e.preventDefault();
          alert('Password does not meet the requirements. Please ensure it meets all criteria.');
          newPassword.focus();
          return false;
        }
      });
    });
  </script>
</body>
</html>