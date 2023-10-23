<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {

  include "../includes/db_connection.php";

  try {
    $sn = $_POST['store-name'];
    $sa = $_POST['store-address'];
    $sp = $_POST['store-phone'];
    $se = $_POST['store-email'];

    $sql = "INSERT INTO store (store_name, store_address, store_email, store_phone) VALUES (:store_name, :store_address, :store_email, :store_phone)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':store_name', $sn);
    $stmt->bindParam(':store_address', $sa);
    $stmt->bindParam(':store_email', $se);
    $stmt->bindParam(':store_phone', $sp);
    $stmt->execute();

    echo "<script type='text/javascript'>
      alert('You\'ve Added A Store Successfully.');
      window.location = 'store.php';
    </script>";
  } catch (Exception $e) {
    echo "Error: " . $e->getMessage();
  }

} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
?>
