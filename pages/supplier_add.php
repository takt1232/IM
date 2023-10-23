<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
?>

<?php
include "../includes/db_connection.php";
$sn = $_POST['supplier-name'];
$sa = $_POST['supplier-address'];
$sp = $_POST['supplier-phone'];
$se = $_POST['supplier-email'];

try {
  $sql = "INSERT INTO supplier (supplier_name, supplier_address, supplier_email, supplier_phone) VALUES (?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$sn, $sa, $se, $sp]);

  echo "<script type='text/javascript'>
    alert('You\'ve Added A Supplier Successfully.');
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
