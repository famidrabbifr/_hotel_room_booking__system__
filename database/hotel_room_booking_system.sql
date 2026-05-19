-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 07:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_room_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(60) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'SYSTEM_SETUP', 'Initial database and demo records created.', '127.0.0.1', '2026-05-14 18:52:31'),
(2, 3, 'RECEPTION_ACTIVITY', 'Created walk-in booking #5', '::1', '2026-05-15 14:30:41'),
(3, 3, 'RECEPTION_ACTIVITY', 'Completed service request #1', '::1', '2026-05-15 18:19:43'),
(4, 3, 'RECEPTION_ACTIVITY', 'Approved late_checkout for booking #5', '::1', '2026-05-15 19:59:21'),
(5, 3, 'RECEPTION_ACTIVITY', 'Approved early_checkin for booking #5', '::1', '2026-05-15 19:59:29'),
(6, 3, 'RECEPTION_ACTIVITY', 'Approved late_checkout for booking #5', '::1', '2026-05-15 19:59:33'),
(7, 3, 'RECEPTION_ACTIVITY', 'Checked in booking #5', '::1', '2026-05-15 20:02:16'),
(8, 3, 'RECEPTION_ACTIVITY', 'Processed payment for bill #5', '::1', '2026-05-15 20:03:27'),
(9, 3, 'RECEPTION_ACTIVITY', 'Created walk-in booking #6', '::1', '2026-05-15 20:05:13'),
(10, 3, 'RECEPTION_ACTIVITY', 'Processed payment for bill #6', '::1', '2026-05-15 20:05:41'),
(11, 3, 'RECEPTION_ACTIVITY', 'Created walk-in booking #7', '::1', '2026-05-15 20:07:33'),
(12, 3, 'RECEPTION_ACTIVITY', 'Checked in booking #7', '::1', '2026-05-15 20:08:37'),
(13, 3, 'RECEPTION_ACTIVITY', 'Approved late_checkout for booking #7', '::1', '2026-05-15 20:08:50'),
(14, 3, 'RECEPTION_ACTIVITY', 'Processed payment for bill #7', '::1', '2026-05-15 20:09:15'),
(15, 3, 'RECEPTION_ACTIVITY', 'Created walk-in booking #8', '::1', '2026-05-15 20:10:01'),
(16, 3, 'RECEPTION_ACTIVITY', 'Processed payment for bill #8', '::1', '2026-05-15 20:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `message`, `created_by`, `is_active`, `created_at`) VALUES
(1, 'Welcome to Grand Palace Hotel', 'Enjoy clean rooms, professional service, online booking, and premium hospitality.', 1, 1, '2026-05-14 18:52:31'),
(2, 'Eid Holiday Pricing Notice', 'Seasonal pricing will apply during Eid holiday dates.', 1, 1, '2026-05-14 18:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `base_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `extras_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','card','bkash','nagad','bank_transfer') DEFAULT NULL,
  `payment_status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `receipt_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `booking_id`, `guest_id`, `base_amount`, `extras_amount`, `discount_amount`, `total_amount`, `payment_method`, `payment_status`, `paid_at`, `receipt_path`, `created_at`) VALUES
(1, 1, 2, 5000.00, 0.00, 0.00, 5000.00, 'nagad', 'paid', '2026-05-15 17:47:06', NULL, '2026-05-14 18:52:30'),
(2, 2, 2, 9000.00, 500.00, 0.00, 9500.00, 'bkash', 'paid', '2026-05-15 00:52:30', NULL, '2026-05-14 18:52:30'),
(5, 5, 8, 8000.00, 0.00, 0.00, 8000.00, 'bkash', 'paid', '2026-05-16 02:03:27', 'receipts/receipt_5.php', '2026-05-15 14:30:41'),
(6, 6, 9, 64000.00, 0.00, 0.00, 64000.00, 'bank_transfer', 'paid', '2026-05-16 02:05:41', 'receipts/receipt_6.php', '2026-05-15 20:05:13'),
(7, 7, 10, 4500.00, 0.00, 0.00, 4500.00, 'bank_transfer', 'paid', '2026-05-16 02:09:15', NULL, '2026-05-15 20:07:33'),
(8, 8, 11, 16000.00, 0.00, 0.00, 16000.00, 'bank_transfer', 'paid', '2026-05-16 02:10:24', NULL, '2026-05-15 20:10:01');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `room_type_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `num_guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','checked_in','checked_out','cancelled') NOT NULL DEFAULT 'pending',
  `source` enum('online','walk_in') NOT NULL DEFAULT 'online',
  `special_request` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `guest_id`, `room_id`, `room_type_id`, `checkin_date`, `checkout_date`, `num_guests`, `total_price`, `status`, `source`, `special_request`, `created_at`) VALUES
