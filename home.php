<?php
session_start();
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}else{
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey System Dashboard</title>
</head>
<style>
body {
    margin: 0px;
    background-image: linear-gradient(white, rgb(122, 166, 232));
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: hsl(198, 87%, 50%);
    padding: 20px; 
    font-size: 1.4em; 
}

.navbar .user-info {
    color: white;
    padding: 0 20px;
}

.navbar ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
}

.navbar ul li {
    margin: 0 10px;
}

.navbar ul li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

.navbar ul li a:hover {
    background-color: #ddd;
    color: black;
}

.navbar .logout {
    margin-left: auto;
}

.container {
    padding: 20px;
    text-align: center;
    gap: 50px;
}

.center-text {
    text-align: center; 
    font-size: 2em;
    margin-bottom: 20px;
}

.dashboard-sections {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 100px; /* added margin-top to move down */
}

.section {
    background-color:rgb(168, 205, 254);
    padding: 40px;
    margin: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 350px;
    text-align: center;
}

.section h3 {
    margin-top: 0;
    font-size: 2em;
}

.section .icon {
    width: 350px; /* Adjust width for the icon */
    margin: 0 auto; /* Center the image horizontally */
    display: block; /* Ensure the image is a block element */
    
}

.section .btn {
    display: inline-block;
    padding: 15px 30px;
    font-size: 18px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    outline: none;
    color: #fff;
    background-color: #2289e6;
    border: none;
    border-radius: 15px;
    margin-top: 50px;
}

.section .btn:hover {
    background-color: #2289e6;
}

.section .btn:active {
    background-color: #2289e6;
    transform: translateY(4px);
}
</style>
<body>
    <nav class="navbar">
    
        <div class="user-info">
            
        </div>
    </nav>

    <div class="container">
        
        <div class="dashboard-sections">
            <div class="section">
                <img src = "Create survey.png"  alt="Create Survey Icon" class="icon">
                 <p><a href="create_survey.php" class="btn">CREATE SURVEY</a></p>
            </div>
            <div class="section">
                 <img src = "View result.png"  alt="View Results Icon" class="icon">
                <p><a href="view_results.php" class="btn">VIEW RESULTS</a></p>
            </div>
        </div>
    </div>

</body>

</html>