document.addEventListener("DOMContentLoaded", function () {
  const addToCartButtons = document.querySelectorAll(".add-to-cart"); 
  const clearCartButton = document.getElementById("clear-cart");
  const orderPlaceButton = document.getElementById("order-submit-button");
  const showOrderedButton = document.getElementById("show-ordered-products");
  const totalAmountElement = document.getElementById("total-amount");
  const checkoutButton = document.getElementById("checkout-button");

  const cartItems = {};

  addToCartButtons.forEach((button) => {
    button.addEventListener("click", addToCart);
  });

  clearCartButton.addEventListener("click", clearCart);
  checkoutButton.addEventListener("click", checkOut);
  orderPlaceButton.addEventListener("click", submitOrder);
  showOrderedButton.addEventListener("click", showOrderedProducts);
  
  function addToCart(event) {
  const product = event.target.getAttribute("data-product");
  const productId = event.target.getAttribute("data-productid");
  const quantityInput = document.getElementById(`${product}-quantity`);
  const quantity = parseInt(quantityInput.value);
  const productQuantity = parseInt(event.target.getAttribute("data-quantity"));
  const productPrice = parseFloat(event.target.getAttribute("data-product-price"));


  if (quantity > 0) {
    if (quantity > productQuantity) {
      // Display warning to the user
      alert("Requested quantity exceeds available quantity");
      return; // Abort the process
    } else if (quantity < 0 ) {
      alert("Requested quantity is invalid");
      return;
    }
    
    const updatedQuantity = productQuantity - quantity;
    
    const quantityParagraph = event.target.parentNode.querySelector("p");
    quantityParagraph.textContent = "Quantity: " + updatedQuantity;
    
    event.target.setAttribute("data-quantity", updatedQuantity);

    if (cartItems.hasOwnProperty(product)) {
      cartItems[product].quantity += quantity;
    } else {
      cartItems[product] = {
        productId: productId,
        quantity: quantity,
        price: productPrice
      };
      console.log(cartItems);
    }

    quantityInput.value = 0;

    renderCartItems();
    updateTotalAmount();
  }
}

function removeFromCart(event) {
  const product = event.target.getAttribute("data-product");
  
  const removeCount = prompt("How many entries do you want to remove?", "1");
  const count = parseInt(removeCount);
  
  if (!isNaN(count) && cartItems.hasOwnProperty(product)) {
    if (count > cartItems[product].quantity) {
      // Display warning to the user
      alert("Requested quantity exceeds available quantity");
      return; // Abort the process
    } else if (count == cartItems[product].quantity) {
        delete cartItems[product];
    } 
    else {
      cartItems[product].quantity -= count;
    }
    
  }
  
  renderCartItems();
  updateTotalAmount();
  
  const quantityParagraph = document.getElementById(`${product}-q`);
  const currentQuantity = parseInt(quantityParagraph.textContent.replace("Quantity: ", ""));
  const updatedQuantity = currentQuantity + count;
  quantityParagraph.textContent = "Quantity: " + updatedQuantity;
  
  const addToCartButton = document.querySelector(`button[data-product="${product}"]`);
  const cQuantity = parseInt(addToCartButton.getAttribute("data-quantity"));
  const uQuantity = cQuantity + count;
  addToCartButton.setAttribute("data-quantity", uQuantity);
}


  function clearCart() {
    if (cartItems.length === 0) {
      console.log("cart is empty");
    } else {
      for (let product in cartItems) {
        const quantityParagraph = document.getElementById(`${product}-q`);
        const currentQuantity = parseInt(quantityParagraph.textContent.replace("Quantity: ", ""));
        const updatedQuantity = currentQuantity + cartItems[product].quantity;
        quantityParagraph.textContent = "Quantity: " + updatedQuantity;

        const addToCartButton = document.querySelector(`button[data-product="${product}"]`);
        const cQuantity = parseInt(addToCartButton.getAttribute("data-quantity"));
        const uQuantity = cQuantity + cartItems[product].quantity;
        addToCartButton.setAttribute("data-quantity", uQuantity);

        delete cartItems[product];
      }
    }

    renderCartItems();
    updateTotalAmount();
  }

  function renderCartItems() {
    const cartItemsTable = document.getElementById("cart-items");
    const tbody = cartItemsTable.querySelector("tbody");
  
    tbody.innerHTML = "";
  
    for (let product in cartItems) {
      if (cartItems.hasOwnProperty(product)) {
        const cartItem = document.createElement("tr");
        cartItem.innerHTML = `
          <td>${product}</td>
          <td>${cartItems[product].quantity}</td>
          <td>₱ ${(cartItems[product].price * cartItems[product].quantity).toFixed(2)}</td>
          <td><button id="remove-from-cart" class="remove-from-cart" data-product="${product}">Remove</button></td>
        `;
        tbody.appendChild(cartItem);
      }
    }
  
    const removeButtons = document.querySelectorAll(".remove-from-cart");
    removeButtons.forEach((button) => {
      button.addEventListener("click", removeFromCart);
    });
  }  

  function updateTotalAmount() {
    let totalAmount = 0;
  
    for (let product in cartItems) {
      if (cartItems.hasOwnProperty(product)) {
        const price = parseFloat(cartItems[product].price);
        const quantity = cartItems[product].quantity;
        totalAmount += price * quantity;
      }
    }
  
    totalAmountElement.textContent = "Total: " + `₱ ${totalAmount.toFixed(2)}`;
    document.getElementById('payment-amount').value = totalAmount.toFixed(2);
  }

  function checkOut() {
    if (Object.keys(cartItems).length === 0) {
      alert("Unable to checkout. The cart is empty.");
      return;
    } else {
      openCheckoutPopup();
    }
  }

  function submitOrder() {
    // Convert the cartItems array to JSON
    var cartItemsJSON = JSON.stringify(cartItems);

    document.getElementById("cartItems").value = cartItemsJSON;
  }

  function showOrderedProducts() {
    // Retrieve the ordered products from the cartItems variable
    var orderedProducts = cartItems;

    // Display the ordered products in the modal container
    var orderedProductsContainer = document.getElementById("ordered-products-modal");
    orderedProductsContainer.innerHTML = "<h4>Ordered Products:</h4>";

    // Create the table structure
    var table = document.createElement("table");
    table.classList.add("product-table");

    // Create the table header
    var thead = document.createElement("thead");
    var headerRow = document.createElement("tr");
    var productNameHeader = document.createElement("th");
    productNameHeader.textContent = "Product Name";
    var quantityHeader = document.createElement("th");
    quantityHeader.textContent = "Quantity";
    var priceHeader = document.createElement("th");
    priceHeader.textContent = "Price";
    headerRow.appendChild(productNameHeader);
    headerRow.appendChild(quantityHeader);
    headerRow.appendChild(priceHeader);
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create the table body
    var tbody = document.createElement("tbody");
    for (var productName in orderedProducts) {
      var productData = orderedProducts[productName];
      var totalPrice = productData.price * productData.quantity;
      var productRow = document.createElement("tr");
      var productNameCell = document.createElement("td");
      productNameCell.textContent = productName;
      var quantityCell = document.createElement("td");
      quantityCell.textContent = productData.quantity;
      var priceCell = document.createElement("td");
      priceCell.textContent = '₱' + totalPrice;
      productRow.appendChild(productNameCell);
      productRow.appendChild(quantityCell);
      productRow.appendChild(priceCell);
      tbody.appendChild(productRow);
    }
    table.appendChild(tbody);

    orderedProductsContainer.appendChild(table);
  }

});

function openCheckoutPopup() {
  document.getElementById("checkout-form").style.display = "block";
}

function closeCheckoutPopup() {
  document.getElementById("checkout-form").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  const activeElements = document.querySelectorAll(".row_active");
  const inactiveElements = document.querySelectorAll(".row_inactive");

  // Convert is_active values and add visual color indications
  activeElements.forEach((element) => {
    element.textContent = "Active";
    element.style.color = "green"; // Set your desired color for Active
  });

  inactiveElements.forEach((element) => {
    element.textContent = "Inactive";
    element.style.color = "red"; // Set your desired color for Inactive
  });
});
