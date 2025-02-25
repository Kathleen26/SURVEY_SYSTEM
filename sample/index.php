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
  <title>Register & Login</title>
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>  
  <link rel="stylesheet" href="style.css">
</head>

<body>

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
      <i class='bx bx-hide eye-icon'></i>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <i id="eye" class="fa fa-eye"></i>
        <?php
        if (isset($errors['password'])) {
          echo ' <div class="error">
                    <p>' . $errors['password'] . '</p>
                </div>';
        }
        ?>
      </div>

      <div class="form-link">
                <a href="#" class="forgot-pass">Forgot Password?</a>
            </div>

      <input type="submit" class="btn sign-in-btn" value="Sign In" name="signin">

    </form>
    <div class="form-link">
            <span>Don't have an account?<a href="register.php" class="link signup-link">Signup</a></span>
         </div>
        </div>
         
           <div class="line"></div>

           <div class="media-options">
              <a href="#" class="field facebook">
                <i class='bx bxl-facebook facebook-icon'></i>
                <span>Login with Facebook</span>
              </a>
           </div>

           <div class="media-options">
            <a href="#" class="field google">
              <img src="google-color.png" alt="" class="google-img">
              <span>Login with Google</span>
            </a>
         </div>   



  <script src="script.js"></script>
</body>

</html>
<?php
if (isset($_SESSION['errors'])) {
  unset($_SESSION['errors']);
}
?>