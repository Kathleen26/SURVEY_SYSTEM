<?php

session_start();
if (isset($_SESSION['errors'])) {
  $errors = $_SESSION['errors'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surnalysis</title>
    <link rel="stylesheet" href="style1.css">
    <script src="main.js"></script>
</head>
<body>

    <nav class="navbar">
        <ul>
            
            <li><a href="Home.php">Home</a></li>
            <li><a href="About.php">About</a></li>
            <li><a href="Contact.php">Contact</a></li>
        </ul>
    </nav>

    <div class="dateTime" >
        <div class="time" id="Time"></div>
        <div class="date" id="Date"></div>

    </div>

    <div class="container ">
        <img src="SURNALYSIS-removebg-preview.png" alt="Surnalysis">
         
        <div class ="button">
            <a href="index.php" class="btn">Get Started</a></p>
         </div>
     </div>

</body>
</html>