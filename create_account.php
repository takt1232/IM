<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration Form</title>
  <link rel="stylesheet" href="css/register_style.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
  <div class="content">
    <div class="text">Registration Form</div>
    <?php if (isset($_GET['status'])) {
      if ($_GET['status'] === 'Database Error') {
        echo "<p style='color: red;'>" . $_GET['status'] . "</p>";
      } else if ($_GET['status'] === 'Database Error') {
        echo "<p style='color: red;'>" . $_GET['status'] . "</p>";
      } else if ($_GET['status'] === 'Validation Error') {
        echo "<p style='color: red;'>" . $_GET['status'] . "</p>";
      } else if ($_GET['status'] === 'POST Error') {
        echo "<p style='color: red;'>" . $_GET['status'] . "</p>";
      }
    }
    ?>
    <form action="register.php" method="POST">
      <p style="color:green;" id="username-message"></p> <!-- Added p tag for username availability message -->
      <div class="field">
        <span><i class="fas fa-user"></i></span>
        <input type="text" id="username" name="username" required>
        <label>Username</label>
      </div>
      <div class="field">
        <span><i class="fas fa-lock"></i></span>
        <input type="password" id="password" name="password" required>
        <label>Password</label>
      </div>
      <div class="field">
        <span><i class="fas fa-user"></i></span>
        <select id="role" name="role" onchange="toggleFields()" required>
          <option value="">Select Role</option>
          <option value="store_owner">Store Owner</option>
        </select>
      </div>
      <div class="conditional-fields" id="store_fields">
        <div class="field">
          <span><i class="fas fa-store"></i></span>
          <input type="text" id="store_name" name="store_name" required>
          <label>Store Name</label>
        </div>
        <div class="field">
          <span><i class="fas fa-map-marker-alt"></i></span>
          <input type="text" id="store_address" name="store_address" required>
          <label>Store Address</label>
        </div>
        <div class="field">
          <span><i class="fas fa-phone"></i></span>
          <input type="tel" id="store_phone" name="store_phone" required>
          <label>Store Phone Number</label>
        </div>
        <div class="field">
          <span><i class="fas fa-envelope"></i></span>
          <input type="email" id="store_email" name="store_email" required>
          <label>Store Email</label>
        </div>
      </div>
      <div class="conditional-fields" id="supplier_fields">
        <div class="field">
          <span><i class="fas fa-user"></i></span>
          <input type="text" id="supplier_name" name="supplier_name" required>
          <label>Supplier Name</label>
        </div>
        <div class="field">
          <span><i class="fas fa-map-marker-alt"></i></span>
          <input type="text" id="supplier_address" name="supplier_address" required>
          <label>Supplier Address</label>
        </div>
        <div class="field">
          <span><i class="fas fa-phone"></i></span>
          <input type="tel" id="supplier_phone" name="supplier_phone" required>
          <label>Supplier Phone Number</label>
        </div>
        <div class="field">
          <span><i class="fas fa-envelope"></i></span>
          <input type="email" id="supplier_email" name="supplier_email" required>
          <label>Supplier Email</label>
        </div>
      </div>
      <div class="button-container">
        <button class="register-button" type="submit">Register</button>
        <button class="cancel-button" type="button" onclick="redirectToIndex()">Cancel</button>
      </div>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/register.js?v=<?php echo time(); ?>"></script>
  <script>
    $(document).ready(function() {
      $('#username').on('input', function() {
        var username = $(this).val();
        if (username !== '') {
          $.ajax({
            url: 'check_username.php',
            type: 'POST',
            data: {
              username: username
            },
            success: function(response) {
              if (response === 'Username already exists!') {
                $('#username-message').text(response).css('color', 'red');
              } else {
                $('#username-message').text(response).css('color', 'green');
              }
            }
          });
        } else {
          $('#username-message').text('').css('color', 'green');
        }
      });
    });

    function redirectToIndex() {
      window.location.href = "index.php";
    }
  </script>
</body>

</html>