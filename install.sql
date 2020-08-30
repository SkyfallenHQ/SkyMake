-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 30, 2020 at 05:38 PM
-- Server version: 5.7.31-0ubuntu0.18.04.1
-- PHP Version: 7.2.24-0ubuntu0.18.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `theskyfallen_skymake-preproduction`
--

-- --------------------------------------------------------

--
-- Table structure for table `skymake_answer`
--

CREATE TABLE `skymake_answer` (
  `id` int(11) NOT NULL,
  `uniq` varchar(255) NOT NULL,
  `qn` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `examid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_assignments`
--

CREATE TABLE `skymake_assignments` (
  `uniqueline` int(11) NOT NULL,
  `lessonid` varchar(255) NOT NULL,
  `lesson` varchar(255) NOT NULL,
  `teacher` varchar(255) NOT NULL,
  `teacheruser` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `unit` varchar(255) NOT NULL,
  `bgurl` varchar(255) NOT NULL,
  `classid` varchar(255) NOT NULL,
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_classes`
--

CREATE TABLE `skymake_classes` (
  `classid` int(11) NOT NULL,
  `classname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_class_assigned`
--

CREATE TABLE `skymake_class_assigned` (
  `username` varchar(255) NOT NULL,
  `classid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_examdata`
--

CREATE TABLE `skymake_examdata` (
  `examid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_start` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_end` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_qcount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exam_creator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_lctokens`
--

CREATE TABLE `skymake_lctokens` (
  `classid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contentid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uniqueline` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_lessoncontent`
--

CREATE TABLE `skymake_lessoncontent` (
  `uniqueline` int(11) NOT NULL,
  `lessonid` varchar(255) NOT NULL,
  `content-type` varchar(255) NOT NULL,
  `content-id` varchar(255) NOT NULL,
  `content-link` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_operationvalues`
--

CREATE TABLE `skymake_operationvalues` (
  `setting` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_profile`
--

CREATE TABLE `skymake_profile` (
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profilephoto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `biotext` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth` date NOT NULL,
  `school` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_qanswers`
--

CREATE TABLE `skymake_qanswers` (
  `examid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qn` int(11) NOT NULL,
  `answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picurl` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_result`
--

CREATE TABLE `skymake_result` (
  `autoincrement` int(11) NOT NULL,
  `un` varchar(255) NOT NULL,
  `p` varchar(255) NOT NULL,
  `examid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_roles`
--

CREATE TABLE `skymake_roles` (
  `username` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_users`
--

CREATE TABLE `skymake_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `skymake_useruploads`
--

CREATE TABLE `skymake_useruploads` (
  `uploadlink` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `skymake_answer`
--
ALTER TABLE `skymake_answer`
  ADD UNIQUE KEY `uniq` (`uniq`);

--
-- Indexes for table `skymake_assignments`
--
ALTER TABLE `skymake_assignments`
  ADD PRIMARY KEY (`uniqueline`);

--
-- Indexes for table `skymake_classes`
--
ALTER TABLE `skymake_classes`
  ADD PRIMARY KEY (`classid`),
  ADD UNIQUE KEY `classid` (`classid`);

--
-- Indexes for table `skymake_class_assigned`
--
ALTER TABLE `skymake_class_assigned`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `skymake_examdata`
--
ALTER TABLE `skymake_examdata`
  ADD UNIQUE KEY `examid` (`examid`);

--
-- Indexes for table `skymake_lctokens`
--
ALTER TABLE `skymake_lctokens`
  ADD UNIQUE KEY `uniqueline` (`uniqueline`);

--
-- Indexes for table `skymake_lessoncontent`
--
ALTER TABLE `skymake_lessoncontent`
  ADD PRIMARY KEY (`uniqueline`),
  ADD UNIQUE KEY `uniqueline` (`uniqueline`);

--
-- Indexes for table `skymake_operationvalues`
--
ALTER TABLE `skymake_operationvalues`
  ADD UNIQUE KEY `setting` (`setting`);

--
-- Indexes for table `skymake_profile`
--
ALTER TABLE `skymake_profile`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `skymake_qanswers`
--
ALTER TABLE `skymake_qanswers`
  ADD UNIQUE KEY `unique_index` (`examid`,`qn`);

--
-- Indexes for table `skymake_result`
--
ALTER TABLE `skymake_result`
  ADD UNIQUE KEY `autoincrement` (`autoincrement`);

--
-- Indexes for table `skymake_roles`
--
ALTER TABLE `skymake_roles`
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `skymake_users`
--
ALTER TABLE `skymake_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `skymake_useruploads`
--
ALTER TABLE `skymake_useruploads`
  ADD UNIQUE KEY `uploadlink` (`uploadlink`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `skymake_assignments`
--
ALTER TABLE `skymake_assignments`
  MODIFY `uniqueline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skymake_classes`
--
ALTER TABLE `skymake_classes`
  MODIFY `classid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `skymake_lctokens`
--
ALTER TABLE `skymake_lctokens`
  MODIFY `uniqueline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT for table `skymake_lessoncontent`
--
ALTER TABLE `skymake_lessoncontent`
  MODIFY `uniqueline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `skymake_result`
--
ALTER TABLE `skymake_result`
  MODIFY `autoincrement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skymake_users`
--
ALTER TABLE `skymake_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
