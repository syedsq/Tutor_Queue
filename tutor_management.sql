-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 20, 2024 at 08:44 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tutor_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `class` varchar(100) NOT NULL,
  `session_type` varchar(20) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `full_name`, `student_id`, `email`, `class`, `session_type`, `subject`, `submission_time`) VALUES
(3, 'Test 2', 'no', 'no@gmail.com', '', 'online', 'Testing', '2024-09-18 01:09:49'),
(4, 'John Doe', 'S001', 'john.doe@example.com', 'Math 101', 'inperson', 'Algebra', '2024-09-19 20:27:35'),
(5, 'Jane Smith', 'S002', 'jane.smith@example.com', 'Math 101', 'online', 'Geometry', '2024-09-19 20:27:35'),
(6, 'Michael Johnson', 'S003', 'michael.johnson@example.com', 'Science 101', 'inperson', 'Physics', '2024-09-19 20:27:35'),
(7, 'Emily Davis', 'S004', 'emily.davis@example.com', 'Science 101', 'online', 'Biology', '2024-09-19 20:27:35'),
(8, 'Chris Brown', 'S005', 'chris.brown@example.com', 'History 101', 'inperson', 'World History', '2024-09-19 20:27:35'),
(9, 'Sophia Martinez', 'S006', 'sophia.martinez@example.com', 'History 101', 'online', 'US History', '2024-09-19 20:27:35'),
(10, 'Daniel Wilson', 'S007', 'daniel.wilson@example.com', 'English 101', 'inperson', 'Literature', '2024-09-19 20:27:35'),
(11, 'Olivia Thompson', 'S008', 'olivia.thompson@example.com', 'English 101', 'online', 'Grammar', '2024-09-19 20:27:35'),
(12, 'David White', 'S009', 'david.white@example.com', 'Math 101', 'inperson', 'Trigonometry', '2024-09-19 20:27:35'),
(13, 'Ava Moore', 'S010', 'ava.moore@example.com', 'Math 101', 'online', 'Statistics', '2024-09-19 20:27:35'),
(14, 'Ethan Anderson', 'S011', 'ethan.anderson@example.com', 'Science 101', 'inperson', 'Chemistry', '2024-09-19 20:27:35'),
(15, 'Isabella Jackson', 'S012', 'isabella.jackson@example.com', 'Science 101', 'online', 'Astronomy', '2024-09-19 20:27:35'),
(16, 'Jacob Lee', 'S013', 'jacob.lee@example.com', 'History 101', 'inperson', 'Medieval History', '2024-09-19 20:27:35'),
(17, 'Mia Harris', 'S014', 'mia.harris@example.com', 'History 101', 'online', 'Ancient Civilizations', '2024-09-19 20:27:35'),
(18, 'James Walker', 'S015', 'james.walker@example.com', 'English 101', 'inperson', 'Creative Writing', '2024-09-19 20:27:35'),
(19, 'Charlotte Green', 'S016', 'charlotte.green@example.com', 'English 101', 'online', 'Poetry', '2024-09-19 20:27:35'),
(20, 'William Hall', 'S017', 'william.hall@example.com', 'Math 101', 'inperson', 'Calculus', '2024-09-19 20:27:35'),
(21, 'Amelia Young', 'S018', 'amelia.young@example.com', 'Math 101', 'online', 'Algebra 2', '2024-09-19 20:27:35'),
(22, 'Benjamin King', 'S019', 'benjamin.king@example.com', 'Science 101', 'inperson', 'Geology', '2024-09-19 20:27:35'),
(24, 'Camilla', 'Guo', 'dfghj@gmail.com', '', 'online', 'trace and output', '2024-09-20 00:20:07'),
(25, 'Syed Saqib', 'abc123', 'testin@gmail.com', '', 'inperson', 'Shell Scipting', '2024-09-20 00:33:55'),
(26, 'Camilla Test', 'abc123', 'Calmila@gmail.com', '', 'inperson', 'Shell Scripting', '2024-09-20 18:15:27');

-- --------------------------------------------------------

--
-- Table structure for table `tutor_sessions`
--

CREATE TABLE `tutor_sessions` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `session_type` varchar(20) DEFAULT NULL,
  `session_duration` int(11) DEFAULT NULL,
  `session_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_sessions`
--

INSERT INTO `tutor_sessions` (`id`, `student_name`, `student_id`, `subject`, `session_type`, `session_duration`, `session_date`) VALUES
(1, 'Syed Test', 'abc123', 'Test', 'online', 84, '2024-09-20 00:13:57'),
(2, 'Syed Test', 'abc123', 'Test', 'online', 64, '2024-09-20 00:13:59'),
(3, 'Abigail Wright', 'S020', 'Environmental Science', 'online', 107, '2024-09-20 00:15:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutor_sessions`
--
ALTER TABLE `tutor_sessions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tutor_sessions`
--
ALTER TABLE `tutor_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
