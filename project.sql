-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 12, 2023 at 06:59 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `arabic`
--

CREATE TABLE `arabic` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `code`
--

CREATE TABLE `code` (
  `id` int(22) NOT NULL,
  `referral` varchar(22) NOT NULL,
  `subject` varchar(22) NOT NULL,
  `grade` int(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `english`
--

CREATE TABLE `english` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kurdish`
--

CREATE TABLE `kurdish` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mark_table`
--

CREATE TABLE `mark_table` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `year_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL,
  `result` enum('Fail','Pass') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `math`
--

CREATE TABLE `math` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(22) NOT NULL,
  `user` varchar(255) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_seen` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `science`
--

CREATE TABLE `science` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sport`
--

CREATE TABLE `sport` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `sub_id` int(22) NOT NULL,
  `half_of_the_first_semester` double DEFAULT NULL,
  `end_of_the_first_semester` double DEFAULT NULL,
  `first_semester_result` double DEFAULT NULL,
  `half_of_the_second_semester` double DEFAULT NULL,
  `end_of_the_second_semester` double DEFAULT NULL,
  `second_semester_result` double DEFAULT NULL,
  `year_end_average` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `store_mark`
--

CREATE TABLE `store_mark` (
  `id` int(22) NOT NULL,
  `st_id` int(22) NOT NULL,
  `year_id` int(22) NOT NULL,
  `subject` varchar(22) NOT NULL,
  `half_of_the_first_semester` float DEFAULT NULL,
  `end_of_the_first_semester` float DEFAULT NULL,
  `first_semester_result` float DEFAULT NULL,
  `half_of_the_second_semester` float DEFAULT NULL,
  `end_of_the_second_semester` float DEFAULT NULL,
  `second_semester_result` float DEFAULT NULL,
  `year_end_average` float DEFAULT NULL,
  `result` enum('Fail','Pass') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `st_id` int(22) NOT NULL,
  `first_name` varchar(22) NOT NULL,
  `last_name` varchar(22) NOT NULL,
  `phone_email` varchar(255) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `gender` enum('Male','Female','Other','') NOT NULL,
  `date_and_birth` date NOT NULL,
  `address` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_table`
--

CREATE TABLE `subject_table` (
  `subject_id` int(22) NOT NULL,
  `sub_name` varchar(22) NOT NULL,
  `teacher_id` int(22) DEFAULT NULL,
  `year_id` int(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_table`
--

CREATE TABLE `teacher_table` (
  `teacher_id` int(22) NOT NULL,
  `teacher_name` varchar(22) NOT NULL,
  `phone_email` varchar(255) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('Male','Female','Other','') NOT NULL,
  `address` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_teacher`
--

CREATE TABLE `temp_teacher` (
  `id` int(22) NOT NULL,
  `sub_name` varchar(22) NOT NULL,
  `teacher_id` int(22) NOT NULL,
  `year_id` int(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `year_table`
--

CREATE TABLE `year_table` (
  `year_id` int(22) NOT NULL,
  `grade` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `arabic`
--
ALTER TABLE `arabic`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_3` (`st_id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`sub_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `arabic_ibfk_2` (`sub_id`);

--
-- Indexes for table `code`
--
ALTER TABLE `code`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referral` (`referral`),
  ADD UNIQUE KEY `subject` (`subject`,`grade`),
  ADD UNIQUE KEY `referral_2` (`referral`,`subject`,`grade`);

--
-- Indexes for table `english`
--
ALTER TABLE `english`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_3` (`st_id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`sub_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `english_ibfk_2` (`sub_id`);

--
-- Indexes for table `kurdish`
--
ALTER TABLE `kurdish`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_3` (`st_id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`sub_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `kurdish_ibfk_2` (`sub_id`);

--
-- Indexes for table `mark_table`
--
ALTER TABLE `mark_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`year_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `mark_table_ibfk_2` (`year_id`);

--
-- Indexes for table `math`
--
ALTER TABLE `math`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_3` (`st_id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`sub_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `math_ibfk_2` (`sub_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `sub_id` (`sub_id`);

--
-- Indexes for table `science`
--
ALTER TABLE `science`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_3` (`st_id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`sub_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `science_ibfk_2` (`sub_id`);

--
-- Indexes for table `sport`
--
ALTER TABLE `sport`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_3` (`st_id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`sub_id`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `sport_ibfk_2` (`sub_id`);

--
-- Indexes for table `store_mark`
--
ALTER TABLE `store_mark`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `st_id_2` (`st_id`,`year_id`,`subject`),
  ADD KEY `st_id` (`st_id`),
  ADD KEY `store_mark_ibfk_2` (`year_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`st_id`),
  ADD UNIQUE KEY `phone_email` (`phone_email`);

--
-- Indexes for table `subject_table`
--
ALTER TABLE `subject_table`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `sub_name_2` (`sub_name`,`year_id`),
  ADD UNIQUE KEY `sub_name` (`sub_name`,`teacher_id`,`year_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `year_id` (`year_id`);

--
-- Indexes for table `teacher_table`
--
ALTER TABLE `teacher_table`
  ADD PRIMARY KEY (`teacher_id`),
  ADD UNIQUE KEY `phone_email` (`phone_email`);

--
-- Indexes for table `temp_teacher`
--
ALTER TABLE `temp_teacher`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_name` (`sub_name`,`teacher_id`,`year_id`),
  ADD KEY `year_id` (`year_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `year_table`
--
ALTER TABLE `year_table`
  ADD PRIMARY KEY (`year_id`),
  ADD UNIQUE KEY `grade` (`grade`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `arabic`
--
ALTER TABLE `arabic`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `code`
--
ALTER TABLE `code`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `english`
--
ALTER TABLE `english`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kurdish`
--
ALTER TABLE `kurdish`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mark_table`
--
ALTER TABLE `mark_table`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `math`
--
ALTER TABLE `math`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `science`
--
ALTER TABLE `science`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sport`
--
ALTER TABLE `sport`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `store_mark`
--
ALTER TABLE `store_mark`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `st_id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_table`
--
ALTER TABLE `subject_table`
  MODIFY `subject_id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teacher_table`
--
ALTER TABLE `teacher_table`
  MODIFY `teacher_id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_teacher`
--
ALTER TABLE `temp_teacher`
  MODIFY `id` int(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `year_table`
--
ALTER TABLE `year_table`
  MODIFY `year_id` int(22) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arabic`
--
ALTER TABLE `arabic`
  ADD CONSTRAINT `arabic_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `arabic_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `english`
--
ALTER TABLE `english`
  ADD CONSTRAINT `english_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `english_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kurdish`
--
ALTER TABLE `kurdish`
  ADD CONSTRAINT `kurdish_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kurdish_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mark_table`
--
ALTER TABLE `mark_table`
  ADD CONSTRAINT `mark_table_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mark_table_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `year_table` (`year_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `math`
--
ALTER TABLE `math`
  ADD CONSTRAINT `math_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `math_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `science`
--
ALTER TABLE `science`
  ADD CONSTRAINT `science_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `science_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sport`
--
ALTER TABLE `sport`
  ADD CONSTRAINT `sport_ibfk_1` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sport_ibfk_2` FOREIGN KEY (`sub_id`) REFERENCES `subject_table` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `store_mark`
--
ALTER TABLE `store_mark`
  ADD CONSTRAINT `store_mark_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `year_table` (`year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `store_mark_ibfk_3` FOREIGN KEY (`st_id`) REFERENCES `student` (`st_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subject_table`
--
ALTER TABLE `subject_table`
  ADD CONSTRAINT `subject_table_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_table` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_table_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `year_table` (`year_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `temp_teacher`
--
ALTER TABLE `temp_teacher`
  ADD CONSTRAINT `temp_teacher_ibfk_1` FOREIGN KEY (`year_id`) REFERENCES `year_table` (`year_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `temp_teacher_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teacher_table` (`teacher_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
