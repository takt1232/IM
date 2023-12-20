<?php
include "includes/db_connection.php";

try {
  $pdo->beginTransaction();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (!empty($username) && !empty($password) && !empty($role)) {

      // Check if the username already exists
      $checkUsernameQuery = "SELECT COUNT(*) FROM users WHERE username = :username";
      $stmt = $pdo->prepare($checkUsernameQuery);
      $stmt->bindParam(':username', $username);
      $stmt->execute();
      $usernameExists = ($stmt->fetchColumn() > 0);

      if ($usernameExists) {
        $pdo->rollBack(); // Roll back if username already exists
        header("Location: create_account.php?error=Username already exists");
        exit();
      }

      // Insert user information into the users table
      $insertUserQuery = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
      $stmt = $pdo->prepare($insertUserQuery);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':password', $password);
      $stmt->bindParam(':role', $role);
      $stmt->execute();

      // Get the user ID of the inserted record
      $userId = $pdo->lastInsertId();

      if ($role === 'Store Owner') {
        // Insert store/supplier information into the respective table
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
      } elseif ($role === 'Supplier') {
        // Insert store/supplier information into the respective table
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
      } else {
        $pdo->rollBack(); // Roll back if role is not recognized
        header('location: create_account.php?error=Unable to register respected role');
        exit();
      }

      $pdo->commit(); // Commit the transaction

      // Redirect or display a success message
      header('Location: index.php?status=Registration Success');
      exit();
    } else {
      $pdo->rollBack(); // Roll back if validation fails
      header('Location: create_account.php?error=Validation Error');
      exit();
    }
  } else {
    $pdo->rollBack(); // Roll back if not a POST request
    header('Location: create_account.php?error=POST Error');
    exit();
  }
} catch (PDOException $e) {
  $pdo->rollBack(); // Roll back in case of an exception
  header('Location: create_account.php?error=Database Error');
  exit();
  // You can log the error or display a specific error message
}
