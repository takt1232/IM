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
                           WHERE order_product.order_id = :orderId");
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(["message" => "No results found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
}

$pdo = null;
?>
