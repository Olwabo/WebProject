<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Staff</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #8e6cc6;
      color: #333;
      margin: 0;
      padding: 0;
    }

    header {
      background: #8e6cc6;
      padding: 1rem;
      text-align: center;
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .container {
      max-width: 1100px;
      margin: 20px auto;
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      color: #444;
    }

    form {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 15px;
      margin-bottom: 20px;
    }

    input, select, button {
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    button {
      background: #8e6cc6;
      color: white;
      border: none;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: white;
      color: #8e6cc6;
      border: 1px solid #8e6cc6;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background: #8e6cc6;
      color: white;
    }

    tr:hover {
      background: #f1f1f1;
    }

    .actions button {
      padding: 5px 10px;
      font-size: 12px;
      margin-right: 5px;
    }

    .edit-btn {
      background: #8e6cc6;
      color: white;
    }

    .edit-btn:hover {
      background: white;
      color: #8e6cc6;
      border: 1px solid #8e6cc6;
    }

    .delete-btn {
      background: #8e6cc6;
      color: white;
    }

    .delete-btn:hover {
      background: white;
      color: #8e6cc6;
      border: 1px solid #8e6cc6;
    }
     .bg-image {
      background-image: url(images\kiddies.jpg); 
      filter: blur(4px);
      height: 100%;
      background-position: center;
      background-size: cover;
      position: absolute;
      width: 100%;
      z-index: -1;
    }
  </style>
</head>
<body>
<img src="images/kiddies.jpg" class="bg-image" alt="Background Image">
  <header>Admin - Manage Staff</header>

  <div class="container">
    <h2>Add / Update Staff</h2>
    <form action="includes/staffUp.inc.php" method="post">
      <input type="text" id="name" placeholder="Full Name" name="full_name" required>
      <input type="text" id="idNumber" placeholder="ID Number" name="id_number" required>
      <input type="email" id="email" placeholder="Email" name="email" required>
      <input type="text" id="phone" placeholder="Phone Number" name="phone_number" required>
      <select id="role" name="u_role" required>
        <option value="">Select Role</option>
        <option>Teacher</option>
        <option>Assistant</option>
        <option>Cleaner</option>
        <option>Security</option>
      </select>
      <button type="submit">Save Staff</button>
    </form>

    <h2>Staff List</h2>
    <table>
      <thead>
        <tr>
          <th>Full Name</th>
          <th>ID Number</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="staffTable">
    <?php
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");
$query = "SELECT * FROM staffUp";
$result = mysqli_query($conn, $query);
if(mysqli_num_rows($result) > 0){
    foreach($result as $row){
        ?>
        <tr>
          <td><?php echo $row['full_name']; ?></td>
          <td><?php echo $row['id_number']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['phone_number']; ?></td>
          <td><?php echo $row['u_role']; ?></td>
          <td class="actions">
            <button class="edit-btn">Edit</button>
            <button class="delete-btn">Delete</button>
            </td>
        </tr>
        <?php
    
    }
}else{
    echo "<p>No staff found.</p>";
}
?>
  </div>
<h3>Update Staff</h3>
<form action="includes/updateStaff.inc.php" method="post">
      <input type="text" id="idNumber" placeholder="ID Number" name="id_number" required>
      <input type="email" id="email" placeholder="New Email" name="email" required>
      <input type="text" id="phone" placeholder="New Phone Number" name="phone_number" required>
      <select id="role" name="u_role" required>
        <option value="">Select New Role</option>
        <option>Teacher</option>
        <option>Assistant</option>
        <option>Cleaner</option>
        <option>Security</option>
      </select>
    </form>
      <button type="submit">Update Staff</button>
    
</body>
</html>
