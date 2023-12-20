<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
    include "../includes/db_connection.php";

    $pi = $_POST['product-id'];

    try {
        $sql = 'DELETE FROM product WHERE product_id = :product_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':product_id', $pi);
        $stmt->execute();

        $sql1 = 'DELETE FROM product_details WHERE product_id = :product_id';
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->bindParam(':product_id', $pi);
        $stmt1->execute();

        // Success message
        echo "<script type='text/javascript'>
                alert('You\'ve Deleted A Product Successfully.');
                window.location = 'inventory.php';
              </script>";
    } catch (PDOException $e) {
        // Error message
        echo "<script type='text/javascript'>
                alert('Error: " . $e->getMessage() . "');
                window.location = 'inventory.php';
              </script>";
    }
} else {
    header("Location: ../index.php?error=Access Error");
    exit();
}
