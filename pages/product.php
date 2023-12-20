<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
?>

  <?php
  include "../includes/sidebar.php";
  include "../includes/topbar.php";
  ?>

  <div class="dash-content">
    <h2>
      Products
    </h2>
    <?php

    $sql = "CALL GetProductDetails()";

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
    $stmt->closeCursor();
    ?>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        var quantityCells = document.querySelectorAll('.product-table td:nth-child(2)');

        quantityCells.forEach(function(cell) {
          var quantity = parseInt(cell.textContent);

          if (quantity <= 10) {
            cell.style.color = 'red';
          }
        });
      });
    </script>

    <?php
    include "../includes/footbar.php";
    ?>
  <?php

} else {
  header("Location: ../index.php?error=Access Error");
  exit();
}
  ?>