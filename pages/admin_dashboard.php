<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {

?>
    <?php
    include "../includes/sidebar.php";
    include "../includes/admin_topbar.php";
    ?>

    <?php
    // Count the number of stores
    $sqlStore = 'SELECT COUNT(*) AS store_count FROM store';
    $stmtStore = $pdo->query($sqlStore);
    $rowStore = $stmtStore->fetch(PDO::FETCH_ASSOC);
    $nos = $rowStore['store_count'];

    // Count the number of products
    $sqlProduct = 'SELECT COUNT(*) AS product_count FROM product';
    $stmtProduct = $pdo->query($sqlProduct);
    $rowProduct = $stmtProduct->fetch(PDO::FETCH_ASSOC);
    $nop = $rowProduct['product_count'];

    // Count the number of suppliers
    $sqlSupplier = 'SELECT COUNT(*) AS supplier_count FROM supplier';
    $stmtSupplier = $pdo->query($sqlSupplier);
    $rowSupplier = $stmtSupplier->fetch(PDO::FETCH_ASSOC);
    $noss = $rowSupplier['supplier_count'];

    ?>

    <?php


    $sql1 = "SELECT DISTINCT method_id, method FROM payment_method";
    $stmt1 = $pdo->query($sql1);
    $opt1 = "<select class='store-opt' name='method-opt' id='method-opt' required>
        <option disabled selected>Select Method</option>";
    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        $opt1 .= "<option value='" . $row['method_id'] . "'>" . $row['method'] . "</option>";
    }
    $opt1 .= "</select>";

    $sql2 = "SELECT DISTINCT status_id, status FROM payment_status";
    $stmt2 = $pdo->query($sql2);
    $opt2 = "<select class='store-opt' name='status-opt' id='status-opt' required>
        <option disabled selected>Select Method</option>";
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        $opt2 .= "<option value='" . $row['status_id'] . "'>" . $row['status'] . "</option>";
    }
    $opt2 .= "</select>";
    ?>

    <div class="dash-content">
        <div class="header">
            <h1>Welcome to the Dashboard</h1>
        </div>
        <div class="card">
            <h2>Stores</h2>
            <p class="card-number"><?php echo $nos; ?></p>
        </div>
        <div class="card">
            <h2>Products</h2>
            <p class="card-number"><?php echo $nop; ?></p>
        </div>
        <div class="card">
            <h2>Suppliers</h2>
            <p class="card-number"><?php echo $noss; ?></p>
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
                $orderQuery = "SELECT s.store_name, o.total_amount, pm.method, ps.status, o.order_date, o.store_id, o.payment_method_id, o.payment_status_id, o.order_id
                                FROM orders o 
                                INNER JOIN payment_method pm ON o.payment_method_id = pm.method_id
                                INNER JOIN payment_status ps ON o.payment_status_id = ps.status_id
                                INNER JOIN store s ON o.store_id = s.store_id";
                $orderStmt = $pdo->query($orderQuery);

                while ($orderRow = $orderStmt->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $orderRow['store_name']; ?></td>
                        <td>
                            <p>₱ <?php echo $orderRow['total_amount']; ?>
                        </td>
                        <td><?php echo $orderRow['method']; ?></td>
                        <td><span class="status"><?php echo $orderRow['status']; ?></span></td>
                        <td><?php echo $orderRow['order_date']; ?></td>
                        <td><a href="#" onclick='openViewOrderPopup(<?php echo $orderRow["order_id"]; ?>)' class="view"><i class="fa-solid fa-eye"></i></a>
                            <a href='#' onclick='openEditOrderPopup(<?php echo json_encode($orderRow); ?>)' class='edit'>
                                <i class='fas fa-edit'></i><a href='#' class="delete" onclick='openDeleteOrderPopup(<?php echo $orderRow["order_id"]; ?>)'>
                                    <i class='fas fa-trash'></i></a>
                        </td>
                    </tr>
                <?php
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

    <div id="edit-form">
        <form action="order_edit.php" method="POST">
            <h3>Edit Order</h3>
            <input type="hidden" id="order-id" name="order-id">
            <input type="hidden" id="store-id" name="store-id">
            <label for="store-name">Store Name</label>
            <input type="text" class="form-control" id="store-name" name="store-name" readonly>

            <label for="total-amount">Total Amount:</label>
            <input type="number" id="total-amount" name="total-amount" readonly>

            <label for="payment-method">Payment Method</label>
            <?php
            echo $opt1;
            ?>

            <label for="payment-status">Payment Status</label>
            <?php
            echo $opt2;
            ?>

            <label for="order-date">Order-date:</label>
            <input type="date" id="order-date" name="order-date" required>

            <div class="modal-button">
                <button type="button" class="close-button" onclick="closeEditOrderPopup()">Close</button>
                <button type="submit">Update</button>
            </div>
        </form>
    </div>

    <div id="delete-form">
        <form action="order_delete.php" method="POST">
            <h3>Are you sure you want to delete this order?</h3>
            <input type="hidden" id="delete-order-id" name="order-id">
            <div class="modal-button">
                <button type="button" class="close-button" onclick="closeDeleteOrderPopup()">No</button>
                <button type="submit">Yes</button>
            </div>
        </form>
    </div>

    <script src="../js/admin_dashboard.js?v=p<?php echo time(); ?>;"></script>

    <?php
    include "../includes/footbar.php";
    ?>

<?php
} else {
    header("Location: ../index.php?error=access_error");
    exit();
}
?>