-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2023 at 05:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `house`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Single-Family Apartman'),
(2, 'Family Apartman'),
(3, 'Multi-Family Apartman');

-- --------------------------------------------------------

--
-- Table structure for table `costs`
--

CREATE TABLE `costs` (
  `id` int(11) NOT NULL,
  `gas` int(10) NOT NULL,
  `electricity` int(10) NOT NULL,
  `water` int(10) NOT NULL,
  `parent` int(10) NOT NULL,
  `other` int(10) NOT NULL,
  `total_amount` int(10) NOT NULL,
  `rony_part` int(10) DEFAULT NULL,
  `description` text NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `costs`
--

INSERT INTO `costs` (`id`, `gas`, `electricity`, `water`, `parent`, `other`, `total_amount`, `rony_part`, `description`, `status`, `created`) VALUES
(9, 45, 45, 45, 458, 54, 647, 6344, '', 1, '2023-10-04');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(30) NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `category_id` int(30) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_no`, `category_id`, `description`) VALUES
(1, '101', 3, ' oiouio'),
(2, '201', 1, ' rasel'),
(3, '202', 3, ' dfhf'),
(4, '204', 1, ' fdgdfg'),
(5, '403', 1, ' fgff'),
(6, '404', 3, ' dffddf'),
(7, '501', 1, ' ggdf'),
(8, '502', 3, ' fgfgf'),
(9, '503', 3, ' fgfgfg'),
(10, '504', 1, ' fggf'),
(11, '203', 3, ' Multi Family'),
(13, '601', 3, ' multi family'),
(14, '102', 3, ' gds'),
(15, '103', 3, ' sfsd'),
(16, '301', 2, '  dfgdf'),
(17, '104', 2, ' '),
(18, '303', 2, ' '),
(19, '304', 2, ' '),
(20, '401', 2, ' ');

-- --------------------------------------------------------

--
-- Table structure for table `house_cost`
--

CREATE TABLE `house_cost` (
  `id` int(10) NOT NULL,
  `electricity` int(10) DEFAULT NULL,
  `gas` int(10) DEFAULT NULL,
  `water` int(10) NOT NULL,
  `parent` int(10) DEFAULT NULL,
  `other` int(10) DEFAULT NULL,
  `rony` int(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `house_cost`
--

INSERT INTO `house_cost` (`id`, `electricity`, `gas`, `water`, `parent`, `other`, `rony`, `description`, `created`) VALUES
(1, 2545, 454, 4564, 4545, 4554, NULL, NULL, '2023-05-02 21:32:12');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(10) NOT NULL,
  `slip_id` int(10) NOT NULL,
  `amount` float NOT NULL,
  `activet` int(11) DEFAULT NULL,
  `invoice` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `slip_id`, `amount`, `activet`, `invoice`, `date_created`) VALUES
(32, 1, 20, 13334, 1, '5611-1005', '2023-10-05 23:28:04');

-- --------------------------------------------------------

--
-- Table structure for table `rony_houses`
--

CREATE TABLE `rony_houses` (
  `id` int(11) NOT NULL,
  `rapartmant` varchar(255) DEFAULT NULL,
  `house_rent` int(10) DEFAULT NULL,
  `electricity` varchar(255) NOT NULL,
  `gass` varchar(255) NOT NULL,
  `water` varchar(200) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `total_cost` varchar(255) DEFAULT NULL,
  `rony_part` varchar(255) DEFAULT NULL,
  `rest_amount` varchar(255) DEFAULT NULL,
  `status` tinyint(2) NOT NULL DEFAULT 1,
  `created` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `rony_houses`
--

INSERT INTO `rony_houses` (`id`, `rapartmant`, `house_rent`, `electricity`, `gass`, `water`, `other`, `description`, `total_cost`, `rony_part`, `rest_amount`, `status`, `created`) VALUES
(4, '301', 0, '12', '12', '12', '12', '', '48', '0', '-48', 1, '2023-09-24'),
(6, '301+302', 12, '123', '12', '12', '100', 'Ramark', '247', '6344', '6109', 1, '2023-10-06');

-- --------------------------------------------------------

--
-- Table structure for table `slipes`
--

CREATE TABLE `slipes` (
  `id` int(11) NOT NULL,
  `tenant_id` int(10) DEFAULT NULL,
  `first_unit` varchar(100) NOT NULL,
  `last_unit` varchar(100) NOT NULL,
  `total_unit` int(10) DEFAULT NULL,
  `house_rent` varchar(100) NOT NULL,
  `invoice` varchar(200) DEFAULT NULL,
  `gas` int(10) DEFAULT NULL,
  `water` int(10) DEFAULT NULL,
  `dast` int(10) DEFAULT NULL,
  `advance` int(10) DEFAULT 0,
  `house_id` int(10) NOT NULL,
  `total_bill` int(10) DEFAULT NULL,
  `due_bill` int(10) DEFAULT 0,
  `status` tinyint(2) NOT NULL DEFAULT 1,
  `date_in` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slipes`
--

INSERT INTO `slipes` (`id`, `tenant_id`, `first_unit`, `last_unit`, `total_unit`, `house_rent`, `invoice`, `gas`, `water`, `dast`, `advance`, `house_id`, `total_bill`, `due_bill`, `status`, `date_in`) VALUES
(20, 1, '', '', 188, '10000', '1510-1003', 1080, 800, 100, 0, 1, 13334, 0, 0, '2023-10-03'),
(24, 12, '', '', 0, '7500', '3510-1003', 1080, 800, 100, 0, 12, 9480, 0, 1, '2023-10-03'),
(25, 16, '', '', 34, '0', '2310-1003', 1080, 800, 100, 0, 16, 2225, 0, 1, '2023-10-02'),
(26, 2, '', '', 117, '8125', '4710-1003', 1080, 800, 100, 0, 2, 10947, 0, 1, '2023-10-03'),
(27, 3, '', '', 200, '10000', '5910-1003', 1080, 800, 100, 0, 3, 13420, 0, 1, '2023-10-03'),
(28, 14, '', '', 175, '0', '2910-1003', 1080, 800, 100, 0, 14, 3240, 0, 1, '2023-10-03'),
(29, 15, '', '', 44, '8020', '5610-1003', 1080, 800, 100, 0, 15, 10317, 0, 1, '2023-10-03'),
(30, 5, '', '', 152, '9500', '0810-1003', 1080, 800, 100, 0, 5, 12574, 0, 1, '2023-10-02'),
(31, 6, '', '', 118, '6000', '2410-1003', 1080, 800, 100, 0, 6, 8830, 0, 1, '2023-10-03'),
(32, 7, '', '', 48, '8020', '3710-1003', 1080, 800, 100, 0, 7, 10346, 0, 1, '2023-10-03'),
(33, 8, '', '', 79, '9700', '5210-1003', 1080, 800, 100, 0, 8, 12249, 0, 1, '2023-10-03'),
(34, 9, '', '', 107, '9000', '0410-1003', 1080, 800, 100, 0, 9, 11750, 0, 1, '2023-10-03'),
(35, 10, '', '', 155, '8020', '1610-1003', 1080, 800, 100, 0, 10, 11116, 0, 1, '2023-10-01'),
(36, 18, '', '', 227, '10125', '3010-1003', 1080, 800, 100, 0, 18, 13739, 0, 1, '2023-10-03'),
(45, 44, '700', '300', -400, '10000', '5311-1004', 1080, 800, 100, 0, 44, 11980, 0, 1, '2023-10-04'),
(48, 13, '', '', 491, '9000', '2509-1005', 1080, 800, 100, 0, 13, 14515, 0, 1, '2023-10-04'),
(49, 4, '', '', 312, '14000', '5309-1005', 1080, 800, 100, 0, 4, 18226, 0, 1, '2023-10-05'),
(50, 11, '', '', 244, '0', '1909-1005', 1080, 800, 100, 0, 11, 3737, 0, 1, '2023-10-05'),
(51, 17, '', '', 260, '0', '4109-1005', 1080, 800, 100, 0, 17, 3852, 0, 1, '2023-10-04');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'House Rental Management System', 'info@sample.comm', '+123456789', '1611725220_tvs.jpg', '&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-weight: 400; text-align: justify;&quot;&gt;&amp;nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&rsquo;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `rent` int(10) DEFAULT NULL,
  `fmember` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `house_id` int(30) NOT NULL,
  `house_no` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0= inactive',
  `date_in` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `fullname`, `nid`, `rent`, `fmember`, `contact`, `email`, `house_id`, `house_no`, `status`, `date_in`) VALUES
