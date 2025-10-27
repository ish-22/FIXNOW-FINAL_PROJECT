document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');   
    const action = urlParams.get('action');
  
    console.log('Status:', status, 'Action:', action);
  
    if (status && action) {
        let message = '';

        
        switch (action) {
            case 'add':
                message = (status === 'success') ? 'Admin added successfully!' : 'Failed to add admin.';
                break;
            case 'delete':
                message = (status === 'success') ? 'Admin deleted successfully!' : 'Failed to delete admin.';
                break;
            case 'delete_cus':
                    message = (status === 'success') ? 'Customer deleted successfully!' : 'Failed to delete Customer.';
                    break;
            case 'update':
                message = (status === 'success') ? 'Admin updated successfully!' : 'Failed to update admin!';
                break;

            case 'login':
                message = (status === 'success') ? 'Login successful!' : 'Login failed! Username or password did not match! Try again.';
                break;

            case 'no-login-message':
                message = 'Please login to access the admin panel.';
                break;

            case 'add-service':
                message = (status === 'success') ? 'Services Added successful!' : 'failed to add service! try again';
                break;

            case 'delete-service':
                message = (status === 'success') ? 'service deleted successfully!' : 'Failed to delete service.';
                break;


            case 'add-offer':
                message = (status === 'success') ? 'Offer Added successful!' : 'failed to add offer! try again';
                break;

            case 'update-offer':
                message = (status === 'success') ? 'Offer Updated successful!' : 'failed to update offer! try again';
                break;
            case 'delete-offer':
                message = (status === 'success') ? 'Offer deleted successful!' : 'failed to deleted offer! try again';
                break;
                     

                
            case 'add-hack':
                message = (status === 'success') ? 'Dailyhacks Added successful!' : 'failed to add Dailyhacks! try again';
                break;

            case 'update-hack':
                message = (status === 'success') ? 'DailyHack updated successfully!' : 'Failed to update DailyHack!';
                break;
                

            case 'delete-hack':
                message = (status === 'success') ? 'DailyHack deleted successfully!' : 'Failed to delete DailyHack.';
                break;

                        
            case 'add_fee':
                message = (status === 'success') ? 'Amount Added successful!' : 'failed to add amout! try again';
                break;      


            case 'send_code':
                message = (status === 'success') ? ' Account Verified and email sent successfully' : 'Error sending email to the service provider.';
                    break;      
    

            case 'update_profite':
                message = (status === 'success') ? 'Profile updated successfully!' : 'Failed to update profile!';
                break;

            case 'contact_message':
                message = (status === 'success') ? 'Message sent successfully!' : 'Failed to sent message!';
                break;
            case 'booking':
                message = (status === 'success') ? 'booking successfully!' : 'Failed booking!';
                break;

             case 'reply_message':
                message = (status === 'success') ? 'Reply sent successfully!' : 'Failed to sent reply!';
                break;

             case 'delete_message':
                message = (status === 'success') ? 'Delete Message successfully!' : 'Failed to Delete Message!';
                break;

            case 'login_vendor':
                message = (status === 'success') ? 'login successfully!' : 'Failed to login! try again!';
                break;
    

            case 'signup_vendor':
                message = (status === 'success') ? 'Signup as vendor successfully! You will receve a registration code using that please log to the web site. it will take few moment' : 'Failed to signup! try again!';
                break;
                

            case 'sent_email':
                message = (status === 'success') ? 'Email Has sent succcesfully!' : 'Failed to send email! try again!';
                break;
                

            case 'add-category':
                message = (status === 'success') ? 'Category added succcesfully!' : 'Failed to add category try again!';
                break;
                
            case 'update-service':
                message = (status === 'success') ? 'Services updated succcesfully!' : 'Failed to updated services try again!';
                break;
            case 'delete-service':
                message = (status === 'success') ? 'Services deleted succcesfully!' : 'Failed to deleted services try again!';
                break;
                        
            case 'send_invoice':
                message = (status === 'success') ? 'Email sent succcesfully!' : 'Failed to send email! try again!';
                break;   

            case 'delete_invoice':
                message = (status === 'success') ? 'Invoice Deleted succcesfully!' : 'Failed to Deleted invoice! try again!';
                break;   

                
            case 'add-district':
                message = (status === 'success') ? 'District added succcesfully!' : 'Failed to added District! try again!';
                break;   
    
            case 'add-town':
                message = (status === 'success') ? 'Town added succcesfully!' : 'Failed to added Town! try again!';
                break;   
            case 'cus_registration':
                message = (status === 'success') ? 'Registration succcesfully!' : 'Failed toregistration! try again!';
                break;   
                
            case 'login_cus':
                message = (status === 'success') ? 'Login as customer succcesfully!' : 'Failed to login! try again!';
                break;   
                
                

            case 'reply_vendor':
                message = (status === 'success') ? 'Message send succcesfully!' : 'Failed to send meesage! try again!';
                break;   
            case 'add_url':
                message = (status === 'success') ? 'Feedback URL added succcesfully!' : 'Failed to send URL! try again!';
                break;   
                  
              case 'success':
                message = (status === 'success') ? 'Feedback added succcesfully!' : 'Failed to send feedback try again!';
                break;   






            case 'upload':
                message = (status === 'success') ? 'Image uploaded successfully!' : 'Failed to upload image.';
                break;
            case 'add_category':
                message = (status === 'success') ? 'Category added successfully!' : 'Failed to add category.';
                break;
            case 'delete_category':
                message = (status === 'success') ? 'Category deleted successfully!' : 'Failed to delete category.';
                break;
            case 'booking':
                message = (status === 'success') ? 'Booking successful!' : 'Booking failed!';
                break;

            case 'delete_package':
                  message = (status === 'success') ? 'Deleted Package succeffully!' : 'failed to Deleted Package!';
                  break;
  
            case 'update_package':
                  message = (status === 'success') ? 'Updated Package succeffully!' : 'failed to update Package!';
                  break;
            case 'not_found':
                  message = (status === 'success') ? '' : 'not found package Package!';
                  break;
  

            case 'add_category':
                  message = (status === 'success') ? 'Category Successfully added.' : 'failed to add category!';
                  break;
      
                             
            case 'update_class':
                  message = (status === 'success') ? 'Program updated succeffully!' : 'failed to update Program!';
                  break;
  
            case 'delete_class':
                  message = (status === 'success') ? 'Program updated succeffully!' : 'failed to update Program!';
                  break;
         
            case 'success_sent':
                  message = (status === 'success') ? 'Message sent succeffully!' : 'failed to send message!';
                  break;
  
            case 'confirm_booking':
                  message = (status === 'success') ? 'Confirm succeffully!' : 'failed to confirm booking!';
                  break;
          
            case 'cancel_booking':
                  message = (status === 'success') ? 'cancelation succeffully!' : 'failed to cancel booking!';
                  break;
  
  
            case 'customer_delete':
                message = (status === 'success') ? 'Customer Delete successfully!' : 'failed to delete customer!';
                break;
  
            case 'feedback':
                message = (status === 'success') ? 'Your Feddback successfully submitted' : 'Failed to submit feedback.Try again!';
                break;
            case 'contacts':
                message = (status === 'success') ? 'successfully submitted' : 'Failed to submit.Try again!';
                break;
  
  
            default:
                return;
        }
  
        alert(message);
  
        // Remove parameters from URL
        urlParams.delete('status');
        urlParams.delete('action');
        const newUrl = urlParams.toString() ? `${window.location.pathname}?${urlParams.toString()}` : window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
  });
  










