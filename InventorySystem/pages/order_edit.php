<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin' || 'store_owner') {
    include "../includes/db_connection.php";

    $oi = $_POST['order-id'];
    $sn = $_POST['store-owner-opt'];
    $ta = $_POST['total-amount'];
    $pm = $_POST['method-opt'];
    $ps = $_POST['status-opt'];
    $od = $_POST['order-date'];

    try {
        $query = 'UPDATE orders SET store_id = :store_id, total_amount = :total_amount, payment_method_id = :method_id, payment_status_id = :status_id, order_date = :order_date WHERE order_id = :order_id';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':store_id', $sn);
        $stmt->bindParam(':total_amount', $ta);
        $stmt->bindParam(':method_id', $pm);
        $stmt->bindParam(':status_id', $ps);
        $stmt->bindParam(':order_date', $od);
        $stmt->bindParam(':order_id', $oi);
        $stmt->execute();

        // Success message
        echo "<script type='text/javascript'>
                alert('You\'ve Updated An Order Successfully.');
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
?>
