// Add Product
function openPopup() {
    document.getElementById("add-form").style.display = "block";
  }
  
  function closePopup() {
    document.getElementById("add-form").style.display = "none";
  }
  
  function openEditStorePopup(storeInfo) {
    document.getElementById("edit-form").style.display = "block";

    document.getElementById("store-id").value = storeInfo.store_id;
    document.getElementById("store-name").value = storeInfo.store_name;
    document.getElementById("store-address").value = storeInfo.store_address;
    document.getElementById("store-phone").value = storeInfo.store_phone;
    document.getElementById("store-email").value = storeInfo.store_email;
  }  
  
  function closeEditStorePopup() {
    document.getElementById("edit-form").style.display = "none";
  }
  
  function openDeleteStorePopup(storeId) {
    document.getElementById("delete-form").style.display = "block";
    document.getElementById("store-id-del").value = storeId;
  }
  
  function closeDeleteStorePopup() {
    document.getElementById("delete-form").style.display = "none";
  }
  
  function redirectTo(url) {
    window.location.href = url;
  }