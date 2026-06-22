-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2026 at 06:46 PM
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
-- Database: `campustrace_db`
--
CREATE DATABASE IF NOT EXISTS `campustrace_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `campustrace_db`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `details`, `created_at`) VALUES
(1, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 10:24:47'),
(2, 7, 'User registered', 'New user: salusylvia02@gmail.com', '2026-06-22 11:03:49'),
(3, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 11:03:57'),
(4, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 12:36:37'),
(5, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 12:45:41'),
(6, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 13:00:13'),
(7, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 13:33:31'),
(8, 7, 'Found report submitted', 'Found item: Black Wallet', '2026-06-22 13:34:09'),
(9, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 13:35:58'),
(10, 7, 'Submitted claim', 'Claimed item: Black Wallet', '2026-06-22 13:47:53'),
(11, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 13:53:09'),
(12, 7, 'Submitted claim', 'Claimed item: Black Leather Wallet', '2026-06-22 13:53:56'),
(13, 7, 'Posted item', 'Posted a found item: ADAPTER CHARGER', '2026-06-22 14:02:10'),
(14, 8, 'User registered', 'New user: johnester@gmail.com', '2026-06-22 14:03:44'),
(15, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 14:03:55'),
(16, 8, 'Submitted claim', 'Claimed item: ADAPTER CHARGER', '2026-06-22 14:04:25'),
(17, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:04:37'),
(18, 7, 'Approved claim', 'Approved claim for item: ADAPTER CHARGER', '2026-06-22 14:06:44'),
(19, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 14:09:09'),
(20, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:14:58'),
(21, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:30:51'),
(22, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:32:13'),
(23, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:33:22'),
(24, 7, 'Posted item', 'Posted a found item: ID', '2026-06-22 14:33:57'),
(25, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 14:34:20'),
(26, 8, 'Submitted claim', 'Claimed item: ID', '2026-06-22 14:34:51'),
(27, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:35:36'),
(28, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 14:39:46'),
(29, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:39:58'),
(30, 7, 'Approved claim', 'Approved claim for item: ID', '2026-06-22 14:40:13'),
(31, 7, 'Posted item', 'Posted a found item: IPHONE 17', '2026-06-22 14:54:03'),
(32, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 14:54:24'),
(33, 8, 'Submitted claim', 'Claimed item: IPHONE 17', '2026-06-22 14:54:52'),
(34, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 14:56:05'),
(35, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 15:21:22'),
(36, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 15:22:16'),
(37, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 15:23:06'),
(38, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 15:25:11'),
(39, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 15:41:48'),
(40, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 15:42:09'),
(41, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 15:43:21'),
(42, 8, 'User logged in', 'User: johnester@gmail.com', '2026-06-22 15:51:35'),
(43, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 16:04:05'),
(44, 1, 'User logged in', 'User: admin@campustrace.com', '2026-06-22 16:04:44'),
(45, 1, 'Toggled user status', 'User 6 suspended', '2026-06-22 16:07:25'),
(46, 1, 'Deleted user', 'Deleted user ID: 5', '2026-06-22 16:07:30'),
(47, 1, 'Deleted user', 'Deleted user ID: 4', '2026-06-22 16:07:35'),
(48, 1, 'Toggled user status', 'User 6 activated', '2026-06-22 16:07:39'),
(49, 1, 'Deleted user', 'Deleted user ID: 6', '2026-06-22 16:07:43'),
(50, 1, 'Deleted user', 'Deleted user ID: 3', '2026-06-22 16:07:46'),
(51, 1, 'Deleted user', 'Deleted user ID: 2', '2026-06-22 16:07:52'),
(52, 7, 'User logged in', 'User: salusylvia02@gmail.com', '2026-06-22 16:16:26');

-- --------------------------------------------------------

--
-- Table structure for table `claims`
--

CREATE TABLE `claims` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `claimant_id` int(11) NOT NULL,
  `proof_description` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  `chat_enabled` tinyint(1) DEFAULT 0,
  `chat_started_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `claims`
--

