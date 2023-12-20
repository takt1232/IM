document.addEventListener("DOMContentLoaded", function() {
    var statusElements = document.querySelectorAll(".order-table .status");

    for (var i = 0; i < statusElements.length; i++) {
        var statusValue = statusElements[i].textContent;

        if (statusValue === "received") {
            statusElements[i].classList.add("received");
        } else if (statusValue === "cancelled") {
            statusElements[i].classList.add("cancelled");
        } else if (statusValue === "pending") {
            statusElements[i].classList.add("pending");
        }
    }
});

    function openViewOrderPopup(orderId) {
        var tableBody = document.querySelector("#view-form .order-table tbody");
        tableBody.innerHTML = ""; // Clear any existing rows

        // Make an AJAX request to fetch product details
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "get_product_details.php?orderId=" + orderId, true);

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 400) {
                // Success
                var productDetails = JSON.parse(xhr.responseText);

                if (productDetails.length > 0) {
                    productDetails.forEach(function (item) {
                        var row = document.createElement("tr");
                        var productNameCell = document.createElement("td");
                        productNameCell.textContent = item.product_name;

                        var quantityCell = document.createElement("td");
                        quantityCell.textContent = item.quantity;

                        var priceCell = document.createElement("td");
                        priceCell.textContent = '₱' + item.price;

                        var supplierCell = document.createElement("td");
                        supplierCell.textContent = item.supplier_name;

                        row.appendChild(productNameCell);
                        row.appendChild(quantityCell);
                        row.appendChild(priceCell);
                        row.appendChild(supplierCell);

                        tableBody.appendChild(row);
                    });

                    // Show the view-form
                    document.getElementById("view-form").style.display = "block";
                } else {
                    // No results found
                    var noResultsRow = document.createElement("tr");
                    var noResultsCell = document.createElement("td");
                    noResultsCell.colSpan = 4; // Span all columns
                    noResultsCell.textContent = "No results found";
                    noResultsRow.appendChild(noResultsCell);
                    tableBody.appendChild(noResultsRow);
                    document.getElementById("view-form").style.display = "block";
                }
            } else {
                // Error
                console.error('Error fetching product details');
            }
        };

        xhr.send();
    }

    function closeViewOrderPopup() {
        document.getElementById("view-form").style.display = "none";
    }

    function openEditOrderPopup(orderData) {
        document.getElementById("edit-form").style.display = "block";

        document.getElementById("order-id").value = orderData.order_id;
        document.getElementById("store-id").value = orderData.store_id;
        document.getElementById("store-name").value = orderData.store_name;
        document.getElementById("total-amount").value = orderData.total_amount;
        document.getElementById("method-opt").value = orderData.payment_method_id;
        document.getElementById("status-opt").value = orderData.payment_status_id;
        document.getElementById("order-date").value = orderData.order_date;
    }

    function closeEditOrderPopup() {
        document.getElementById("edit-form").style.display = "none";
    }

    function openDeleteOrderPopup(orderId) {
        document.getElementById("delete-form").style.display = "block";

        document.getElementById("delete-order-id").value = orderId;
    }

    function closeDeleteOrderPopup() {
        document.getElementById("delete-form").style.display = "none";
    }