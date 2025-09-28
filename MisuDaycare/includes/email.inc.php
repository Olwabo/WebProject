<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");

if(isset($_POST['email'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM abantu WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        // Generate unique token
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Save token in DB
        mysqli_query($conn, "UPDATE abantu SET reset_token='$token', token_expires='$expires' WHERE email='$email'");

        // Reset link
        $reset_link = "http://yourdomain.com/reset_password.php?token=" . $token;

        // Email content
        $subject = "Password Reset - Misukukhanya DayCare";
        $message = "Hello " . $user['full_name'] . ",\n\n";
        $message .= "Click the link below to reset your password (expires in 1 hour):\n";
        $message .= $reset_link . "\n\n";
        $message .= "If you did not request a password reset, please ignore this email.\n\n";
        $headers = "From: noreply@misukukhanya.com\r\n";

        if(mail($email, $subject, $message, $headers)){
            echo "<p style='color:green; text-align:center;'>A reset link has been sent to your email.</p>";
        } else {
            echo "<p style='color:red; text-align:center;'>Failed to send email. Try again later.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Email not found in our system.</p>";
    }
} else {
    header("Location: ../forgotPassword.php");
    exit();
}
?>
