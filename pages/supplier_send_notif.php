<?php
session_start();
include "../includes/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Check if the user is a valid supplier
  if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'supplier') {
    // Get the form data
    $supplierId = $_POST['supplier_id'];
    $productId = $_POST['product_id'];
    $message = $_POST['message'];
    $notificationDate = date('Y-m-d H:i:s');

    try {
      // Prepare and execute the SQL query to insert the new entry
      $sql = "INSERT INTO admin_notification (supplier_id, product_id, message, notification_date) VALUES (:supplierId, :productId, :message, :notificationDate)";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':supplierId', $supplierId);
      $stmt->bindParam(':productId', $productId);
      $stmt->bindParam(':message', $message);
      $stmt->bindParam(':notificationDate', $notificationDate);

      if ($stmt->execute()) {
        // Insertion successful
        header("Location: supplier_product.php?status=success");
        exit();
      } else {
        // Insertion failed
        echo "Error sending notification. Please try again.";
      }
    } catch (PDOException $e) {
      // Handle database errors
      echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
      // Handle other errors
      echo "Error: " . $e->getMessage();
    }
  } else {
    // User is not a valid supplier
    echo "Access denied. Invalid user role.";
  }
}
?>
