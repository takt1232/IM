<?php
include "includes/db_connection.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];

  // Check if the username already exists
  $checkUsernameQuery = "SELECT COUNT(*) FROM users WHERE username = :username";
  $stmt = $pdo->prepare($checkUsernameQuery);
  $stmt->bindParam(':username', $username);
  $stmt->execute();
  $usernameExists = ($stmt->fetchColumn() > 0);

  if ($usernameExists) {
    echo "Username already exists!";
  } else {
    echo "Username is available.";
  }
}
?>
