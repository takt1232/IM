<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {

  include "../includes/db_connection.php";

  try {
    $si = $_POST['store-id'];

    $sql = "DELETE FROM store WHERE store_id = :store_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':store_id', $si);
    $stmt->execute();

    // Success message
    echo "<script type='text/javascript'>
      alert('You\'ve Deleted A Store Successfully.');
      window.location = 'store.php';
    </script>";
  } catch (PDOException $e) {
    // Error message
    echo "<script type='text/javascript'>
      alert('Error: " . $e->getMessage() . "');
      window.location = 'store.php';
    </script>";
  }

} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
?>
