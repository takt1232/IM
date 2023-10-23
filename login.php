<?php
session_start();
include "includes/db_connection.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['username']);
    $pass = validate($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT u.*, s.supplier_id, st.store_id FROM users u
                               LEFT JOIN supplier s ON u.user_id = s.user_id
                               LEFT JOIN store st ON u.user_id = st.user_id
                               WHERE u.username=:uname AND u.password=:pass");
        $stmt->bindParam(':uname', $uname);
        $stmt->bindParam(':pass', $pass);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $stmt->rowCount() === 1) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            
            if ($row['role'] === 'admin') {
                $_SESSION['id'] = $row['user_id'];
                $_SESSION['role'] = $row['role'];
                header("Location: pages/admin_dashboard.php");
                exit();
            } else if ($row['role'] === 'store_owner') {
                $_SESSION['id'] = $row['store_id'];
                $_SESSION['role'] = $row['role'];
                header("Location: pages/store_owner_dashboard.php");
                exit();
            } else if ($row['role'] === 'supplier') {
                $_SESSION['id'] = $row['supplier_id'];
                $_SESSION['role'] = $row['role'];
                header("Location: pages/supplier_dashboard.php");
                exit();
            }
            
        } else {
            header("Location: index.php?error=Incorrect username or Password");
            exit();
        }
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
} else {
    header("Location: index.php?error");
    exit();
}
?>
