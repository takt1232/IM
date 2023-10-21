<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
    include "../includes/db_connection.php";

    $pn = $_POST['product-name'];
    $pq = $_POST['product-quantity'];
    $pp = $_POST['product-price'];
    $ps = $_POST['product-supplier'];

    try {
        $sql1 = "INSERT INTO product (product_name, supplier_id) VALUES (:product_name, :supplier_id)";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->bindParam(':product_name', $pn);
        $stmt1->bindParam(':supplier_id', $ps);
        $stmt1->execute();

        $product_id = $pdo->lastInsertId();

        $sql2 = "INSERT INTO product_details (product_id, product_quantity, product_price) VALUES (:product_id, :product_quantity, :product_price)";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindParam(':product_id', $product_id);
        $stmt2->bindParam(':product_quantity', $pq);
        $stmt2->bindParam(':product_price', $pp);
        $stmt2->execute();

        // Success message
        echo "<script type='text/javascript'>
                alert('You\'ve Added A Product Successfully.');
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
