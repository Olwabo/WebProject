<?php
session_start();
$conn = mysqli_connect("localhost","root","","misukukhanya");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Collect form data
$guardian_id = $_POST['guardian_id']; 
$child_name = $_POST['child_name'];
$child_dob = $_POST['child_dob'];
$gender = $_POST['gender'];
$age_group = $_POST['age_group'];
$allergies = $_POST['allergies'];
$relationship = $_POST['relationship'];



// Handle file uploads
$birth_cert = $_FILES['birth_certificate']['name'];
$medical_note = $_FILES['medical_note']['name'];

// Save files into a folder called uploads
move_uploaded_file($_FILES['birth_certificate']['tmp_name'], "uploads/".$birth_cert);
move_uploaded_file($_FILES['medical_note']['tmp_name'], "uploads/".$medical_note);

// Insert into DB
$sql = "INSERT INTO applications (guardian_id, child_name, child_dob, gender, age_group, allergies, relationship, birth_certificate, medical_note) 
        VALUES ('$guardian_id', '$child_name', '$child_dob', '$gender', '$age_group', '$allergies', '$relationship', '$birth_cert', '$medical_note')";

if (mysqli_query($conn, $sql)) {
    echo "Application submitted successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
