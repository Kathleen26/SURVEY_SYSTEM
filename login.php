<?php

require './fb-init.php';

require __DIR__. "/vendor/autoload.php";

$client = new Google\Client;

$client->setClientId("489210130241-pk14q3ai30begvhd0qm0l7h5k19rhq9d.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/sample/home.php");

$client->addScope("email");
$client->addScope("profile");

$url = $client->createAuthUrl();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register & Login</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>  
  <link rel="stylesheet" href="style.css">
</head>

<body>
   <script src="script.js"></script>
  
  <div class="container forms" id="signIn">

     <div class="form login">
     <div class="form-content">
         <header>Login</header>
    <?php
    if (isset($errors['login'])) {
      echo '<div class="error-main">
                    <p>' . $errors['login'] . '</p>
                    </div>';
      unset($errors['login']);
    }
    ?>
    <form method="POST" action="user-account.php">
    <div class="field input-field">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <?php
        if (isset($errors['email'])) {
          echo ' <div class="error">
                    <p>' . $errors['email'] . '</p>
                </div>';
        }
        ?>
      </div>
      <div class="field input-field">
        <input type="password" name="password" id="password" placeholder="Password" required>
        <?php
        if (isset($errors['password'])) {
          echo ' <div class="error">
                    <p>' . $errors['password'] . '</p>
                </div>';
        }
        ?>
      </div>

      <div class="form-link">
                <a href="forgot-password.php" class="forgot-pass">Forgot Password?</a>
            </div>

            <div class="field button-field">
            <input type="submit"  value="Log in" name="signin">
            </div>


    </form>
    <div class="form-link">
            <span>Don't have an account?<a href="register.php" class="link signup-link">Signup</a></span>
         </div>
        </div>
         
           <div class="line"></div>

           <div class="media-options">
              <a href="<?php echo $login_url;?>" class="field facebook">
                <i class='bx bxl-facebook facebook-icon'></i>
                <span>Login with Facebook</span>
              </a>
           </div>

           <div class="media-options">
            <a href="<?= $url ?>" class="field google">
              <img src="google-color.png" alt="" class="google-img">
              <span>Login with Google</span>
            </a>
         </div>   
    </div>
</body>
</html>


<?php
if (isset($_SESSION['errors'])) {
  unset($_SESSION['errors']);
}
?>