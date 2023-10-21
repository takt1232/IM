<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'store_owner') {

?>

  <?php
  include "../includes/store_sidebar.php";
  include "../includes/topbar.php";

  $store_id = $_SESSION['id'];

  // Assuming $pdo is your database connection
  $stmt = $pdo->prepare("SELECT DISTINCT store_id, store_name FROM store WHERE store_id = :store_id");
  $stmt->bindParam(':store_id', $store_id);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $store_name = $row['store_name'];
  } else {
    $store_name = 'Store Not Found';
  }
  ?>

  <?php
  $sql1 = "SELECT DISTINCT method_id, method FROM payment_method";
  $stmt1 = $pdo->query($sql1);
  $opt1 = "<select class='store-opt' name='method-opt' required>
        <option disabled selected>Select Method</option>";
  while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
    $opt1 .= "<option value='" . $row['method_id'] . "'>" . $row['method'] . "</option>";
  }
  $opt1 .= "</select>";
  ?>

  <div class="dash-content">
    <h1>Order Page</h1>
    <?php
    if (isset($_GET['error'])) {
      echo '<p style="color: red; font-weight: bold;">' . $_GET['error'] . '</p>';
    }
    ?>
    <div class="products-container">
      <?php
      // Retrieve the product data from the database
      $sql = "SELECT p.product_id, p.product_name, pd.product_quantity, pd.product_price FROM product p
                INNER JOIN product_details pd ON p.product_id = pd.product_id WHERE pd.product_quantity > 0";
      $stmt = $pdo->query($sql);

      // Generate the product cards based on the fetched data
      if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $productId = $row["product_id"];
          $productName = $row["product_name"];
          $productQuantity = $row["product_quantity"];
          $productPrice = $row["product_price"];

          echo '
              <div class="card">
                <h2>' . $productName . '</h2>
                <p class="product-quantity" id="' . $productName . '-q" data-quantity="' . $productQuantity . '">Quantity: ' . $productQuantity . '</p>
                <p class="product-price">₱ ' . $productPrice . '</p>
                <input type="number" value="1" id="' . $productName . '-quantity">
                <button class="add-to-cart" data-product="' . $productName . '"' . $productId . '" data-productid="' . $productId . '" data-quantity="' . $productQuantity . '">Add to Cart</button>
              </div>
            ';
        }
      } else {
        echo "No products found.";
      }
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


    <script src="../js/order.js?v=p<?php echo time(); ?>;"></script>

    <?php
    include "../includes/footbar.php";
    ?>
  <?php
} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
  ?>