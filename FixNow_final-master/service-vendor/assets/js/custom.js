
/*******************************Index.php(This will apply header section with nav bar all forms) ******************************************************************************************** */

'use strict';

/**
 * navbar toggle
 */

const navToggleBtn = document.querySelector('[data-nav-toggle-btn]');
const navbar = document.querySelector('[data-navbar]');
const menuIcon = document.querySelector('.menu-icon');
const closeIcon = document.querySelector('.close-icon');

// Toggle the 'active' class when the nav toggle button is clicked
navToggleBtn.addEventListener('click', () => {
  navbar.classList.toggle('active');
  menuIcon.classList.toggle('active');
  closeIcon.classList.toggle('active');
});

// const navbar = document.querySelector("[data-navbar]");
// const navToggleBtn = document.querySelector("[data-nav-toggle-btn]");
// const navbarLinks = document.querySelectorAll("[data-nav-link]");

// navToggleBtn.addEventListener("click", function () {
//   navbar.classList.toggle("active");
//   this.classList.toggle("active");
// });

// for (let i = 0; i < navbarLinks.length; i++) {
//   navbarLinks[i].addEventListener("click", function () {
//     navbar.classList.toggle("active");
//     navToggleBtn.classList.toggle("active");
//   });
// }

/**
 * header
 */

const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

window.addEventListener("scroll", function () {
  if (window.scrollY >= 100) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
});


/*******************************faq.php******************************************************************************************** */

// accordion slide-down effect
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    }
  }
}


/*******************************services.php******************************************************************************************** */

document.getElementById('filterButton').addEventListener('click', () => {
  alert('Filter functionality not implemented yet!');
});

document.getElementById('searchBar').addEventListener('input', (event) => {
  const searchTerm = event.target.value.toLowerCase();
  const serviceBoxes = document.querySelectorAll('.service-box');
  serviceBoxes.forEach((box) => {
    const title = box.querySelector('.service-title').textContent.toLowerCase();
    if (title.includes(searchTerm)) {
      box.style.display = 'block';
    } else {
      box.style.display = 'none';
    }
  });
});



/*******************************contact.php******************************************************************************************** */

// Function to validate the contact form before submission
function validateForm() {
  // Get values from input fields
  var name = document.getElementById('name').value;
  var phone = document.getElementById('phone').value;
  var email = document.getElementById('email').value;
  var message = document.getElementById('message').value;

  // Check if name is empty
  if (name.trim() == '') {
      alert('Please enter your name');
      return false;
  }

  // Check if phone number is empty
  if (phone.trim() == '') {
      alert('Please enter your phone number');
      return false;
  }

  // Check if email is empty
  if (email.trim() == '') {
      alert('Please enter your email');
      return false;
  }

  // Check if message is empty
  if (message.trim() == '') {
      alert('Please enter your message');
      return false;
  }

  // All fields are filled, return true for form submission
  return true;
}


