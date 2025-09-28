<?php
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");

// Show reset form if token is valid
if(isset($_GET['token'])){
    $token = $_GET['token'];
    $query = "SELECT * FROM abantu WHERE reset_token='$token' AND token_expires > NOW() LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        ?>

        <!DOCTYPE html>
        <html>
        <head>
            <title>Reset Password</title>
        </head>
        <body>
            <h2>Reset Password for <?php echo $user['email']; ?></h2>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <label>New Password:</label>
                <input type="password" name="password" required><br>
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required><br>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        </body>
        </html>

        <?php
    } else {
        echo "<p style='color:red;'>Invalid or expired token.</p>";
    }
}

// Handle password reset
if(isset($_POST['reset_password'])){
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password){
        echo "<p style='color:red;'>Passwords do not match.</p>";
        exit();
    }

    $hashed_pwd = password_hash($password, PASSWORD_DEFAULT);
    mysqli_query($conn, "UPDATE abantu SET pwd='$hashed_pwd', reset_token=NULL, token_expires=NULL WHERE reset_token='$token'");
    echo "<p style='color:green;'>Password reset successful. <a href='login.php'>Login now</a>.</p>";
}
?>
