<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'store_owner') {
    include "../includes/store_sidebar.php";
    include "../includes/topbar.php";
?>

    <div class="dash-content">
        <div class="header">
            <h1>Order Status</h1>
        </div>
    </div>

    <div class="order-table-div">
        <h2>Orders Table</h2>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Order Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $store_id = $_SESSION['id'];

                $orderQuery = "SELECT s.store_name, o.total_amount, pm.method, ps.status, o.order_date, o.store_id, o.payment_method_id, o.payment_status_id, o.order_id
                FROM orders o 
                INNER JOIN payment_method pm ON o.payment_method_id = pm.method_id
                INNER JOIN payment_status ps ON o.payment_status_id = ps.status_id
                INNER JOIN store s ON o.store_id = s.store_id
                WHERE o.store_id = :store_id"; // Added WHERE clause

                $orderStmt = $pdo->prepare($orderQuery);
                $orderStmt->bindParam(':store_id', $store_id, PDO::PARAM_INT);
                $orderStmt->execute();

                if ($orderStmt->rowCount() > 0) {
                    while ($orderRow = $orderStmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <tr>
                            <td><?php echo $orderRow['store_name']; ?></td>
                            <td>
                                <p>₱ <?php echo $orderRow['total_amount']; ?></p>
                            </td>
                            <td><?php echo $orderRow['method']; ?></td>
                            <td><span class="status"><?php echo $orderRow['status']; ?></span></td>
                            <td><?php echo $orderRow['order_date']; ?></td>
                            <td><a href="#" onclick='openViewOrderPopup(<?php echo $orderRow["order_id"]; ?>)' class="view"><i class="fa-solid fa-eye"></i></a></td>
                        </tr>
                <?php
                    }
                } else {
                    // Display a message when no orders are found
                    echo '<tr><td colspan="6">No orders found.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="view-form">
        <form>
            <h3>Edit Order</h3>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Supplier</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <div class="modal-button">
                <button type="button" class="close-button" onclick="closeViewOrderPopup()">Close</button>
            </div>
        </form>
    </div>

    <script src="../js/store_owner_status.js?v=p<?php echo time(); ?>;"></script>

<?php
    include "../includes/footbar.php";
} else {
    header("Location: ../index.php?access_error");
    exit();
}
?>