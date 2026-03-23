# FixNow

FixNow is a comprehensive web-based service platform designed to bridge the gap between customers and service vendors. It allows users to browse numerous services, discover local vendors, and seamlessly book them online, whilst managing payments, feedback, and accounts. 

## 🌟 Key Features

### For Customers
- **Authentication**: Secure registration, login, logout, and password recovery (`forgot_password.php`, `reset_password.php`).
- **Service Discovery**: Browse available services, search for nearby vendors (`vendor-search.php`), and filter by town or service type.
- **Vendor Profiles**: View comprehensive vendor details, including service offerings, ratings, and reviews (`vendors-details.php`).
- **Booking & Payments**: Book vendors directly (`booking.php`), view past booking history (`bookingHistory.php`), and integrate with secure payment flows (`payment-success.php`).
- **Profile Management**: Update personal details and manage account preferences (`profile.php`, `profileupdate.php`).
- **Interactions**: Direct chat with vendors or support (`chat.php`), submit feedback (`feedback.php`), and read daily hacks/tips (`dailyHacks.php`).

### For Service Vendors
- **Vendor Dashboard**: A dedicated portal (`service-vendor/`) to manage upcoming bookings, interact with customers, and manage their service portfolio.
- **Service Management**: Define services offered, pricing, and availability.

### For Administrators
- **Admin Panel**: A centralized dashboard (`admin/`) for platform moderation.
- **User & Vendor Management**: Approve and manage new service vendors, and oversee customer accounts.
- **System Settings**: Platform-wide controls and monitoring tools.

## 🛠 Technologies Used
- **Backend**: Core PHP 
- **Database**: MySQL (optimized for relational customer, vendor, and booking data)
- **Frontend**: HTML5, Vanilla CSS / CSS Frameworks, JavaScript
- **Server**: Apache (via XAMPP/WAMP or minimal LAMP stack)

## 📁 Directory Structure
```text
FIXNOW-FINAL_PROJECT/
├── admin/                  # Administrator dashboard and management scripts
├── assets/                 # Global assets (CSS stylesheets, Web Fonts, global JS)
├── config/                 # Core configuration files (e.g., database connection)
├── images/                 # Image assets (logos, placeholders, banners)
├── js/                     # Custom JavaScript for frontend interactions
├── partials/               # Reusable UI components (header, footer, navigation)
├── service-vendor/         # Dedicated Vendor dashboard and isolated vendor scripts
├── index.php               # Landing page
├── signup.php/signin.php   # User onboarding paths
├── booking.php             # Core booking pipeline
└── ...                     # Additional utility and core pages
```

## 🚀 Installation & Setup

1. **Prerequisites**: 
   - A local server environment like XAMPP, WAMP, MAMP, or a LAMP stack.
   - PHP 7.4 or higher recommended.
   - MySQL / MariaDB installed.

2. **Clone the Repository**:
   - Extract the project files into your local server's root directory (e.g., `C:\xampp\htdocs\FIXNOW-FINAL_PROJECT`).

3. **Database Configuration**:
   - Open PHPMyAdmin (or your preferred database manager).
   - Create a new, empty database (e.g., `fixnow_db`).
   - Import the provided `.sql` file to populate tables, relationships, and initial data.

4. **Connect Application to Database**:
   - Navigate to the `config/` directory.
   - Open the primary configuration file (often `config.php` or `db.php`) and update the credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'fixnow_db');
     ```

5. **Run the Application**:
   - Start your Apache and MySQL services.
   - Access the application in your browser via: `http://localhost/FIXNOW-FINAL_PROJECT/`

## 📑 Core Pages Reference
- **Home**: `index.php`
- **Vendors Directory**: `vendors.php` / `vendor-search.php`
- **User Booking Pipeline**: `booking.php`
- **Feedback & Support**: `feedback.php`, `faq.php`, `contact.php`
- **Legal**: `privacy_policy.php`, `terms_of_service.php`

## 👥 Authors
- **Ishan Chinthaka**
