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


$sqlGuardian = "SELECT * FROM app_users WHERE id = $guardian_id";
$guardian = mysqli_fetch_assoc(mysqli_query($conn, $sqlGuardian));


$sqlChildren = "SELECT * FROM approved_applications WHERE guardian_id = $guardian_id";
$children = mysqli_query($conn, $sqlChildren);

$sqlIncidents = "
    SELECT hi.*, aa.child_name, s.full_name AS staff_name
    FROM health_incidentss hi
    JOIN approved_applications aa ON hi.child_id = aa.id
    JOIN app_users s ON hi.staff_id = s.id
    WHERE aa.guardian_id = $guardian_id
    ORDER BY hi.incident_date DESC
";
$incidents = mysqli_query($conn, $sqlIncidents);


$sqlPayments = "SELECT * FROM payments WHERE guardian_id = $guardian_id ORDER BY payment_date DESC";
$payments = mysqli_query($conn, $sqlPayments);


$sqlReports = "SELECT tr.*, aa.child_name AS child_name 
               FROM term_reports tr
               JOIN approved_applications aa ON tr.child_id = aa.id
               WHERE aa.guardian_id = $guardian_id
               ORDER BY tr.report_date DESC";
$reports = mysqli_query($conn, $sqlReports);


$sqlMessages = "SELECT * FROM messages WHERE guardian_id = $guardian_id ORDER BY created_at DESC";
$messages = mysqli_query($conn, $sqlMessages);


$announcements = "SELECT  * from events ORDER BY created_at DESC";
$event = mysqli_query($conn, $announcements);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Guardian Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
body { 
    font-family: 'Segoe UI', sans-serif; 
    background: #f5f7fa; 
    margin: 0; 
    padding: 0; 
}

header, footer { 
    background: #4a6fa5; /* main blue */
    color: white; 
    padding: 1rem; 
    text-align: center;
    font-weight: bold;
}

.dashboard-section { 
    margin: 2rem 0; 
}

.dashboard-section h3 { 
    color: #4a6fa5; /* same as heading color */
    margin-bottom: 1rem; 
}

.card { 
    border-radius: 12px; 
    margin-bottom: 1rem; 
    background: white; 
    padding: 20px; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.1); 
}

.btn-edit { 
    background: #4a6fa5; /* button main blue */
    color: white; 
    border: none; 
    padding: 8px 14px; 
    border-radius: 6px; 
    font-weight: 500; 
    cursor: pointer; 
    transition: background 0.3s ease; 
}

.btn-edit:hover { 
    background: #3a5a8a; /* darker blue hover */
}

.alert-decline { 
    background: #f8d7da; 
    color: #721c24; 
    border-radius: 8px; 
    padding: 10px; 
    margin-bottom: 10px; 
}

</style>
</head>
<body>

<header>
  <h2>Welcome, <?php echo $guardian['full_name']; ?>!</h2>
</header>

<div class="container">

 
  <div class="dashboard-section">
    <h3>My Profile</h3>
    <div class="card p-3">
      <p><strong>Name:</strong> <?php echo $guardian['full_name']; ?></p>
      <p><strong>Email:</strong> <?php echo $guardian['email']; ?></p>
      <p><strong>Phone:</strong> <?php echo $guardian['phone_number']; ?></p>
      <p><strong>ID Number:</strong> <?php echo $guardian['id_number']; ?></p>
      <button class="btn btn-edit" onclick="location.href='edit_guardian.php?id=<?php echo $guardian['id']; ?>'">Edit My Info</button>
    </div>
  </div>

  
  <div class="dashboard-section">
    <h3>My Child</h3>
    <?php if (mysqli_num_rows($children) > 0): ?>
      <?php while($child = mysqli_fetch_assoc($children)): ?>
      <div class="card p-3">
        <p><strong>Child Name:</strong> <?php echo $child['child_name']; ?></p>
        <p><strong>Date of Birth:</strong> <?php echo $child['child_dob']; ?></p>
        <p><strong>Class:</strong> <?php echo $child['age_group']; ?></p>
        <p><strong>Status:</strong> <?php echo $child['status'] ?? "Pending"; ?></p>
        <button class="btn btn-edit" onclick="location.href='edit_child.php?id=<?php echo $child['id']; ?>'">Edit Child Info</button>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>You currently have no registered children.</p>
      <button class="btn btn-primary" onclick="location.href='apply.php'">Apply</button>
    <?php endif; ?>
  </div>


  <div class="dashboard-section">
    <h3>Messages</h3>
    <?php if(mysqli_num_rows($messages) > 0): ?>
      <?php while($msg = mysqli_fetch_assoc($messages)): ?>
        <div class="alert-decline">
          <?php echo $msg['message']; ?> <br>
          <small><em><?php echo $msg['created_at']; ?></em></small>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No messages at this time.</p>
    <?php endif; ?>
  </div>

  
  <div class="dashboard-section">
    <h3>Payments</h3>
    <div class="card p-3">
      <?php if (mysqli_num_rows($payments) > 0): ?>
        <table class="table table-bordered">
          <thead>
            <tr><th>Date</th><th>Amount</th><th>Notes</th></tr>
          </thead>
          <tbody>
            <?php while($pay = mysqli_fetch_assoc($payments)): ?>
              <tr>
                <td><?php echo $pay['payment_date']; ?></td>
                <td>R<?php echo $pay['amount']; ?></td>
                <td><?php echo $pay['notes']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p>No payments found.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="dashboard-section">
  <h3>Child Incidents</h3>
  <?php if (mysqli_num_rows($incidents) > 0): ?>
    <?php while($incident = mysqli_fetch_assoc($incidents)): ?>
      <div class="card p-3 mb-3">
        <p><strong>Child Name:</strong> <?php echo $incident['child_name']; ?></p>
        <p><strong>Date:</strong> <?php echo $incident['incident_date']; ?></p>
        <p><strong>Problem:</strong> <?php echo $incident['description_problem']; ?></p>
        <p><strong>Reported By:</strong> <?php echo $incident['staff_name']; ?></p>

      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No incidents reported for your child.</p>
  <?php endif; ?>
</div>

  
  <div class="dashboard-section">
    <h3>Term Reports</h3>
    <?php if (mysqli_num_rows($reports) > 0): ?>
      <?php while($report = mysqli_fetch_assoc($reports)): ?>
      <div class="card p-3">
        <p><strong>Child:</strong> <?php echo $report['child_name']; ?></p>
        <p><strong>Period:</strong> <?php echo $report['startDate']; ?> - <?php echo $report['endDate']; ?></p>
        <p><strong>Performance:</strong> <?php echo $report['performanceD']; ?></p>
        <p><strong>Submitted On:</strong> <?php echo $report['report_date']; ?></p>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No reports available.</p>
    <?php endif; ?>
  </div>

 
 <div class="dashboard-section">
  <h3>Announcements</h3>
  
  <?php if(mysqli_num_rows($event) > 0) { ?>
    <?php while($row = mysqli_fetch_assoc($event)) { ?>
      <div class="card p-3 mb-3" style="border:1px solid #ddd; border-radius:8px;">
        <h4><?php echo htmlspecialchars($row['title']); ?></h4>
        <small><strong>Date:</strong> <?php echo htmlspecialchars($row['event_date']); ?></small>
        <p><small><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
        <h6><small><strong>Posted on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></em></small></h6>
        
      </div>
    <?php } ?>
  <?php } else { ?>
    <p style="color:gray;">No announcements available.</p>
  <?php } ?>
</div>

</div>

<footer class="text-center">&copy; 2025 Misukukhanya Daycare | Empowering Little Steps</footer>
</body>
</html>
