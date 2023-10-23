<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'store_owner') {
	include "../includes/store_sidebar.php";
	include "../includes/topbar.php";
?>

	<div class="dash-content">
		<div class="header">
			<h1>Dashboard</h1>
		</div>
	</div>

<?php
	include "../includes/footbar.php";
} else {
	header("Location: ../index.php?access_error");
	exit();
}
?>