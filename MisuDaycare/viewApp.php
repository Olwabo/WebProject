<?php
$conn = mysqli_connect("localhost","root","","misukukhanya");
if(!$conn){
     die("Connection failed: ".mysqli_connect_error()); 
    }


if(isset($_POST['accept'])){
    $id = $_POST['application_id'];

    $res = mysqli_query($conn, "SELECT * FROM applications WHERE id='$id'");
    if($res && mysqli_num_rows($res) > 0){
        $app = mysqli_fetch_assoc($res);

        
        $insert = "INSERT INTO approved_applications
        (child_name,child_dob,age_group,relationship,allergies,birth_certificate,medical_note,guardian_id)
        VALUES
        ('".$app['child_name']."','".$app['child_dob']."','".$app['age_group']."','Child','".$app['allergies']."',
        '".$app['birth_certificate']."','".$app['medical_note']."','".$app['guardian_id']."')";

        if(mysqli_query($conn,$insert)){
            mysqli_query($conn,"DELETE FROM applications WHERE id='$id'");
            echo "<p style='color:green;'>Application accepted successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: ".mysqli_error($conn)."</p>";
        }
    }
}


if(isset($_POST['decline'])){
    $id = $_POST['application_id'];
    $res = mysqli_query($conn, "SELECT * FROM applications WHERE id='$id'");
    if($res && mysqli_num_rows($res) > 0){
        $app = mysqli_fetch_assoc($res);

       
        $message = "Dear guardian, your application for ".$app['child_name']." has been declined.";
        mysqli_query($conn, "INSERT INTO messages (guardian_id, message) VALUES ('".$app['guardian_id']."','$message')");

        mysqli_query($conn,"DELETE FROM applications WHERE id='$id'");
        echo "<p style='color:red;'>Application declined and guardian notified.</p>";
    }
}


$query = "SELECT a.*, u.full_name AS guardian_name, u.email, u.phone_number 
          FROM applications a
          JOIN app_users u ON a.guardian_id = u.id
          ORDER BY a.application_date DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - View Applications</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 0;
}

header {
    background: #4a6fa5; /* main blue */
    color: white;
    padding: 15px;
    text-align: center;
    position: relative;
}

.back-btn {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    background: white;
    color: #4a6fa5;
    padding: 8px 15px;
    border: 2px solid white;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

.back-btn:hover {
    background: #3a5a8a; /* darker blue hover */
    color: white;
}

.container {
    max-width: 900px;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.application-card {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    background: #fafcff; /* light variant */
}

h3, h4 {
    color: #4a6fa5;
}

p {
    margin: 5px 0;
}

.buttons {
    margin-top: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    margin-right: 10px;
    transition: all 0.3s ease;
}

.accept {
    background: #4a6fa5;
    color: white;
}

.accept:hover {
    background: #3a5a8a;
    color: white;
    border: 1px solid #3a5a8a;
}

.decline {
    background: #ccc;
    color: black;
}

.decline:hover {
    background: white;
    color: #d9534f;
    border: 1px solid #d9534f;
}

</style>
</head>
<body>

<header>
    <h1>Admin - View Applications</h1>
</header>

<div class="container">
<?php
if($result && mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        ?>
        <div class="application-card">
            <h3>Child: <?php echo htmlspecialchars($row['child_name']); ?></h3>
            <p><strong>DOB:</strong> <?php echo htmlspecialchars($row['child_dob']); ?></p>
            <p><strong>Age Group:</strong> <?php echo htmlspecialchars($row['age_group']); ?></p>
            <p><strong>Allergies:</strong> <?php echo htmlspecialchars($row['allergies']); ?></p>

            <h4>Guardian Info</h4>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($row['guardian_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($row['phone_number']); ?></p>

            <h4>Documents</h4>
            <?php if(!empty($row['birth_certificate'])) { ?>
                <p>Birth Certificate: <a href="uploads/<?php echo $row['birth_certificate']; ?>" target="_blank">View</a></p>
            <?php } ?>
            <?php if(!empty($row['medical_note'])) { ?>
                <p>Medical Note: <a href="uploads/<?php echo $row['medical_note']; ?>" target="_blank">View</a></p>
            <?php } ?>

            <div class="buttons">
                <form method="POST" action="">
                    <input type="hidden" name="application_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="accept" class="btn accept">Accept</button>
                    <button type="submit" name="decline" class="btn decline">Decline</button>
                </form>
            </div>
        </div>
        <?php
    }
}else{
    echo "<p>No pending applications.</p>";
}
?>
</div>
</body>
</html>
