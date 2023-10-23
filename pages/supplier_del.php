<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
?>

<?php
include "../includes/db_connection.php";
$si = $_POST['supplier-id'];

try {
  $sql = 'DELETE FROM supplier WHERE supplier_id = :supplier_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':supplier_id', $si);
  $stmt->execute();

  echo "<script type='text/javascript'>
    alert('You\'ve Deleted A Supplier Successfully.');
    window.location = 'supplier.php';
  </script>";
} catch (PDOException $e) {
  echo "<script type='text/javascript'>
    alert('Error: " . $e->getMessage() . "');
    window.location = 'supplier.php';
  </script>";
}
?>

<?php
} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
?>
