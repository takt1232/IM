<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'supplier') {
?>
<?php
  include "../includes/supplier_sidebar.php";
  include "../includes/supplier_topbar.php";
?>

<?php
$sql = "SELECT DISTINCT supplier_id, supplier_name FROM supplier";
$stmt = $pdo->query($sql);
$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dash-content">
  <h2>
    Products 
  </h2>
  <?php if (isset($_GET['status'])) {
      if ($_GET['status'] === 'success') {
        echo "<p style='color : green;'> Notification sent successfully</p>";
      }
    }
  ?>
    <?php
    $sql = "SELECT p.product_id, p.product_name, pd.product_price, pd.product_quantity, p.supplier_id FROM product p, product_details pd WHERE p.product_id = pd.product_id";
    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
      echo "<table class='product-table'>";
      echo "<tr><th>Product Name</th><th>Product Price</th><th>Product Quantity</th><th>Action</th></tr>";
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $quantityColor = ($row['product_quantity'] <= 10) ? 'red' : 'black';
        echo "<tr>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['product_price'] . "</td>";
        echo "<td style='color: " . $quantityColor . "'>" . $row['product_quantity'] . "</td>";
        echo "<td><a href='#' class='message-icon' data-product-id='" . $row['product_id'] . "'><i class='fas fa-paper-plane'></i></a></td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    } else {
      echo "No stores found.";
    }
  ?>

  <!-- Modal HTML -->
  <div id="messageModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <form id="messageForm" action="supplier_send_notif.php" method="POST">
        <input type="hidden" name="product_id" id="product_id">
        <input type="hidden" name="supplier_id" value="<?php echo $_SESSION['id']; ?>">
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea>
        <button type="submit">Send</button>
      </form>
    </div>
  </div>

  <script>
    const productQuantities = document.querySelectorAll('.product-table td:nth-child(3)');

    productQuantities.forEach(quantity => {
      const value = parseInt(quantity.textContent);
      if (value <= 10) {
        quantity.style.color = 'red';
      }
    });
      const messageModal = document.getElementById("messageModal");
  const messageForm = document.getElementById("messageForm");
  const productInputs = document.querySelectorAll(".message-icon");

  productInputs.forEach(product => {
    product.addEventListener("click", function() {
      const productId = this.getAttribute("data-product-id");
      document.getElementById("product_id").value = productId;
      messageModal.style.display = "block";
    });
  });

  const closeBtn = document.querySelector(".close");
  closeBtn.addEventListener("click", function() {
    messageModal.style.display = "none";
  });

  window.addEventListener("click", function(event) {
    if (event.target === messageModal) {
      messageModal.style.display = "none";
    }
  });
  </script>

<?php
  include "../includes/footbar.php";
?>
<?php
} else {
    header("Location: ../index.php?error=access_error");
    exit();
}
?>