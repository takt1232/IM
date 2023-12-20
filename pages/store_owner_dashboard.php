<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'Store Owner') {
	include "../includes/store_sidebar.php";
	include "../includes/topbar.php";
?>

	<div class="dash-content">
		<div class="header">
			<h1>Dashboard</h1>
		</div>
		<div class="header">
			<h2>New Products</h2>

			<div class="products-container">
				<?php
				// Include the database connection file
				include "../includes/db_connection.php";

				try {
					// Retrieve products stocked within the last 10 days
					$query = "SELECT p.product_name, pd.product_quantity, pd.product_price, DATE_FORMAT(pd.stocked_date, '%M %d, %Y') AS human_readable_date 
							  FROM product p
							  INNER JOIN product_details pd ON p.product_id = pd.product_id 
							  WHERE pd.stocked_date >= DATE_SUB(CURDATE(), INTERVAL 10 DAY)";
					$statement = $pdo->prepare($query);
					$statement->execute();
					$products = $statement->fetchAll(PDO::FETCH_ASSOC);

					// Display the products
					foreach ($products as $product) {
						echo "<a href='order.php' class='card-link'>"; // Opening anchor tag for clickable card
						echo "<div class='card'>";
						echo "<h2>" . $product['product_name'] . "</h2>";
						echo "<p>Quantity: " . $product['product_quantity'] . "</p>";
						echo "<p>Price: ₱ " . $product['product_price'] . "</p>";
						echo "<p>Stocked Date: " . $product['human_readable_date'] . "</p>";
						
						// Styling for new products (customize as needed)
						$currentDate = date("Y-m-d");
						$stockedDate = date("Y-m-d", strtotime($product['human_readable_date']));
						
						// Calculate difference in days between current date and stocked date
						$daysDifference = abs(strtotime($currentDate) - strtotime($stockedDate)) / (60 * 60 * 24);
						
						if ($daysDifference <= 10) {
						    echo "<span class='new-product-badge'>New!</span>";
						}
						
						// Close the card div and anchor tag
						echo "</div>";
						echo "</a>"; // Closing anchor tag
					}
				} catch (PDOException $e) {
					echo "Error: " . $e->getMessage();
				}
				?>
			</div>
		</div>
	</div>

	<style>
		/* Styling for the new product badge */
		.new-product-badge {
			background-color: #ffcc00; /* Yellow background */
			color: #fff; /* White text */
			padding: 5px 10px; /* Padding around the text */
			border-radius: 5px; /* Rounded corners */
			font-weight: bold; /* Bold text */
			position: absolute; /* Position the badge */
			top: 10px; /* Adjust top position */
			right: 10px; /* Adjust left position */
		}
		a {
			text-decoration: none;
			color: black;
		}
	</style>

<?php
	include "../includes/footbar.php";
}
?>
