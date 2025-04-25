-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 24, 2025 at 07:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


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
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `connection_id`, `amount`, `state`, `due_date`, `payment_id`) VALUES
('bi25042423483900127', 'co25042423273600e34', 1000, 'Paid', '2025-04-29', 'py25042500251800990'),
('bi25042423484100526', 'co250424232938007a8', 1200, 'Paid', '2025-04-29', 'py25042500255200fff'),
('bi250424234842008e8', 'co25042423304900df3', 3000, 'Unpaid', '2025-04-29', NULL),
('bi25042423484200f0c', 'co2504242333330009f', 2000, 'Unpaid', '2025-04-29', NULL),
('bi25042423484300faa', 'co25042423350800007', 5500, 'Unpaid', '2025-04-29', NULL),
('bi2504242348440046e', 'co2504242335500085d', 1200, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi25042423491200694', 'co25042423285600ee3', 2000, 'Unpaid', '2025-04-29', NULL),
('bi250424234913001ce', 'co25042423302600250', 2000, 'Unpaid', '2025-04-29', NULL),
('bi2504242349140091f', 'co250424233126007cc', 5500, 'Unpaid', '2025-04-29', NULL),
('bi25042423491400afb', 'co2504242334540027c', 5500, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi250424234915005af', 'co25042423352700b3f', 1000, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi25042423491600947', 'co250424233607002f9', 2000, 'Paid', '2025-04-29', 'py25042500194300206'),
('bi25042500005900e4f', 'co2504242345030025a', 1200, 'Paid', '2025-04-30', 'py25042500182300f42');

--
-- Triggers `bill`
--
DELIMITER $$
CREATE TRIGGER `before_insert_payment` BEFORE INSERT ON `bill` FOR EACH ROW BEGIN
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
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`id`, `title`, `type`, `state`, `complaining_date`, `customer_id`, `details`, `comments`) VALUES
('com25042500235400923', 'Low speed', 'Speed', 'Pending', '2025-04-25 00:23:54', 'cu2504140208430063b', 'Low speed in my home', NULL);

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
-- Dumping data for table `connections`
--

