<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daycare Staff Dashboard</title>
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
    font-size: 1.5rem;
    font-weight: bold;
}

.dashboard {
    display: grid;
    grid-template-columns: 220px 1fr;
    min-height: 100vh;
}

nav {
    background: #4a6fa5; /* match first style */
    color: white;
    padding: 20px;
}

nav h3 { 
    margin-bottom: 20px; 
    color: white;
}

nav ul { 
    list-style: none; 
    padding: 0; 
}

nav ul li {
    margin: 15px 0;
    cursor: pointer;
    padding: 8px;
    background: rgba(255,255,255,0.1);
    border-radius: 6px;
    transition: 0.3s;
    color: white;
}

nav ul li:hover { 
    background: rgba(255,255,255,0.3); 
}

main { 
    padding: 20px; 
}

section {
    display: none;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
}

section.active { 
    display: block; 
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

th { 
    background: #4a6fa5; /* match first style */
    color: white; 
}

button {
    padding: 6px 10px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    background: #4a6fa5; /* match first style */
    color: white;
    transition: 0.3s;
}

button:hover { 
    background: #3a5a8a; /* darker blue hover */
}

textarea, input, select {
    width: 100%;
    padding: 8px;
    margin: 8px 0;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.list-item {
    padding: 10px;
    background: #fafcff; /* light variant */
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid #e2e8f0;
}

.welcome-section {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.welcome-section h2 {
    margin-top: 0;
    color: #4a6fa5;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.stat-card {
    background: #fafcff;
    padding: 15px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    text-align: center;
}

.stat-card h3 {
    margin: 0;
    font-size: 1.2rem;
    color: #4a6fa5;
}

.stat-card p {
    margin: 5px 0 0;
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
}

@media (max-width: 768px) {
    .dashboard { grid-template-columns: 1fr; }
    nav { display: flex; overflow-x: auto; }
    nav ul { display: flex; flex-direction: row; }
    nav ul li { margin: 5px; white-space: nowrap; }
}



header {
  background: #4a6fa5;
  color: white;
  padding: 1rem;
  text-align: center;
  font-size: 1.5rem;
  font-weight: bold;
  position: relative; /* allow absolute positioning inside */
}

header h1 {
  margin: 0;
}

.top-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  padding: 0.4rem 1rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 0.9rem;
  color: white;
}



.logout-btn {
  right: 1rem;   /* far right corner */
  background: #225079ff;
}

.back-btn:hover {
  background: #2e485cff;
}

.logout-btn:hover {
  background: #6c757d;
}

  </style>
</head>
<body>
<header>
  <h1>Daycare Staff Dashboard</h1>
  <button class="top-btn logout-btn" onclick="window.location.href='home.php'">Logout ⮕</button>
</header>

<div class="dashboard">
  <nav>
    <h3>Menu</h3>
    <ul>
      <li onclick="showSection('welcome')">Dashboard Home</li>
      <li onclick="showSection('children')">Children List</li>
      <li onclick="showSection('attendance')">Attendance</li>
      <li onclick="showSection('health')">Health Reports</li>
      <li onclick="showSection('term')">Term Reports</li>
      <li onclick="showSection('events')">Events & Activities</li>
      <li onclick="showSection('meals')">Meal Generation</li>
      
    </ul>
  </nav>
  <main>
    <section id="welcome" class="section active">
      <div class="welcome-section">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Staff Member'); ?>!</h2>
        <p>Here's a quick overview of your daycare dashboard for today, <?php echo date('F j, Y'); ?>.</p>
        <div class="stats-grid">
          <?php
          $conn = mysqli_connect("localhost", "root", "", "misukukhanya");
          
          // Total children
          $total_children = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM approved_applications"));
          
          // Today's attendance
          $today = date('Y-m-d');
          $present_today = mysqli_num_rows(mysqli_query($conn, "SELECT attendance_id FROM attendance WHERE date_att='$today' AND statuss='Present' AND staff_id=" . $_SESSION['user_id']));
          
          // Upcoming events
          $upcoming_events = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM events WHERE event_date >= '$today'"));
          
          // Unreplied messages
        ?>
          <div class="stat-card">
            <h3>Total Children</h3>
            <p><?php echo $total_children; ?></p>
          </div>
          <div class="stat-card">
            <h3>Present Today</h3>
            <p><?php echo $present_today; ?></p>
          </div>
          <div class="stat-card">
            <h3>Upcoming Events</h3>
            <p><?php echo $upcoming_events; ?></p>
          </div>
         
        </div>
      </div>
    </section>
    <section id="children" class="section">
     <?php
      $conn = mysqli_connect("localhost", "root", "", "misukukhanya");
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
            </tr>';
      while($row = mysqli_fetch_assoc($result)){
          echo '<tr>';
          echo '<td>'.htmlspecialchars($row['child_name']).'</td>';
          echo '<td>'.htmlspecialchars($row['guardian_name']).'</td>';
          echo '<td>'.htmlspecialchars($row['email']).'</td>';
          echo '<td>'.htmlspecialchars($row['phone_number']).'</td>';
          echo '<td>'.htmlspecialchars($row['age_group']).'</td>';
          echo '</tr>';
      }
      echo '</table>';
      echo '</div>';
  } else {
      echo '<div class="container"><p>No children list yet.</p></div>';
  }
     ?>
    </section>
<section id="attendance" class="section">
<?php
$conn = mysqli_connect("localhost","root","","misukukhanya");

if (isset($_GET['delete'])) {
    $attendance_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM attendance WHERE attendance_id=$attendance_id AND staff_id=" . $_SESSION['user_id']);
    echo "<p style='color:red;'>Attendance deleted successfully!</p>";
}

if (isset($_POST['update_attendance'])) {
    $attendance_id   = intval($_POST['attendance_id']);
    $child_id = mysqli_real_escape_string($conn, $_POST['child_id']);
    $date_att = mysqli_real_escape_string($conn, $_POST['date_att']);
    $time_in  = mysqli_real_escape_string($conn, $_POST['time_in']);
    $time_out = mysqli_real_escape_string($conn, $_POST['time_out']);
    $statuss  = mysqli_real_escape_string($conn, $_POST['statuss']);

    $update = "UPDATE attendance 
               SET child_id='$child_id', date_att='$date_att', time_in='$time_in', time_out='$time_out', statuss='$statuss'
               WHERE attendance_id='$attendance_id' AND staff_id=" . $_SESSION['user_id'];

    if (mysqli_query($conn, $update)) {
        echo "<p style='color:green;'>Attendance updated successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

if (isset($_POST['submit_attendance'])) {
    $child_id  = mysqli_real_escape_string($conn, $_POST['child_id']);
    $date_att  = mysqli_real_escape_string($conn, $_POST['date_att']);
    $time_in   = mysqli_real_escape_string($conn, $_POST['time_in']);
    $time_out  = mysqli_real_escape_string($conn, $_POST['time_out']);
    $statuss   = mysqli_real_escape_string($conn, $_POST['statuss']);
    $staff_id  = $_SESSION['user_id'];

    if (!empty($child_id) && !empty($date_att) && !empty($time_in) && !empty($time_out) && !empty($statuss)) {
        $insert = "INSERT INTO attendance (child_id, date_att, time_in, time_out, statuss, staff_id)
                   VALUES ('$child_id','$date_att','$time_in','$time_out','$statuss','$staff_id')";
        if (mysqli_query($conn, $insert)) {
            echo "<p style='color:green;'>Attendance saved successfully</p>";
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill in all fields before submitting.</p>";
    }
}

$children = mysqli_query($conn, "SELECT * FROM approved_applications ORDER BY child_name ASC");

$attendance_records = mysqli_query($conn, "SELECT a.*, c.child_name 
                                           FROM attendance a
                                           JOIN approved_applications c ON a.child_id=c.id
                                           WHERE a.staff_id=" . $_SESSION['user_id'] . "
                                           ORDER BY a.date_att DESC");
?>

<h2>Track Attendance</h2>

<form method="post" action="">
    <table>
        <tr>
            <th>Child Name</th>
            <th>Attend Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
        </tr>
        <tr>
            <td>
                <select name="child_id" required>
                    <option value="">-- Select Child --</option>
                    <?php while($row = mysqli_fetch_assoc($children)){ ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['child_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input type="date" name="date_att" required></td>
            <td><input type="time" name="time_in" required></td>
            <td><input type="time" name="time_out" required></td>
            <td>
                <select name="statuss" required>
                    <option value="">-- Select --</option>
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                </select>
            </td>
        </tr>
    </table>
    <br>
    <button type="submit" name="submit_attendance">Save Attendance</button>
</form>

<hr>

<h2>My Attendance Records</h2>
<?php if (mysqli_num_rows($attendance_records) > 0): ?>
    <form method="post">
        <table border="1" cellpadding="8">
            <tr>
                <th>Child</th>
                <th>Date</th>
                <th>Time In</th>
                <th>Time Out</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($att = mysqli_fetch_assoc($attendance_records)): ?>
            <tr>
                <td>
                    <input type="hidden" name="attendance_id" value="<?php echo $att['attendance_id']; ?>">
                    <input type="hidden" name="child_id" value="<?php echo $att['child_id']; ?>">
                    <?php echo $att['child_name']; ?>
                </td>
                <td>
                    <input type="date" name="date_att" value="<?php echo $att['date_att']; ?>">
                </td>
                <td>
                    <input type="time" name="time_in" value="<?php echo $att['time_in']; ?>">
                </td>
                <td>
                    <input type="time" name="time_out" value="<?php echo $att['time_out']; ?>">
                </td>
                <td>
                    <select name="statuss">
                        <option value="Present" <?php if($att['statuss']=="Present") echo "selected"; ?>>Present</option>
                        <option value="Absent" <?php if($att['statuss']=="Absent") echo "selected"; ?>>Absent</option>
                    </select>
                </td>
                <td>
                    <button type="submit" name="update_attendance">Update</button>
                    <a href="?delete=<?php echo $att['attendance_id']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </form>
<?php else: ?>
    <p>No attendance records found.</p>
<?php endif; ?>
</section>

<section id="health" class="section">
  <?php
  $conn = mysqli_connect("localhost","root","","misukukhanya");

if(isset($_POST['submit_incident'])){
    $child_id = mysqli_real_escape_string($conn, $_POST['child_id']);
    $incident_date = mysqli_real_escape_string($conn, $_POST['incident_date']);
    $description_problem = mysqli_real_escape_string($conn, $_POST['description_problem']);
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);

    if(!empty($child_id) && !empty($incident_date) && !empty($description_problem) && !empty($staff_id)){
        $insert = "INSERT INTO health_incidentss (child_id, incident_date, description_problem, staff_id) 
                   VALUES('$child_id', '$incident_date', '$description_problem', '$staff_id')";
        if(mysqli_query($conn, $insert)){
            echo "<p style='color:green;'>Health incident successfully saved!</p>";
        } else {
            echo "<p style='color:red;'>Error: ".mysqli_error($conn)."</p>";
        }
    } else {
        echo "<p style='color:red;'>Please fill in all fields before submitting.</p>";
    }
}

if(isset($_GET['delete_id'])){
    $delete_id = intval($_GET['delete_id']);
    mysqli_query($conn, "DELETE FROM health_incidentss WHERE incident_id='$delete_id'");
    echo "<p style='color:green;'>Incident deleted successfully.</p>";
}

if(isset($_POST['update_incident'])){
    $incident_id = intval($_POST['incident_id']);
    $child_id = mysqli_real_escape_string($conn, $_POST['child_id']);
    $incident_date = mysqli_real_escape_string($conn, $_POST['incident_date']);
    $description_problem = mysqli_real_escape_string($conn, $_POST['description_problem']);

    mysqli_query($conn, "UPDATE health_incidentss 
                         SET child_id='$child_id', incident_date='$incident_date', description_problem='$description_problem' 
                         WHERE incident_id='$incident_id'");
    echo "<p style='color:green;'>Incident updated successfully.</p>";
}

$children = mysqli_query($conn, "SELECT * FROM approved_applications ORDER BY child_name ASC");

$staff_id = $_SESSION['user_id'];
$incidents = mysqli_query($conn, "SELECT hi.*, aa.child_name 
                                  FROM health_incidentss hi 
                                  JOIN approved_applications aa ON hi.child_id = aa.id 
                                  WHERE hi.staff_id='$staff_id' 
                                  ORDER BY hi.incident_date DESC");
?>

<h2>Health Incident</h2>
<form method="post" action="">
    <table>
        <tr>
            <th>Child Name</th>
            <th>Incident Date</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <tr>
            <td>
                <select name="child_id" required>
                    <option value="">-- Select Child --</option>
                    <?php while($row = mysqli_fetch_assoc($children)){ ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['child_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input type="date" name="incident_date" required></td>
            <td><input type="text" name="description_problem" placeholder="Description..." required></td>
            <td><button type="submit" name="submit_incident">Save</button></td>
        </tr>
    </table>
    <input type="hidden" name="staff_id" value="<?php echo $_SESSION['user_id']; ?>">
</form>

<h3>Your Submitted Incidents</h3>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Child Name</th>
        <th>Date</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>
    <?php while($incident = mysqli_fetch_assoc($incidents)) { ?>
        <tr>
            <td><?php echo $incident['child_name']; ?></td>
            <td><?php echo $incident['incident_date']; ?></td>
            <td><?php echo $incident['description_problem']; ?></td>
            <td>
                <a href="?delete_id=<?php echo $incident['incident_id']; ?>" onclick="return confirm('Delete this incident?');">Delete</a> | 
                <a href="?edit_id=<?php echo $incident['incident_id']; ?>">Edit</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?php
if(isset($_GET['edit_id'])){
    $incident_id = intval($_GET['edit_id']);
    $edit_incident = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM health_incidentss WHERE incident_id='$incident_id'"));
?>
    <h3>Edit Incident</h3>
    <form method="post" action="">
        <input type="hidden" name="incident_id" value="<?php echo $edit_incident['incident_id']; ?>">
        <select name="child_id" required>
            <?php
            mysqli_data_seek($children, 0); 
            while($row = mysqli_fetch_assoc($children)){
                $selected = ($row['id'] == $edit_incident['child_id']) ? "selected" : "";
                echo "<option value='{$row['id']}' $selected>{$row['child_name']}</option>";
            }
            ?>
        </select>
        <input type="date" name="incident_date" value="<?php echo $edit_incident['incident_date']; ?>" required>
        <input type="text" name="description_problem" value="<?php echo $edit_incident['description_problem']; ?>" required>
        <button type="submit" name="update_incident">Update</button>
    </form>
<?php } ?>
</section>

<section id="term" class="section">
  <?php
$conn = mysqli_connect("localhost","root","","misukukhanya");

if(isset($_POST['submit_report'])){
    $child_id = mysqli_real_escape_string($conn, $_POST['child_id']);
    $startDate = mysqli_real_escape_string($conn, $_POST['startDate']);
    $endDate = mysqli_real_escape_string($conn, $_POST['endDate']);
    $performanceD = mysqli_real_escape_string($conn, $_POST['performanceD']);
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);
    $report_date = mysqli_real_escape_string($conn, $_POST['report_date']);

    if(!empty($child_id) && !empty($startDate) && !empty($endDate) && !empty($performanceD) && !empty($staff_id) && !empty($report_date)){
        $insert = "INSERT INTO term_reports (child_id, startDate, endDate, performanceD, staff_id, report_date) 
                   VALUES ('$child_id','$startDate','$endDate','$performanceD','$staff_id','$report_date')";
        if(mysqli_query($conn, $insert)){
            echo "<p style='color:green;'>Report submitted successfully for $child_id.</p>";
        } else {
            echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        echo "<p style='color:red;'>All fields are required.</p>";
    }
}

 $children = mysqli_query($conn, "SELECT * FROM approved_applications ORDER BY child_name ASC");

$reports = mysqli_query($conn, "SELECT hi.*, aa.child_name 
                                  FROM term_reports hi 
                                  JOIN approved_applications aa ON hi.child_id = aa.id 
                                  WHERE hi.staff_id='$staff_id' 
                                  ORDER BY hi.report_date DESC");
?>
<h2>Staff - Generate Term Report</h2>

<form method="post" action="">
  <table>
    <tr>
      <th>Child Name</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Performance</th>
      <th>Report Date</th>
    </tr>
    <tr>
       <td>
          <select name="child_id" required>
                    <option value="">-- Select Child --</option>
                    <?php while($row = mysqli_fetch_assoc($children)){ ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['child_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
      <td><input type="date" name="startDate" required></td>
      <td><input type="date" name="endDate" required></td>
      <td><textarea name="performanceD" placeholder="Child performance..." required></textarea></td>
      <td><input type="date" name="report_date" required></td>
      <td><input type="hidden" name="staff_id" value="<?php echo $_SESSION['user_id']; ?>"></td>
      
    </tr>
  </table>
  <br>
  <button type="submit" name="submit_report">Submit Report</button>
</form>

<h2>Submitted Reports</h2>
<table>
  <tr>
    <th>Child Name</th>
      <th>Start Date</th>
      <th>End Date</th>
      <th>Performance</th>
      <th>Report Date</th>
  </tr>
  <?php while($report = mysqli_fetch_assoc($reports)){ ?>
  <tr>
    <td><?php echo $report['child_name']; ?></td>
    <td><?php echo $report['startDate']; ?></td>
    <td><?php echo $report['endDate']; ?></td>
    <td><?php echo $report['performanceD']; ?></td>
    <td><?php echo $report['report_date']; ?></td>
  </tr>
  <?php } ?>
</table>
</section>

<section id="events" class="section">
  <?php
  if (isset($_POST['post_event'])) {
      $title       = $_POST['title'];
      $event_date  = $_POST['event_date'];
      $description = $_POST['description'];

      if (!empty($title) && !empty($event_date) && !empty($description)) {
          $insert = "INSERT INTO events (title, event_date, description) 
                     VALUES ('$title', '$event_date', '$description')";
          mysqli_query($conn, $insert);
          echo "<p style='color:green;'>Event posted successfully!</p>";
      } else {
          echo "<p style='color:red;'>All fields are required.</p>";
      }
  }

  $events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date DESC");
  ?>
  <h2>Post Events & Activities</h2>
  <form method="post" action="">
    <label>Event Title:</label>
    <input type="text" name="title" required>
    
    <label>Event Date:</label>
    <input type="date" name="event_date" required>
    
    <label>Description:</label>
    <textarea name="description" rows="4" required></textarea>
    
    <button type="submit" name="post_event">Post Event</button>
  </form>

  <h3>Upcoming Events</h3>
  <table>
    <tr><th>Title</th><th>Date</th><th>Description</th></tr>
    <?php
    if (mysqli_num_rows($events) > 0) {
        while ($row = mysqli_fetch_assoc($events)) {
            echo "<tr>
                    <td>{$row['title']}</td>
                    <td>{$row['event_date']}</td>
                    <td>{$row['description']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No events posted yet.</td></tr>";
    }
    ?>
  </table>
</section>

<section id="meals" class="section">
  <?php
$conn = mysqli_connect("localhost","root","","misukukhanya");

if(isset($_POST['save_meal'])){
    $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);
    $day = mysqli_real_escape_string($conn, $_POST['day_of_week']);
    $breakfast = mysqli_real_escape_string($conn, $_POST['breakfast']);
    $lunch = mysqli_real_escape_string($conn, $_POST['lunch']);
    $snacks = mysqli_real_escape_string($conn, $_POST['snacks']);

    $insert = "INSERT INTO meal_plans (staff_id, day_of_week, breakfast, lunch, snacks) 
               VALUES ('$staff_id','$day','$breakfast','$lunch','$snacks')";
    if(mysqli_query($conn, $insert)){
        echo "<p style='color:green;'>Meal plan saved for $day!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . mysqli_error($conn) . "</p>";
    }
}

$plans = mysqli_query($conn, "
    SELECT mp.*, u.full_name 
    FROM meal_plans mp
    LEFT JOIN app_users u ON mp.staff_id = u.id
    ORDER BY FIELD(mp.day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')
");
?>

<h2>Staff - Weekly Meal Plan Generator</h2>

<form method="post" action="">
  <label>Day of Week:</label>
  <select name="day_of_week" required>
    <option value="">-- Select Day --</option>
    <option>Monday</option>
    <option>Tuesday</option>
    <option>Wednesday</option>
    <option>Thursday</option>
    <option>Friday</option>
  </select><br><br>

  <label>Breakfast:</label>
  <input type="text" name="breakfast" placeholder="Enter breakfast meal" required><br><br>

  <label>Lunch:</label>
  <input type="text" name="lunch" placeholder="Enter lunch meal" required><br><br>

  <label>Snacks:</label>
  <input type="text" name="snacks" placeholder="Enter snack meal" required><br><br>

  <input type="hidden" name="staff_id" value="<?php echo $_SESSION['user_id'] ?? 1; ?>">

  <button type="submit" name="save_meal">Save Meal Plan</button>
</form>

<h2>Weekly Meal Plans</h2>
<table border="1" cellpadding="8" cellspacing="0">
  <tr style="background:#f2f2f2;">
    <th>Day</th>
    <th>Breakfast</th>
    <th>Lunch</th>
    <th>Snacks</th>
    <th>Created By</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($plans)){ ?>
  <tr>
    <td><?php echo htmlspecialchars($row['day_of_week']); ?></td>
    <td><?php echo htmlspecialchars($row['breakfast']); ?></td>
    <td><?php echo htmlspecialchars($row['lunch']); ?></td>
    <td><?php echo htmlspecialchars($row['snacks']); ?></td>
    <td><?php echo htmlspecialchars($row['full_name'] ?? 'Unknown'); ?></td>
  </tr>
  <?php } ?>
</table>
</section>



  </main>
</div>

<script>
    function showSection(sectionId) {
      document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
      document.getElementById(sectionId).classList.add('active');
    }
</script>
</body>
</html>