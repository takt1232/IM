function toggleFields() {
  var role = document.getElementById("role").value;
  var storeFields = document.getElementById("store_fields");
  var supplierFields = document.getElementById("supplier_fields");

  var storeNameInput = document.getElementById("store_name");
  var storeAddressInput = document.getElementById("store_address");
  var storePhoneInput = document.getElementById("store_phone");
  var storeEmailInput = document.getElementById("store_email");

  var supplierNameInput = document.getElementById("supplier_name");
  var supplierAddressInput = document.getElementById("supplier_address");
  var supplierPhoneInput = document.getElementById("supplier_phone");
  var supplierEmailInput = document.getElementById("supplier_email");

  if (role === "store_owner") {
    storeFields.style.display = "block";
    supplierFields.style.display = "none";

    storeNameInput.required = true;
    storeAddressInput.required = true;
    storePhoneInput.required = true;
    storeEmailInput.required = true;

    supplierNameInput.required = false;
    supplierAddressInput.required = false;
    supplierPhoneInput.required = false;
    supplierEmailInput.required = false;

  } else if (role === "supplier") {
    storeFields.style.display = "none";
    supplierFields.style.display = "block";

    storeNameInput.required = false;
    storeAddressInput.required = false;
    storePhoneInput.required = false;
    storeEmailInput.required = false;

    supplierNameInput.required = true;
    supplierAddressInput.required = true;
    supplierPhoneInput.required = true;
    supplierEmailInput.required = true;
    
  } else {
    storeFields.style.display = "none";
    supplierFields.style.display = "none";

    storeNameInput.required = false;
    storeAddressInput.required = false;
    storePhoneInput.required = false;
    storeEmailInput.required = false;

    supplierNameInput.required = false;
    supplierAddressInput.required = false;
    supplierPhoneInput.required = false;
    supplierEmailInput.required = false;

    alert("Please Select a role");
  }
}
// 