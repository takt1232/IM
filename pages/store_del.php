<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {

  include "../includes/db_connection.php";

  try {
    $si = $_POST['store-id'];

    // Start a transaction
    $pdo->beginTransaction();

    // Delete from users table where user_id matches store_id
    $sqlStore = "DELETE FROM users WHERE user_id IN (SELECT user_id FROM store WHERE store_id = :store_id)";
    $stmtStore = $pdo->prepare($sqlStore);
    $stmtStore->bindParam(':store_id', $si);
    $stmtStore->execute();

    // Commit the transaction
    $pdo->commit();

    // Success message
    echo "<script type='text/javascript'>
      alert('You\'ve Deleted A Store Successfully.');
      window.location = 'store.php';
    </script>";
  } catch (PDOException $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();

    // Error message
    echo "<script type='text/javascript'>
      alert('Error: " . $e->getMessage() . "');
      window.location = 'store.php';
    </script>";
  }
} else {
  header("Location: ../index.php?error=Access Error");
  exit();
}
