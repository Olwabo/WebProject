<?php
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$childrenQuery = "SELECT COUNT(*) AS total_children FROM approved_applications";
$childrenResult = mysqli_query($conn, $childrenQuery);
$childrenRow = mysqli_fetch_assoc($childrenResult);
$totalChildren = $childrenRow['total_children'];


$appQuery = "SELECT COUNT(*) AS total_pending FROM applications WHERE status='pending'";
$appResult = mysqli_query($conn, $appQuery);
$appRow = mysqli_fetch_assoc($appResult);
$totalPending = $appRow['total_pending'];


$month = date('m');
$eventQuery = "SELECT COUNT(*) AS total_events FROM events WHERE MONTH(event_date) = '$month'";
$eventResult = mysqli_query($conn, $eventQuery);
$eventRow = mysqli_fetch_assoc($eventResult);
$totalEvents = $eventRow['total_events'];

// Query to count incidents
$incidentQuery = "SELECT COUNT(*) AS total_incidents FROM health_incidentss";
$incidentResult = mysqli_query($conn, $incidentQuery);
$incidentRow = mysqli_fetch_assoc($incidentResult);
$totalIncidents = $incidentRow['total_incidents'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Misukukhanya Daycare</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4a6fa5;
      --primary-dark: #3a5780;
      --secondary-color: #6d98cb;
      --accent-color: #f8b500;
      --light-color: #f5f7fa;
      --dark-color: #2c3e50;
      --text-color: #333;
      --border-radius: 8px;
      --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f0f2f5;
      color: var(--text-color);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      line-height: 1.6;
    }

    header {
      background-color: white;
      padding: 1.2rem 0;
      box-shadow: var(--box-shadow);
      position: relative;
      z-index: 100;
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.5rem;
    }
    
    .logo {
      width: 50px;
      height: 50px;
      background-color: var(--primary-color);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 15px;
      color: white;
      font-size: 1.5rem;
    }

    header h1 {
      color: var(--dark-color);
      font-weight: 600;
      font-size: 1.8rem;
      margin: 0;
      text-align: center;
    }

    .subtitle {
      color: var(--primary-color);
      font-size: 1rem;
      text-align: center;
      margin-top: 0.3rem;
      font-weight: 400;
    }

    nav {
      background-color: var(--primary-color);
      padding: 0.8rem 0;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    nav ul {
      display: flex;
      justify-content: center;
      padding: 0;
      list-style: none;
      margin: 0;
      flex-wrap: wrap;
    }

    nav li {
      margin: 0 0.5rem;
    }

    nav a {
      color: white;
      text-decoration: none;
      padding: 0.7rem 1.5rem;
      border-radius: var(--border-radius);
      background-color: var(--primary-dark);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      font-weight: 500;
      font-size: 0.95rem;
    }

    nav a i {
      margin-right: 8px;
      font-size: 1rem;
    }

    nav a:hover {
      background-color: var(--secondary-color);
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    main {
      flex-grow: 1;
      padding: 2.5rem 1.5rem;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .welcome-card {
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 2.5rem;
      max-width: 800px;
      width: 100%;
      text-align: center;
      margin-bottom: 2rem;
    }

    .welcome-card h2 {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 1.2rem;
      font-size: 1.8rem;
    }

    .welcome-card p {
      color: #555;
      font-size: 1.1rem;
      margin-bottom: 0;
    }

    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
      max-width: 800px;
      width: 100%;
    }

    .stat-card {
      background-color: white;
      border-radius: var(--border-radius);
      box-shadow: var(--box-shadow);
      padding: 1.5rem;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    .stat-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
    }

    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--dark-color);
      margin-bottom: 0.5rem;
    }

    .stat-label {
      color: #666;
      font-size: 0.95rem;
    }

    footer {
      background-color: var(--dark-color);
      color: white;
      text-align: center;
      padding: 1.2rem;
      font-size: 0.9rem;
      margin-top: auto;
    }

    @media (max-width: 768px) {
      nav ul {
        flex-direction: column;
        align-items: center;
      }

      nav li {
        margin: 0.3rem 0;
        width: 80%;
      }

      nav a {
        justify-content: center;
        width: 100%;
      }
      
      .welcome-card {
        padding: 1.5rem;
      }
      
      .stats-container {
        grid-template-columns: 1fr;
      }
    }

.Buttons {
  display: flex;
  justify-content: space-between; /* left and right */
  align-items: center;
  position: absolute;
  top: 10px;   /* distance from top */
  left: 0;
  right: 0;
  padding: 0 20px;
}

.top-btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
  color: #fff;
}

.back-btn {
  background: #6c757d; /* gray */
}

.logout-btn {
  background: #dc3545; /* red */
}

.back-btn:hover {
  background: #5a6268;
}

.logout-btn:hover {
  background: #c82333;
}

  </style>
</head>
<body>

  <header>
    <div class="container">
      <div class="logo-container">
        <div class="logo">
          <i class="fas fa-child"></i>
        </div>
        <h1>Misukukhanya Daycare</h1>
      </div>
      <div class="subtitle">Admin Dashboard</div>
    </div>
    
  </header>
  <nav>
    <div class="container">
      <ul>
        <li><a href="manageChild.php"><i class="fas fa-users"></i> Manage Children</a></li>
        <li><a href="viewApp.php"><i class="fas fa-file-alt"></i> View Applications</a></li>
        <li><a href="payment.php"><i class="fas fa-credit-card"></i> Payments</a></li>
        <li><a href="billing.php"><i class="fas fa-money-bill-wave"></i> Manage Billing</a></li>
        <li><a href="home.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
      </ul>
    </div>
  </nav>

  <main>
    <div class="welcome-card">
      <h2>Welcome, Administrator</h2>
      <p>Select an action from the menu to manage the daycare system efficiently and effectively.</p>
    </div>
    
    <div class="stats-container">
  <div class="stat-card">
    <div class="stat-icon">
      <i class="fas fa-child"></i>
    </div>
    <div class="stat-number"><?php echo $totalChildren; ?></div>
    <div class="stat-label">Children Enrolled</div>
  </div>
      
       <div class="stat-card">
    <div class="stat-icon">
      <i class="fas fa-clock"></i>
    </div>
    <div class="stat-number"><?php echo $totalPending; ?></div>
    <div class="stat-label">Pending Applications</div>
  </div>


    <div class="stat-card">
    <div class="stat-icon">
      <i class="fas fa-calendar-check"></i>
    </div>
    <div class="stat-number"><?php echo $totalEvents; ?></div>
    <div class="stat-label">Events This Month</div>
  </div>

      <div class="stat-card">
  <div class="stat-icon">
    <i class="fas fa-exclamation-circle"></i>
  </div>
  <div class="stat-number">
    <?php echo $totalIncidents; ?>
  </div>
  <div class="stat-label">Attention Required</div>
</div>

  </div>
    </div>
  </main>

  <footer>
    <div class="container">
      &copy; 2025 Misukukhanya Daycare Admin Panel | Providing Quality Childcare
    </div>
  </footer>

</body>
</html>