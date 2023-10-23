<?php
$host = "localhost";
$dbname = "inventory";
$username = "root";
$password = "";

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // You can set additional PDO attributes if needed
    
    // You can start executing queries using the $pdo object
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