(1, 2, 5, 1, '2026-05-11', '2026-05-15', 2, 5000.00, 'checked_out', 'online', 'Need quiet room', '2026-05-14 18:52:30'),
(2, 2, 6, 2, '2026-05-14', '2026-05-16', 3, 9000.00, 'checked_in', 'online', 'Extra pillow needed', '2026-05-14 18:52:30'),
(5, 8, 9, 3, '2026-05-15', '2026-05-16', 1, 8000.00, 'checked_in', 'walk_in', ' | Late checkout approved | Early check-in approved | Late checkout approved', '2026-05-15 14:30:41'),
(6, 9, NULL, 3, '2026-05-23', '2026-05-31', 1, 64000.00, 'checked_out', 'walk_in', '', '2026-05-15 20:05:13'),
(7, 10, 5, 2, '2026-05-15', '2026-05-16', 1, 4500.00, 'checked_in', 'walk_in', ' | Late checkout approved', '2026-05-15 20:07:33'),
(8, 11, NULL, 3, '2026-05-13', '2026-05-15', 3, 16000.00, 'checked_out', 'walk_in', '', '2026-05-15 20:10:01');

-- --------------------------------------------------------

--
-- Table structure for table `booking_modification_requests`
--

CREATE TABLE `booking_modification_requests` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `requested_checkin_date` date NOT NULL,
  `requested_checkout_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_modification_requests`
--

