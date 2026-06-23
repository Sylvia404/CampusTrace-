-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2026 at 10:38 AM
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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
