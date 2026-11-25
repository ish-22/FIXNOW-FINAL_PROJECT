# FixNow — On-Demand Home & Technical Service Marketplace

<div align="center">

![FixNow Banner](images/logo.png)

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Vercel Deployment](https://img.shields.io/badge/Vercel-Deployed-000000?style=for-the-badge&logo=vercel&logoColor=white)](https://vercel.com/)
[![TiDB Cloud](https://img.shields.io/badge/TiDB%20Cloud-Serverless%20MySQL-E30C34?style=for-the-badge&logo=pingcap&logoColor=white)](https://tidbcloud.com/)
[![License](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)

**A modern, full-stack web application connecting homeowners and businesses with verified, high-quality technical specialists and service vendors across Sri Lanka.**

[Features](#-key-features) • [Architecture](#-architecture--tech-stack) • [Installation & Setup](#-installation--setup) • [Vercel Deployment](#-cloud-deployment-vercel--tidb-cloud) • [Directory Structure](#-directory-structure) • [Security](#-security--best-practices)

---

</div>

## 📖 Overview

**FixNow** solves the friction of finding reliable, skilled local tradespeople (electricians, plumbers, AC technicians, carpenters, landscapers, and home cleaners). The platform provides transparent pricing, real-time availability, direct customer-vendor communication, secure bookings, and customer feedback.

Built with a modular PHP architecture, FixNow runs seamlessly on local **XAMPP/Apache** servers as well as modern **serverless cloud platforms (Vercel + TiDB Cloud)**.

---

## 🌟 Key Features

### 👤 Customer Experience
* **Service Catalog & Smart Discovery**: Browse services by category or use search (`vendor-search.php`) with real-time town filtering.
* **Specialist Profiles**: Verified badges, experience ratings, hourly/base pricing, contact channels, and customer reviews (`vendors-details.php`).
* **Direct Booking Flow**: Schedule appointments with flexible time slots and custom repair job notes (`booking.php`).
* **Booking History & Status Tracking**: Live status badges (*Pending*, *Confirmed*, *In-Progress*, *Completed*, *Cancelled*) with automated fee calculations (`bookingHistory.php`).
* **Customer Feedback & Ratings**: Submit star ratings and verified testimonials (`feedback.php`, `user_feedback.php`).
* **Daily Hacks & DIY Tips**: Curated maintenance guides, preventive care articles, and home improvement tutorials (`dailyHacks.php`, `dailyHackDetails.php`).
* **Real-time Live Chat**: In-app messaging system connecting customers directly with their booked technicians (`chat.php`).
* **User Authentication**: Secure signup, login, session management, and password recovery (`signin.php`, `signup.php`, `forgot_password.php`).

### 🛠️ Service Vendor / Specialist Portal (`/service-vendor/`)
* **Vendor Dashboard**: Overview of assigned jobs, revenue summary, and pending job requests (`index.php`).
* **Portfolio & Profile Management**: Update qualifications, bio, contact details, profile photo, and serviceable towns (`profile.php`, `update_profile.php`).
* **Job Request Pipeline**: Accept, schedule, or decline incoming customer booking requests with one click.
* **Onboarding & Verification Fee**: Integrated vendor registration fee payment pipeline (`registration-payment.php`).
* **Digital Invoices**: Auto-generate downloadable and printable professional invoices for completed work (`invoice.php`).

### 🛡️ Administrator Control Center (`/admin/`)
* **Analytics & Performance Overview**: Platform metrics including total bookings, registered specialists, customers, and gross volume (`index.php`).
* **Specialist Verification**: Approve or suspend vendor profiles after credential verification (`manage-sp.php`).
* **Service Category Management**: Add, update, or reorganize service types and pricing guidelines (`manage-services.php`).
* **Feedback Moderation**: Monitor and manage customer ratings and reviews (`manage-feedback.php`).
* **Booking & Dispute Oversight**: Platform-wide booking audit trail with customer and specialist details (`manage-booking.php`).
* **FAQ & Support Knowledgebase**: Manage frequently asked questions and user inquiries (`manage-question.php`).

---

## 🛠️ Architecture & Tech Stack

| Layer | Technology | Details |
| :--- | :--- | :--- |
| **Backend** | PHP 8.2+ | Procedural & OOP modules, safe session handling, secure input sanitization |
| **Database** | MySQL 8.0+ / MariaDB | Relational schema with foreign keys, transactional integrity, and `utf8mb4` |
| **Cloud Database** | TiDB Cloud Serverless | Cloud-native, distributed MySQL-compatible database with SSL/TLS encryption |
| **Serverless Runtime**| Vercel Serverless (`vercel-php`) | Front Controller pattern (`api/index.php`) routing dynamic and static assets |
| **Frontend** | HTML5, CSS3, JavaScript | Modern glassmorphism UI, mobile-responsive layout, CSS grid & flexbox |
| **Email Service** | PHPMailer 7.x | SMTP transactional notifications for booking confirmations and password resets |
| **Icons & Typography**| FontAwesome 6, Google Fonts | Outfit, Inter, and Poppins font families |

---

## 📁 Directory Structure

```text
FIXNOW-FINAL_PROJECT/
├── admin/                     # Administrator portal (management scripts, UI, actions)
│   ├── admin_css/             # Dedicated Admin stylesheet suite
│   ├── partials/              # Admin navigation and header components
│   └── manage-*.php           # Management controllers (bookings, vendors, services)
├── api/                       # Cloud serverless front-controller
│   └── index.php              # Dynamic routing engine for Vercel deployment
├── assets/                    # Platform-wide CSS, animations, and icons
│   ├── css/                   # Shared stylesheets (home, auth, modern UI)
│   └── fonts/                 # Webfont assets
├── config/                    # Core configuration and database adapters
│   ├── constant.php           # Dual-environment DB connection & dynamic SITEURL
│   ├── db_helper.php          # Prepared statement wrapper (prevents SQL injection)
│   └── mail.php               # PHPMailer SMTP credentials configuration
├── images/                    # Platform logos, badges, and category banners
├── js/                        # Client-side validation, AJAX town-fetchers, live chat
├── partials/                  # Global reusable templates (navigation, footer)
│   ├── header.php             # Unified responsive navigation bar
│   └── footer.php             # Modern multi-column footer
├── service-vendor/            # Dedicated Service Vendor dashboard & workflows
│   ├── assets/                # Vendor-specific stylesheets and JavaScript
│   ├── partials/              # Vendor navigation components
│   └── index.php              # Vendor dashboard homepage
├── .gitignore                 # Version control exclusions
├── .vercelignore              # Deployment exclusion rules for Vercel
├── composer.json              # Dependency configuration (PHPMailer)
├── fixnow.sql                 # Complete database schema and seed data
├── index.php                  # Customer marketplace landing page
├── booking.php                # Service booking wizard
├── vendors.php                # Directory of verified specialists
├── vercel.json                # Vercel serverless routing configuration
└── README.md                  # Comprehensive project documentation
```

---

## 💻 Local Development Setup

### 1. Prerequisites
* **XAMPP**, **WAMP**, or **MAMP** with **PHP 8.2+** and **MySQL / MariaDB**.
* Web browser (Google Chrome, Firefox, Microsoft Edge).

### 2. Clone / Copy Repository
Place the project folder inside your web server's root directory:
```bash
# For XAMPP on Windows:
C:\xampp\htdocs\FIXNOW-FINAL_PROJECT
```

### 3. Setup Database
1. Start **Apache** and **MySQL** in your XAMPP Control Panel.
2. Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
3. Create a new database named **`fixnow`** with collation `utf8mb4_unicode_ci`.
4. Click **Import** and upload [`fixnow.sql`](file:///c:/xampp/htdocs/FIXNOW-FINAL_PROJECT/fixnow.sql) from the project root.

### 4. Verify Configuration
Open [`config/constant.php`](file:///c:/xampp/htdocs/FIXNOW-FINAL_PROJECT/config/constant.php). The project is pre-configured with default local XAMPP credentials:
```php
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USERNAME') ?: 'root'; // or 'fixnow'
$db_pass = getenv('DB_PASSWORD') ?: '';     // or your MySQL password
$db_name = getenv('DB_NAME') ?: 'fixnow';
$db_port = getenv('DB_PORT') ? intval(getenv('DB_PORT')) : 3306;
```

### 5. Launch FixNow
Open your browser and navigate to:
```text
http://localhost/FIXNOW-FINAL_PROJECT/
```

---

## ☁️ Cloud Deployment (Vercel + TiDB Cloud)

FixNow is pre-configured for instant zero-configuration deployment to [Vercel](https://vercel.com/) paired with a free cloud MySQL cluster on [TiDB Cloud](https://tidbcloud.com/).

### Step 1: Provision Free Cloud MySQL (TiDB Cloud)
1. Sign in to [tidbcloud.com](https://tidbcloud.com) with GitHub or Google.
2. Click **Create Resource** → choose **Starter / Serverless (Free $0/mo)**.
3. Name your cluster `fixnow-db` and set a root password.
4. In the **SQL Editor**, create the database and run `fixnow.sql`:
   ```sql
   CREATE DATABASE IF NOT EXISTS fixnow;
   USE fixnow;
   -- Paste and run the contents of fixnow.sql
   ```
5. Note your connection details from the **Connect** modal:
   * **Host**: `gateway01.us-west-2.prod.aws.tidbcloud.com`
   * **Port**: `4000`
   * **User**: `<cluster-id>.root`
   * **Password**: `<your-cluster-password>`
   * **Database**: `fixnow`

### Step 2: Deploy to Vercel
1. Import your GitHub repository into Vercel.
2. Set **Project Name** to lowercase (e.g. `fixnow-final-project`).
3. Under **Environment Variables**, add the following 5 variables:

| Environment Variable | Description | Example Value |
| :--- | :--- | :--- |
| `DB_HOST` | Remote MySQL / TiDB Host | `gateway01.us-west-2.prod.aws.tidbcloud.com` |
| `DB_PORT` | Remote Database Port | `4000` |
| `DB_USERNAME` | Database Username | `xxxxxxxx.root` |
| `DB_PASSWORD` | Database User Password | `your_secure_password` |
| `DB_NAME` | Database Name | `fixnow` |

4. Click **Deploy**. Vercel will build the serverless functions and publish your live production URL!

---

## 🔒 Security & Best Practices

* **SQL Injection Prevention**: All modern query points utilize parameterized SQL statements via `config/db_helper.php` (`db_query_params`).
* **Encrypted Cloud Transport**: Remote database connections automatically enforce `MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT` to encrypt traffic between Vercel serverless nodes and TiDB Cloud.
* **Role-Based Access Control**: Pages within `admin/` and `service-vendor/` enforce session authentication guards to prevent unauthorized access.
* **Cross-Site Scripting (XSS) Sanitization**: Dynamic user inputs and feedback strings are safely escaped using `htmlspecialchars()` before output rendering.
* **Centralized Database Abstraction**: Automatic fallback and informative deployment setup cards if database credentials are not yet populated.

---

## 👥 Demo Credentials

For quick evaluation and testing:

| Role | Username / Email | Password | Access Portal |
| :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` | `/admin/login.php` |
| **Service Specialist**| `ishanchinthaka2002@gmail.com` | `123456` | `/signin.php` |
| **Customer** | `kasun@gmail.com` | `123456` | `/signin.php` |

---

## 📄 License

This project is licensed under the **MIT License** — feel free to use, modify, and distribute for academic, personal, or commercial initiatives.

---

<div align="center">
  <sub>Developed with ❤️ by <b>Ishan Chinthaka</b></sub>
</div>
