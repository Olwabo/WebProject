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


if (!isset($_GET['id'])) {
    header("Location: edit_child.php");
    echo('Successfully updated profile');
    exit;
}
$child_id = intval($_GET['id']);


$sql = "SELECT * FROM approved_applications WHERE id = $child_id AND guardian_id = $guardian_id";
$result = mysqli_query($conn, $sql);
$child = mysqli_fetch_assoc($result);

if (!$child) {
    echo "<p style='color:red;'>Child not found or you don’t have permission to edit this record.</p>";
    exit;
}


if (isset($_POST['update_child'])) {
    $child_name      = mysqli_real_escape_string($conn, $_POST['child_name']);
    $child_dob      = mysqli_real_escape_string($conn, $_POST['child_dob']);
    $age_group      = mysqli_real_escape_string($conn, $_POST['age_group']);
    $relationship   = mysqli_real_escape_string($conn, $_POST['relationship']);
    $allergies      = mysqli_real_escape_string($conn, $_POST['allergies']);

    $update = "UPDATE approved_applications 
               SET child_name='$child_name', child_dob='$child_dob', age_group='$age_group', 
                   relationship='$relationship', allergies='$allergies'
               WHERE id = $child_id AND guardian_id = $guardian_id";

    if (mysqli_query($conn, $update)) {
        header("Location: guardiantesting.php?child_updated=1");
        exit;
    } else {
        echo "<p style='color:red;'>Error updating child: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Child Details</title>
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
  <h2>Edit Child Details</h2>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="child_name" class="form-control" value="<?php echo $child['child_name']; ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Date of Birth</label>
      <input type="text" name="child_dob" class="form-control" value="<?php echo $child['child_dob']; ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Age Group</label>
      <select name="age_group" class="form-control" required>
        <option value="Toddler" <?php if ($child['age_group']=="Toddler") echo "selected"; ?>>Toddler</option>
        <option value="Preschool" <?php if ($child['age_group']=="Preschool") echo "selected"; ?>>Preschool</option>
        <option value="Grade R" <?php if ($child['age_group']=="Grade R") echo "selected"; ?>>Grade R</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Relationship</label>
      <input type="text" name="relationship" class="form-control" value="<?php echo $child['relationship']; ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Allergies</label>
      <textarea name="allergies" class="form-control" rows="3"><?php echo $child['allergies']; ?></textarea>
    </div>
    <button type="submit" name="update_child" class="btn btn-primary w-100">Save Changes</button>
    <a href="guardian_dashboard.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
  </form>
</div>

</body>
</html>
