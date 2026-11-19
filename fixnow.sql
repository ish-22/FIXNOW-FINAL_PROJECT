-- FIXNOW Database Schema & Initial Data
-- Generated for FixNow Service Platform

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `fixnow` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `fixnow`;

-- --------------------------------------------------------
-- Table structure for table `tbl_admin`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_admin`;
CREATE TABLE `tbl_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL UNIQUE,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_admin`
-- Default password: admin123
INSERT INTO `tbl_admin` (`id`, `full_name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'System Administrator', 'admin', 'admin@fixnow.lk', '$2y$10$qur.fHMKMtSAZNd2n0nTfufgpSIaAORJfqdEDNEGtGMyXk1ZW.e1u', NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_districts`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_districts`;
CREATE TABLE `tbl_districts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `district_name` varchar(100) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_districts`
INSERT INTO `tbl_districts` (`id`, `district_name`) VALUES
(1, 'Colombo'),
(2, 'Gampaha'),
(3, 'Kalutara'),
(4, 'Kandy'),
(5, 'Matale'),
(6, 'Nuwara Eliya'),
(7, 'Galle'),
(8, 'Matara'),
(9, 'Hambantota'),
(10, 'Jaffna'),
(11, 'Kilinochchi'),
(12, 'Mannar'),
(13, 'Vavuniya'),
(14, 'Mullaitivu'),
(15, 'Batticaloa'),
(16, 'Ampara'),
(17, 'Trincomalee'),
(18, 'Kurunegala'),
(19, 'Puttalam'),
(20, 'Anuradhapura'),
(21, 'Polonnaruwa'),
(22, 'Badulla'),
(23, 'Monaragala'),
(24, 'Ratnapura'),
(25, 'Kegalle');

