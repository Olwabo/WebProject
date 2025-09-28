<?php
$conn = mysqli_connect("localhost","root","","misukukhanya");

// ✅ Handle Accept button
if(isset($_POST['accept'])){
    $id = $_POST['application_id'];
    $query = "SELECT * FROM childapply WHERE id = '$id'";
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);

    if($row){
        // Insert only guardian & child details (no documents)
        $insert = "INSERT INTO approved_applications 
        (guardian_name, email, phone, home_address, relationship, child_name, id_number, dob, medical_info) 
        VALUES (
            '".mysqli_real_escape_string($conn, $row['guardian_name'])."',
            '".mysqli_real_escape_string($conn, $row['email'])."',
            '".mysqli_real_escape_string($conn, $row['phone'])."',
            '".mysqli_real_escape_string($conn, $row['home_address'])."',
            '".mysqli_real_escape_string($conn, $row['relationship'])."',
            '".mysqli_real_escape_string($conn, $row['child_name'])."',
            '".mysqli_real_escape_string($conn, $row['id_number'])."',
            '".mysqli_real_escape_string($conn, $row['dob'])."',
            '".mysqli_real_escape_string($conn, $row['medical_info'])."'
        )";

        if(mysqli_query($conn, $insert)){
            // Delete from childapply
            $delete = "DELETE FROM childapply WHERE id = '$id'";
            mysqli_query($conn, $delete);

            // ✅ Send email notification
            $to = $row['email'];
            $subject = "Application Accepted - Misukukhanya Daycare";
            $message = "Dear ".$row['guardian_name'].",\n\n".
                       "We are happy to inform you that your child (".$row['child_name'].") has been accepted into Misukukhanya Daycare.\n\n".
                       "Thank you for trusting us.\n\nBest regards,\nMisukukhanya Daycare Team";
            $headers = "From: daycare@misukukhanya.com";

            // Try to send mail
            if(mail($to, $subject, $message, $headers)){
                echo "<script>alert('Application accepted and guardian notified by email!'); window.location.href=window.location.href;</script>";
            }else{
                echo "<script>alert('Application accepted but email failed to send.'); window.location.href=window.location.href;</script>";
            }
        }else{
            echo "Error inserting: " . mysqli_error($conn);
        }
    }
}

// ✅ Handle Decline button
if(isset($_POST['decline'])){
    $id = $_POST['application_id'];
    $delete = "DELETE FROM childapply WHERE id = '$id'";
    if(mysqli_query($conn, $delete)){
        echo "<script>alert('Application declined and removed.'); window.location.href=window.location.href;</script>";
    }
}

// ✅ Fetch applications
$query = "SELECT * FROM childapply";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Applications - Misukukhanya</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .application { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .buttons { margin-top: 10px; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; }
        .accept { background: green; color: white; }
        .decline { background: red; color: white; }
    </style>
</head>
<body>

<h2>Pending Applications</h2>

<?php while($row = mysqli_fetch_assoc($result)): ?>
<div class="application">
    <p><strong>Guardian:</strong> <?php echo $row['guardian_name']; ?></p>
    <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
    <p><strong>Child:</strong> <?php echo $row['child_name']; ?> (<?php echo $row['dob']; ?>)</p>
    <p><strong>Medical Info:</strong> <?php echo $row['medical_info']; ?></p>

    <div class="buttons">
        <form method="POST" action="">
            <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="accept" class="btn accept">Accept</button>
            <button type="submit" name="decline" class="btn decline">Decline</button>
        </form>
    </div>
</div>
<?php endwhile; ?>

</body>
</html>
