<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {

?>
  <?php
  include "../includes/sidebar.php";
  include "../includes/topbar.php";
  ?>

  <?php
  $sql = "SELECT DISTINCT supplier_id, supplier_name FROM supplier";
  $stmt = $pdo->query($sql);
  $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $opt = "<select class='supplier-opt' name='product-supplier' required>";
  foreach ($suppliers as $supplier) {
    $opt .= "<option value='" . $supplier['supplier_id'] . "'>" . $supplier['supplier_name'] . "</option>";
  }
  $opt .= "</select>";
  ?>

  <div class="dash-content">
    <h2>
      Inventory
      <span class="popup-icon" onclick="openPopup()">
        <i class="fas fa-plus"></i> Add Product
      </span>
    </h2>
    <?php
    $sql = "SELECT p.product_id, p.product_name, pd.product_price, pd.product_quantity, p.supplier_id FROM product p, product_details pd WHERE p.product_id = pd.product_id";
    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
      echo "<table class='product-table'>";
      echo "<tr><th>Product Name</th><th>Product Price</th><th>Product Quantity</th><th>Action</th></tr>";
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>₱ " . $row['product_price'] . "</td>";
        echo "<td>" . $row['product_quantity'] . "</td>";
        echo "<td>";
        echo "<a href='#' onclick='openEditProductPopup(" . json_encode($row) . ")' class='edit'><i class='fas fa-edit'></i></a>";
        echo "<a href='#' onclick='openDeleteProductPopup(" . $row['product_id'] . ")' class='delete'><i class='fas fa-trash'></i></a>";
        echo "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    } else {
      echo "No Products found.";
    }
    ?>

    <div id="add-form">
      <form action="product_add.php" method="POST">
        <h3>Add Product</h3>
        <label for="product-name">Product Name:</label>
        <input type="text" name="product-name" required>

        <label for="product-quantity">Product Quantity:</label>
        <input type="number" min="1" max="9999999" name="product-quantity" required>

        <label for="product-price">Product Price:</label>
        <input type="number" min="1" max="9999999" name="product-price" required>

        <label for="product-supplier">Product Supplier:</label>
        <?php
        echo $opt;
        ?>
        <div class="modal-button">
          <button type="button" class="close-button" onclick="closePopup()">Close</button>
          <button type="submit">Add</button>
        </div>
      </form>
    </div>

    <!-- Additional code for editing product -->
    <?php
    $sql2 = "SELECT DISTINCT supplier_id, supplier_name FROM supplier";
    $stmt2 = $pdo->query($sql2);
    $opt1 = "<select class='supplier-opt' name='product-supplier' id='product-supplier' required>";
    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
      $opt1 .= "<option value='" . $row['supplier_id'] . "'>" . $row['supplier_name'] . "</option>";
    }
    $opt1 .= "</select>";
    ?>

    <div id="edit-form">
      <form action="product_edit.php" method="POST">
        <h3>Edit Product</h3>
        <input type="hidden" id="product-id" name="product-id" value="<?php echo $pi; ?>">
        <label for="product-name">Product Name:</label>
        <input type="text" id="product-name" name="product-name" value="<?php echo $pn; ?>" required>

        <label for="product-quantity">Product Quantity:</label>
        <input type="number" id="product-quantity" name="product-quantity" value="<?php echo $pq; ?>" required>

        <label for="product-price">Product Price:</label>
        <input type="number" id="product-price" name="product-price" value="<?php echo $pp; ?>" required>

        <label for="product-supplier">Product Supplier:</label>
        <?php
        echo $opt1;
        ?>

        <div class="modal-button">
          <button type="button" class="close-button" onclick="closeEditProductPopup()">Close</button>
          <button type="submit">Update</button>
        </div>
      </form>
    </div>

    <div id="delete-form">
      <form action="product_del.php" method="POST">
        <h3>Are you sure you want to delete this product?</h3>
        <input type="hidden" id="product-id-del" name="product-id">
        <div class="modal-button">
          <button type="button" class="close-button" onclick="closeDeleteProductPopup()">No</button>
          <button type="submit">Yes </button>
        </div>
      </form>
    </div>

    <script src="../js/product_modal.js?v=p<?php echo time(); ?>;"></script>

    <?php
    include "../includes/footbar.php";
    ?>
  <?php
} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
  ?>