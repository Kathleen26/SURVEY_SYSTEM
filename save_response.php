<?php
// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "survey_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Get form data
$answer = $_POST['answer'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];

// Save to database
$stmt = $conn->prepare("INSERT INTO survey_map (answer, latitude, longitude) VALUES (?, ?, ?)");
$stmt->bind_param("sdd", $answer, $lat, $lng);
$stmt->execute();
$stmt->close();
$conn->close();

// Redirect to registration.php after saving the response
header("Location: thankyou.php");
exit();
?>
