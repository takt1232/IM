<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'Supplier') {
	include "../includes/supplier_sidebar.php";
	include "../includes/supplier_topbar.php";
?>
	<div class="dash-content">
		<p>List of customer</p>
	</div>

<?php
	include "../includes/footbar.php";
} else {
	header("Location: ../index.php?error=Access Error");
	exit();
}
?>