INSERT INTO `booking_modification_requests` (`id`, `booking_id`, `guest_id`, `requested_checkin_date`, `requested_checkout_date`, `reason`, `status`, `processed_by`, `processed_at`, `requested_at`) VALUES
(1, 1, 2, '2026-05-21', '2026-05-23', 'Guest requested one day delay due to travel schedule.', 'pending', NULL, NULL, '2026-05-14 18:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `housekeeping_tasks`
--

CREATE TABLE `housekeeping_tasks` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `task_type` enum('cleaning','inspection','maintenance') NOT NULL,
  `priority` enum('normal','urgent') NOT NULL DEFAULT 'normal',
  `status` enum('pending','in_progress','done') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `housekeeping_tasks`
--

INSERT INTO `housekeeping_tasks` (`id`, `room_id`, `assigned_to`, `task_type`, `priority`, `status`, `notes`, `scheduled_date`, `completed_at`, `created_at`) VALUES
(1, 3, 4, 'cleaning', 'urgent', 'pending', 'Room 103 needs cleaning after checkout.', '2026-05-15', NULL, '2026-05-14 18:52:31'),
(2, 7, 4, 'maintenance', 'urgent', 'in_progress', 'Check bathroom plumbing issue.', '2026-05-15', NULL, '2026-05-14 18:52:31'),
(3, 10, 4, 'inspection', 'normal', 'pending', 'AC inspection required.', '2026-05-15', NULL, '2026-05-14 18:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `points_used` int(11) NOT NULL DEFAULT 0,
  `balance` int(11) NOT NULL DEFAULT 0,
  `transaction_type` enum('earned','redeemed','adjusted') NOT NULL DEFAULT 'earned',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_points`
--

INSERT INTO `loyalty_points` (`id`, `guest_id`, `booking_id`, `points_earned`, `points_used`, `balance`, `transaction_type`, `created_at`) VALUES
(1, 2, 2, 95, 0, 95, 'earned', '2026-05-14 18:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_reports`
--

CREATE TABLE `maintenance_reports` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('low','medium','high') NOT NULL DEFAULT 'low',
  `status` enum('open','in_progress','resolved') NOT NULL DEFAULT 'open',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `maintenance_reports`
--

INSERT INTO `maintenance_reports` (`id`, `room_id`, `reported_by`, `description`, `severity`, `status`, `reported_at`, `resolved_at`) VALUES
(1, 7, 4, 'Bathroom plumbing issue reported by housekeeping.', 'high', 'in_progress', '2026-05-14 18:52:31', NULL),
(2, 10, 4, 'AC cooling performance is low.', 'medium', 'open', '2026-05-14 18:52:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`) VALUES
(1, 2, 'Booking Confirmed', 'Your upcoming booking has been confirmed.', 0, '2026-05-14 18:52:31'),
(2, 3, 'Today Check-in Reminder', 'Please review today’s confirmed check-in list.', 0, '2026-05-14 18:52:31'),
(3, 4, 'Cleaning Task Assigned', 'Room 103 cleaning task has been assigned.', 0, '2026-05-14 18:52:31'),
(4, 1, 'System Setup Completed', 'Initial hotel management system demo data has been created.', 0, '2026-05-14 18:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `overall_rating` int(11) NOT NULL,
  `cleanliness_rating` int(11) NOT NULL,
  `service_rating` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `admin_reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `replied_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `guest_id`, `overall_rating`, `cleanliness_rating`, `service_rating`, `review_text`, `admin_reply`, `created_at`, `replied_at`) VALUES
(1, 1, 2, 5, 5, 4, 'The hotel room was clean and the service was good.', 'Thank you for your valuable feedback.', '2026-05-14 18:52:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `room_number` varchar(30) NOT NULL,
  `floor` int(11) NOT NULL,
  `status` enum('available','occupied','dirty','maintenance','blocked') NOT NULL DEFAULT 'available',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type_id`, `room_number`, `floor`, `status`, `notes`, `created_at`) VALUES
(1, 1, '101', 1, 'available', 'Standard room near lobby', '2026-05-14 18:52:30'),
(2, 1, '102', 1, 'available', 'Standard room with city view', '2026-05-14 18:52:30'),
(3, 1, '103', 1, 'dirty', 'Recently checked out, needs cleaning', '2026-05-14 18:52:30'),
(4, 1, '104', 1, 'blocked', 'Temporarily blocked for internal use', '2026-05-14 18:52:30'),
(5, 2, '201', 2, 'occupied', 'Deluxe room with balcony', '2026-05-14 18:52:30'),
(6, 2, '202', 2, 'occupied', 'Currently occupied by guest', '2026-05-14 18:52:30'),
(7, 2, '203', 2, 'maintenance', 'Bathroom plumbing issue', '2026-05-14 18:52:30'),
(8, 2, '204', 2, 'available', 'Deluxe corner room', '2026-05-14 18:52:30'),
(9, 3, '301', 3, 'occupied', 'Executive suite with premium view', '2026-05-14 18:52:30'),
(10, 3, '302', 3, 'maintenance', 'AC inspection required', '2026-05-14 18:52:30'),
(11, 3, '303', 3, 'available', 'Executive suite with city view', '2026-05-14 18:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `name`, `description`, `price_per_night`, `max_capacity`, `thumbnail_path`, `amenities`, `is_active`, `created_at`) VALUES
(1, 'Standard Room', 'A clean and comfortable budget-friendly room suitable for solo travelers and couples. Includes essential hotel facilities for a pleasant stay.', 2500.00, 2, 'standard-room.jpg', '[\"Free WiFi\", \"Air Conditioning\", \"LED TV\", \"Attached Bathroom\", \"Room Service\"]', 1, '2026-05-14 18:52:30'),
(2, 'Deluxe Room', 'A spacious room with enhanced comfort, better interior design, and additional facilities for families and business travelers.', 4500.00, 3, 'deluxe-room.jpg', '[\"Free WiFi\", \"Air Conditioning\", \"Smart TV\", \"Mini Fridge\", \"Tea Table\", \"Balcony View\"]', 1, '2026-05-14 18:52:30'),
(3, 'Executive Suite', 'A premium luxury suite designed for executive guests, families, and VIP customers with a large space and premium amenities.', 8000.00, 4, 'executive-suite.jpg', '[\"Free WiFi\", \"Air Conditioning\", \"Smart TV\", \"Mini Bar\", \"Bathtub\", \"Work Desk\", \"Premium View\"]', 1, '2026-05-14 18:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `room_type_images`
--

CREATE TABLE `room_type_images` (
  `id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `caption` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_type_images`
--

INSERT INTO `room_type_images` (`id`, `room_type_id`, `image_path`, `caption`, `created_at`) VALUES
(1, 1, 'standard-room-1.jpg', 'Standard room front view', '2026-05-14 18:52:30'),
(2, 1, 'standard-room-2.jpg', 'Standard room interior', '2026-05-14 18:52:30'),
(3, 2, 'deluxe-room-1.jpg', 'Deluxe room interior', '2026-05-14 18:52:30'),
(4, 2, 'deluxe-room-2.jpg', 'Deluxe room balcony view', '2026-05-14 18:52:30'),
(5, 3, 'suite-room-1.jpg', 'Executive suite master bedroom', '2026-05-14 18:52:30'),
(6, 3, 'suite-room-2.jpg', 'Executive suite premium lounge', '2026-05-14 18:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `seasonal_pricing`
--

CREATE TABLE `seasonal_pricing` (
  `id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `label` varchar(120) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seasonal_pricing`
--

INSERT INTO `seasonal_pricing` (`id`, `room_type_id`, `label`, `start_date`, `end_date`, `price_per_night`, `is_active`) VALUES
(1, 1, 'Eid Holiday Season', '2026-06-01', '2026-06-10', 3000.00, 1),
(2, 2, 'Eid Holiday Season', '2026-06-01', '2026-06-10', 5200.00, 1),
(3, 3, 'Eid Premium Season', '2026-06-01', '2026-06-10', 9500.00, 1),
(4, 1, 'Winter Offer', '2026-12-01', '2026-12-31', 2300.00, 1),
(5, 2, 'Winter Offer', '2026-12-01', '2026-12-31', 4200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `service_type` enum('extra_bed','toiletries','laundry','room_service','other') NOT NULL,
  `description` text NOT NULL,
  `service_charge` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `booking_id`, `guest_id`, `room_id`, `service_type`, `description`, `service_charge`, `status`, `requested_at`, `completed_at`) VALUES
(1, 2, 2, 6, 'toiletries', 'Need extra towels and soap.', 0.00, 'completed', '2026-05-14 18:52:30', NULL),
(2, 2, 2, 6, 'room_service', 'Evening tea and snacks.', 500.00, 'completed', '2026-05-14 18:52:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `updated_at`) VALUES
(1, 'hotel_name', 'Grand Palace Hotel', '2026-05-14 18:52:31'),
(2, 'hotel_email', 'info@grandpalacehotel.com', '2026-05-14 18:52:31'),
(3, 'hotel_phone', '01710000000', '2026-05-14 18:52:31'),
(4, 'cancellation_days_before_checkin', '2', '2026-05-14 18:52:31'),
(5, 'loyalty_points_per_100_taka', '1', '2026-05-14 18:52:31'),
(6, 'service_charge_percentage', '5', '2026-05-14 18:52:31'),
(7, 'default_currency', 'BDT', '2026-05-14 18:52:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `nationality` varchar(80) DEFAULT 'Bangladeshi',
  `id_number` varchar(80) DEFAULT NULL,
  `role` enum('guest','receptionist','housekeeping','admin') NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `phone`, `nationality`, `id_number`, `role`, `profile_pic`, `is_active`, `last_login_at`, `created_at`) VALUES
(1, 'Famid Rabbi', 'famid.rabbi@grandpalacehotel.com', '$2b$10$nY5/YJ7VOtSZ4V9TrqmCeOZx9MA/j8snTq7hBADCIqYMnT0wL4Wt2', '01710000001', 'Bangladeshi', 'ADM-001', 'admin', NULL, 1, NULL, '2026-05-14 18:52:30'),
(2, 'Tasmin Tashu', 'tasmin.tashu@grandpalacehotel.com', '$2b$10$nY5/YJ7VOtSZ4V9TrqmCeOZx9MA/j8snTq7hBADCIqYMnT0wL4Wt2', '01710000002', 'Bangladeshi', 'GST-001', 'guest', NULL, 1, NULL, '2026-05-14 18:52:30'),
(3, 'Rayhan Rabby', 'rayhan.rabby@grandpalacehotel.com', '$2b$10$nY5/YJ7VOtSZ4V9TrqmCeOZx9MA/j8snTq7hBADCIqYMnT0wL4Wt2', '01710000003', 'Bangladeshi', 'REC-001', 'receptionist', NULL, 1, NULL, '2026-05-14 18:52:30'),
(4, 'Anika Tahsin', 'anika.tahsin@grandpalacehotel.com', '$2b$10$nY5/YJ7VOtSZ4V9TrqmCeOZx9MA/j8snTq7hBADCIqYMnT0wL4Wt2', '01716667657', 'Bangladeshi', 'HK-001', 'housekeeping', NULL, 1, NULL, '2026-05-14 18:52:30'),
(8, 'Lamisa', 'lamisa1778855440@walkin.local', '$2y$10$CU8.A55WFQD1r5qD1a1vzOBYQNDOVspsJ4OCZdQFkzd/zt3z/yYYu', '01904322517', 'Bangladesh', '2323344321', 'guest', NULL, 1, NULL, '2026-05-15 14:30:41'),
(9, 'Lamisa N', 'lamisan1778875513@walkin.local', '$2y$10$2HfONk08fcqy3xA1AyM.VeoDqeMO8o7d.mnJqEiasp1I4cNqDaJuW', '01904322517', 'Bangladesh', '2323344321', 'guest', NULL, 1, NULL, '2026-05-15 20:05:13'),
(10, 'hasam', 'hasam1778875653@walkin.local', '$2y$10$eXhojY/ybW63t8mbbwSpK.lmJgi/X.5UVlLlxzIokXZXunqwo/AxK', '01904322517', 'Bangladesh', '2323344321', 'guest', NULL, 1, NULL, '2026-05-15 20:07:33'),
(11, 'Lamisa', 'lamisa1778875801@walkin.local', '$2y$10$Xhb9mXPSvL1lSWTbyQeZW.50stao3GsmXShmUhcf9dJYP65GytAUW', '01717772920', 'Bangladesh', '2323344321', 'guest', NULL, 1, NULL, '2026-05-15 20:10:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `idx_billing_status` (`payment_status`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `room_type_id` (`room_type_id`),
  ADD KEY `idx_bookings_guest` (`guest_id`),
  ADD KEY `idx_bookings_status` (`status`),
  ADD KEY `idx_bookings_dates` (`checkin_date`,`checkout_date`);

--
-- Indexes for table `booking_modification_requests`
--
ALTER TABLE `booking_modification_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `housekeeping_tasks`
--
ALTER TABLE `housekeeping_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `idx_housekeeping_status` (`status`);

--
-- Indexes for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `maintenance_reports`
--
ALTER TABLE `maintenance_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `reported_by` (`reported_by`),
  ADD KEY `idx_maintenance_status` (`status`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `guest_id` (`guest_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `idx_rooms_status` (`status`),
  ADD KEY `idx_rooms_type` (`room_type_id`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_type_images`
--
ALTER TABLE `room_type_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `seasonal_pricing`
--
ALTER TABLE `seasonal_pricing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_type_id` (`room_type_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `guest_id` (`guest_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `idx_service_status` (`status`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `booking_modification_requests`
--
ALTER TABLE `booking_modification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `housekeeping_tasks`
--
ALTER TABLE `housekeeping_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `maintenance_reports`
--
ALTER TABLE `maintenance_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `room_type_images`
--
ALTER TABLE `room_type_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `seasonal_pricing`
--
ALTER TABLE `seasonal_pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `billing`
--
ALTER TABLE `billing`
  ADD CONSTRAINT `billing_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `billing_ibfk_2` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `booking_modification_requests`
--
ALTER TABLE `booking_modification_requests`
  ADD CONSTRAINT `booking_modification_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_modification_requests_ibfk_2` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_modification_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `housekeeping_tasks`
--
ALTER TABLE `housekeeping_tasks`
  ADD CONSTRAINT `housekeeping_tasks_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `housekeeping_tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `loyalty_points_ibfk_1` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `loyalty_points_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `maintenance_reports`
--
ALTER TABLE `maintenance_reports`
  ADD CONSTRAINT `maintenance_reports_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `maintenance_reports_ibfk_2` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `room_type_images`
--
ALTER TABLE `room_type_images`
  ADD CONSTRAINT `room_type_images_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seasonal_pricing`
--
ALTER TABLE `seasonal_pricing`
  ADD CONSTRAINT `seasonal_pricing_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_requests_ibfk_2` FOREIGN KEY (`guest_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `service_requests_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
