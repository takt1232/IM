<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
?>
<?php
  include "../includes/sidebar.php";
  include "../includes/admin_topbar.php";
?>

  <div class="dash-content">
    <div class="messaging-container">
      <div class="messaging-header">Messaging</div>
      <div class="messaging-body">
        <?php
        // Assuming you have a database connection and query execution beforehand

        // Fetching data from admin_notification table and joining with supplier and product tables
        $stmt = $pdo->query("SELECT n.*, s.supplier_name AS supplier_name, p.product_name AS product_name
                             FROM admin_notification n
                             INNER JOIN supplier s ON n.supplier_id = s.supplier_id
                             INNER JOIN product p ON n.product_id = p.product_id");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $supplierName = $row['supplier_name'];
          $productName = $row['product_name'];
          $message = $row['message'];
          $notificationDate = $row['notification_date'];
        ?>
          <div class="message">
            <div class="message-sender" style="text-align: left;">Supplier Name: <?php echo $supplierName; ?></div>
            <div class="message-content" style="text-align: left;">
              Product: <?php echo $productName; ?><br>
              Message: <?php echo $message; ?><br>
              Notification Date: <?php echo $notificationDate; ?>
            </div>
            <div class="message-actions">
              <button class="delete-button" data-message-id="<?php echo $notificationId; ?>"><i class="fas fa-trash"></i></button>
              <button class="reply-button"><i class="fas fa-reply"></i></button>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>


<?php
  include "../includes/footbar.php";
?>
<?php 
}
?>