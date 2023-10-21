<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
    include "../includes/db_connection.php";

    $pi = $_POST['product-id'];
    $pn = $_POST['product-name'];
    $pq = $_POST['product-quantity'];
    $pp = $_POST['product-price'];
    $ps = $_POST['product-supplier'];

    try {
        $query = 'UPDATE product SET product_name = :product_name, supplier_id = :supplier_id WHERE product_id = :product_id';
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':product_name', $pn);
        $stmt->bindParam(':supplier_id', $ps);
        $stmt->bindParam(':product_id', $pi);
        $stmt->execute();

        $query1 = 'UPDATE product_details SET product_quantity = :product_quantity, product_price = :product_price WHERE product_id = :product_id';
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(':product_quantity', $pq);
        $stmt1->bindParam(':product_price', $pp);
        $stmt1->bindParam(':product_id', $pi);
        $stmt1->execute();

        // Success message
        echo "<script type='text/javascript'>
                alert('You\'ve Updated A Product Successfully.');
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
    header("Location: ../index.php?error=access_error");
    exit();
}
