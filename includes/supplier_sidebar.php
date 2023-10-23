<?php 
    include "db_connection.php";
?>

<!DOCTYPE html>
<html lang="en">
  <!--Created by Tivotal-->
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>INVENTORY SYSTEM</title>

    <!--font awesome-->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    />

    <!--css file-->
    <link rel="stylesheet" href="../css/dashboard.css?v=p<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/supplier_modal.css?v=p<?php echo time(); ?>" />
  </head>
  <body>
    <nav>
      <div class="logo">
        <div class="logo-icon">
          <i class="fas fa-cubes"></i>
        </div>
        <span class="logo_name">INVENTORY SYSTEM</span>
      </div>
    
      <div class="menu-items">
        <ul class="nav-links">
          <li>
            <a href="../pages/supplier_dashboard.php">
              <i class="fas fa-tachometer-alt"></i>
              <span class="link-name">Dashboard</span>
            </a>
          </li>
          <li>
            <a href="../pages/supplier_product.php">
              <i class="fas fa-box-open"></i>
              <span class="link-name">Products</span>
            </a>
          </li>
        <ul class="logout-mode">
          <li>
            <a href="../pages/logout.php">
              <i class="fas fa-sign-out-alt"></i>
              <span class="link-name">Logout</span>
            </a>
          </li>
          <!--
          <li class="mode">
            <a href="#">
              <i class="fas fa-moon"></i>
              <span class="link-name">Dark Mode</span>
            </a>
            <div class="mode-toggle">
              <span class="switch"></span>
            </div>
          </li>
        -->
        </ul>
      </div>
    </nav>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Get the current page URL
        var currentPageUrl = window.location.href;

        // Get all the menu items
        var menuItems = document.querySelectorAll(".menu-items li");

        // Iterate over each menu item
        menuItems.forEach(function (menuItem) {
          // Get the link within the menu item
          var link = menuItem.querySelector("a");

          // Check if the link's href matches the current page URL
          if (link.href === currentPageUrl) {
            // Add the 'active' class to the menu item
            menuItem.classList.add("active");
          }
        });
      });
  </script>