-- --------------------------------------------------------
-- Table structure for table `tbl_towns`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_towns`;
CREATE TABLE `tbl_towns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `town_name` varchar(100) NOT NULL,
  `district_id` int(11) NOT NULL,
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `district_id` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_towns`
INSERT INTO `tbl_towns` (`id`, `town_name`, `district_id`) VALUES
(1, 'Colombo 01 - Fort', 1),
(2, 'Colombo 03 - Kollupitiya', 1),
(3, 'Colombo 07 - Cinnamon Gardens', 1),
(4, 'Dehiwala', 1),
(5, 'Mount Lavinia', 1),
(6, 'Moratuwa', 1),
(7, 'Maharagama', 1),
(8, 'Nugegoda', 1),
(9, 'Kottawa', 1),
(10, 'Homagama', 1),
(11, 'Gampaha', 2),
(12, 'Negombo', 2),
(13, 'Kelaniya', 2),
(14, 'Wattala', 2),
(15, 'Kadawatha', 2),
(16, 'Kiribathgoda', 2),
(17, 'Kalutara', 3),
(18, 'Panadura', 3),
(19, 'Horana', 3),
(20, 'Kandy', 4),
(21, 'Peradeniya', 4),
(22, 'Katugastota', 4),
(23, 'Galle', 7),
(24, 'Hikkaduwa', 7),
(25, 'Matara', 8),
(26, 'Kurunegala', 18),
(27, 'Ratnapura', 24);

-- --------------------------------------------------------
-- Table structure for table `tbl_category`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_category`;
CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL UNIQUE,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_category`
INSERT INTO `tbl_category` (`id`, `category_name`, `created_at`) VALUES
(1, 'Electrical Services', NOW()),
(2, 'Plumbing Services', NOW()),
(3, 'Carpentry & Woodwork', NOW()),
(4, 'Cleaning & Janitorial', NOW()),
(5, 'Painting & Decorating', NOW()),
(6, 'Appliance & Electronic Repair', NOW()),
(7, 'Gardening & Landscaping', NOW()),
(8, 'Masonry & Construction', NOW()),
(9, 'Roofing & Ceiling Repair', NOW()),
(10, 'Air Conditioning & Refrigeration', NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_service`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_service`;
CREATE TABLE `tbl_service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `service_type` varchar(150) NOT NULL,
  `about_service` text DEFAULT NULL,
  `booking_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `service_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_service`
INSERT INTO `tbl_service` (`id`, `category_id`, `service_type`, `about_service`, `booking_price`, `service_image`, `created_at`) VALUES
(1, 1, 'House Electrical Wiring & Troubleshooting', 'Complete home electrical inspection, wiring repairs, and short circuit diagnostic services.', 2500.00, 'Service_1174.jpg', NOW()),
(2, 1, 'Circuit Breaker & DB Board Installation', 'Safe circuit breaker replacement, tripping fix, and distribution box installations.', 2000.00, 'Service_1223.jpeg', NOW()),
(3, 2, 'Pipe Leak Detection & Plumbing Repair', 'Rapid detection and repairing of concealed leaking pipes, tap repairs, and water line fixes.', 1800.00, 'Service_1338.jpeg', NOW()),
(4, 2, 'Bathroom Sanitaryware & Tap Installation', 'Full installation and maintenance of wash basins, shower mixers, commodes, and drainage.', 3000.00, 'Service_1341.jpeg', NOW()),
(5, 3, 'Custom Furniture Repair & Woodwork', 'Expert repair of chairs, tables, doors, locks, wooden cabinets, and custom carpentry.', 3500.00, 'Service_1505.jpeg', NOW()),
(6, 4, 'Full Deep Home & Office Cleaning', 'Thorough cleaning service for kitchens, bathrooms, floor scrubbing, and post-construction cleaning.', 5000.00, 'Service_2541.jpg', NOW()),
(7, 5, 'Interior & Exterior Wall Painting', 'High-quality wall putty, primer, interior emulsion, and exterior weather-coat paint jobs.', 4500.00, 'Service_3168.jpg', NOW()),
(8, 6, 'Refrigerator & Freezer Repair', 'Comprehensive cooling issue diagnosis, compressor replacement, and thermostat troubleshooting.', 3000.00, 'Service_3543.jpeg', NOW()),
(9, 6, 'Washing Machine Repair & Service', 'Diagnosis and repair of front load, top load washing machines, motor, and PCB repair.', 2800.00, 'Service_3680.jpg', NOW()),
(10, 7, 'Lawn Mowing & Landscape Maintenance', 'Professional garden cleanup, trimming, lawn leveling, grass cutting, and pest control.', 2200.00, 'Service_3726.jpeg', NOW()),
(11, 8, 'Brickwork, Plastering & Tile Fixing', 'Tile laying, wall crack repairs, plastering, and general masonry maintenance services.', 4000.00, 'Service_4116.jpg', NOW()),
(12, 9, 'Roof Leak Waterproofing & Tile Replacement', 'Tile leak repairs, sheet sealing, gutter cleaning, and waterproofing solutions.', 5500.00, 'Service_4995.jpg', NOW()),
(13, 10, 'AC Inverter Servicing & Gas Refill', 'Complete indoor and outdoor AC wash, gas recharge, filter cleaning, and cooling optimization.', 3500.00, 'Service_5112.jpg', NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_customer`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_customer`;
CREATE TABLE `tbl_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cus_name` varchar(100) NOT NULL,
  `cus_email` varchar(100) NOT NULL UNIQUE,
  `cus_phone` varchar(20) NOT NULL,
  `cus_address` text NOT NULL,
  `cus_password` varchar(255) NOT NULL,
  `cus_image` varchar(255) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `district_id` int(11) DEFAULT NULL,
  `town_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `district_id` (`district_id`),
  KEY `town_id` (`town_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_customer`
-- Passwords are bcrypt hash of '123456' = '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG'
INSERT INTO `tbl_customer` (`id`, `cus_name`, `cus_email`, `cus_phone`, `cus_address`, `cus_password`, `cus_image`, `registration_date`, `district_id`, `town_id`, `created_at`) VALUES
(1, 'Kasun Perera', 'kasun@gmail.com', '0771234567', '45 Galle Road, Colombo 03', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', '16_savi.jpg', NOW(), 1, 2, NOW()),
(2, 'Lakshi Fernando', 'lakshi@gmail.com', '0719876543', '12 Kandy Road, Kadawatha', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', '16_lakshi.jpg', NOW(), 2, 15, NOW()),
(3, 'Sewwandi Silva', 'sewwandi@gmail.com', '0754567890', '78 Temple Road, Negombo', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', '16_sewwandi.jpeg', NOW(), 2, 12, NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_service_providers`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_service_providers`;
CREATE TABLE `tbl_service_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `registration_code` varchar(50) DEFAULT NULL UNIQUE,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `contact_no` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `district_id` int(11) DEFAULT NULL,
  `town_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `servicetype_id` int(11) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `available_dates_and_times` varchar(255) DEFAULT NULL,
  `about_you` text DEFAULT NULL,
  `registration_fee_paid` varchar(10) DEFAULT 'no',
  `payment_status` varchar(50) DEFAULT 'Pending',
  `vendor_image` varchar(255) DEFAULT NULL,
  `feedback_url` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `district_id` (`district_id`),
  KEY `town_id` (`town_id`),
  KEY `category_id` (`category_id`),
  KEY `servicetype_id` (`servicetype_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_service_providers`
-- Passwords are bcrypt hash of '123456' = '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG'
INSERT INTO `tbl_service_providers` (`id`, `registration_code`, `full_name`, `email`, `contact_no`, `password`, `district_id`, `town_id`, `category_id`, `servicetype_id`, `experience`, `available_dates_and_times`, `about_you`, `registration_fee_paid`, `payment_status`, `vendor_image`, `feedback_url`, `status`, `created_at`) VALUES
(1, 'REG1001', 'Sunil Wickramasinghe', 'sunil.electric@gmail.com', '0772345678', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', 1, 8, 1, 1, '8 Years', 'Monday to Saturday, 8:00 AM - 6:00 PM', 'Certified master electrician with over 8 years experience in residential wiring, short-circuit troubleshooting, and appliance repairs.', 'Yes', 'Paid', 'vendor_1850.jpg', 'http://localhost/fixnow/service-vendor/feedback.php?service_provider_id=1', 'Confirmed', NOW()),
(2, 'REG1002', 'Nimal Jayasuriya', 'nimal.plumber@gmail.com', '0713456789', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', 1, 4, 2, 3, '6 Years', 'All days, 7:00 AM - 8:00 PM', 'Reliable plumbing expert specializing in leak detection, pressure pumps, pipe maintenance, and modern bathroom fittings.', 'Yes', 'Paid', 'plumber man.webp', 'http://localhost/fixnow/service-vendor/feedback.php?service_provider_id=2', 'Confirmed', NOW()),
(3, 'REG1003', 'Chaminda Bandara', 'chaminda.cleaning@gmail.com', '0764567891', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', 2, 12, 4, 6, '5 Years', 'Weekdays & Weekends, 8:00 AM - 5:00 PM', 'Professional cleaning technician equipped with high-pressure washers, steam cleaners, and eco-friendly cleaning detergents.', 'Yes', 'Paid', 'vendor_2156.jpg', 'http://localhost/fixnow/service-vendor/feedback.php?service_provider_id=3', 'Confirmed', NOW()),
(4, 'REG1004', 'Ruwan Senanayake', 'ruwan.ac@gmail.com', '0785678912', '$2y$10$HF5qjSOQib/A2olR6XBP6Ozc8fodKV/c7dc2m3iquCyTJ65CMRftG', 1, 7, 10, 13, '7 Years', 'Monday to Sunday, 9:00 AM - 7:00 PM', 'HVAC & Inverter AC specialist offering fast diagnostics, chemical wash, coil cleaning, and gas top-up with service warranty.', 'Yes', 'Paid', 'vendor_2789.jpg', 'http://localhost/fixnow/service-vendor/feedback.php?service_provider_id=4', 'Confirmed', NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_booking`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_booking`;
CREATE TABLE `tbl_booking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_provider_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `booking_date` varchar(50) NOT NULL,
  `time_slot` varchar(50) DEFAULT '09:00 AM - 11:00 AM',
  `message` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `platform_fee` decimal(10,2) DEFAULT 0.00,
  `vendor_payout` decimal(10,2) DEFAULT 0.00,
  `booking_status` varchar(50) DEFAULT 'Pending',
  `task_status` varchar(50) DEFAULT 'Pending',
  `payment_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `service_provider_id` (`service_provider_id`),
  KEY `service_id` (`service_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_booking`
INSERT INTO `tbl_booking` (`id`, `service_provider_id`, `service_id`, `customer_id`, `customer_name`, `contact_number`, `email`, `address`, `booking_date`, `message`, `total_amount`, `booking_status`, `task_status`, `payment_status`, `created_at`) VALUES
(1, 1, 1, 1, 'Kasun Perera', '0771234567', 'kasun@gmail.com', '45 Galle Road, Colombo 03', '2026-11-20', 'Main trip switch frequently goes off during heavy appliance usage.', 2500.00, 'Confirmed', 'Completed', 'Paid', NOW() - INTERVAL 5 DAY),
(2, 2, 3, 2, 'Lakshi Fernando', '0719876543', 'lakshi@gmail.com', '12 Kandy Road, Kadawatha', '2026-11-22', 'Under-sink water pipe leaking onto kitchen floor.', 1800.00, 'Confirmed', 'Pending', 'Paid', NOW() - INTERVAL 2 DAY),
(3, 4, 13, 1, 'Kasun Perera', '0771234567', 'kasun@gmail.com', '45 Galle Road, Colombo 03', '2026-11-25', 'Master bedroom split AC not cooling adequately.', 3500.00, 'Pending', 'Pending', 'Paid', NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_feedback`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_feedback`;
CREATE TABLE `tbl_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `feedback` text NOT NULL,
  `rating` decimal(2,1) NOT NULL DEFAULT 5.0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_feedback`
INSERT INTO `tbl_feedback` (`id`, `vendor_id`, `customer_name`, `feedback`, `rating`, `created_at`) VALUES
(1, 1, 'Kasun Perera', 'Excellent service! Sunil arrived on time and resolved the short circuit within an hour.', 5.0, NOW() - INTERVAL 4 DAY),
(2, 2, 'Mahesh Dissanayake', 'Very clean and efficient plumbing work. Highly recommend Nimal for any leak fixes.', 4.5, NOW() - INTERVAL 3 DAY),
(3, 4, 'Dinesh Weerasinghe', 'The AC is running icy cold now. Very transparent with pricing and polite behavior.', 5.0, NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_user_feedback`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_user_feedback`;
CREATE TABLE `tbl_user_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_user_feedback`
INSERT INTO `tbl_user_feedback` (`id`, `name`, `email`, `message`, `submitted_at`) VALUES
(1, 'Anura Gunasekara', 'anura@yahoo.com', 'FixNow made finding emergency plumbing assistance incredibly simple and convenient in Colombo.', NOW() - INTERVAL 6 DAY),
(2, 'Dilani Wickrama', 'dilani@outlook.com', 'Great interface and prompt responses from verified service vendors. Excellent service!', NOW() - INTERVAL 3 DAY),
(3, 'Tharindu Kumara', 'tharindu@gmail.com', 'Booking an electrician was hassle-free. Everything was confirmed within minutes.', NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_chat_messages`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_chat_messages`;
CREATE TABLE `tbl_chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `vendor_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `vendor_reply` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'sent',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `vendor_id` (`vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_chat_messages`
INSERT INTO `tbl_chat_messages` (`id`, `customer_id`, `vendor_id`, `message`, `vendor_reply`, `status`, `sent_at`) VALUES
(1, 1, 1, 'Hello Sunil, are you available tomorrow morning around 9:30 AM?', 'Yes Kasun, I will be available and will bring all inspection tools.', 'read', NOW() - INTERVAL 2 DAY),
(2, 2, 2, 'Do you repair concealed PPR pipe leaks inside wall tiles?', 'Yes I do. I have ultrasonic leak detection and tile repair tools.', 'sent', NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_contact`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_contact`;
CREATE TABLE `tbl_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_contact`
INSERT INTO `tbl_contact` (`id`, `name`, `phone`, `email`, `message`, `reply`, `created_at`) VALUES
(1, 'Priyani Rajapaksha', '0779988776', 'priyani@gmail.com', 'Can I request multiple service providers for a single renovation project?', 'Yes, you can book separate categories such as plumbing, electrical, and painting through FixNow.', NOW() - INTERVAL 3 DAY),
(2, 'Kamal Gamage', '0711122334', 'kamal@gmail.com', 'How are vendor qualifications and identity documents verified?', 'All service vendors undergo identity and skills verification by our platform admin prior to activation.', NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_dailyhacks`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_dailyhacks`;
CREATE TABLE `tbl_dailyhacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_dailyhacks`
INSERT INTO `tbl_dailyhacks` (`id`, `title`, `description`, `image`, `created_at`) VALUES
(1, 'Quick Fix for a Leaky Faucet Washer', 'A dripping tap is frequently caused by a worn-out rubber washer inside the valve stem. Shut off the main isolation valve, unscrew the handle, and swap out the neoprene washer in under 10 minutes to save hundreds of liters of water.', 'hack1.jpg', NOW() - INTERVAL 7 DAY),
(2, 'Essential Safety Tips for Home Electrical Outlets', 'Never overload an extension multi-plug socket with high-wattage heating or cooking appliances. Inspect socket covers for discoloration or warmth, and test your RCCB trip switch monthly using the test button.', 'electrical.jpg', NOW() - INTERVAL 4 DAY),
(3, 'How to Maintain Your Air Conditioner Filters', 'Clogged AC air filters force compressor motors to work 15% harder, drastically increasing electricity bills. Remove your mesh filters every fortnight, wash them with lukewarm water, air-dry completely, and reinsert.', 'hack2.jpg', NOW() - INTERVAL 2 DAY),
(4, 'Preventing Bathroom Tile Mold & Mildew', 'Maintain healthy airflow in your bathroom by keeping vents open. Spraying equal parts white vinegar and warm water once a week discourages black mold colonies from settling into grout lines.', 'hack3.jpg', NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_offer`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_offer`;
CREATE TABLE `tbl_offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `offer_category_id` int(11) NOT NULL,
  `offer_type_id` int(11) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `current_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_rate` decimal(5,2) NOT NULL DEFAULT 0.00,
  `discounted_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `offer_image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `offer_category_id` (`offer_category_id`),
  KEY `offer_type_id` (`offer_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_offer`
INSERT INTO `tbl_offer` (`id`, `title`, `offer_category_id`, `offer_type_id`, `duration`, `current_price`, `discount_rate`, `discounted_price`, `offer_image`, `description`, `status`, `created_at`) VALUES
(1, 'Seasonal 20% Off Home Deep Cleaning', 4, 6, 'Valid till End of Month', 5000.00, 20.00, 4000.00, 'best offer.jpg', 'Get your entire residential premises deeply scrubbed, sanitized, and deodorized with our special discount rate.', 'active', NOW()),
(2, 'Summer AC Gas Refill & General Service Promo', 10, 13, 'Valid for Next 14 Days', 3500.00, 15.00, 2975.00, 'Offers.jpg', 'Beat the tropical heat with our complete Inverter AC tune-up including pressure testing and free coil wash.', 'active', NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_payment`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_payment`;
CREATE TABLE `tbl_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_payment`
INSERT INTO `tbl_payment` (`id`, `payment_amount`, `created_at`) VALUES
(1, 1500.00, NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_registration_fees`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_registration_fees`;
CREATE TABLE `tbl_registration_fees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_registration_fees`
INSERT INTO `tbl_registration_fees` (`id`, `fee_amount`, `created_at`) VALUES
(1, 2500.00, NOW());

-- --------------------------------------------------------
-- Table structure for table `tbl_notification`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_notification`;
CREATE TABLE `tbl_notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `serviceProvider_id` int(11) NOT NULL,
  `customer_notification_message` text DEFAULT NULL,
  `service_provider_notification_message` text DEFAULT NULL,
  `status_customer` varchar(50) DEFAULT 'unread',
  `status_provider` varchar(50) DEFAULT 'unread',
  `type` varchar(50) DEFAULT 'provider',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `serviceProvider_id` (`serviceProvider_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_notification`
INSERT INTO `tbl_notification` (`id`, `booking_id`, `customer_id`, `serviceProvider_id`, `customer_notification_message`, `service_provider_notification_message`, `status_customer`, `status_provider`, `type`, `created_at`) VALUES
(1, 1, 1, 1, NULL, 'New booking available. Please check.', 'read', 'read', 'provider', NOW() - INTERVAL 5 DAY),
(2, 2, 2, 2, NULL, 'New booking available. Please check.', 'unread', 'read', 'provider', NOW() - INTERVAL 2 DAY),
(3, 3, 1, 4, NULL, 'New booking available. Please check.', 'unread', 'unread', 'provider', NOW() - INTERVAL 1 DAY);

-- --------------------------------------------------------
-- Table structure for table `tbl_invoice`
-- --------------------------------------------------------

DROP TABLE IF EXISTS `tbl_invoice`;
CREATE TABLE `tbl_invoice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_provider_id` int(11) NOT NULL,
  `service_type_id` int(11) DEFAULT NULL,
  `mobile_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `paid_month` varchar(50) DEFAULT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `message` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `latitude` decimal(10,6) DEFAULT NULL,
  `longitude` decimal(10,6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `service_provider_id` (`service_provider_id`),
  KEY `service_type_id` (`service_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `tbl_invoice`
INSERT INTO `tbl_invoice` (`id`, `service_provider_id`, `service_type_id`, `mobile_no`, `email`, `paid_month`, `paid_amount`, `message`, `status`, `created_at`) VALUES
(1, 1, 1, '0772345678', 'sunil.electric@gmail.com', 'November 2026', 1500.00, 'Monthly subscription fee received. Account is in good standing.', 'paid', NOW() - INTERVAL 10 DAY),
(2, 2, 3, '0713456789', 'nimal.plumber@gmail.com', 'November 2026', 1500.00, 'Monthly subscription fee received with thanks.', 'paid', NOW() - INTERVAL 8 DAY),
(3, 3, 6, '0764567891', 'chaminda.cleaning@gmail.com', 'November 2026', 1500.00, 'Monthly payment confirmed. Profile promoted on homepage.', 'paid', NOW() - INTERVAL 5 DAY);

COMMIT;

