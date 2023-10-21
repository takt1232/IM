<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">

<head>
   <meta charset="utf-8">
   <title>Login Page</title>
   <link rel="stylesheet" href="css/style.css?v=p<?php echo time(); ?>">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>

<body>
   <div class="content">
      <div class="text">
         Login
      </div>
      <?php if (isset($_GET['status'])) {
         if ($_GET['status'] === 'Registration Success') {
            echo "<p style='color: green;'>" . $_GET['status'] . "</p>";
         }
      }

      if (isset($_GET['error'])) {
         if ($_GET['error'] === 'Incorrect username or Password') {
            echo "<p style='color: red;'>" . $_GET['error'] . "</p>";
         } else if ($_GET['error'] === 'access_error') {
            echo "<p style='color: red;'>" . $_GET['error'] . "</p>";
         }
      }

      ?>
      <form action="login.php" method="POST">
         <div class="field">
            <input type="text" name="username" required>
            <span class="fas fa-user"></span>
            <label>Email or Phone</label>
         </div>
         <div class="field">
            <input type="password" name="password" required>
            <span class="fas fa-lock"></span>
            <label>Password</label>
         </div>
         <div class="forgot-pass">
            <a href="#">Forgot Password?</a>
         </div>
         <button type="submit">Sign in</button>
         <div class="sign-up">
            Not a member?
            <a href="create_account.php">signup now</a>
         </div>
      </form>
   </div>
</body>

</html>