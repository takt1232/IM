<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  include "includes/db_connection.php";

  // Retrieve form data
  $username = $_POST['username'];
  $password = $_POST['password'];
  $role = $_POST['role'];

  // Validate and process the form data
  if (!empty($username) && !empty($password) && !empty($role)) {
    try {
      // Check if the username already exists
      $checkUsernameQuery = "SELECT COUNT(*) FROM users WHERE username = :username";
      $stmt = $pdo->prepare($checkUsernameQuery);
      $stmt->bindParam(':username', $username);
      $stmt->execute();
      $usernameExists = ($stmt->fetchColumn() > 0);

      if ($usernameExists) {
        // Username already exists, display an error message
        header("Location: create_account.php?error=Username already exist");
        exit();
      } else {
        // Insert user information into the users table
        $insertUserQuery = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $pdo->prepare($insertUserQuery);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        $stmt->execute();

        // Get the user ID of the inserted record
        $userId = $pdo->lastInsertId();

        // Insert store/supplier information into the respective table
        if ($role === 'store_owner') {
          $storeName = $_POST['store_name'];
          $storeAddress = $_POST['store_address'];
          $storePhoneNumber = $_POST['store_phone'];
          $storeEmail = $_POST['store_email'];

          $insertStoreQuery = "INSERT INTO store (user_id, store_name, store_address, store_phone, store_email) VALUES (:user_id, :store_name, :store_address, :store_phone, :store_email)";
          $stmt = $pdo->prepare($insertStoreQuery);
          $stmt->bindParam(':user_id', $userId);
          $stmt->bindParam(':store_name', $storeName);
          $stmt->bindParam(':store_address', $storeAddress);
          $stmt->bindParam(':store_phone', $storePhoneNumber);
          $stmt->bindParam(':store_email', $storeEmail);
          $stmt->execute();

        } elseif ($role === 'supplier') {
          $supplierName = $_POST['supplier_name'];
          $supplierAddress = $_POST['supplier_address'];
          $supplierPhoneNumber = $_POST['supplier_phone'];
          $supplierEmail = $_POST['supplier_email'];

          $insertSupplierQuery = "INSERT INTO supplier (user_id, supplier_name, supplier_address, supplier_phone, supplier_email) VALUES (:user_id, :supplier_name, :supplier_address, :supplier_phone, :supplier_email)";
          $stmt = $pdo->prepare($insertSupplierQuery);
          $stmt->bindParam(':user_id', $userId);
          $stmt->bindParam(':supplier_name', $supplierName);
          $stmt->bindParam(':supplier_address', $supplierAddress);
          $stmt->bindParam(':supplier_phone', $supplierPhoneNumber);
          $stmt->bindParam(':supplier_email', $supplierEmail);
          $stmt->execute();
        }

        // Redirect or display a success message
        header('Location: index.php?status=Registration Success');
        exit();
      }
    } catch (PDOException $e) {
      // Handle database errors
      header('Location: create_account.php?status=Database Error');
      exit();
      // You can log the error or display a specific error message
    }
  } else {
    // Handle validation errors
    // You can redirect back to the registration form with error messages or display them on the same page
    header('Location: create_account.php?status=Validation Error');
    exit();
    // Redirect or display the error message
  }
} else {
  // Handle non-POST requests
  // Redirect or display an error message
  header('Location: create_account.php?status=POST Error');
  exit();
}
