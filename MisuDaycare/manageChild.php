<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Manage Children</title>
  <style>
    body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    margin: 0;
    padding: 0;
}

header {
    background: #4a6fa5; /* main blue */
    padding: 1rem;
    text-align: center;
    color: white;
    font-size: 1.5rem;
    font-weight: bold;
    position: relative;
}

.back-btn {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    background: white;
    color: #4a6fa5;
    padding: 8px 15px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    border: 1px solid #4a6fa5;
    transition: 0.3s;
}

.back-btn:hover {
    background: #3a5a8a; /* darker blue hover */
    color: white;
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
    color: #4a6fa5; 
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
    background: #4a6fa5; 
    color: white; 
}

tr:hover { 
    background: #f1f1f1; 
}

.actions button {
    padding: 5px 10px;
    font-size: 12px;
    margin-right: 5px;
    border-radius: 5px;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}

.edit-btn {
    background: #4a6fa5;
    color: white;
}

.edit-btn:hover {
    background: white;
    color: #4a6fa5;
    border: 1px solid #4a6fa5;
}

.delete-btn {
    background: #d9534f;
    color: white;
}

.delete-btn:hover {
    background: white;
    color: #d9534f;
    border: 1px solid #d9534f;
}

  </style>
</head>
<body>
  
  <header>
    Admin - Manage Children
  </header>

  <?php
  $conn = mysqli_connect("localhost", "root", "", "misukukhanya");
  if(!$conn){ die("Connection failed: ".mysqli_connect_error()); }

  // Join approved_applications with app_users
  $query = "SELECT a.id, a.child_name, a.age_group,
                   u.full_name AS guardian_name, u.email, u.phone_number
            FROM approved_applications a
            JOIN app_users u ON a.guardian_id = u.id
            ORDER BY a.id DESC";
  $result = mysqli_query($conn, $query);

  if(mysqli_num_rows($result) > 0){
      echo '<div class="container">';
      echo '<h2>Registered Children</h2>';
      echo '<table>';
      echo '<tr>
              <th>Child Name</th>
              <th>Guardian Name</th>
              <th>Guardian Email</th>
              <th>Guardian Phone</th>
              <th>Classroom</th>
              <th>Actions</th>
            </tr>';
      while($row = mysqli_fetch_assoc($result)){
          echo '<tr>';
          echo '<td>'.htmlspecialchars($row['child_name']).'</td>';
          echo '<td>'.htmlspecialchars($row['guardian_name']).'</td>';
          echo '<td>'.htmlspecialchars($row['email']).'</td>';
          echo '<td>'.htmlspecialchars($row['phone_number']).'</td>';
          echo '<td>'.htmlspecialchars($row['age_group']).'</td>';
          echo '<td class="actions">
                  <button class="edit-btn" onclick="location.href=\'edit.php?id='.$row['id'].'\'">Edit</button>
                  <button class="delete-btn" onclick="if(confirm(\'Are you sure you want to delete this child?\')){location.href=\'deleteChild.php?id='.$row['id'].'\';}">Delete</button>
                </td>';
          echo '</tr>';
      }
      echo '</table>';
      echo '</div>';
  } else {
      echo '<div class="container"><p>No children registered yet.</p></div>';
  }
  ?>
</body>
</html>
