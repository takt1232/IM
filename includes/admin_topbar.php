    <section class="dashboard">
      <div class="top">
        <i class="fas fa-bars sidebar-toggle"></i>

        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search..." />
        </div>

        <div class="notif-and-img">
          <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <div class="notification-badge">0</div>
            <div class="notification-container">
              <?php
                // Assuming you have a PDO connection established
                $query = "SELECT n.notification_id, s.supplier_name, n.message, n.notification_date 
                    FROM admin_notification n
                    INNER JOIN supplier s ON n.supplier_id = s.supplier_id";
                $statement = $pdo->query($query);

                if ($statement->rowCount() > 0) {
                  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                    $supplierName = $row['supplier_name'];
                    $message = $row['message'];
                    $notificationDate = $row['notification_date'];

                    // Output the notification item HTML
                    echo '<div class="notification">';
                    echo '<div class="notification-title">' . $supplierName . '</div>';
                    echo '<div class="notification-message">' . $message . '</div>';
                    echo '<div class="notification-date">' . $notificationDate . '</div>';
                    echo '</div>';
                  }
                } else {
                  echo '<div class="empty-notification">No notifications found.</div>';
                }
              ?>
            </div>
          </div>
          <img src="../media/profile.png" alt="profile" />
        </div>

      </div>