INSERT INTO `connections` (`id`, `name`, `address`, `type`, `customer_id`, `plan_id`, `state`, `starting_date`, `submission_date`, `req_plan`) VALUES
('co25042423273600e34', 'My home', 'Dhaka, Bangladesh', 'residential_plans', 'cu25041402151500309', 'pl2504141448140029b', 'Active', '2025-05-01', NULL, NULL),
('co2504242328140041d', '2nd Home', 'Dhaka, Bangladash', 'residential_plans', 'cu25041402151500309', 'pl25041414463200f6f', 'Pending', '2025-05-02', NULL, NULL),
('co250424232938007a8', 'Hostel Connection', 'Dhaka, Bangladash', 'residential_plans', 'cu25041402151500309', 'pl2504141448140029b', 'Disconnection in process', '2025-04-21', NULL, NULL),
('co25042423302600250', 'My startup', 'Dhaka, Bangladash', 'organizational_plans', 'cu25041402151500309', 'pl25041414513300316', 'Active', '2025-05-01', NULL, NULL),
('co25042423304900df3', 'Parents Office', 'Dhaka, Bangladash', 'organizational_plans', 'cu25041402151500309', 'pl25041414525000129', 'Disconnection pending', '2025-05-03', NULL, NULL),
('co250424233126007cc', 'My office', 'Dhaka, Bangladesh', 'organizational_plans', 'cu25041402151500309', 'pl2504141450000053c', 'Disconnection in process', '2025-05-05', NULL, NULL),
('co2504242333330009f', 'My new beginning', 'Dhaka, Bangladesh', 'organizational_plans', 'cu2504140208430063b', 'pl25041414513300316', 'Active', '2025-04-19', NULL, NULL),
('co250424233403009e7', '2nd Biasness ', 'Dhaka, Bangladesh', 'organizational_plans', 'cu2504140208430063b', 'pl25041414513300316', 'Pending', '2025-05-01', NULL, NULL),
('co2504242334540027c', 'Office 1', 'Dhaka, Bangladesh', 'organizational_plans', 'cu2504140208430063b', 'pl25041414513300316', 'Active', '2025-05-02', NULL, NULL),
('co25042423352700b3f', 'Sweet Home', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414453500b5b', 'Update in process', '2025-05-01', NULL, 'pl2504141448140029b'),
('co2504242335500085d', 'My hostel', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414463200f6f', 'Update pending', '2025-04-20', NULL, 'pl2504141448140029b'),
('co250424233607002f9', 'Go life go', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl2504141448140029b', 'Update pending', '2025-05-05', NULL, 'pl25041414453500b5b'),
('co250424234302004a8', '@nd home', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl2504141448140029b', 'Connection in process', '2025-05-06', NULL, NULL),
('co25042423434400cd7', '@Future plan', 'Dhaka, Bangladesh', 'organizational_plans', 'cu25041402151500309', 'pl25041414513300316', 'Connection in process', '2025-05-29', NULL, NULL),
('co2504242345030025a', '@ Future home', 'Dhaka, Bangladesh', 'residential_plans', 'cu2504140208430063b', 'pl25041414463200f6f', 'Disconnection pending', '2025-06-30', NULL, NULL);

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
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `name`, `email`, `phone`, `address`, `password`, `nid`, `gender`, `photo`) VALUES
('cu2504140208430063b', 'Kobir Khan', 'customer1@fisp.com', '01693674298', 'Dhaka, Bangladesh', 'c1', '8653567689', 'male', 'files/customer/profile_pic_file/8653567689_photo.jpg'),
('cu25041402151500309', 'Hanbe Errcel', 'customer2@fisp.com', '01575398478', 'Dhaka, Bangladesh', 'c2', '7568236769', 'male', 'files/customer/profile_pic_file/7568236769_photo.jpg');

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
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `name`, `post`, `phone`, `email`, `password`, `address`, `gender`, `salary`, `nid`, `nid_file`, `certificate_file`, `resume_file`, `photo_file`, `is_admin`, `is_sup_admin`) VALUES
('em25041400001500724', 'Abdullah Md Jahid Hassan', 'Admin', 17654536373, 'abdullahmdjahidhassan@gmail.com', 'F.s.56564545', 'Uttara, Dhaka, Bangladesh', 'Male', 15000, 1234567890, 'files/employee/nid_file/em25041400001500724_nid.pdf', 'files/employee/certificate_file/em25041400001500724_certificate.pdf', 'files/employee/resume_file/em25041400001500724_resume.pdf', 'files/employee/profile_pic_file/em25041400001500724_photo.jpg', 1, 1),
('em25041400064300dc2', 'Rato Talukdar', 'Line Man', 1564346536, 'employee4@fisp.com', 'e4', 'Dhaka, Bangladesh', 'male', 25000, 5363927493, 'files/employee/nid_file/em25041400064300dc2_nid.pdf', 'files/employee/certificate_file/em25041400064300dc2_certificate.pdf', 'files/employee/resume_file/em25041400064300dc2_resume.pdf', 'files/employee/profile_pic_file/em25041400064300dc2_photo.jpg', 0, 0),
('em25041400095800752', 'Rased Alom', 'Sarver Oparator', 1646863674, 'employee3@fisp.com', 'e3', 'Dhaka, Bangladash', 'male', 40000, 2567354677, 'files/employee/nid_file/em25041400095800752_nid.pdf', 'files/employee/certificate_file/em25041400095800752_certificate.pdf', 'files/employee/resume_file/em25041400095800752_resume.pdf', 'files/employee/profile_pic_file/em25041400095800752_photo.jpg', 0, 0),
('em25041400140500f5d', 'Kobir Khan', 'Manager', 1357546842, 'admin@fisp.com', 'a', 'Dhaka, Bangladash', 'male', 80000, 6541956837, 'files/employee/nid_file/em25041400140500f5d_nid.pdf', 'files/employee/certificate_file/em25041400140500f5d_certificate.pdf', 'files/employee/resume_file/em25041400140500f5d_resume.pdf', 'files/employee/profile_pic_file/em25041400140500f5d_photo.jpg', 1, 0);

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

--
-- Dumping data for table `footer_data`
--

INSERT INTO `footer_data` (`id`, `address_text`, `address_link`, `phone_text`, `phone_link`, `mail_text`, `mail_link`, `fb_link`, `ms_link`, `wh_link`, `in_link`, `yt_link`) VALUES
(1, 'Uttara, Dhaka, Bangladesh.', 'https://maps.app.goo.gl/omjEwXmMNdb8XheK6', '+8801756-254873', 'tel:+8801756-254873', 'abdullahmdjahidhassan@gmail.com', 'mailto:abdullahmdjahidhassan@gmail.com', 'https://www.facebook.com/AbdullahMdJahidHassan', 'https://m.me/AbdullahMdJahidHassan', 'https://wa.me/qr/SCY3CD2PK635G1', 'https://www.instagram.com/abdullah_md_jahid_hassan', 'https://www.youtube.com/@azoobgamers');

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
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `customer_id`, `amount`, `state`, `tran_id`, `currency`, `pay_date`, `val_id`, `card_type`, `store_amount`, `bank_tran_id`, `tran_status`, `card_issuer`, `card_brand`, `card_sub_brand`, `card_issuer_country`, `card_issuer_country_code`, `store_id`, `verify_sign`, `verify_sign_sha2`, `risk_level`, `risk_title`) VALUES
('py25042500182300f42', 'cu2504140208430063b', 1200, 'Paid', 'py25042500182300f42680a806ff000d', 'BDT', '2025-04-25 00:18:24', '25042501845s8Mf8jTFym2XkfK', 'BKASH-BKash', 1170.00, '25042501845wbFQheIVoRpiX6U', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', 'd296f3ae35814971f198b50a8a7b7eb3', '58363d7f4717d941b4051e301e40d4f28d3d285441b398455bb54f10f8c61b84', 0, 'Safe'),
('py25042500194300206', 'cu2504140208430063b', 9700, 'Paid', 'py25042500194300206680a80bf6c547', 'BDT', '2025-04-25 00:19:43', '250425020010WBp2Qa9IRRw2QX', 'BKASH-BKash', 9457.50, '250425020018HDftodo2Mbscm6', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', 'f54f5470ec80ab88142d3c0615adfb31', '8a76e5da4b51cdb6ff288dcc6289c0565eb4417a030c189bf24c17cb3424ffcb', 0, 'Safe'),
('py25042500251800990', 'cu25041402151500309', 1000, 'Paid', 'py25042500251800990680a820e66f1b', 'BDT', '2025-04-25 00:25:21', '250425025330BW4CZUZcd99ato', 'BKASH-BKash', 975.00, '2504250253305uubJx5svNjLcs', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '0c82f5a4ffdf8cb6c9976fcc38ba2876', 'a7a4a948d672d2cd2cf0ef52029ab47d6ac8f92b25d94bd426e4d8494540f2b7', 0, 'Safe'),
('py25042500255200fff', 'cu25041402151500309', 1200, 'Paid', 'py25042500255200fff680a82304c95f', 'BDT', '2025-04-25 00:25:56', '250425026080hNNSSu5BwR3GVC', 'BKASH-BKash', 1170.00, '25042502608bLGPXDRzBm4kTPC', 'VALID', 'BKash Mobile Banking', 'MOBILEBANKING', 'Classic', 'Bangladesh', 'BD', 'amjhl67e02337e567a', '9c8bd33873e9336dde7c524fe772982b', '33e2e48a8bf6486c65e4e5ef59302dce866135d26363e6f60c1dad87f88ec7cd', 0, 'Safe');

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
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `type`, `name`, `speed`, `realip`, `price`) VALUES
('pl25041414453500b5b', 'residential_plans', 'A good start 🏠', 5, 'No', 500),
('pl25041414463200f6f', 'residential_plans', 'Hello Speed 🏠⚡', 10, 'No', 700),
('pl2504141448140029b', 'residential_plans', 'Speedy Life 🏃‍♂️‍➡️', 20, 'Yes', 1500),
('pl2504141450000053c', 'organizational_plans', 'Bootstrap ⭐', 20, 'No', 1500),
('pl25041414513300316', 'organizational_plans', 'Grow With Speed ⚡', 30, 'Yes', 2500),
('pl25041414525000129', 'organizational_plans', 'Sky is the Limit 🪂', 50, 'Yes', 5000);

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
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `date`, `residential_plan`, `organizational_plan`) VALUES
(1, '2024-04-01', 2000, 2500),
(2, '2024-05-01', 3000, 2500),
(3, '2024-06-01', 4500, 2500),
(4, '2024-07-01', 5500, 6000),
(5, '2024-08-01', 7500, 9000),
(6, '2024-09-01', 10000, 17500),
(7, '2024-10-01', 8000, 5500),
(8, '2024-11-01', 12000, 8000),
(9, '2024-12-01', 20000, 15000),
(10, '2025-01-01', 25000, 27000),
(11, '2025-02-01', 30000, 42567),
(12, '2025-03-31', 45870, 56275);

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
  `task_ref` varchar(50) DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `name`, `address`, `start`, `end`, `state`, `employee_id`, `details`, `task_ref`, `completed_at`) VALUES
