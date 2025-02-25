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
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container forms" id="signup">
       
      <div class="form singup">
        <div class="form-content">
          <header>Sign up</header>
        
        
        <?php
        if (isset($errors['user_exist'])) {
            echo '<div class="error-main">
                    <p>' . $errors['user_exist'] . '</p>
                    </div>';
                    unset($errors['user_exist']);
        }
        ?>
        <form method="POST" action="user-account.php">
          <div class="field input-field">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Name" required>
                <?php
                if (isset($errors['name'])){
                    echo ' <div class="error">
                    <p>' . $errors['name'] . '</p>
                </div>';
          
                }
                ?>
            </div>

            <div class="field input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <?php
                if (isset($errors['email'])) {
                    echo '<div class="error">
                    <p>' . $errors['email'] . '</p>
                    </div>';
                    unset($errors['email']);

                }
                ?>
            </div>
            <div class="field input-field">
                <input type="password" name="password" id="password" placeholder="Password" >
                <i id="eye" class="fa fa-eye"></i>
                <?php
                if (isset($errors['password'])) {
                    echo '<div class="error">
                    <p>' . $errors['password'] . '</p>
                    </div>'
                    ;
                    unset($errors['password']);

                }
                ?>
            </div>
            <div class="field input-field">
                 <i class='bx bx-hide eye-icon'></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <?php
                if (isset($errors['confirm_password'])) {
                    echo '<div class="error">
                    <p>' . $errors['confirm_password'] . '</p>
                    </div>';
                    unset($errors['confirm_password']);

                }
                ?>
            </div>
            <input type="submit" class="sign-in-btn" value="Sign Up" name="signup">
        </form>

        <div class="form-link">
          <span>Already have an account?<a href="index.php" class="link login-link">Login</a></span>
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

  </div>
    <script src="script.js"></script>
</body>

</html>
<?php
if(isset($_SESSION['errors'])){
unset($_SESSION['errors']);
}
?>