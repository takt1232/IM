<?php
include "../includes/db_connection.php";

$si = $_POST['store-id'];

try {
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
?>
