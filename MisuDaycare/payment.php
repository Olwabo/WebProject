<?php
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");
if(!$conn){ die("Connection failed: ".mysqli_connect_error()); }


if(isset($_POST['submit'])){
    $child_id = intval($_POST['child_id']);
    $guardian_id = intval($_POST['guardian_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);

    $insert = "INSERT INTO payments (child_id, guardian_id, amount, payment_date, payment_method, notes) 
               VALUES ('$child_id', '$guardian_id', '$amount', '$payment_date', '$method', '$notes')";
    
    if(mysqli_query($conn, $insert)){
        echo "<script>alert('Payment recorded successfully!'); window.location='addPayment.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Get all children + guardians
$query = "SELECT a.id AS child_id, a.child_name, u.id AS guardian_id, u.full_name AS guardian_name
          FROM approved_applications a
          JOIN app_users u ON a.guardian_id = u.id
          ORDER BY a.child_name ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Payment</title>
  <style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 0;
}

.container {
    width: 600px;
    margin: 50px auto;
    background: white;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

h2 {
    text-align: center;
    color: #4a6fa5;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

label {
    font-weight: bold;
    color: #4a5568;
}

input, select, textarea {
    padding: 12px 15px;
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
    padding: 14px;
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
    color: white;
    border: none;
}

  </style>
</head>
<body>
  <div class="container">
    <h2>Record a Payment</h2>
    <form method="post">
      <label for="child_id">Select Child:</label>
      <select name="child_id" id="child_id" required onchange="updateGuardian()">
        <option value="">-- Select Child --</option>
        <?php while($row = mysqli_fetch_assoc($result)){ ?>
          <option value="<?php echo $row['child_id']; ?>" data-guardian="<?php echo $row['guardian_id']; ?>" data-guardian-name="<?php echo $row['guardian_name']; ?>">
            <?php echo $row['child_name']; ?> (Guardian: <?php echo $row['guardian_name']; ?>)
          </option>
        <?php } ?>
      </select>

      <label>Guardian:</label>
      <input type="hidden" name="guardian_id" id="guardian_id">
      <input type="text" id="guardian_name" readonly>

      <label>Amount:</label>
      <input type="number" step="0.01" name="amount" required>

      <label>Payment Date:</label>
      <input type="date" name="payment_date" required>

      <label>Payment Method:</label>
      <select name="payment_method" required>
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Other">Other</option>
      </select>

      <label>Notes:</label>
      <textarea name="notes" rows="3"></textarea>

      <button type="submit" name="submit">Save Payment</button>
    </form>
  </div>

  <script>
    function updateGuardian(){
      let select = document.getElementById("child_id");
      let guardianId = select.options[select.selectedIndex].getAttribute("data-guardian");
      let guardianName = select.options[select.selectedIndex].getAttribute("data-guardian-name");
      document.getElementById("guardian_id").value = guardianId;
      document.getElementById("guardian_name").value = guardianName;
    }
  </script>
</body>
</html>
