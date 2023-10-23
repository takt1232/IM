<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'supplier') {
?>

<?php
include "../includes/supplier_sidebar.php";
include "../includes/supplier_topbar.php";
?>

<div class="dash-content">
  <h2>
    Inventory
  </h2>
  <?php

 $sql = "SELECT p.product_name, pd.product_quantity, s.supplier_name
FROM product p
INNER JOIN product_details pd ON p.product_id = pd.product_id
LEFT JOIN supplier s ON p.supplier_id = s.supplier_id";

  $stmt = $pdo->query($sql);

  if ($stmt->rowCount() > 0) {
    echo "<table class='product-table neumorphic'>";
    echo "<tr><th>Product Name</th><th>Product Quantity</th><th>Supplier Name</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      if (empty($row['supplier_name'])) {
        echo "<tr><td>" . $row['product_name'] . "</td><td>" . $row['product_quantity'] . "</td><td>No Supplier</td></tr>";
      } else {
        echo "<tr><td>" . $row['product_name'] . "</td><td>" . $row['product_quantity'] . "</td><td>" . $row['supplier_name'] . "</td></tr>";
      }
    }
    echo "</table>";
  } else {
    echo "No products found.";
  }
  ?>

  <?php
  include "../includes/footbar.php";
  ?>
  
<?php
} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
?>