('ta25042423383800af3', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-01', 'Completed', 'em25041400095800752', 'cc', 'co25042423273600e34', '2025-04-24 23:48:39'),
('ta25042423385500383', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-20', 'Late', 'em25041400064300dc2', 'cc', 'co25042423285600ee3', '2025-04-24 23:49:12'),
('ta250424233925000cd', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-21', 'Late', 'em25041400095800752', 'cc', 'co250424232938007a8', '2025-04-24 23:48:41'),
('ta25042423393400a65', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-01', 'Completed', 'em25041400064300dc2', 'cc', 'co25042423302600250', '2025-04-24 23:49:13'),
('ta25042423400300d73', 'New connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-03', 'Completed', 'em25041400095800752', 'cc', 'co25042423304900df3', '2025-04-24 23:48:42'),
('ta2504242340170018c', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-05', 'Completed', 'em25041400064300dc2', 'cc', 'co250424233126007cc', '2025-04-24 23:49:14'),
('ta25042423403500b6d', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-19', 'Late', 'em25041400095800752', 'cc', 'co2504242333330009f', '2025-04-24 23:48:42'),
('ta25042423404800b3b', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-02', 'Completed', 'em25041400064300dc2', 'cc', 'co2504242334540027c', '2025-04-24 23:49:14'),
('ta2504242341000050b', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-07', 'Completed', 'em25041400095800752', 'cc', 'co25042423350800007', '2025-04-24 23:48:43'),
('ta25042423411800f74', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-01', 'Completed', 'em25041400064300dc2', 'cc', 'co25042423352700b3f', '2025-04-24 23:49:15'),
('ta25042423413100b05', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-20', 'Late', 'em25041400095800752', 'cc', 'co2504242335500085d', '2025-04-24 23:48:44'),
('ta25042423414300bb2', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-05', 'Completed', 'em25041400064300dc2', 'cc', 'co250424233607002f9', '2025-04-24 23:49:16'),
('ta25042423441200134', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-06', 'Pending', 'em25041400064300dc2', 'cc', 'co250424234302004a8', NULL),
('ta2504242344200072c', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-29', 'Pending', 'em25041400095800752', 'cc', 'co25042423434400cd7', NULL),
('ta25042423463500c7b', 'New connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-06-30', 'Completed', 'em25041400064300dc2', 'cc', 'co2504242345030025a', '2025-04-25 00:00:59'),
('ta25042423570900738', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-22', 'Late', 'em25041400064300dc2', 'uu', 'co25042423273600e34', '2025-04-25 00:01:00'),
('ta25042423572500821', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-02', 'Completed', 'em25041400095800752', 'uu', 'co25042423285600ee3', '2025-04-25 00:00:27'),
('ta25042423574400221', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-02', 'Completed', 'em25041400064300dc2', 'uu', 'co250424232938007a8', '2025-04-25 00:01:01'),
('ta25042423580500370', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-21', 'Late', 'em25041400095800752', 'uu', 'co25042423302600250', '2025-04-25 00:00:28'),
('ta25042423582100859', 'Update connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-19', 'Late', 'em25041400064300dc2', 'uu', 'co25042423304900df3', '2025-04-25 00:01:02'),
('ta250424235832006cb', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-02', 'Completed', 'em25041400095800752', 'uu', 'co250424233126007cc', '2025-04-25 00:00:28'),
('ta25042423584800fea', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-01', 'Completed', 'em25041400095800752', 'uu', 'co2504242333330009f', '2025-04-25 00:00:29'),
('ta25042423590400e4b', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-27', 'Completed', 'em25041400095800752', 'uu', 'co2504242334540027c', '2025-04-25 00:00:31'),
('ta25042423592000d0e', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-26', 'Pending', 'em25041400064300dc2', 'uu', 'co25042423350800007', NULL),
('ta250424235932002cb', 'Update connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-08', 'Pending', 'em25041400095800752', 'uu', 'co25042423352700b3f', NULL),
('ta25042500112300ae8', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-04-23', 'Late', 'em25041400095800752', 'dd', 'co25042423285600ee3', '2025-04-25 00:12:54'),
('ta25042500114700a3f', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-04-23', 'Late', 'em25041400064300dc2', 'dd', 'co25042423350800007', '2025-04-25 00:13:22'),
('ta25042500121700b4f', 'Disconnect connection in Dhaka, Bangladash', 'Dhaka, Bangladash', '2025-04-24', '2025-05-02', 'Pending', 'em25041400095800752', 'dd', 'co250424232938007a8', NULL),
('ta25042500123600d53', 'Disconnect connection in Dhaka, Bangladesh', 'Dhaka, Bangladesh', '2025-04-24', '2025-05-03', 'Pending', 'em25041400064300dc2', 'dd', 'co250424233126007cc', NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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

CREATE DEFINER=`root`@`localhost` EVENT `late_monthly_bills` ON SCHEDULE EVERY 1 MONTH STARTS '2024-06-11 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE `payment` 
SET 
    `state` = 'Late',
    `amount` = `amount` * 1.10
WHERE `state` = 'Unpaid'$$

CREATE DEFINER=`root`@`localhost` EVENT `generate_monthly_revenue` ON SCHEDULE EVERY 1 MONTH STARTS '2024-06-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO -- Insert revenue for the previous month
INSERT INTO revenue (date, organizational_plan, residential_plan)
SELECT 
    -- Use the first day of the previous month as the record date
    DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), '%Y-%m-01') AS revenue_date,

    -- Sum of organizational payments
    COALESCE(SUM(CASE 
        WHEN connections.type = 'organizational_plans' THEN payment.amount 
        ELSE 0 
    END), 0) AS organizational_plan,

    -- Sum of residential payments
    COALESCE(SUM(CASE 
        WHEN connections.type = 'residential_plans' THEN payment.amount 
        ELSE 0 
    END), 0) AS residential_plan

FROM payment
JOIN connections ON payment.connection_id = connections.id
WHERE payment.state = 'Paid'
  AND YEAR(payment.pay_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))
  AND MONTH(payment.pay_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
