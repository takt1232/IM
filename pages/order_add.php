<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && ($_SESSION['role'] === 'Store Owner' || $_SESSION['role'] === 'admin')) {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['store-id']) && !empty($_POST['method-opt'])) {
        // Include the database connection file
        include "../includes/db_connection.php";

        try {
            // Start a transaction
            $pdo->beginTransaction();

            // Retrieve the order data from the request
            $storeId = $_POST['store-id'];
            $paymentAmount = $_POST['payment-amount'];
            $paymentMethodId = $_POST['method-opt'];
            $paymentStatusId = 2;
            $cartItemsJson = $_POST['cartItems'];
            $dateNow = date("Y-m-d");

            // Insert the order data into the database
            $query = "INSERT INTO orders (order_id, store_id, total_amount, payment_method_id, payment_status_id, order_date) VALUES (NULL, ?, ?, ?, ?, ?)";

            // Prepare the statement
            $statement = $pdo->prepare($query);

            // Bind the parameters
            $statement->bindParam(1, $storeId);
            $statement->bindParam(2, $paymentAmount);
            $statement->bindParam(3, $paymentMethodId);
            $statement->bindParam(4, $paymentStatusId);
            $statement->bindParam(5, $dateNow);

            // Execute the statement
            $statement->execute();

            // Check if the insertion was successful
            if ($statement->rowCount() > 0) {
                // The order was successfully inserted

                // Get the last inserted order_id
                $orderID = $pdo->lastInsertId();

                // Decode the cartItems JSON
                $cartItems = json_decode($cartItemsJson, true);

                // Loop through the cartItems and insert into order_product table
                foreach ($cartItems as $cartItem) {
                    $productID = $cartItem['productId'];
                    $quantity = $cartItem['quantity'];
                    $price = $cartItem['price'] * $cartItem['quantity'];

                    // Insert the order_product data into the database
                    $query = "INSERT INTO order_product (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";

                    // Prepare the statement
                    $statement = $pdo->prepare($query);

                    // Bind the parameters
                    $statement->bindParam(1, $orderID);
                    $statement->bindParam(2, $productID);
                    $statement->bindParam(3, $quantity);
                    $statement->bindParam(4, $price);

                    // Execute the statement
                    $statement->execute();
                }

                // Commit the transaction
                $pdo->commit();

                // Success message
                echo "<script type='text/javascript'>
                    alert('You\'ve Placed An Order Successfully.');
                    window.location = 'order.php';
                </script>";
            } else {
                // An error occurred while inserting the order
                echo "Failed to place the order.";
            }
        } catch (PDOException $e) {
            // An exception occurred, rollback the transaction
            $pdo->rollBack();
            echo "Error: " . $e->getMessage();
        } finally {
            // Close the statement
            $statement = null;
        }
    } else {
        header("Location: order.php?error=store or method is empty");
        exit();
    }
} else {
    header("Location: ../index.php?error=Access Error");
    exit();
}
?>
