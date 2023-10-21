<?php 
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
?>
<?php
  include "../includes/sidebar.php";
  include "../includes/admin_topbar.php";
?>
<div class="dash-content">
  <h2>
    Stores
    <span class="popup-icon" onclick="openPopup()">
      <i class="fas fa-plus"></i> Add Store
    </span>
  </h2>
  <?php

    $sql = "SELECT * FROM store";

    $result = $pdo->query($sql);

    if ($result->rowCount() > 0) {
      echo "<table class='product-table'>";
      echo "<thead><tr><th>Store Name</th><th>Store Address</th><th>Store Phone</th><th>Store Email</th><th>Action</th></tr></thead>";
      echo "<tbody>";
      while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['store_name'] . "</td>";
        echo "<td>" . $row['store_address'] . "</td>";
        echo "<td>" . $row['store_phone'] . "</td>";
        echo "<td>" . $row['store_email'] . "</td>";
        echo "<td>";
        echo "<a href='#' onclick='openEditStorePopup(" . json_encode($row) . ")' class='edit'><i class='fas fa-edit'></i></a>";
        echo "<a href='#' onclick='openDeleteStorePopup(" . $row['store_id'] . ")' class='delete'><i class='fas fa-trash'></i></a>";
        echo "</td>";
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";
    } else {
      echo "No stores found.";
    }
?>

<div id="add-form">
  <form action="store_add.php" method="POST">
    <h3>Add Store</h3>
    <label for="store-name">Store Name:</label>
    <input type="text" name="store-name" required>

    <label for="store-address">Store Address:</label>
    <input type="text" name="store-address" required>

    <label for="store-price">Store Phone:</label>
    <input type="text" name="store-phone" required>

    <label for="store-email">Store Email:</label>
    <input type="text" name="store-email" required>

    <div class="modal-button">
      <button type="button" class="close-button" onclick="closePopup()">Close</button>
      <button type="submit">Add</button>
    </div>
  </form>
</div>

<div id="edit-form">
  <form action="store_edit.php" method="POST">
    <h3>Edit Store</h3>
    <input type="hidden" id="store-id" name="store-id">
    <label for="store-name">Store Name:</label>
    <input type="text" id="store-name" name="store-name" required>

    <label for="store-address">Store Address:</label>
    <input type="text" id="store-address" name="store-address" required>

    <label for="store-address">Store Phone:</label>
    <input type="text" id="store-phone" name="store-phone" required>

    <label for="store-email">Store Email:</label>
    <input type="text" id="store-email" name="store-email" required>

    <div class="modal-button">
      <button type="button" class="close-button" onclick="closeEditStorePopup()">Close</button>
      <button type="submit">Update</button>
    </div>
  </form>
</div>

<div id="delete-form">
  <form action="store_dels.php" method="POST">
    <h3>Are you sure you want to delete this store?</h3>
    <input type="hidden" id="store-id-del" name="store-id">
    <div class="modal-button">
      <button type="button" class="close-button" onclick="closeDeleteStorePopup()">No</button>
      <button type="submit">Yes</button>
    </div>
  </form>
</div>

<script src="../js/store_modal.js?v=p<?php echo time(); ?>;"></script>

<?php
  include "../includes/footbar.php";
?>
<?php 
} else {
  header("Location: ../index.php?error=access_error");
  exit();
}
?>
