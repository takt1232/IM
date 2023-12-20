<?php

function generateMethodDropdown($pdo)
{
    $sql = "SELECT DISTINCT method_id, method FROM payment_method";
    $stmt = $pdo->query($sql);
    $options = "<select class='store-opt' name='method-opt' id='method-opt' required>
                    <option disabled selected>Select Method</option>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='" . $row['method_id'] . "'>" . $row['method'] . "</option>";
    }
    $options .= "</select>";
    return $options;
}

function generateStatusDropdown($pdo)
{
    $sql = "SELECT DISTINCT status_id, status FROM payment_status";
    $stmt = $pdo->query($sql);
    $options = "<select class='store-opt' name='status-opt' id='status-opt' required>
                    <option disabled selected>Select Status</option>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='" . $row['status_id'] . "'>" . $row['status'] . "</option>";
    }
    $options .= "</select>";
    return $options;
}

function getStoreName($pdo, $session_id)
{
    $stmt = $pdo->prepare("SELECT DISTINCT store_id, store_name FROM store WHERE store_id = :store_id");
    $stmt->bindParam(':store_id', $session_id);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['store_name'];
    } else {
        return false;
    }
}

function getSupplier($pdo)
{
    $sql = "SELECT DISTINCT supplier_id, supplier_name FROM supplier";
  $stmt = $pdo->query($sql);
  $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $opt = "<select class='supplier-opt' name='product-supplier' required>";
  foreach ($suppliers as $supplier) {
    $opt .= "<option value='" . $supplier['supplier_id'] . "'>" . $supplier['supplier_name'] . "</option>";
  }
  $opt .= "</select>";
    return $opt;
}

function getTopProductsByTimeFrame($pdo, $startDate, $endDate) {

    $sql = "CALL GetTop3ProductsByQuantityForTimeFrame('$startDate', '$endDate')";
    $result = $pdo->query($sql);

    displayVerticalBarGraph($result);
}

function displayVerticalBarGraph($result) {
    // Fetch data here and store it in $result

    echo '<div class="vertical-bar-graph">';
    foreach ($result as $row) {
        $product_name = $row['product_name'];
        $highest_quantity = $row['highest_quantity'];
        echo '<div class="vertical-bar">';
        echo '<div class="bar-fill" style="height:' . $highest_quantity . 'px;"></div>';
        echo '<span class="bar-label">' . $product_name . ' (' . $highest_quantity . ')</span>';
        echo '</div>';
    }
    echo '</div>';
}
