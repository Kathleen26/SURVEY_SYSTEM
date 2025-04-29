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
    <link rel="stylesheet" href="style2.css">
</head>

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