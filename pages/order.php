<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'Store Owner') {

?>

  <?php
  include "../includes/store_sidebar.php";
  include "../includes/topbar.php";
  include "../includes/functions.php";

  $store_id = $_SESSION['id'];
  $store_name = getStoreName($pdo, $store_id);

  if ($store_name !== false) {
    echo "Store Name: " . $store_name;
  } else {
    header("Location: index.php?error=Store Name Not Found!");
    exit();
  }
  ?>

  <?php
  $opt1 = generateMethodDropdown($pdo);
  ?>

  <div class="dash-content">
    <h1>Order Page</h1>
    <form action="order.php" method="GET" class="filter-form">
      <div class="form-content">
        <label for="min_price">Min Price:</label>
        <input type="number" name="min_price" id="min_price" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
        <label for="max_price">Max Price:</label>
        <input type="number" name="max_price" id="max_price" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
        <!-- Example dropdown for supplier -->
        <!-- <label for="supplier_id">Supplier:</label>
        <select name="supplier_id" id="supplier_id">
            <option value="">Select Supplier</option>
        </select> -->
      </div>
      <div class="form-submit">
        <input type="button" value="Reset" onclick="resetForm()" class="reset">
        <input type="submit" value="Filter">
      </div>
    </form>

    <script>
      function resetForm() {
        window.location.href = "order.php";
      }
    </script>

    <?php
    if (isset($_GET['error'])) {
      echo '<p style="color: red; font-weight: bold;">' . $_GET['error'] . '</p>';
    }
    ?>
    <div class="products-container">
    <?php
    $minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? $_GET['min_price'] : null;
    $maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? $_GET['max_price'] : null;
    $supplierId = isset($_GET['supplier_id']) && $_GET['supplier_id'] !== '' ? $_GET['supplier_id'] : null;


    // Prepare SQL statement
    $sql = "CALL GetFilteredProducts(:p_min_price, :p_max_price, :p_supplier_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':p_min_price', $minPrice, PDO::PARAM_INT);
    $stmt->bindParam(':p_max_price', $maxPrice, PDO::PARAM_INT);
    $stmt->bindParam(':p_supplier_id', $supplierId, PDO::PARAM_INT);
    $stmt->execute();


    // Generate the product cards based on the fetched data
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productId = $row["product_id"];
            $productName = $row["product_name"];
            $productQuantity = $row["product_quantity"];
            $productPrice = $row["product_price"];
            $supplierName = $row["supplier_name"];
            $stockedDate = $row["stocked_date"];
            $isActive = "<span class='" . ($row['is_active'] == 1 ? 'row_active' : 'row_inactive') . "'>" . $row['is_active'] . "</span>";

            echo '
            <div class="card">
                <h2>' . $productName . '</h2>
                <p class="product-quantity" id="' . $productName . '-q">Quantity: ' . $productQuantity . '</p>
                <p class="product-price">Price: ₱ ' . $productPrice . '</p>
                <p class="supplier-info">Supplier: ' . $supplierName . '</p>
                <p class="supplier-info">Supplier Status: ' . $isActive . '</p>
                <p class="supplier-info">Stocked Date: ' . $stockedDate . '</p>
                <input type="number" value="1" id="' . $productName . '-quantity">
                <button class="add-to-cart" data-product="' . $productName . '" data-productid="' . $productId . '" data-quantity="' . $productQuantity . '" data-product-price="' . $productPrice . '">Add to Cart</button>';

            // Check if the product is new (stocked within the last 10 days)
            $currentDate = date("Y-m-d");
            $stockedDate = date("Y-m-d", strtotime($row['stocked_date']));
            $daysDifference = abs(strtotime($currentDate) - strtotime($stockedDate)) / (60 * 60 * 24);

            if ($daysDifference <= 10) {
                echo '<span class="new-product-badge">New!</span>';
            }

            echo '</div>';
        } // Close the while loop
    } else {
        echo "No products found.";
    }
    $stmt->closeCursor();
    ?>
</div>

    <h2 class="cart-header">Cart</h2>
    <div class="cart-container">
      <table id="cart-items">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Cart items will be dynamically added here -->
        </tbody>
      </table>
      <div class="cart-actions">
        <button id="clear-cart">Clear Cart</button>
        <button id="checkout-button">Order</button>
      </div>
      <p id="total-amount">Total: ₱ 0.00</p>
    </div>

    <!-- Modal Form -->
    <div id="checkout-form">
      <form action="order_add.php" method="POST">
        <h3>Place an order</h3>
        <div class="form-group">
          <label for="store-name">Store Name</label>
          <input type="text" class="form-control" id="store-name" name="store-name" value="<?php echo htmlspecialchars($store_name); ?>" readonly>
          <input type="hidden" id="store-id" name="store-id" value="<?php echo $store_id; ?>">
        </div>
        <div class="form-group">
          <label for="payment-amount">Payment Amount</label>
          <input type="number" id="payment-amount" name="payment-amount" step="0.01" min="0" readonly>
          <input type="hidden" id="cartItems" name="cartItems">
          <input type="hidden" name="date-now">
        </div>
        <div class="form-group">
          <label for="payment-method">Payment Method</label>
          <?php
          echo $opt1;
          ?>
        </div>
        <div class="form-group">
          <label>Ordered Products</label>
          <button type="button" id="show-ordered-products">Show Ordered Products</button>
          <div id="ordered-products-modal"></div> <!-- Container for ordered products -->
        </div>
        <div class="modal-button">
          <button type="button" class="close-button" onclick="closeCheckoutPopup()">Close</button>
          <button id="order-submit-button" type="submit">Order</button>
        </div>
      </form>
    </div>
    <style>
		/* Styling for the new product badge */
		.new-product-badge {
			background-color: #ffcc00; /* Yellow background */
			color: #fff; /* White text */
			padding: 5px 10px; /* Padding around the text */
			border-radius: 5px; /* Rounded corners */
			font-weight: bold; /* Bold text */
			position: absolute; /* Position the badge */
			top: 10px; /* Adjust top position */
			right: 10px; /* Adjust left position */
		}
	</style>

    <script src="../js/order.js?v=p<?php echo time(); ?>;"></script>

    <?php
    include "../includes/footbar.php";
    ?>
  <?php
} else {
  header("Location: ../index.php?error=Access Error");
  exit();
}
  ?>