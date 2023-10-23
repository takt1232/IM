<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['type'] === 'admin') {
?>

<?php
include "../includes/db_connection.php";

$supplierId = $_POST['supplier-id'];
$supplierName = $_POST['supplier-name'];
$supplierAddress = $_POST['supplier-address'];
$supplierPhone = $_POST['supplier-phone'];
$supplierEmail = $_POST['supplier-email'];

try {
  $sql = 'UPDATE supplier SET supplier_name = :supplier_name, supplier_address = :supplier_address, supplier_phone = :supplier_phone, supplier_email = :supplier_email WHERE supplier_id = :supplier_id';
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':supplier_id', $supplierId);
  $stmt->bindParam(':supplier_name', $supplierName);
  $stmt->bindParam(':supplier_address', $supplierAddress);
  $stmt->bindParam(':supplier_phone', $supplierPhone);
  $stmt->bindParam(':supplier_email', $supplierEmail);
  $stmt->execute();

  echo "<script type='text/javascript'>
    alert('You\'ve Updated A Supplier information Successfully.');
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
