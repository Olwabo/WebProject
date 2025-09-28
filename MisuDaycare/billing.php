<?php
session_start();

// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != "Admin") {
    header("Location: login.php");
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "misukukhanya");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Insert billing record
if (isset($_POST['add_billing'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $monthly_fee = mysqli_real_escape_string($conn, $_POST['monthly_fee']);
    $registration_fee = mysqli_real_escape_string($conn, $_POST['registration_fee']);

    if (!empty($category) && !empty($monthly_fee) && !empty($registration_fee)) {
        $insert = "INSERT INTO billing (category, monthly_fee, registration_fee)
                   VALUES ('$category','$monthly_fee','$registration_fee')";
        if (mysqli_query($conn, $insert)) {
            echo "<p style='color:green;'>Billing record added successfully!</p>";
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill in all fields!</p>";
    }
}

// Update billing record
if (isset($_POST['update_billing'])) {
    $id = intval($_POST['id']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $monthly_fee = mysqli_real_escape_string($conn, $_POST['monthly_fee']);
    $registration_fee = mysqli_real_escape_string($conn, $_POST['registration_fee']);

    $update = "UPDATE billing SET category='$category', monthly_fee='$monthly_fee', registration_fee='$registration_fee' WHERE id=$id";
    if (mysqli_query($conn, $update)) {
        echo "<p style='color:green;'>Billing record updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

// Delete billing record
if (isset($_POST['delete_billing'])) {
    $id = intval($_POST['id']);
    $delete = "DELETE FROM billing WHERE id=$id";
    if (mysqli_query($conn, $delete)) {
        echo "<p style='color:green;'>Billing record deleted successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    }
}

// Fetch billing records
$billing_records = mysqli_query($conn, "SELECT * FROM billing ORDER BY category ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Billing Management</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 20px;
}

.container {
    max-width: 900px;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

h2 {
    text-align: center;
    color: #4a6fa5;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    display: block;
    font-weight: bold;
    color: #4a5568;
}

input, select, textarea {
    padding: 12px 15px;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 1rem;
    background-color: #f8fafc;
    transition: all 0.3s ease;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #4a6fa5;
    box-shadow: 0 0 0 3px rgba(74,111,165,0.1);
    background-color: white;
}

button {
    margin-top: 10px;
    padding: 10px;
    background: #4a6fa5;
    color: white;
    font-weight: bold;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background: #3a5a8a;
}

table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
}

th, td {
    border: 1px solid #e2e8f0;
    padding: 10px;
    text-align: center;
}

th {
    background: #4a6fa5;
    color: white;
}

tr:hover {
    background: #f1f1f1;
}

.edit-form input, .edit-form select {
    width: 100px;
    padding: 5px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}
</style>
</head>
<body>

<h2>Billing Management</h2>

<!-- Add Billing Form -->
<form method="post" action="">
    <label>Category:</label>
    <select name="category" required>
        <option value="">-- Select Category --</option>
        <option value="Infant">Infant</option>
        <option value="Toddler">Toddler</option>
        <option value="Preschool">Preschool</option>
    </select>

    <label>Monthly Fee:</label>
    <input type="number" step="0.01" name="monthly_fee" required>

    <label>Registration Fee:</label>
    <input type="number" step="0.01" name="registration_fee" required>

    <button type="submit" name="add_billing">Add Billing</button>
</form>

<!-- Display Billing Records -->
<h3>Existing Billing Records</h3>
<table>
    <tr>
        <th>Category</th>
        <th>Monthly Fee</th>
        <th>Registration Fee</th>
        <th>Actions</th>
    </tr>
    <?php if (mysqli_num_rows($billing_records) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($billing_records)): ?>
        <tr>
            <td><?php echo $row['category']; ?></td>
            <td>R <?php echo number_format($row['monthly_fee'], 2); ?></td>
            <td>R <?php echo number_format($row['registration_fee'], 2); ?></td>
            <td>
                <form method="post" action="" class="edit-form" style="display:inline-block;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <select name="category">
                        <option value="Infant" <?php if($row['category']=='Infant') echo 'selected'; ?>>Infant</option>
                        <option value="Toddler" <?php if($row['category']=='Toddler') echo 'selected'; ?>>Toddler</option>
                        <option value="Preschool" <?php if($row['category']=='Preschool') echo 'selected'; ?>>Preschool</option>
                    </select>
                    <input type="number" step="0.01" name="monthly_fee" value="<?php echo $row['monthly_fee']; ?>">
                    <input type="number" step="0.01" name="registration_fee" value="<?php echo $row['registration_fee']; ?>">
                    <button type="submit" name="update_billing">Update</button>
                </form>

                <form method="post" action="" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_billing" style="background:#e53e3e;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="4">No billing records found.</td></tr>
    <?php endif; ?>
</table>

</body>
</html>
