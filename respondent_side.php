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
</head>
<style>
body{
    margin: 0px;
    background-image: linear-gradient(white, rgb(122, 166, 232));
    background-repeat: no-repeat;
    background-position: center;
    background-attachment: fixed;
    background-size: cover;

    
}
main{
    margin-left: 20px;
    margin-right: 20px;
}

h1{
    text-align: center;
}

.Title {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 75%;
    border-style: solid;
  }
    
  .navbar ul {
    list-style-type: none;
    background-color: hsl(198, 87%, 50%);
    padding: 0px;
    margin: 0px;
    overflow: hidden;
    font-size: 1.2em; 
}

.navbar a {
    color: white;
    text-decoration: none;
    padding: 20px; 
    display: block;
    text-align: center;
}

.navbar a:hover {
    background-color: hsl(209, 86%, 66%);
}


.navbar li{
    float: left;
}

.container {
    position: relative;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    text-align: center; 
    margin-bottom: 20px;
    margin-top: 60px;
}

.container img {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 700px;
    margin-top: 100px;
}

.button {
    margin-top: -60px;
}

.dateTime {
    position: absolute;
    top: 0px;
    right: 0px;
    font-size: 16px; 
    padding-top: 16px;
    padding-right: 15px;
    padding-bottom: 10px;
    padding-left: 15px;
    background: transparent;
    color: white;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    outline: none;
    color: #fff;
    background-color: hsl(198, 87%, 50%);
    border: none;
    border-radius: 15px;
    margin: 10px auto 0 auto;
    width: 200px; 
}

.btn:hover {
    background-color: hsl(198, 87%, 50%);
}

.btn:active {
    background-color: hsl(198, 87%, 50%);
    transform: translateY(4px);
}
</style>
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
            <a href="registration.php" class="btn">Take Survey</a></p>
         </div>
     </div>
<script>
    function callDate(){
    var currentDate=(new Date()).toDateString(); 
    document.getElementById('Time').innerHTML=`${currentDate}`;
  }

  function callTime(){
    var currentTime=(new Date()).toLocaleTimeString(); 
    document.getElementById('Date').innerHTML=`${currentTime}`;
  }

setInterval(function(){callDate(), callTime()}, 1000);
</script>

</body>
</html>
