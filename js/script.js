  // get the current page's filename
  var path = window.location.pathname;
  var page = path.split("/").pop();
  
  // get all the links in the sidebar
  var links = document.querySelectorAll(".sidebar a");

  // loop through the links and remove the active class from all of them except for the one that matches the current page
  for (var i = 0; i < links.length; i++) {
    if (links[i].getAttribute("href") === page) {
      links[i].classList.add("active");
    } else {
      links[i].classList.remove("active");
    }
  }

  //notification script
  // Get the bell icon element
  const bellIcon = document.querySelector('.notification-icon');

  // Get the notification container element
  const notificationContainer = document.querySelector('.notification-container');

  // Get the notification badge element
  const notificationBadge = document.querySelector('.notification-badge');

  // Function to update the notification count
  function updateNotificationCount() {
    const notificationCount = notificationContainer.querySelectorAll('.notification').length;
    notificationBadge.textContent = notificationCount;
    
    // Toggle the display of the badge based on the count
    notificationBadge.style.display = notificationCount > 0 ? 'flex' : 'none';
  }

  // Add a click event listener to the bell icon
  bellIcon.addEventListener('click', function() {
    // Toggle the 'active' class on the bell icon
    bellIcon.classList.toggle('active');

    // Toggle the 'active' class on the notification container
    notificationContainer.classList.toggle('active');

    // Toggle the display property of the notification container
    if (bellIcon.classList.contains('active')) {
      notificationContainer.style.display = 'block';
      setTimeout(() => {
        notificationContainer.style.opacity = '1';
        notificationContainer.style.transform = 'translateY(0)';
      }, 10);
    } else {
      notificationContainer.style.opacity = '0';
      notificationContainer.style.transform = 'translateY(100%)';
      setTimeout(() => {
        notificationContainer.style.display = 'none';
      }, 300);
    }
  });

  // Call the updateNotificationCount function initially
  updateNotificationCount();