(1, 'Md Liton', '6460674192', 10000, '5', '01750603435', '', 1, 101, 1, '2021-01-01'),
(2, 'Md Faruk Ahmed', '7777187894', 8125, '3', '01750340365', '', 2, 201, 1, '2021-01-01'),
(3, 'Md Golap Hossain', '2696402432185', 10000, '4', '01913113760', '', 3, 202, 1, '2021-01-01'),
(4, 'MD Satter ALi', '2842550010', 14000, '3', '01726027333', '', 4, 204, 1, '2021-01-02'),
(5, 'Md Al-amin Hossain', '7769948014', 9500, '5', '01785906599', '', 5, 403, 1, '2021-01-01'),
(6, 'Md Sohel', '5076869535', 6000, '5', '01731198954', '', 6, 404, 1, '2021-01-01'),
(7, 'Md Hossain', '2851255487', 8020, '4', '010000000', '', 7, 501, 1, '2021-01-01'),
(8, 'MD 502', '4203095908', 9700, '2', '01788884945', '', 8, 502, 1, '2021-01-01'),
(9, 'Md Saiful Islam', '19948113123000103', 9000, '5', '01793831271', '', 9, 503, 1, '2021-01-01'),
(10, 'Monir Hossain', '2696406740047', 8020, '5', '01712180340', '', 10, 504, 1, '2021-01-01'),
(11, 'rony', '4564654', 0, '5', '015488974', '', 16, 302, 1, '2021-02-01'),
(12, 'MD Zakir Hossain', '1001187424', 7500, '5', '01886600063', '', 14, 102, 1, '2023-08-01'),
(13, 'MD Zakir Hossain', '1001187424', 9000, '5', '01886600063', '', 15, 103, 1, '0000-00-00'),
(14, 'MD Rashedul Kairm', '2693014941856', 0, '4', '01818401065', '', 18, 303, 1, '2023-08-04'),
(15, 'MD Sultan Shek', '19705418076000013', 8020, '5', '01955469968', '', 19, 304, 1, '2023-08-02'),
(16, 'Kamalm', '100000000', 0, '1', '010000000', '', 17, 104, 1, '2023-08-03'),
(17, 'Rupon', '100000000', 0, '5', '010000000', '', 20, 401, 1, '2023-08-03'),
(18, 'MD Shahin Garami', '19940616923000099', 10125, '5', '01704744473', '', 13, 601, 1, '2023-08-03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=Admin,2=Staff',
  `establishment_id` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `type`, `establishment_id`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', 1, ''),
(12, 'karim', 'rashed', '679a174817090a90b9906e88cd789ba6', 1, '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `costs`
--
ALTER TABLE `costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `house_no` (`house_no`);

--
-- Indexes for table `house_cost`
--
ALTER TABLE `house_cost`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rony_houses`
--
ALTER TABLE `rony_houses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slipes`
--
ALTER TABLE `slipes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `costs`
--
ALTER TABLE `costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `house_cost`
--
ALTER TABLE `house_cost`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `rony_houses`
--
ALTER TABLE `rony_houses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `slipes`
--
ALTER TABLE `slipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
