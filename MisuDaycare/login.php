<?php
session_start();


$conn = mysqli_connect("localhost", "root", "", "misukukhanya");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['pwd'];

    
    $sql = "SELECT * FROM app_users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        
        if ($password === $user['pwd']) { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['user_role'];

           
            if ($user['user_role'] == "Admin") {
                header("Location: admin.php");
            } elseif ($user['user_role'] == "Staff") {
                header("Location: staff_dashboard.php");
            } elseif ($user['user_role'] == "Guardian") {
                header("Location: guardian_dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No account found with that email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Misukukhanya DayCare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin: 20px;
        }
        
        .header {
            background: #4a6fa5;
            color: white;
            padding: 25px 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .logo {
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: #ffd166;
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
        
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }
        
        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #4a6fa5;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.1);
            background-color: white;
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
        
        .login-btn {
            width: 100%;
            padding: 14px;
            background: #4a6fa5;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        
        .login-btn:hover {
            background: #3a5a8a;
        }
        
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .link-btn {
            background: none;
            border: none;
            color: #4a6fa5;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 8px 5px;
            transition: color 0.3s ease;
            text-decoration: underline;
        }
        
        .link-btn:hover {
            color: #3a5a8a;
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
            .container {
                margin: 10px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .links {
                flex-direction: column;
                align-items: center;
            }
            
            .link-btn {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🧸</div>
            <h1>Misukukhanya DayCare</h1>
        </div>
        
        <div class="form-container">
            <?php if (!empty($error)) { echo "<div class='error'>$error</div>"; } ?>
            
            <form method="post" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="pwd">Password</label>
                    <input type="password" id="pwd" name="pwd" required placeholder="Enter your password">
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>
            
            <div class="links">
                <button type="button" class="link-btn" onclick="window.location.href='sign.php'">Create Account</button>
                <button type="button" class="link-btn" onclick="window.location.href='forgetPassword.php'">Forgot Password?</button>
                <button type="button" class="link-btn" onclick="window.location.href='changePassword.php'">Change Password</button>
            </div>
            
            <div class="footer">
                &copy; <?php echo date('Y'); ?> Misukukhanya DayCare. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>