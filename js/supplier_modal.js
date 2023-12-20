// Add Product
function openPopup() {
    window.location.href = "../create_supplier.php";
  }

  function closePopup() {
    document.getElementById("add-form").style.display = "none";
  }
  
  function openEditSupplierPopup(supplierData) {
    document.getElementById("edit-form").style.display = "block";

    document.getElementById("supplier-id").value = supplierData.supplier_id;
    document.getElementById("supplier-name").value = supplierData.supplier_name;
    document.getElementById("supplier-address").value = supplierData.supplier_address;
    document.getElementById("supplier-phone").value = supplierData.supplier_phone;
    document.getElementById("supplier-email").value = supplierData.supplier_email;
    document.getElementById("supplier-active").value = supplierData.is_active;
  }  
  
  function closeEditSupplierPopup() {
    document.getElementById("edit-form").style.display = "none";
  }
  
  function openDeleteSupplierPopup(supplierId) {
    document.getElementById("delete-form").style.display = "block";
    document.getElementById("supplier-id-del").value = supplierId;
  }
  
  function closeDeleteSupplierPopup() {
    document.getElementById("delete-form").style.display = "none";
  }
  
  function redirectTo(url) {
    window.location.href = url;
  }
  
document.addEventListener("DOMContentLoaded", function () {
  const activeElements = document.querySelectorAll(".row_active");
  const inactiveElements = document.querySelectorAll(".row_inactive");

  // Convert is_active values and add visual color indications
  activeElements.forEach((element) => {
    element.textContent = "Active";
    element.style.color = "green"; // Set your desired color for Active
    element.style.padding = "8px"; // Adjust padding as needed
    element.style.backgroundColor = "#c0f9d6"; // Set your desired background color for Active
    element.style.borderRadius = "10px";
  });

  inactiveElements.forEach((element) => {
    element.textContent = "Inactive";
    element.style.color = "red"; // Set your desired color for Inactive
    element.style.padding = "8px"; // Adjust padding as needed
    element.style.backgroundColor = "#f9c0c0"; // Set your desired background color for Inactive
    element.style.borderRadius = "10px";
  });
});
