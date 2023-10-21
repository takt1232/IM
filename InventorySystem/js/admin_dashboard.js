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

    function openEditOrderPopup(orderData) {
        document.getElementById("edit-form").style.display = "block";

        document.getElementById("order-id").value = orderData.order_id;
        document.getElementById("store-opt").value = orderData.store_id;
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