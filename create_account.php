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
    <div class="options">
      <a href="create_store.php" class="register-button">Create Store Owner Account</a>
      <a href="create_supplier.php" class="register-button">Create Supplier Account</a>
    </div>
    <button class="cancel-button" type="button" onclick="redirectToIndex()">Cancel</button>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/register.js?v=<?php echo time(); ?>"></script>
  <script>
    function redirectToIndex() {
      window.location.href = "index.php";
    }
  </script>
</body>

</html>