<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin' || 'store') {
    include "../includes/db_connection.php";

    $oi = $_POST['order-id'];

    try {
        $sql = "DELETE FROM orders WHERE order_id = :order_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_id', $oi);
        $stmt->execute();

        // Success message
        echo "<script type='text/javascript'>
                alert('You\'ve Deleted An Order Successfully.');
                window.location = 'admin_dashboard.php';
              </script>";
    } catch (PDOException $e) {
        // Error message
        echo "<script type='text/javascript'>
                alert('Error: " . $e->getMessage() . "');
                window.location = 'admin_dashboard.php';
              </script>";
    }
} else {
    header("Location: ../index.php?error=access_error");
    exit();
}
