<?php
include "../includes/db_connection.php";
?>

<?php
$orderId = $_GET['orderId'];

try {
    // Fetch product details using a JOIN query
    $stmt = $pdo->prepare("SELECT order_product.*, product.product_name, supplier.supplier_name
                       FROM order_product
                       JOIN product ON order_product.product_id = product.product_id
                       JOIN supplier ON product.supplier_id = supplier.supplier_id
                       JOIN orders ON order_product.order_id = orders.order_id
                       WHERE order_product.order_id = :orderId");
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo "No results found";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$pdo = null;
?>
