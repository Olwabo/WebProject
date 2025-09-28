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
<title>Child Application</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body, html {
        min-height: 100%;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        display: flex;
        justify-content: center;
        padding: 40px 20px;
    }

    .container {
        width: 100%;
        max-width: 600px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin: 20px auto;
        padding: 30px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #4a6fa5;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-bottom: 6px;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.95rem;
    }

    input[type="text"], input[type="date"], select, textarea, input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8fafc;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #4a6fa5;
        box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.1);
        background-color: #fff;
    }

    textarea {
        resize: vertical;
    }

    button {
        width: 100%;
        padding: 14px;
        background: #4a6fa5;
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background: #3a5a8a;
    }

    @media (max-width: 600px) {
        .container {
            padding: 20px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h2>Submit Child Application</h2>

    <form action="save_application.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="guardian_id" value="<?php echo $_SESSION['user_id']; ?>">

      <label>Child Name:</label>
      <input type="text" name="child_name" required>

      <label>Date of Birth:</label>
      <input type="date" name="child_dob" required>

      <label>Gender:</label>
      <select name="gender" required>
        <option value="">Select</option>
        <option>Male</option>
        <option>Female</option>
        <option>Other</option>
      </select>

      <label>Age Group:</label>
      <select name="age_group" required>
        <option value="">Select</option>
        <option>Infant (0-12 months)</option>
        <option>Toddler (1-3 years)</option>
        <option>Preschool (3-5 years)</option>
      </select>

      <label>Medical Info / Allergies:</label>
      <textarea name="allergies" rows="3"></textarea>

      <label>Relationship:</label>
      <input type="text" name="relationship" required>

      <label>Birth Certificate:</label>
      <input type="file" name="birth_certificate" required>

      <label>Medical Note:</label>
      <input type="file" name="medical_note" required>

      <button type="submit">Submit Application</button>
    </form>
</div>

</body>
</html>