// Function to fetch services based on selected category
function fetchServices() {
    var categoryId = document.getElementById('category-select').value;

    if (categoryId) {
        // Send AJAX request to get services based on category
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_services.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Populate the service-type dropdown with the response
                document.getElementById('service-type').innerHTML = xhr.responseText;
            }
        };
        xhr.send('offer_category_id=' + categoryId);  // Sending 'offer_category_id' as the parameter
    } else {
        // Clear service dropdown if no category is selected
        document.getElementById('service-type').innerHTML = '<option value="">Select Service Type</option>';
    }
}

// Function to update the current price based on the selected service type
function fetchServicePrice() {
    var selectedOption = document.getElementById('service-type').selectedOptions[0];
    var price = selectedOption.getAttribute('data-price');
    
    // Set the price in the "Current Price" and "Discounted Price" fields
    document.getElementById('current-price').value = price;
    document.getElementById('discounted-price').value = price;  // Initially set the discounted price to the same as current price
}

// Function to automatically calculate and update the discounted price when discount rate is entered
function calculateDiscountedPrice() {
    var currentPrice = parseFloat(document.getElementById('current-price').value);
    var discountRate = parseFloat(document.getElementById('discount-rate').value);

    // Check if both current price and discount rate are numbers and valid
    if (!isNaN(currentPrice) && !isNaN(discountRate)) {
        var discountedPrice = currentPrice - (currentPrice * (discountRate / 100));  // Apply discount
        document.getElementById('discounted-price').value = discountedPrice.toFixed(2);  // Set discounted price with 2 decimal places
    }
}


// Function to fetch services based on selected category for updating the offer
function fetchServicesForUpdate() {
    var categoryId = document.getElementById('offer-category').value;

    if (categoryId) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'fetch_services_update.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Populate the offer-type dropdown with the response
                document.getElementById('offer-type').innerHTML = xhr.responseText;
                // After the offer type is populated, check the selected option's price
                fetchServicePriceForUpdate(); // This updates the price field based on the selected offer type
            }
        };
        xhr.send('category_id=' + categoryId);
    } else {
        document.getElementById('offer-type').innerHTML = '<option value="">Select Offer Type</option>';
    }
}

// Function to update the current price based on the selected offer type
function fetchServicePriceForUpdate() {
    var selectedOption = document.getElementById('offer-type').selectedOptions[0];
    if (selectedOption) {
        var price = selectedOption.getAttribute('data-price');
        document.getElementById('current-price').value = price;
        document.getElementById('discounted-price').value = price;  // Initially set the discounted price to the same as current price
    }
}

// Function to automatically calculate and update the discounted price when discount rate is entered
function calculateDiscountedPriceForUpdate() {
    var currentPrice = parseFloat(document.getElementById('current-price').value);
    var discountRate = parseFloat(document.getElementById('discount-rate').value);

    // Check if both current price and discount rate are numbers and valid
    if (!isNaN(currentPrice) && !isNaN(discountRate)) {
        var discountedPrice = currentPrice - (currentPrice * (discountRate / 100));  // Apply discount
        document.getElementById('discounted-price').value = discountedPrice.toFixed(2);  // Set discounted price with 2 decimal places
    }
}














document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const closeBtn = document.getElementById('close-btn');
    const menuItems = document.querySelectorAll('.sidebar ul li a');  // Select all <a> tags inside the sidebar

    // Show sidebar when menu icon is clicked
    menuToggle.addEventListener('click', function () {
        sidebar.classList.toggle('active'); // Toggle the sidebar visibility
    });

    // Close sidebar when close button is clicked
    closeBtn.addEventListener('click', function () {
        sidebar.classList.remove('active'); // Hide the sidebar
    });

    // Close sidebar when any menu item is clicked
    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            sidebar.classList.remove('active'); // Hide the sidebar when a menu item is clicked
        });
    });
});
