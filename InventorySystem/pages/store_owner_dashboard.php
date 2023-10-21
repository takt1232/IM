<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['role'] === 'store_owner') {
	include "../includes/store_sidebar.php";
	include "../includes/topbar.php";




	include "../includes/footbar.php";
} else {
	header("Location: ../index.php?access_error");
	exit();
}
?>
