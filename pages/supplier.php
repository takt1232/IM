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
    Suppliers
    <span class="popup-icon" onclick="openPopup()">
      <i class="fas fa-plus"></i> Add Suppliers
    </span>
  </h2>
  <?php

  $sql = "SELECT * FROM supplier";
  $stmt = $pdo->query($sql);

  if ($stmt->rowCount() > 0) {
    echo "<table class='product-table'>";
    echo "<thead><tr><th>Supplier Name</th><th>Supplier Address</th><th>Supplier Phone</th><th>Supplier Email</th><th>Action</th></tr></thead>";
    echo "<tbody>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo "<tr>";
      echo "<td>" . $row['supplier_name'] . "</td>";
      echo "<td>" . $row['supplier_address'] . "</td>";
      echo "<td>" . $row['supplier_phone'] . "</td>";
      echo "<td>" . $row['supplier_email'] . "</td>";
      echo "<td>";
      echo "<a href='#' onclick='openEditSupplierPopup(" . json_encode($row) . ")' class='edit'><i class='fas fa-edit'></i></a>";
      echo "<a href='#' onclick='openDeleteSupplierPopup(" . $row['supplier_id'] . ")' class='delete'><i class='fas fa-trash'></i></a>";
      echo "</td>";
      echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
  } else {
    echo "No suppliers found.";
  }
  ?>

  <div id="add-form">
    <form action="supplier_add.php" method="POST">
      <h3>Add Supplier</h3>
      <label for="supplier-name">Supplier Name:</label>
      <input type="text" name="supplier-name" required>

      <label for="supplier-address">Supplier Address:</label>
      <input type="text" name="supplier-address" required>

      <label for="supplier-price">Supplier Phone:</label>
      <input type="text" name="supplier-phone" required>

      <label for="supplier-email">Supplier Email:</label>
      <input type="text" name="supplier-email" required>

      <div class="modal-button">
          <button type="button" class="close-button" onclick="closePopup()">Close</button>
          <button type="submit">Add</button>
      </div>
    </form>
  </div>

  <div id="edit-form">
    <form action="supplier_edit.php" method="POST">
      <h3>Edit Supplier</h3>
      <input type="hidden" id="supplier-id" name="supplier-id">
      <label for="supplier-name">Supplier Name:</label>
      <input type="text" id="supplier-name" name="supplier-name" required>

      <label for="supplier-address">Supplier Address:</label>
      <input type="text" id="supplier-address" name="supplier-address" required>

      <label for="supplier-phone">Supplier Phone:</label>
      <input type="text" id="supplier-phone" name="supplier-phone" required>

      <label for="supplier-email">Supplier Email:</label>
      <input type="text" id="supplier-email" name="supplier-email" required>

      <div class="modal-button">
        <button type="button" class="close-button" onclick="closeEditSupplierPopup()">Close</button>
        <button type="submit">Update</button>
      </div>
    </form>
  </div>

  <div id="delete-form">
    <form action="supplier_del.php" method="POST">
      <h3>Are you sure you want to delete this supplier?</h3>
      <input type="hidden" id="supplier-id-del" name="supplier-id">
      <div class="modal-button">
         <button type="button" class="close-button" onclick="closeDeleteSupplierPopup()">No</button>
        <button type="submit">Yes</button>
      </div>
    </form>
  </div>

  <script src="../js/supplier_modal.js?v=p<?php echo time(); ?>;"></script>

  <?php
  include "../includes/footbar.php";
  ?>
  <?php
} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
?>
