<?php 
    include "../includes/db_connection.php";

    $si = $_POST['store-id'];
    $sn = $_POST['store-name'];
    $sa = $_POST['store-address'];
    $sp = $_POST['store-phone'];
    $se = $_POST['store-email'];

    $sql = 'UPDATE store SET store_name = :store_name, store_address = :store_address, store_phone = :store_phone, store_email = :store_email WHERE store_id = :store_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':store_name', $sn);
    $stmt->bindParam(':store_address', $sa);
    $stmt->bindParam(':store_phone', $sp);
    $stmt->bindParam(':store_email', $se);
    $stmt->bindParam(':store_id', $si);
    $stmt->execute();
?>
<script type="text/javascript">
    alert("You've Updated A Store information Successfully.");
    window.location = "store.php";
</script>
