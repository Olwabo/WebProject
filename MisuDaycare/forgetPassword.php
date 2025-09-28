<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Misukukhanya DayCare</title>
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
      max-width: 450px;
      width: 90%;
      background: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      overflow: hidden;
      animation: fadeIn 0.5s ease-out;
      margin: auto;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .header {
      background-color: var(--primary-color);
      color: white;
      padding: 1.5rem;
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
      font-size: 1.8rem;
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
      font-size: 1.2rem;
      border-bottom: 1px solid #eaeaea;
    }

    .form-content {
      padding: 1.5rem;
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

    input[type="email"] {
      width: 100%;
      padding: 12px 12px 12px 40px;
      border: 1px solid #ddd;
      border-radius: var(--border-radius);
      font-size: 1rem;
      transition: border-color 0.3s;
    }

    input[type="email"]:focus {
      border-color: var(--primary-color);
      outline: none;
      box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
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

    .instructions {
      background-color: var(--light-color);
      border-radius: var(--border-radius);
      padding: 1rem;
      margin-top: 1rem;
      font-size: 0.85rem;
    }

    .instructions h4 {
      margin-bottom: 0.5rem;
      color: var(--dark-color);
      display: flex;
      align-items: center;
    }

    .instructions h4 i {
      margin-right: 8px;
      color: var(--primary-color);
    }

    .instructions ul {
      padding-left: 1.2rem;
      color: #555;
    }

    .instructions li {
      margin-bottom: 0.3rem;
    }

    .success-message {
      display: none;
      background-color: #d4edda;
      color: var(--success-color);
      border: 1px solid #c3e6cb;
      border-radius: var(--border-radius);
      padding: 1.5rem;
      margin-top: 1rem;
      text-align: center;
    }

    .success-message i {
      font-size: 2rem;
      margin-bottom: 0.8rem;
    }

    .success-message h3 {
      margin-bottom: 0.8rem;
    }

    @media (max-width: 480px) {
      body, html {
        padding: 10px 0;
      }
      
      .container {
        width: 95%;
        margin: 0 10px;
      }
      
      .buttons {
        flex-direction: column;
      }
      
      .header h1 {
        font-size: 1.5rem;
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
      <div class="subtitle">Password Recovery</div>
    </div>

    <div class="form-title">
      <i class="fas fa-key"></i> Reset Your Password
    </div>

    <div class="form-content">
      <form id="forgotPasswordForm" action="includes/email.inc.php" method="POST">
        <div class="form-group">
          <label for="email">Enter Your Registered Email Address</label>
          <div class="input-with-icon">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="name@example.com" required>
          </div>
        </div>

        <div class="instructions">
          <h4><i class="fas fa-info-circle"></i> Instructions</h4>
          <ul>
            <li>Enter the email address associated with your account</li>
            <li>We will send you a secure link to reset your password</li>
            <li>The reset link will expire in 1 hour for security</li>
            <li>Check your spam folder if you don't see the email</li>
          </ul>
        </div>

        <div class="buttons">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Send Reset Link
          </button>
          <button type="button" class="btn btn-secondary" onclick="window.location.href='login.php'">
            <i class="fas fa-arrow-left"></i> Back to Login
          </button>
        </div>

        <div class="note">We will email you a secure link to reset your password. This link will expire after 1 hour.</div>
      </form>

      <!-- Success message (shown after form submission) -->
      <div class="success-message" id="successMessage">
        <i class="fas fa-check-circle"></i>
        <h3>Reset Link Sent!</h3>
        <p>We've sent a password reset link to your email address. Please check your inbox and follow the instructions.</p>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('forgotPasswordForm');
      const successMessage = document.getElementById('successMessage');
      
      // Simulate form submission (remove this in production)
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show success message
        form.style.display = 'none';
        successMessage.style.display = 'block';
        
        // In a real application, you would submit the form normally
        // form.submit();
      });
      
      // Email validation
      const emailInput = document.getElementById('email');
      emailInput.addEventListener('blur', function() {
        const email = emailInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
          emailInput.style.borderColor = 'var(--error-color)';
        } else {
          emailInput.style.borderColor = '#ddd';
        }
      });
    });
  </script>
</body>
</html>