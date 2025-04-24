-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 13, 2025 at 11:02 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+06:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `isp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `id` varchar(50) NOT NULL,
  `connection_id` varchar(50) DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `state` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `due_date` date DEFAULT NULL,
  `payment_id` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `bill`
--
DELIMITER $$
CREATE TRIGGER `before_insert_bill` BEFORE INSERT ON `bill` FOR EACH ROW BEGIN
    -- Generate unique payment ID
    SET NEW.id = CONCAT(
        'bi', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );

    -- Set due_date to 5 days after the insertion date
    SET NEW.due_date = DATE_ADD(NOW(), INTERVAL 5 DAY);

    -- Set state to Unpaid
    SET NEW.state = 'Unpaid';
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `id` varchar(50) NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `complaining_date` datetime DEFAULT NULL,
  `customer_id` varchar(50) DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `comments` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `complaint`
--
DELIMITER $$
CREATE TRIGGER `before_insert_complaint` BEFORE INSERT ON `complaint` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'com', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `connections`
--

CREATE TABLE `connections` (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `customer_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `plan_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `state` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `starting_date` date NOT NULL,
  `submission_date` date DEFAULT NULL,
  `req_plan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `connections`
--
DELIMITER $$
CREATE TRIGGER `before_insert_connections` BEFORE INSERT ON `connections` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'co', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nid` varchar(15) DEFAULT NULL,
  `gender` text NOT NULL,
  `photo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `customer`
--
DELIMITER $$
CREATE TRIGGER `before_insert_customer` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'cu', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `post` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` bigint DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `gender` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `salary` int DEFAULT NULL,
  `nid` bigint DEFAULT NULL,
  `nid_file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `certificate_file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `resume_file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `photo_file` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_sup_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `employee`
--
DELIMITER $$
CREATE TRIGGER `before_insert_employee` BEFORE INSERT ON `employee` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'em', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `footer_data`
--

CREATE TABLE `footer_data` (
  `id` int NOT NULL,
  `address_text` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone_text` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mail_text` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mail_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fb_link` varchar(255) DEFAULT NULL,
  `ms_link` varchar(255) DEFAULT NULL,
  `wh_link` varchar(255) DEFAULT NULL,
  `in_link` varchar(255) DEFAULT NULL,
  `yt_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `customer_id` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `state` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tran_id` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'BDT',
  `pay_date` datetime DEFAULT NULL,
  `val_id` varchar(27) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_type` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `store_amount` decimal(10,2) DEFAULT NULL,
  `bank_tran_id` varchar(27) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `tran_status` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_issuer` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_brand` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_sub_brand` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_issuer_country` varchar(50) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `card_issuer_country_code` varchar(5) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `store_id` varchar(18) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `verify_sign` varchar(32) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `verify_sign_sha2` varchar(64) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `risk_level` int DEFAULT NULL,
  `risk_title` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Triggers `payments`
--
DELIMITER $$
CREATE TRIGGER `before_insert_payments` BEFORE INSERT ON `payments` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'py', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` text,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `speed` int DEFAULT NULL,
  `realip` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `price` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `plans`
--
DELIMITER $$
CREATE TRIGGER `before_insert_residential_plans` BEFORE INSERT ON `plans` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'pl', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int NOT NULL,
  `date` date NOT NULL,
  `residential_plan` int NOT NULL,
  `organizational_plan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `revenue`
--
DELIMITER $$
CREATE TRIGGER `before_insert_revenue` BEFORE INSERT ON `revenue` FOR EACH ROW BEGIN
    SET NEW.date = LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 1 MONTH));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` varchar(50) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `state` text,
  `employee_id` varchar(50) DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `task_ref` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Triggers `task`
--
DELIMITER $$
CREATE TRIGGER `before_insert_task` BEFORE INSERT ON `task` FOR EACH ROW BEGIN
    SET NEW.id = CONCAT(
        'ta', 
        DATE_FORMAT(NOW(), '%y%m%d%H%i%s'), 
        LPAD(MICROSECOND(NOW()) DIV 1000, 2, '0'),
        SUBSTRING(MD5(RAND()), 1, 3)
    );
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connections`
--
ALTER TABLE `connections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `footer_data`
--
ALTER TABLE `footer_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `footer_data`
--
ALTER TABLE `footer_data`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `generate_monthly_bills` ON SCHEDULE EVERY 1 MONTH STARTS '2024-06-05 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO INSERT INTO `payment` (`connection_id`, `amount`, `state`)
SELECT 
    c.id AS connection_id,
    p.price AS amount,
    'Unpaid' AS state
FROM `connections` c
JOIN `plans` p ON c.plan_id = p.id
WHERE c.state != 'Pending'$$

CREATE DEFINER=`root`@`localhost` EVENT `generate_monthly_revenue` ON SCHEDULE EVERY 1 YEAR STARTS '2024-06-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    -- Insert revenue for the previous month
    INSERT INTO revenue (organizational_plan, residential_plan)
    SELECT 
        COALESCE(SUM(CASE 
            WHEN connections.type = 'organizational_plans' THEN payment.amount 
            ELSE 0 
        END), 0) AS organizational_plan,
        COALESCE(SUM(CASE 
            WHEN connections.type = 'residential_plans' THEN payment.amount 
            ELSE 0 
        END), 0) AS residential_plan
    FROM payment
    JOIN connections ON payment.connection_id = connections.id
    WHERE payment.state = 'Paid'
    AND YEAR(payment.pay_date) = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH))
    AND MONTH(payment.pay_date) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH));

END$$

CREATE DEFINER=`root`@`localhost` EVENT `late_monthly_bills` ON SCHEDULE EVERY 1 MONTH STARTS '2024-06-11 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `payment` 
SET 
    `state` = 'Late',
    `amount` = `amount` * 1.10
WHERE `state` = 'Unpaid'$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
