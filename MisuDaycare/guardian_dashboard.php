<?php
session_start();

// ✅ Connect to database
$conn = mysqli_connect("localhost", "root", "", "misukukhanya");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Fetch Fees
$fees = [];
$sqlFees = "SELECT * FROM billing";
$resultFees = mysqli_query($conn, $sqlFees);
while ($row = mysqli_fetch_assoc($resultFees)) {
    $fees[$row['category']] = $row;
}

// ✅ Fetch Meals
$meals = [];
$sqlMeals = "SELECT * FROM meal_plans";
$resultMeals = mysqli_query($conn, $sqlMeals);
while ($row = mysqli_fetch_assoc($resultMeals)) {
    $meals[strtolower($row['day_of_week'])] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guardian Dashboard - Misukukhanya DayCare</title>
  <style>
 body {
    font-family: 'Segoe UI', sans-serif;
    background: #f5f7fa;
    margin: 0;
    padding: 0;
}

header {
    background: #4a6fa5; /* main blue */
    color: white;
    padding: 15px 25px;
    text-align: center;
    font-size: 1.5em;
    font-weight: bold;
}

nav {
    background: #3a5a8a; /* darker blue */
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 10px;
    position: sticky;
    top: 0;
}

nav a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    padding: 8px 14px;
    border-radius: 6px;
    transition: background 0.3s;
}

nav a:hover {
    background: #4a6fa5; /* lighter hover */
}

.content {
    max-width: 800px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

h2 {
    color: #4a6fa5; /* heading color same as login form */
}

select {
    padding: 8px;
    margin-top: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}

.apply-btn {
    margin-top: 15px;
    padding: 12px 20px;
    background: #4a6fa5; /* button main blue */
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease, color 0.3s ease;
}

.apply-btn:hover {
    background: #3a5a8a; /* darker hover */
    color: white;
}







    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: #f5f7fa;
    }

    .header {
      background: #4a6fa5;
      color: white;
      padding: 1rem;
      text-align: center;
      font-size: 1.5rem;
      font-weight: bold;
      position: relative; /* to place buttons inside */
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
    }

    .back-btn {
      left: 1rem;
      background: #6c757d;
      color: white;
    }

    .logout-btn {
      right: 1rem;
      background: #233d63ff;
      color: white;
    }

    .back-btn:hover {
      background: #5a6268;
    }

    .logout-btn:hover {
      background: #5a6268; 
    }

    .container {
      max-width: 800px;
      margin: 2rem auto;
      padding: 2rem;
      background: white;
      border-radius: 10px;
      box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
</style>
</head>
<body>

<header>Guardian Dashboard</header>

  
    
    
<nav>
<!-- Navigation Bar -->

   
  <a onclick="showContent('fees')">Fees</a>
  <a onclick="showContent('hours')">Working Hours</a>
  <a onclick="showContent('contacts')">Contacts</a>
  <a onclick="showContent('meals')">Meals</a>
  <a onclick="showContent('profile')">Profile</a>
  <a onclick="showContent('apply')">Apply</a>
<button class="top-btn logout-btn" onclick="window.location.href='home.php'">Logout ⮕</button>
 
</nav>

<!-- Content Area -->
<div class="content" id="contentArea">
  <h2>Welcome 👋</h2>
  <p>Select an option from the menu above to view details.</p>
</div>

<footer style="text-align:center; padding:15px; background:#eee; margin-top:20px; color:#555;">
  © 2025 Misukukhanya DayCare. All Rights Reserved.
</footer>

<script>
  function showContent(section) {
    let content = "";

    if (section === "fees") {
      content = `
        <h2>💰 Fees</h2>
        <p>Please select your child’s category:</p>
        <select onchange="showFees(this.value)">
          <option value="">-- Select Category --</option>
          <option value="Infant">Infant (0-12 months)</option>
          <option value="Toddler">Toddler (2-3 years)</option>
          <option value="Preschool">Pre School (3-5 years)</option>
        </select>
        <div id="feeDetails" style="margin-top:15px;"></div>
      `;
    }
    else if (section === "hours") {
      content = `
        <h2>⏰ Working Hours</h2>
        <p>Monday - Friday</p>
        <p>07:00 AM - 05:30 PM</p>
        <p>Closed on weekends & public holidays</p>
      `;
    }
    else if (section === "contacts") {
      content = `
        <h2>📞 Contact Details</h2>
        <p>Phone: +27 71 234 5678</p>
        <p>Email: info@misukukhanyadaycare.co.za</p>
        <p>Address: 1326 Spine Road, Unit 5 Mdantsane, East London Eastern Cape, 5219</p>
      `;
    }
    else if (section === "profile") {
      content = `
        <h2>👤 My Profile</h2>
        <p>Click the button below to view and edit your profile.</p>
        <button class="apply-btn" onclick="profile()">Profile</button>
      `;
    }
    else if (section === "meals") {
      content = `
        <h2>🍴 Meals</h2>
        <p>Select a day to view meals:</p>
        <select onchange="showMeals(this.value)">
          <option value="">-- Select Day --</option>
          <option value="monday">Monday</option>
          <option value="tuesday">Tuesday</option>
          <option value="wednesday">Wednesday</option>
          <option value="thursday">Thursday</option>
          <option value="friday">Friday</option>
        </select>
        <div id="mealDetails" style="margin-top:15px;"></div>
      `;
    }
    else if (section === "apply") {
      content = `
        <h2>📝 Apply Now</h2>
        <p>Click the button below to apply for your child’s enrollment.</p>
        <button class="apply-btn" onclick="applyNow()">Apply</button>
      `;
    }

    document.getElementById("contentArea").innerHTML = content;
  }

  function showFees(type) {
    let details = "";
    const fees = <?php echo json_encode($fees); ?>;

    if (fees[type]) {
      details = `
        <p><b>Monthly:</b> R${fees[type].monthly_fee}</p>
        <p><b>Registration Fee:</b> R${fees[type].registration_fee}</p>
        
      `;
    }

    document.getElementById("feeDetails").innerHTML = details;
  }

  function showMeals(day) {
    let meals = "";
    const mealData = <?php echo json_encode($meals); ?>;

    if (mealData[day]) {
      meals = `
        <p><b>Breakfast:</b> ${mealData[day].breakfast}</p>
        <p><b>Lunch:</b> ${mealData[day].lunch}</p>
        <p><b>Snack:</b> ${mealData[day].snacks}</p>
      `;
    }

    document.getElementById("mealDetails").innerHTML = meals;
  }

  function profile() {
    window.location.href = "guardian_profile.php"; 
  }

  function applyNow() {
    window.location.href = "apply.php"; 
  }
</script>

</body>
</html>
