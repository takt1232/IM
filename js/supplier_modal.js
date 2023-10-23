// Add Product
function openPopup() {
    document.getElementById("add-form").style.display = "block";
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