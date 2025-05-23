<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>Signup</title>
</head>
<body>
  <section class="container forms">
    <div class="form signup">
      <div class="form-content">
        <header>Signup</header>
        <form action="#">
          <div class="field input-field">
            <input type="email" placeholder="Email" class="input">
          </div>
          <div class="field input-field">
            <input type="password" placeholder="Password" class="password">
          </div>
          <div class="field input-field">
            <input type="password" placeholder="Confirm Password" class="password">
            <i class='bx bx-hide eye-icon'></i>
          </div>
          <div class="field button-field">
            <button>Signup</button>
          </div>
        </form>
        <div class="form-link">
          <span>Already have an account? <a href="login.php" class="link login-link">Login</a></span>
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
  </section>
  <script src="script.js"></script>
</body>
</html>
