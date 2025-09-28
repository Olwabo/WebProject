<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != "Guardian") {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "misukukhanya");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$guardian_id = $_SESSION['user_id'];


$sql = "SELECT * FROM app_users WHERE id = $guardian_id";
$result = mysqli_query($conn, $sql);
$guardian = mysqli_fetch_assoc($result);


if (isset($_POST['update_guardian'])) {
    $full_name   = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $phone       = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $id_number   = mysqli_real_escape_string($conn, $_POST['id_number']);

    $update = "UPDATE app_users 
               SET full_name='$full_name', email='$email', phone_number='$phone', id_number='$id_number' 
               WHERE id = $guardian_id";

    if (mysqli_query($conn, $update)) {
        $_SESSION['user_name'] = $full_name;
        header("Location: edit_guardian.php?updated=1");
        exit;
    } else {
        echo "<p style='color:red;'>Error updating profile: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Guardian Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { 
        background: #f5f7fa; 
        font-family: 'Segoe UI', sans-serif; 
        margin: 0; 
        padding: 0; 
    }

    .container { 
        max-width: 650px; 
        margin: 40px auto; 
        background: #fff; 
        padding: 20px; 
        border-radius: 10px; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
    }

    h2 { 
        color: #4a6fa5; /* updated to match main blue */
        text-align: center; 
        margin-bottom: 20px; 
    }

    .btn-primary { 
        background: #4a6fa5; /* main blue */
        border: none; 
        color: white; 
        font-weight: 500; 
        padding: 10px 16px; 
        border-radius: 6px; 
        cursor: pointer; 
        transition: background 0.3s ease; 
    }

    .btn-primary:hover { 
        background: #3a5a8a; /* darker hover blue */
    }
</style>
</head>
<body>

<div class="container">
  <h2>Edit My Profile</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="full_name" class="form-control" value="<?php echo $guardian['full_name']; ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="<?php echo $guardian['email']; ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Phone Number</label>
      <input type="text" name="phone_number" class="form-control" value="<?php echo $guardian['phone_number']; ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">ID Number</label>
      <input type="text" name="id_number" class="form-control" value="<?php echo $guardian['id_number']; ?>" required>
    </div>
    <button type="submit" name="update_guardian" class="btn btn-primary w-100">Save Changes</button>
    <a href="guardian_dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
  </form>
</div>

</body>
</html>