INSERT INTO `claims` (`id`, `item_id`, `claimant_id`, `proof_description`, `status`, `admin_notes`, `created_at`, `resolved_at`, `chat_enabled`, `chat_started_at`) VALUES
(4, 34, 7, 'there was an ID in it', 'pending', NULL, '2026-06-22 13:47:53', NULL, 0, NULL),
(6, 40, 8, 'its mine', 'approved', NULL, '2026-06-22 14:04:25', '2026-06-22 14:06:44', 0, NULL),
(7, 41, 8, 'it has my name Esther', 'approved', NULL, '2026-06-22 14:34:51', '2026-06-22 14:40:13', 0, NULL),
(8, 42, 8, 'she is short', 'approved', NULL, '2026-06-22 14:54:52', '2026-06-22 14:56:15', 1, '2026-06-22 14:56:15');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `claim_id` int(11) DEFAULT NULL,
  `last_message` text DEFAULT NULL,
  `last_message_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `unread_count_user1` int(11) DEFAULT 0,
  `unread_count_user2` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `user1_id`, `user2_id`, `claim_id`, `last_message`, `last_message_time`, `unread_count_user1`, `unread_count_user2`) VALUES
(15, 7, 8, NULL, 'where', '2026-06-22 15:20:35', 0, 0),
(16, 7, 8, NULL, 'umeskiaa', '2026-06-22 14:55:48', 1, 0),
(17, 7, 8, 8, 'umeona meeting  schedule apo', '2026-06-22 15:40:45', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `found_reports`
--

CREATE TABLE `found_reports` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `finder_id` int(11) NOT NULL,
  `found_location` varchar(255) DEFAULT NULL,
  `found_date` date DEFAULT NULL,
  `proof_description` text DEFAULT NULL,
  `status` enum('pending','matched','returned') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `found_reports`
--

INSERT INTO `found_reports` (`id`, `item_id`, `finder_id`, `found_location`, `found_date`, `proof_description`, `status`, `created_at`) VALUES
(1, 34, 7, 'cafeteria', '2026-06-22', 'it had ID car inside', 'pending', '2026-06-22 13:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category` enum('electronics','accessories','documents','bags','clothing','keys','id_cards','books','others') NOT NULL,
  `type` enum('lost','found') NOT NULL,
  `location` varchar(255) NOT NULL,
  `found_lost_date` date NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('open','claimed','returned','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `user_id`, `title`, `description`, `category`, `type`, `location`, `found_lost_date`, `image_url`, `status`, `created_at`) VALUES
(30, 1, 'Scientific Calculator', 'Casio FX-991EX scientific calculator. Lost in the Library during my study session on Wednesday morning.', 'electronics', 'lost', 'Library - 2nd Floor', '2026-06-20', 'https://images.unsplash.com/photo-1587132137056-bcbf0166836e?w=400&h=300&fit=crop', 'open', '2026-06-20 12:44:45'),
(32, 1, 'Laptop Charger', 'Dell laptop charger (65W). Lost in Lecture Hall B after the 10am Accounting class.', 'electronics', 'lost', 'Lecture Hall B', '2026-06-19', 'https://images.unsplash.com/photo-1586953208448-b95a79798f07?w=400&h=300&fit=crop', 'open', '2026-06-19 12:44:45'),
(34, 1, 'Black Wallet', 'Black leather wallet containing my Student ID, ATM card, and 20,000 TZS. Lost near the Administration Block.', 'accessories', 'lost', 'Administration Block', '2026-06-21', 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=400&h=300&fit=crop', 'open', '2026-06-21 12:44:45'),
(40, 7, 'ADAPTER CHARGER', 'ITS A LONG AND BLACK CHARGER', 'electronics', 'found', 'library', '2026-06-22', 'assets/uploads/1782136930_download.png', 'claimed', '2026-06-22 14:02:10'),
(41, 7, 'ID', 'School ID', 'id_cards', 'found', 'library', '2026-06-22', 'assets/uploads/1782138837_Alida results.png', 'claimed', '2026-06-22 14:33:57'),
(42, 7, 'IPHONE 17', 'ORANGE COLOR', 'electronics', 'found', 'PG', '2026-06-22', 'assets/uploads/1782140043_PXL_20250401_184028214.NIGHT~2.jpg', 'claimed', '2026-06-22 14:54:03');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `claim_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `claim_id`, `message`, `is_read`, `created_at`) VALUES
(19, 8, 7, NULL, 'my phone number is 444 lets meet', 1, '2026-06-22 14:55:35'),
(20, 8, 7, NULL, 'umeskiaa', 1, '2026-06-22 14:55:48'),
(28, 7, 8, NULL, 'okee', 1, '2026-06-22 15:20:22'),
(29, 7, 8, NULL, 'where', 1, '2026-06-22 15:20:35'),
(30, 7, 8, 8, 'umeona meeting  schedule apo', 1, '2026-06-22 15:40:45');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `claim_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `claim_id`, `type`, `message`, `is_read`, `created_at`) VALUES
(10, 8, 7, 'claim_approved', 'Your claim for ID was approved!', 0, '2026-06-22 14:40:13'),
(12, 1, NULL, 'welcome', 'Welcome to CampusTrace! Start by posting a lost or found item.', 1, '2026-05-23 15:16:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `role` enum('student','admin') DEFAULT 'student',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `trust_score` int(11) DEFAULT 0,
  `successful_returns` int(11) DEFAULT 0,
  `failed_claims` int(11) DEFAULT 0,
  `is_verified_user` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password_hash`, `phone`, `student_id`, `role`, `is_active`, `created_at`, `trust_score`, `successful_returns`, `failed_claims`, `is_verified_user`) VALUES
(1, 'System Administrator', 'admin@campustrace.com', '$2a$12$ZuroHosHI5KybUflWYwnxu06P54szBPPp0TEU8W4RdPKN4Iii8d8C', '+255712345678', 'ADMIN001', 'admin', 1, '2026-06-22 09:44:08', 0, 0, 0, 0),
(7, 'Sylvia', 'salusylvia02@gmail.com', '$2y$10$ECvcIb/W3qBU5AVNZYypXuVghF9pSrPXN7gF0k3d0BRr2v/NMaPCa', '0688054087', 'BIT-01-0028-2023', 'student', 1, '2026-06-22 11:03:49', 0, 0, 0, 0),
(8, 'Esther', 'johnester@gmail.com', '$2y$10$IskeL/CIdA1U66X9U0RDdupOPQdv31TyQiYvqUKB2WgPSbjB47M0K', '0688054087', 'BIT-01-0013-2023', 'student', 1, '2026-06-22 14:03:44', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_reviews`
--

CREATE TABLE `user_reviews` (
  `id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `reviewed_id` int(11) NOT NULL,
  `claim_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `claims`
--
ALTER TABLE `claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `claimant_id` (`claimant_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_conversation` (`user1_id`,`user2_id`,`claim_id`),
  ADD KEY `user2_id` (`user2_id`),
  ADD KEY `conversations_ibfk_3` (`claim_id`);

--
-- Indexes for table `found_reports`
--
ALTER TABLE `found_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `finder_id` (`finder_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `messages_ibfk_3` (`claim_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `claim_id` (`claim_id`),
  ADD KEY `idx_notifications_user_id` (`user_id`),
  ADD KEY `idx_notifications_is_read` (`is_read`),
  ADD KEY `idx_notifications_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_reviews`
--
ALTER TABLE `user_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `reviewed_id` (`reviewed_id`),
  ADD KEY `claim_id` (`claim_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `claims`
--
ALTER TABLE `claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `found_reports`
--
ALTER TABLE `found_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_reviews`
--
ALTER TABLE `user_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `claims`
--
ALTER TABLE `claims`
  ADD CONSTRAINT `claims_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `claims_ibfk_2` FOREIGN KEY (`claimant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_ibfk_3` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `found_reports`
--
ALTER TABLE `found_reports`
  ADD CONSTRAINT `found_reports_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `found_reports_ibfk_2` FOREIGN KEY (`finder_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_reviews`
--
ALTER TABLE `user_reviews`
  ADD CONSTRAINT `user_reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_reviews_ibfk_2` FOREIGN KEY (`reviewed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_reviews_ibfk_3` FOREIGN KEY (`claim_id`) REFERENCES `claims` (`id`) ON DELETE CASCADE;
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"campustrace_db\",\"table\":\"activity_logs\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-06-22 16:46:14', '{\"Console\\/Mode\":\"collapse\",\"NavigationWidth\":0}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `smarttech_labs`
--
CREATE DATABASE IF NOT EXISTS `smarttech_labs` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smarttech_labs`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--
-- Error reading structure for table smarttech_labs.activity_logs: #1932 - Table &#039;smarttech_labs.activity_logs&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.activity_logs: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`activity_logs`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `ai_workflows`
--
-- Error reading structure for table smarttech_labs.ai_workflows: #1932 - Table &#039;smarttech_labs.ai_workflows&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.ai_workflows: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`ai_workflows`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--
-- Error reading structure for table smarttech_labs.clients: #1932 - Table &#039;smarttech_labs.clients&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.clients: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`clients`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `crm_leads`
--
-- Error reading structure for table smarttech_labs.crm_leads: #1932 - Table &#039;smarttech_labs.crm_leads&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.crm_leads: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`crm_leads`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--
-- Error reading structure for table smarttech_labs.documents: #1932 - Table &#039;smarttech_labs.documents&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.documents: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`documents`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--
-- Error reading structure for table smarttech_labs.expenses: #1932 - Table &#039;smarttech_labs.expenses&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.expenses: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`expenses`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--
-- Error reading structure for table smarttech_labs.invoices: #1932 - Table &#039;smarttech_labs.invoices&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.invoices: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`invoices`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--
-- Error reading structure for table smarttech_labs.messages: #1932 - Table &#039;smarttech_labs.messages&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.messages: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`messages`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--
-- Error reading structure for table smarttech_labs.notifications: #1932 - Table &#039;smarttech_labs.notifications&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.notifications: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`notifications`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--
-- Error reading structure for table smarttech_labs.payments: #1932 - Table &#039;smarttech_labs.payments&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.payments: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`payments`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--
-- Error reading structure for table smarttech_labs.projects: #1932 - Table &#039;smarttech_labs.projects&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.projects: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`projects`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--
-- Error reading structure for table smarttech_labs.support_tickets: #1932 - Table &#039;smarttech_labs.support_tickets&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.support_tickets: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`support_tickets`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--
-- Error reading structure for table smarttech_labs.tasks: #1932 - Table &#039;smarttech_labs.tasks&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.tasks: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`tasks`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `time_entries`
--
-- Error reading structure for table smarttech_labs.time_entries: #1932 - Table &#039;smarttech_labs.time_entries&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.time_entries: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`time_entries`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Error reading structure for table smarttech_labs.users: #1932 - Table &#039;smarttech_labs.users&#039; doesn&#039;t exist in engine
-- Error reading data for table smarttech_labs.users: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smarttech_labs`.`users`&#039; at line 1
--
-- Database: `smsc_radio`
--
CREATE DATABASE IF NOT EXISTS `smsc_radio` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `smsc_radio`;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--
-- Error reading structure for table smsc_radio.contact_messages: #1932 - Table &#039;smsc_radio.contact_messages&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.contact_messages: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`contact_messages`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `events`
--
-- Error reading structure for table smsc_radio.events: #1932 - Table &#039;smsc_radio.events&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.events: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`events`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `news`
--
-- Error reading structure for table smsc_radio.news: #1932 - Table &#039;smsc_radio.news&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.news: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`news`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--
-- Error reading structure for table smsc_radio.newsletter_subscribers: #1932 - Table &#039;smsc_radio.newsletter_subscribers&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.newsletter_subscribers: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`newsletter_subscribers`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `podcasts`
--
-- Error reading structure for table smsc_radio.podcasts: #1932 - Table &#039;smsc_radio.podcasts&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.podcasts: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`podcasts`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--
-- Error reading structure for table smsc_radio.schedule: #1932 - Table &#039;smsc_radio.schedule&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.schedule: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`schedule`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `shows`
--
-- Error reading structure for table smsc_radio.shows: #1932 - Table &#039;smsc_radio.shows&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.shows: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`shows`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--
-- Error reading structure for table smsc_radio.site_settings: #1932 - Table &#039;smsc_radio.site_settings&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.site_settings: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`site_settings`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `training`
--
-- Error reading structure for table smsc_radio.training: #1932 - Table &#039;smsc_radio.training&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.training: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`training`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `training_inquiries`
--
-- Error reading structure for table smsc_radio.training_inquiries: #1932 - Table &#039;smsc_radio.training_inquiries&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.training_inquiries: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`training_inquiries`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `training_topics`
--
-- Error reading structure for table smsc_radio.training_topics: #1932 - Table &#039;smsc_radio.training_topics&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.training_topics: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`training_topics`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Error reading structure for table smsc_radio.users: #1932 - Table &#039;smsc_radio.users&#039; doesn&#039;t exist in engine
-- Error reading data for table smsc_radio.users: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `smsc_radio`.`users`&#039; at line 1
--
-- Database: `stockvault_db`
--
CREATE DATABASE IF NOT EXISTS `stockvault_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `stockvault_db`;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_banners`
--
-- Error reading structure for table stockvault_db.announcement_banners: #1932 - Table &#039;stockvault_db.announcement_banners&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.announcement_banners: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`announcement_banners`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--
-- Error reading structure for table stockvault_db.branches: #1932 - Table &#039;stockvault_db.branches&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.branches: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`branches`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--
-- Error reading structure for table stockvault_db.categories: #1932 - Table &#039;stockvault_db.categories&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.categories: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`categories`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--
-- Error reading structure for table stockvault_db.email_templates: #1932 - Table &#039;stockvault_db.email_templates&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.email_templates: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`email_templates`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `low_stock_alerts`
--
-- Error reading structure for table stockvault_db.low_stock_alerts: #1932 - Table &#039;stockvault_db.low_stock_alerts&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.low_stock_alerts: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`low_stock_alerts`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_log`
--
-- Error reading structure for table stockvault_db.maintenance_log: #1932 - Table &#039;stockvault_db.maintenance_log&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.maintenance_log: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`maintenance_log`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_payments`
--
-- Error reading structure for table stockvault_db.mpesa_payments: #1932 - Table &#039;stockvault_db.mpesa_payments&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.mpesa_payments: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`mpesa_payments`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
-- Error reading structure for table stockvault_db.products: #1932 - Table &#039;stockvault_db.products&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.products: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`products`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--
-- Error reading structure for table stockvault_db.purchase_items: #1932 - Table &#039;stockvault_db.purchase_items&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.purchase_items: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`purchase_items`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--
-- Error reading structure for table stockvault_db.purchase_orders: #1932 - Table &#039;stockvault_db.purchase_orders&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.purchase_orders: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`purchase_orders`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--
-- Error reading structure for table stockvault_db.sales: #1932 - Table &#039;stockvault_db.sales&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.sales: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`sales`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--
-- Error reading structure for table stockvault_db.sale_items: #1932 - Table &#039;stockvault_db.sale_items&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.sale_items: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`sale_items`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--
-- Error reading structure for table stockvault_db.stock_movements: #1932 - Table &#039;stockvault_db.stock_movements&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.stock_movements: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`stock_movements`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `subscription_payments`
--
-- Error reading structure for table stockvault_db.subscription_payments: #1932 - Table &#039;stockvault_db.subscription_payments&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.subscription_payments: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`subscription_payments`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--
-- Error reading structure for table stockvault_db.subscription_plans: #1932 - Table &#039;stockvault_db.subscription_plans&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.subscription_plans: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`subscription_plans`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--
-- Error reading structure for table stockvault_db.suppliers: #1932 - Table &#039;stockvault_db.suppliers&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.suppliers: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`suppliers`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--
-- Error reading structure for table stockvault_db.system_settings: #1932 - Table &#039;stockvault_db.system_settings&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.system_settings: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`system_settings`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--
-- Error reading structure for table stockvault_db.tenants: #1932 - Table &#039;stockvault_db.tenants&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.tenants: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`tenants`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `tenant_activity_log`
--
-- Error reading structure for table stockvault_db.tenant_activity_log: #1932 - Table &#039;stockvault_db.tenant_activity_log&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.tenant_activity_log: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`tenant_activity_log`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Error reading structure for table stockvault_db.users: #1932 - Table &#039;stockvault_db.users&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.users: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`users`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--
-- Error reading structure for table stockvault_db.user_sessions: #1932 - Table &#039;stockvault_db.user_sessions&#039; doesn&#039;t exist in engine
-- Error reading data for table stockvault_db.user_sessions: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `stockvault_db`.`user_sessions`&#039; at line 1
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
--
-- Database: `wrapped_by_vee`
--
CREATE DATABASE IF NOT EXISTS `wrapped_by_vee` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `wrapped_by_vee`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `admin_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'Admin Login', 'Successful login', '::1', '2026-06-20 10:31:42'),
(2, 1, 'Admin Login', 'Successful login', '::1', '2026-06-20 10:38:22'),
(3, 1, 'Admin Login', 'Successful login', '172.20.10.1', '2026-06-20 10:48:25'),
(4, 1, 'Admin Login', 'Successful login', '172.20.10.1', '2026-06-20 10:51:21'),
(5, 1, 'Admin Login', 'Successful login', '::1', '2026-06-20 11:04:01'),
(6, 1, 'Admin Login', 'Successful login', '::1', '2026-06-20 12:05:42'),
(7, 1, 'Admin Login', 'Successful login', '::1', '2026-06-20 12:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT 'admin',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account_locked` tinyint(1) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `last_login_attempt` datetime DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `email_verify_token` varchar(255) DEFAULT NULL,
  `email_verify_expires` datetime DEFAULT NULL,
  `temp_new_email` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `email`, `password_hash`, `full_name`, `role`, `is_active`, `created_at`, `updated_at`, `account_locked`, `locked_until`, `login_attempts`, `last_login_attempt`, `email_verified`, `email_verify_token`, `email_verify_expires`, `temp_new_email`, `reset_token`, `reset_expires`) VALUES
(1, 'admin', 'admin@wrappedbyvee.com', '$2y$10$5GLdinqhScNbRSphSO0FxOpRCvds419z6gC8jr/XSpgYPvShSZ52S', 'Administrator', 'admin', 1, '2026-06-20 10:31:31', '2026-06-21 10:51:57', 0, NULL, 0, '2026-06-20 13:51:07', 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branding_settings`
--

CREATE TABLE `branding_settings` (
  `id` int(11) NOT NULL,
  `primary_color` varchar(20) DEFAULT '#C2697E',
  `secondary_color` varchar(20) DEFAULT '#FFE0EC',
  `welcome_message` text DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branding_settings`
--

INSERT INTO `branding_settings` (`id`, `primary_color`, `secondary_color`, `welcome_message`, `favicon`, `created_at`, `updated_at`) VALUES
(1, '#C2697E', '#FFE0EC', 'Welcome to Wrapped by Vee Admin Panel', NULL, '2026-06-20 10:32:45', '2026-06-20 10:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` int(11) NOT NULL,
  `business_name` varchar(255) DEFAULT 'Wrapped by Vee',
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `whatsapp` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `business_logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `business_name`, `email`, `phone`, `whatsapp`, `address`, `facebook`, `instagram`, `twitter`, `linkedin`, `business_logo`, `created_at`, `updated_at`) VALUES
(1, 'Wrapped by Vee', 'info@wrappedbyvee.com', '+255 712 345 678', '+255 712 345 678', 'Dodoma, Tanzania', '', '', '', '', '', '2026-06-20 10:37:34', '2026-06-20 10:37:34');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_settings`
--

CREATE TABLE `delivery_settings` (
  `id` int(11) NOT NULL,
  `base_delivery_fee` decimal(10,2) DEFAULT 5000.00,
  `free_delivery_threshold` decimal(10,2) DEFAULT 60000.00,
  `same_day_cutoff_time` varchar(10) DEFAULT '14:00',
  `delivery_instructions` text DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_settings`
--

INSERT INTO `delivery_settings` (`id`, `base_delivery_fee`, `free_delivery_threshold`, `same_day_cutoff_time`, `delivery_instructions`, `contact_phone`, `created_at`, `updated_at`) VALUES
(1, 5000.00, 60000.00, '14:00', 'Our delivery team will contact you to confirm delivery time. Same-day delivery available for orders placed before 2:00 PM.', '+255 712 345 678', '2026-06-20 10:25:33', '2026-06-20 10:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zones`
--

CREATE TABLE `delivery_zones` (
  `id` int(11) NOT NULL,
  `zone_name` varchar(100) NOT NULL,
  `regions` text NOT NULL,
  `cities_towns` text DEFAULT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimated_days` varchar(50) DEFAULT '1-2 days',
  `is_active` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_zones`
--

INSERT INTO `delivery_zones` (`id`, `zone_name`, `regions`, `cities_towns`, `delivery_fee`, `estimated_days`, `is_active`, `sort_order`, `created_at`) VALUES
(1, 'Dar es Salaam City', 'Dar es Salaam', 'Kinondoni,Ilala,Temeke,Ubungo,Kigamboni', 3000.00, 'Same day (2-4 hours)', 1, 1, '2026-06-20 11:01:44'),
(2, 'Dar es Salaam Suburbs', 'Dar es Salaam', 'Mbezi Beach,Tegeta,Bunju,Goba', 5000.00, 'Same day', 1, 2, '2026-06-20 11:01:44'),
(3, 'Arusha City', 'Arusha', 'Arusha City,Njiro,Sakina,Themi', 8000.00, '1 day', 1, 3, '2026-06-20 11:01:44'),
(4, 'Kilimanjaro Region', 'Kilimanjaro', 'Moshi,Rombo,Marangu,Himo', 10000.00, '1-2 days', 1, 4, '2026-06-20 11:01:44'),
(5, 'Mwanza City', 'Mwanza', 'Mwanza City,Ilemela,Nyamagana', 12000.00, '2 days', 1, 5, '2026-06-20 11:01:44'),
(6, 'Dodoma City', 'Dodoma', 'Dodoma City,Makutupora,Ihumwa', 7000.00, '1 day', 1, 6, '2026-06-20 11:01:44'),
(7, 'Morogoro City', 'Morogoro', 'Morogoro City,Mikumi,Turiani', 8000.00, '1-2 days', 1, 7, '2026-06-20 11:01:44'),
(8, 'Tanga City', 'Tanga', 'Tanga City,Pangani,Muheza', 10000.00, '1-2 days', 1, 8, '2026-06-20 11:01:44'),
(9, 'Zanzibar Urban', 'Unguja', 'Zanzibar City,Mbweni,Chukwani', 25000.00, '2-3 days', 1, 9, '2026-06-20 11:01:44'),
(10, 'Pemba Island', 'Pemba', 'Wete,Chake-Chake,Mkoani', 28000.00, '3-4 days', 1, 10, '2026-06-20 11:01:44'),
(11, 'Dodoma CBD', '', NULL, 0.00, 'Same day', 1, 0, '2026-06-21 06:48:02'),
(12, 'Dodoma Outskirts', '', NULL, 5000.00, '1-2 days', 1, 0, '2026-06-21 06:48:02'),
(13, 'Dar es Salaam', '', NULL, 5000.00, '2-3 days', 1, 0, '2026-06-21 06:48:02'),
(15, 'Mwanza', '', NULL, 18000.00, '3-4 days', 1, 0, '2026-06-21 06:48:02'),
(16, 'Other Regions', '', NULL, 20000.00, '3-5 days', 1, 0, '2026-06-21 06:48:02'),
(17, 'Dodoma CBD', '', NULL, 0.00, 'Same day', 1, 0, '2026-06-21 06:54:17'),
(18, 'Dodoma Outskirts', '', NULL, 5000.00, '1-2 days', 1, 0, '2026-06-21 06:54:17'),
(19, 'Dar es Salaam', '', NULL, 15000.00, '2-3 days', 1, 0, '2026-06-21 06:54:17'),
(21, 'Mwanza', '', NULL, 18000.00, '3-4 days', 1, 0, '2026-06-21 06:54:17'),
(22, 'Tanga', '', NULL, 10000.00, '2-3 days', 1, 0, '2026-06-21 06:54:17'),
(23, 'Morogoro', '', NULL, 8000.00, '2-3 days', 1, 0, '2026-06-21 06:54:17'),
(24, 'Zanzibar', '', NULL, 25000.00, '3-5 days', 1, 0, '2026-06-21 06:54:17'),
(25, 'moshi', '', NULL, 5000.00, '2-3 days', 1, 0, '2026-06-21 10:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `email_change_logs`
--

CREATE TABLE `email_change_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `old_email` varchar(255) DEFAULT NULL,
  `new_email` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT 'Other',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `description`, `amount`, `category`, `note`, `created_at`) VALUES
(1, 'Flower supplies', 25000.00, 'Supplies', 'Bought fresh roses and lilies', '2026-06-21 09:06:45'),
(2, 'Delivery fuel', 8000.00, 'Delivery', 'Fuel for deliveries', '2026-06-21 09:06:45'),
(3, 'Shop rent', 50000.00, 'Rent', 'Monthly rent payment', '2026-06-21 09:06:45'),
(4, 'Marketing materials', 12000.00, 'Marketing', 'Flyers and business cards', '2026-06-21 09:06:45'),
(5, 'Staff salary', 30000.00, 'Salaries', 'Part-time staff payment', '2026-06-21 09:06:45'),
(6, 'Ribbons', 3000.00, 'Utilities', NULL, '2026-06-21 09:07:35');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `homepage_settings`
--

CREATE TABLE `homepage_settings` (
  `id` int(11) NOT NULL,
  `hero_title` varchar(255) DEFAULT 'Wrapped by Vee',
  `hero_description` text DEFAULT NULL,
  `hero_badge_text` varchar(255) DEFAULT 'New arrivals - Season''s finest blooms',
  `hero_title_text` varchar(255) DEFAULT 'Every bloom tells a story',
  `hero_tagline` varchar(255) DEFAULT '"Where flowers tell stories"',
  `hero_location` varchar(255) DEFAULT 'Handcrafted with love in Dodoma, Tanzania',
  `hero_image` varchar(255) DEFAULT NULL,
  `quote_text` text DEFAULT 'Flowers are the music of the ground. From earth\'s lips, spoken without sound.',
  `quote_author` varchar(100) DEFAULT 'Edwin Curran',
  `features_title` varchar(255) DEFAULT 'Why Wrapped by Vee',
  `features_subtitle` varchar(255) DEFAULT 'The Wrapped by Vee difference',
  `testimonial_1_name` varchar(100) DEFAULT 'Amina T.',
  `testimonial_1_text` text DEFAULT 'The bouquet arrived looking like it had been photographed for a magazine. My mother cried happy tears.',
  `testimonial_1_location` varchar(100) DEFAULT 'Dodoma',
  `testimonial_2_name` varchar(100) DEFAULT 'David M.',
  `testimonial_2_text` text DEFAULT 'Ordered the Golden Hour gift set for my anniversary. Vee went above and beyond.',
  `testimonial_2_location` varchar(100) DEFAULT 'Dodoma',
  `testimonial_3_name` varchar(100) DEFAULT 'Rehema K.',
  `testimonial_3_text` text DEFAULT 'Used Wrapped by Vee for our corporate event centrepieces. Professional and breathtaking.',
  `testimonial_3_location` varchar(100) DEFAULT 'Dodoma',
  `footer_tagline` varchar(255) DEFAULT '"Where flowers tell stories"',
  `footer_location` varchar(255) DEFAULT 'Handcrafted in Dodoma, Tanzania',
  `announcement_text` varchar(255) DEFAULT 'Free delivery on orders over TZS 60,000 in Dodoma',
  `announcement_link_text` varchar(100) DEFAULT 'Shop now',
  `scroll_banner_items` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `homepage_settings`
--

INSERT INTO `homepage_settings` (`id`, `hero_title`, `hero_description`, `hero_badge_text`, `hero_title_text`, `hero_tagline`, `hero_location`, `hero_image`, `quote_text`, `quote_author`, `features_title`, `features_subtitle`, `testimonial_1_name`, `testimonial_1_text`, `testimonial_1_location`, `testimonial_2_name`, `testimonial_2_text`, `testimonial_2_location`, `testimonial_3_name`, `testimonial_3_text`, `testimonial_3_location`, `footer_tagline`, `footer_location`, `announcement_text`, `announcement_link_text`, `scroll_banner_items`, `created_at`, `updated_at`) VALUES
(1, 'Wrapped by Vee', 'Luxury Florals & Gift Wrapping', 'New arrivals - Season\'s finest blooms', 'Every bloom tells a story', '\"Where flowers tell stories\"', 'Handcrafted with love in Dodoma, Tanzania', NULL, 'Flowers are the music of the ground. From earth\'s lips, spoken without sound.', 'Edwin Curran', 'Why Wrapped by Vee', 'The Wrapped by Vee difference', 'Amina T.', 'The bouquet arrived looking like it had been photographed for a magazine. My mother cried happy tears.', 'Dodoma', 'David M.', 'Ordered the Golden Hour gift set for my anniversary. Vee went above and beyond.', 'Dodoma', 'Rehema K.', 'Used Wrapped by Vee for our corporate event centrepieces. Professional and breathtaking.', 'Dodoma', '\"Where flowers tell stories\"', 'Handcrafted in Dodoma, Tanzania', 'Free delivery on orders over TZS 60,000 in Dodoma', 'Shop now', '? Fresh Bouquets Daily ? Same-Day Dodoma Delivery ? Handcrafted with Love ? Custom Gift Wrapping ? Weddings & Events ? Corporate Orders Welcome', '2026-06-20 10:25:07', '2026-06-20 10:25:24');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`id`, `name`, `amount`, `category`, `source`, `note`, `created_at`) VALUES
(1, 'tuition', 50000.00, 'Workshop', 'Bank Transfer', '', '2026-06-21 08:51:12');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_categories`
--

CREATE TABLE `inventory_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` varchar(20) DEFAULT '#C2697E',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `min_stock` int(11) DEFAULT 5,
  `unit` varchar(50) DEFAULT 'piece',
  `cost_price` decimal(12,2) DEFAULT 0.00,
  `supplier` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT 'adjust',
  `quantity` int(11) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `localization_settings`
--

CREATE TABLE `localization_settings` (
  `id` int(11) NOT NULL,
  `language` varchar(10) DEFAULT 'en',
  `currency` varchar(10) DEFAULT 'TZS',
  `currency_symbol` varchar(10) DEFAULT 'TZS',
  `timezone` varchar(50) DEFAULT 'Africa/Dar_es_Salaam',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `localization_settings`
--

INSERT INTO `localization_settings` (`id`, `language`, `currency`, `currency_symbol`, `timezone`, `created_at`, `updated_at`) VALUES
(1, 'en', 'TZS', 'TZS', 'Africa/Dar_es_Salaam', '2026-06-20 10:32:45', '2026-06-20 10:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `success` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `ip_address`, `username`, `success`, `created_at`) VALUES
(1, '::1', 'admin', 0, '2026-06-20 10:28:25'),
(2, '::1', 'admin', 0, '2026-06-20 10:29:40'),
(3, '::1', 'admin', 0, '2026-06-20 10:29:46'),
(4, '::1', 'admin', 0, '2026-06-20 10:29:56'),
(5, '::1', 'admin', 0, '2026-06-20 10:30:08'),
(6, '::1', 'admin', 1, '2026-06-20 10:31:42'),
(7, '::1', 'admin', 1, '2026-06-20 10:38:22'),
(8, '172.20.10.1', 'admin', 0, '2026-06-20 10:48:01'),
(9, '172.20.10.1', 'admin', 1, '2026-06-20 10:48:25'),
(10, '172.20.10.1', 'admin', 0, '2026-06-20 10:51:07'),
(11, '172.20.10.1', 'admin', 1, '2026-06-20 10:51:21'),
(12, '::1', 'admin', 1, '2026-06-20 11:04:01'),
(13, '::1', 'admin', 1, '2026-06-20 12:05:42'),
(14, '::1', 'admin', 1, '2026-06-20 12:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `admin_id`, `ip_address`, `user_agent`, `login_time`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-20 10:31:42'),
(2, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-20 10:38:22'),
(3, 1, '172.20.10.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-06-20 10:48:25'),
(4, 1, '172.20.10.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/26.0 Mobile/15E148 Safari/604.1', '2026-06-20 10:51:21'),
(5, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-20 11:04:01'),
(6, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-06-20 12:05:42'),
(7, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-06-20 12:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` int(11) NOT NULL,
  `order_email` tinyint(1) DEFAULT 1,
  `payment_email` tinyint(1) DEFAULT 1,
  `low_stock_alert` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `order_email`, `payment_email`, `low_stock_alert`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-06-20 10:32:45', '2026-06-20 10:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `occasion_types`
--

CREATE TABLE `occasion_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `occasion_types`
--

INSERT INTO `occasion_types` (`id`, `name`, `slug`, `icon`, `created_at`) VALUES
(1, 'Birthday', 'birthday', '??', '2026-06-20 10:32:45'),
(2, 'Anniversary', 'anniversary', '??', '2026-06-20 10:32:45'),
(3, 'Wedding', 'wedding', '??', '2026-06-20 10:32:45'),
(4, 'Sympathy', 'sympathy', '???', '2026-06-20 10:32:45'),
(5, 'Just Because', 'just-because', '??', '2026-06-20 10:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `phone` varchar(50) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'M-Pesa',
  `total_amount` decimal(12,2) NOT NULL,
  `gift_wrap_selected` tinyint(1) DEFAULT 0,
  `status` varchar(50) DEFAULT 'Pending',
  `tracking_status` varchar(50) DEFAULT 'Pending',
  `payment_status` varchar(50) DEFAULT 'Pending',
  `items` text DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `client_name`, `client_email`, `address`, `phone`, `payment_method`, `total_amount`, `gift_wrap_selected`, `status`, `tracking_status`, `payment_status`, `items`, `delivery_notes`, `payment_proof`, `created_at`) VALUES
(1, 'Sylvia louis Salu', 'salusylvia02@gmail.com', 'ARUSHA', '0688054087', 'M-Pesa', 103300.00, 1, 'Pending', 'Pending', 'Pending', '[{\"id\":2,\"name\":\"Eternal Silk Roses\",\"price\":85000,\"qty\":1}]', 'Delivery Zone: Dar es Salaam City\nZone ID: 1\nDelivery Fee: TZS 3,000\nGift Message: hhhhhhhhhhhhhhh', 'uploads/proofs/1781953401_Cutewallpaper.jfif', '2026-06-20 11:03:21'),
(2, 'Sylvia', '', 'Arusha', '0748147625', 'M-Pesa', 58100.00, 1, 'Pending', 'Pending', 'Pending', '[{\"id\":1,\"name\":\"Fresh Red Roses Bouquet\",\"price\":45000,\"qty\":1}]', 'Delivery Zone: Dar es Salaam Suburbs\nZone ID: 2\nDelivery Fee: TZS 5,000\nGift Message: I love youuu', 'uploads/proofs/1781954048_6a367600278d9.jpeg', '2026-06-20 11:14:08'),
(3, 'INSTITUTE OF ACCOUNTANCY ARUSHA', 'julius@gmail.com', 'ARUSHA', '0688054087', 'M-Pesa', 58100.00, 1, 'Pending', 'Processing', 'Paid', '[{\"id\":1,\"name\":\"Fresh Red Roses Bouquet\",\"price\":45000,\"qty\":1}]', 'Delivery Zone: Dar es Salaam Suburbs\nZone ID: 2\nDelivery Fee: TZS 5,000\nGift Message: i misss you ', 'uploads/payments/1781955240_6a367aa8c0646.png', '2026-06-20 11:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_instructions`
--

CREATE TABLE `payment_instructions` (
  `id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `instruction_text` text DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `account_number` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `main_category` varchar(50) NOT NULL,
  `sub_category` varchar(50) DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `image_url` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `featured` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `main_category`, `sub_category`, `price`, `image_url`, `description`, `is_active`, `featured`, `created_at`) VALUES
(2, 'White Lily Elegance', 'Flowers', 'Flowers', 'Fresh Flowers', 55000.00, 'uploads/products/1782030296_6a379fd88fe8d.jpeg', 'Pure white lilies arranged with eucalyptus leaves for a sophisticated, elegant look. Perfect for weddings and formal events.', 1, 1, '2026-06-21 06:57:00'),
(3, 'Sunshine Daisy Bunch', 'Flowers', 'Flowers', 'Fresh Flowers', 35000.00, 'https://images.unsplash.com/photo-1562690868-60bbe7293e94?w=400&q=80', 'A cheerful bouquet of yellow daisies and sunflowers that brings warmth and joy to any occasion.', 1, 0, '2026-06-21 06:57:00'),
(4, 'Romantic Pink Peonies', 'Flowers', 'Flowers', 'Fresh Flowers', 65000.00, 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?w=400&q=80', 'Lush pink peonies with delicate baby\'s breath, creating a romantic and dreamy bouquet for special moments.', 1, 0, '2026-06-21 06:57:00'),
(5, 'Mixed Seasonal Flowers', 'Flowers', 'Flowers', 'Fresh Flowers', 40000.00, 'https://images.unsplash.com/photo-1563241527-3004b7be0ffd?w=400&q=80', 'A beautiful mix of seasonal blooms in vibrant colors, curated by our expert florists. Changes with the season.', 1, 1, '2026-06-21 06:57:00'),
(6, 'Blue Hydrangea Dream', 'Flowers', 'Flowers', 'Fresh Flowers', 58000.00, 'https://images.unsplash.com/photo-1563435051587-3d1edfca68e0?w=400&q=80', 'Stunning blue hydrangeas arranged in a classic style. These long-lasting flowers make a memorable gift.', 1, 0, '2026-06-21 06:57:00'),
(7, 'Artificial Rose Arrangement', 'Flowers', 'Flowers', 'Artificial Flowers', 25000.00, 'https://images.unsplash.com/photo-1588736196907-33e55c8ea578?w=400&q=80', 'Lifelike artificial roses in a beautiful vase. Perfect for long-lasting decor that never fades.', 1, 0, '2026-06-21 06:57:00'),
(8, 'Silk Peony Bouquet', 'Flowers', 'Flowers', 'Artificial Flowers', 30000.00, 'https://images.unsplash.com/photo-1582794543139-8ac9cb0f7b11?w=400&q=80', 'High-quality silk peonies that look incredibly real. Available in various colors. Ideal for events and home decor.', 1, 0, '2026-06-21 06:57:00'),
(9, 'Faux Lavender Stems', 'Flowers', 'Flowers', 'Artificial Flowers', 22000.00, 'https://images.unsplash.com/photo-1558640474-60033905538e?w=400&q=80', 'Beautiful faux lavender stems in a ceramic pot. Adds a touch of Provence to any space. No maintenance required.', 1, 0, '2026-06-21 06:57:00'),
(10, 'Luxury Gift Box - Gold Edition', 'Gift Packages', 'Gift Packages', 'Birthday', 75000.00, 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?w=400&q=80', 'A premium gift box containing artisanal chocolates, a mini champagne bottle, and a small floral arrangement.', 1, 1, '2026-06-21 06:57:00'),
(11, 'Anniversary Love Set', 'Gift Packages', 'Gift Packages', 'Anniversary', 68000.00, 'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?w=400&q=80', 'Romantic gift set with roses, chocolates, and a personalized card. Perfect for celebrating love and commitment.', 1, 1, '2026-06-21 06:57:00'),
(12, 'Wedding Package - Classic White', 'Gift Packages', 'Gift Packages', 'Wedding', 150000.00, 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=400&q=80', 'Complete wedding floral package including bridal bouquet, bridesmaid posies, and buttonholes. Customizable to your theme.', 1, 0, '2026-06-21 06:57:00'),
(13, 'Birthday Surprise Box', 'Gift Packages', 'Gift Packages', 'Birthday', 42000.00, 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=400&q=80', 'A delightful gift box filled with birthday treats, a small flower arrangement, and a handwritten birthday card.', 1, 0, '2026-06-21 06:57:00'),
(14, 'Corporate Gift Set', 'Gift Packages', 'Gift Packages', 'Corporate', 85000.00, 'https://images.unsplash.com/photo-1566473965997-3de9c817e938?w=400&q=80', 'Elegant gift set perfect for business partners and clients. Includes premium flowers, gourmet treats, and a branded card.', 1, 0, '2026-06-21 06:57:00'),
(15, 'Get Well Soon Basket', 'Gift Packages', 'Gift Packages', 'Other', 48000.00, 'https://images.unsplash.com/photo-1577083552431-6e5fd01988ec?w=400&q=80', 'Comforting gift basket with bright flowers, soft plush toy, and wellness treats. Designed to uplift spirits and bring smiles.', 1, 0, '2026-06-21 06:57:00'),
(16, 'Elegant Wedding Arch Decor', 'Decorations', 'Decorations', 'Wedding Decor', 200000.00, 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=400&q=80', 'Stunning floral arch decoration for wedding ceremonies. Includes fresh flowers, greenery, and custom styling. Price varies by season.', 1, 1, '2026-06-21 06:57:00'),
(17, 'Party Centerpiece Collection', 'Decorations', 'Decorations', 'Party Decor', 95000.00, 'https://images.unsplash.com/photo-1532712937440-daec112e5cbd?w=400&q=80', 'Set of 5 elegant floral centerpieces perfect for dinner parties, events, and celebrations. Each piece is uniquely designed.', 1, 0, '2026-06-21 06:57:00'),
(18, 'Rustic Table Runner', 'Decorations', 'Decorations', 'Wedding Decor', 70000.00, 'https://images.unsplash.com/photo-1519741497674-611481863552?w=400&q=80', 'Beautiful eucalyptus and flower table runner for wedding receptions and special events. Creates a romantic, natural atmosphere.', 1, 0, '2026-06-21 06:57:00'),
(19, 'Balloon and Flower Garland', 'Decorations', 'Decorations', 'Party Decor', 55000.00, 'https://images.unsplash.com/photo-1530023367847-a683933f4172?w=400&q=80', 'Stunning combination of balloons and fresh flowers, perfect for birthday parties, baby showers, and celebrations.', 1, 0, '2026-06-21 06:57:00'),
(20, 'Seasonal Wreath', 'Decorations', 'Decorations', 'Other', 38000.00, 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=400&q=80', 'Handcrafted wreath made from seasonal flowers and foliage. Perfect for door decoration or as a gift for any occasion.', 1, 0, '2026-06-21 06:57:00'),
(21, 'Event Backdrop Design', 'Decorations', 'Decorations', 'Event Decor', 180000.00, 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=400&q=80', 'Custom floral backdrop design for events, photo booths, and special occasions. Fully customizable to your theme and colors.', 1, 0, '2026-06-21 06:57:00'),
(22, 'Elegant Wedding Package', 'Events', 'Events', 'Wedding', 250000.00, 'https://images.unsplash.com/photo-1519741497674-611481863552?w=400&q=80', 'Complete wedding floral decoration package. Includes arch, table centerpieces, bridal bouquet, and bridesmaid posies.', 1, 1, '2026-06-21 06:57:00'),
(23, 'Corporate Event Decor', 'Events', 'Events', 'Corporate', 180000.00, 'https://images.unsplash.com/photo-1532712937440-daec112e5cbd?w=400&q=80', 'Professional event decoration services for corporate functions, conferences, and business events.', 1, 0, '2026-06-21 06:57:00'),
(24, 'Birthday Party Package', 'Events', 'Events', 'Birthday', 120000.00, 'https://images.unsplash.com/photo-1530103862676-de8c9debad1d?w=400&q=80', 'Complete birthday party decoration including balloons, flowers, table centerpieces, and themed decorations.', 1, 0, '2026-06-21 06:57:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `slug`, `sort_order`, `created_at`) VALUES
(1, 'Flowers', 'flowers', 1, '2026-06-20 10:32:45'),
(2, 'Gift Packages', 'gift-packages', 2, '2026-06-20 10:32:45'),
(3, 'Decorations', 'decorations', 3, '2026-06-20 10:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_subcategories`
--

CREATE TABLE `product_subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tags`
--

CREATE TABLE `product_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `created_at`, `updated_at`) VALUES
(1, 'homepage_title', 'Wrapped by Vee', 'homepage', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(2, 'homepage_tagline', 'Beautiful Gift Wrapping & Floral Arrangements', 'homepage', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(3, 'homepage_hero_image', 'assets/images/hero.jpg', 'homepage', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(4, 'homepage_about_text', 'We create beautiful gift wrapping and floral arrangements for all occasions. Our team of creative designers will make your gift unforgettable.', 'homepage', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(5, 'homepage_phone', '+255 755 555 555', 'contact', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(6, 'homepage_email', 'info@wrappedbyvee.com', 'contact', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(7, 'homepage_address', 'Dar es Salaam, Tanzania', 'contact', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(8, 'site_name', 'Wrapped', 'general', '2026-06-21 06:46:09', '2026-06-21 09:13:27'),
(9, 'site_description', 'Premium gift wrapping and floral arrangements in Tanzania', 'general', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(10, 'tax_rate', '18', 'general', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(11, 'currency', 'TZS', 'general', '2026-06-21 06:46:09', '2026-06-21 06:46:09'),
(12, 'business_logo', '', 'business', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(13, 'business_name', 'Wrapped by Vee', 'business', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(14, 'business_phone', '+255 755 555 555', 'business', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(15, 'business_email', 'info@wrappedbyvee.com', 'business', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(16, 'business_address', 'Dodoma, Tanzania', 'business', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(17, 'free_delivery_threshold', '50000', 'delivery', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(18, 'delivery_base_fee', '5000', 'delivery', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(19, 'delivery_time', '2-3 business days', 'delivery', '2026-06-21 06:47:48', '2026-06-21 06:47:48'),
(20, 'hero_badge_text', 'New arrivals Season\'s finest blooms', 'homepage', '2026-06-21 06:47:50', '2026-06-21 09:23:29'),
(21, 'hero_title_text', 'Every bloom tells a story', 'homepage', '2026-06-21 06:47:50', '2026-06-21 09:25:20'),
(22, 'hero_tagline', '\"Where flowers tell stories”', 'homepage', '2026-06-21 06:47:50', '2026-06-21 09:25:20'),
(23, 'hero_location', 'Handcrafted with love in Dodoma, Tanzania', 'homepage', '2026-06-21 06:47:50', '2026-06-21 09:25:20'),
(24, 'features_title', 'Curated Collections', 'homepage', '2026-06-21 06:47:50', '2026-06-21 09:25:20'),
(25, 'features_subtitle', 'From quiet love notes to grand celebrations', 'homepage', '2026-06-21 06:47:50', '2026-06-21 09:25:20'),
(26, 'quote_text', 'Flowers are the music of the ground. From earth\'s lips, spoken without sound.', 'homepage', '2026-06-21 06:47:50', '2026-06-21 06:47:50'),
(27, 'quote_author', 'Edwin Curran', 'homepage', '2026-06-21 06:47:50', '2026-06-21 06:47:50'),
(28, 'scroll_banner_items', '? Fresh Bouquets Daily ? Same-Day Dodoma Delivery ? Handcrafted with Love ? Custom Gift Wrapping ? Weddings & Events ? Corporate Orders Welcome', 'homepage', '2026-06-21 06:47:50', '2026-06-21 06:47:50'),
(29, 'brand_name', 'Wrapped by Vee', 'branding', '2026-06-21 09:12:04', '2026-06-21 09:12:04'),
(30, 'brand_tagline', 'Where flowers tell issuess', 'branding', '2026-06-21 09:12:04', '2026-06-21 09:12:21'),
(31, 'brand_color', '#c2697e', 'branding', '2026-06-21 09:12:04', '2026-06-21 09:12:04'),
(32, 'brand_logo', 'uploads/branding/logo_1782033124.jpeg', 'branding', '2026-06-21 09:12:04', '2026-06-21 09:12:04'),
(38, 'site_email', 'info@wrappedbyvee.com', 'general', '2026-06-21 09:13:27', '2026-06-21 09:13:27'),
(39, 'site_phone', '+255 755 555 555', 'general', '2026-06-21 09:13:27', '2026-06-21 09:13:27'),
(40, 'site_address', 'Dodoma, Tanzania', 'general', '2026-06-21 09:13:27', '2026-06-21 09:13:27'),
(50, 'footer_tagline', '\"Where flowers tell stories\"', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(51, 'footer_location', 'Handcrafted in Dodoma, Tanzania', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(52, 'testimonial_1_text', 'The bouquet arrived looking like it had been photographed for a magazine. My mother cried happy tears.', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(53, 'testimonial_1_name', 'Amina T.', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(54, 'testimonial_1_location', 'Kigoma', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:25:20'),
(55, 'testimonial_2_text', 'Ordered the Golden Hour gift set for my anniversary. Vee went above and beyond.', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(56, 'testimonial_2_name', 'David M.', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(57, 'testimonial_2_location', 'Dodoma', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(58, 'testimonial_3_text', 'Used Wrapped by Vee for our corporate event centrepieces. Professional and breathtaking.', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(59, 'testimonial_3_name', 'Rehema K.', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:23:29'),
(60, 'testimonial_3_location', 'Dar es Salaam', 'homepage', '2026-06-21 09:23:29', '2026-06-21 09:25:20');

-- --------------------------------------------------------

--
-- Table structure for table `store_settings`
--

CREATE TABLE `store_settings` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) DEFAULT 'Wrapped by Vee',
  `store_phone` varchar(50) DEFAULT NULL,
  `store_address` text DEFAULT NULL,
  `store_whatsapp` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `store_settings`
--

INSERT INTO `store_settings` (`id`, `store_name`, `store_phone`, `store_address`, `store_whatsapp`, `created_at`, `updated_at`) VALUES
(1, 'Wrapped by Vee', '+255 712 345 678', 'Dodoma, Tanzania', '+255 712 345 678', '2026-06-20 10:32:47', '2026-06-20 10:32:47');

-- --------------------------------------------------------

--
-- Table structure for table `website_content`
--

CREATE TABLE `website_content` (
  `id` int(11) NOT NULL,
  `page` varchar(100) NOT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `branding_settings`
--
ALTER TABLE `branding_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_settings`
--
ALTER TABLE `delivery_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_change_logs`
--
ALTER TABLE `email_change_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homepage_settings`
--
ALTER TABLE `homepage_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `localization_settings`
--
ALTER TABLE `localization_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `occasion_types`
--
ALTER TABLE `occasion_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_instructions`
--
ALTER TABLE `payment_instructions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_method` (`payment_method`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `store_settings`
--
ALTER TABLE `store_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `website_content`
--
ALTER TABLE `website_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `page` (`page`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branding_settings`
--
ALTER TABLE `branding_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_settings`
--
ALTER TABLE `business_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_settings`
--
ALTER TABLE `delivery_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `email_change_logs`
--
ALTER TABLE `email_change_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `homepage_settings`
--
ALTER TABLE `homepage_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_categories`
--
ALTER TABLE `inventory_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `localization_settings`
--
ALTER TABLE `localization_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `occasion_types`
--
ALTER TABLE `occasion_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_instructions`
--
ALTER TABLE `payment_instructions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tags`
--
ALTER TABLE `product_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `store_settings`
--
ALTER TABLE `store_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `website_content`
--
ALTER TABLE `website_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_change_logs`
--
ALTER TABLE `email_change_logs`
  ADD CONSTRAINT `email_change_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD CONSTRAINT `inventory_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `inventory_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_subcategories`
--
ALTER TABLE `product_subcategories`
  ADD CONSTRAINT `product_subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `product_categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
