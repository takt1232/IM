document.addEventListener('DOMContentLoaded', function() {
    var quantityCells = document.querySelectorAll('.product-table td:nth-child(3)'); // Selecting the third column (quantity)

    quantityCells.forEach(function(cell) {
      var quantity = parseInt(cell.textContent);

      if (quantity <= 10) {
        cell.style.color = 'red';
      }
    });
  });

// Add Product
function openPopup() {
  document.getElementById("add-form").style.display = "block";
}

function closePopup() {
  document.getElementById("add-form").style.display = "none";
}

function openEditProductPopup(productData) {
  document.getElementById("edit-form").style.display = "block";

  document.getElementById("product-id").value = productData.product_id;
  document.getElementById("product-name").value = productData.product_name;
  document.getElementById("product-quantity").value = productData.product_quantity;
  document.getElementById("product-price").value = productData.product_price;

  // Set the selected supplier in the dropdown
  var supplierSelect = document.getElementById("product-supplier");
  if (supplierSelect) {
    supplierSelect.value = productData.supplier_id;
  }
}

function closeEditProductPopup() {
  document.getElementById("edit-form").style.display = "none";
}

function openDeleteProductPopup(productId) {
  document.getElementById("delete-form").style.display = "block";
  document.getElementById("product-id-del").value = productId;
}

function closeDeleteProductPopup() {
  document.getElementById("delete-form").style.display = "none";
}

function redirectTo(url) {
  window.location.href = url;
}
