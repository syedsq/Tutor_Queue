-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2024 at 11:28 PM
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
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `assigned_tutor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `full_name`, `student_id`, `email`, `class`, `session_type`, `subject`, `submission_time`, `assigned_tutor`) VALUES
(6, 'Michael Johnson', 'S003', 'michael.johnson@example.com', 'Science 101', 'inperson', 'Physics', '2024-09-19 20:27:35', NULL),
(7, 'Emily Davis', 'S004', 'emily.davis@example.com', 'Science 101', 'online', 'Biology', '2024-09-19 20:27:35', NULL),
(8, 'Chris Brown', 'S005', 'chris.brown@example.com', 'History 101', 'inperson', 'World History', '2024-09-19 20:27:35', NULL),
(9, 'Sophia Martinez', 'S006', 'sophia.martinez@example.com', 'History 101', 'online', 'US History', '2024-09-19 20:27:35', NULL),
(10, 'Daniel Wilson', 'S007', 'daniel.wilson@example.com', 'English 101', 'inperson', 'Literature', '2024-09-19 20:27:35', NULL),
(11, 'Olivia Thompson', 'S008', 'olivia.thompson@example.com', 'English 101', 'online', 'Grammar', '2024-09-19 20:27:35', NULL),
(12, 'David White', 'S009', 'david.white@example.com', 'Math 101', 'inperson', 'Trigonometry', '2024-09-19 20:27:35', NULL),
(13, 'Ava Moore', 'S010', 'ava.moore@example.com', 'Math 101', 'online', 'Statistics', '2024-09-19 20:27:35', NULL),
(15, 'Isabella Jackson', 'S012', 'isabella.jackson@example.com', 'Science 101', 'online', 'Astronomy', '2024-09-19 20:27:35', NULL),
(16, 'Jacob Lee', 'S013', 'jacob.lee@example.com', 'History 101', 'inperson', 'Medieval History', '2024-09-19 20:27:35', NULL),
(17, 'Mia Harris', 'S014', 'mia.harris@example.com', 'History 101', 'online', 'Ancient Civilizations', '2024-09-19 20:27:35', NULL),
(18, 'James Walker', 'S015', 'james.walker@example.com', 'English 101', 'inperson', 'Creative Writing', '2024-09-19 20:27:35', NULL),
(19, 'Charlotte Green', 'S016', 'charlotte.green@example.com', 'English 101', 'online', 'Poetry', '2024-09-19 20:27:35', NULL),
(20, 'William Hall', 'S017', 'william.hall@example.com', 'Math 101', 'inperson', 'Calculus', '2024-09-19 20:27:35', NULL),
(21, 'Amelia Young', 'S018', 'amelia.young@example.com', 'Math 101', 'online', 'Algebra 2', '2024-09-19 20:27:35', NULL),
(22, 'Benjamin King', 'S019', 'benjamin.king@example.com', 'Science 101', 'inperson', 'Geology', '2024-09-19 20:27:35', NULL),
(24, 'Camilla', 'Guo', 'dfghj@gmail.com', '', 'online', 'trace and output', '2024-09-20 00:20:07', NULL),
(25, 'Syed Saqib', 'abc123', 'testin@gmail.com', '', 'inperson', 'Shell Scipting', '2024-09-20 00:33:55', NULL),
(26, 'Camilla Test', 'abc123', 'Calmila@gmail.com', '', 'inperson', 'Shell Scripting', '2024-09-20 18:15:27', NULL),
(28, 'Submit test', '123455', 'no@gmail.com', 'CS1063,CS1083,CS1714,CS2073,CS2113,CS2124,CS2233,CS3424,CS3443,CS3723,CS3843', 'online', 'Nope', '2024-10-25 21:06:17', NULL),
(29, 'Anoterh test', '23839', 'No@gmail.com', 'CS1063,CS1083,CS1714,CS2073,CS2113,CS2124,CS2233,CS3424,CS3443,CS3723,CS3843', 'online', 'Test', '2024-10-25 21:08:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student_feedback`
--

CREATE TABLE `student_feedback` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text DEFAULT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE `tutors` (
  `id` int(11) NOT NULL,
  `tutor_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `schedule` varchar(255) DEFAULT NULL,
  `active_sessions` int(11) DEFAULT 0,
  `email` varchar(255) NOT NULL,
  `banner_id` varchar(50) NOT NULL,
  `is_logged_in` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`id`, `tutor_name`, `subject`, `schedule`, `active_sessions`, `email`, `banner_id`, `is_logged_in`) VALUES
(24, 'Tutor Camila', 'CS1063,CS1714', 'Thursday 1:59 PM - 10:58 PM', 0, 'camila@gmail.com', '12345', 0),
(26, 'Tutor Yash', 'CS1063,CS1083,CS1714,CS2073,CS2113,CS2124,CS2233,CS3424,CS3443,CS3723,CS3843', 'Monday 12:01 AM - 11:59 PM, Tuesday 12:01 AM - 11:59 PM, Wednesday 12:01 AM - 11:59 PM, Thursday 12:01 AM - 11:59 PM, Friday 12:01 AM - 11:59 PM, Saturday 12:01 AM - 11:59 PM', 0, 'Yash@gmail.com', '1234567', 0),
(27, 'Tutor Yash', 'CS1063,CS1083,CS1714,CS2073,CS2113,CS2124,CS2233,CS3424,CS3443,CS3723,CS3843', NULL, 0, 'Yash@gmail.com', '1234567', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tutor_availability`
--

CREATE TABLE `tutor_availability` (
  `id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `day_of_week` varchar(20) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutor_availability`
--

INSERT INTO `tutor_availability` (`id`, `tutor_id`, `day_of_week`, `start_time`, `end_time`) VALUES
(13, 26, 'Monday', '00:01:00', '23:59:00'),
(14, 26, 'Tuesday', '00:01:00', '23:59:00'),
(15, 26, 'Wednesday', '00:01:00', '23:59:00'),
(16, 26, 'Thursday', '00:01:00', '23:59:00'),
(17, 26, 'Friday', '00:01:00', '23:59:00'),
(18, 26, 'Saturday', '00:01:00', '23:59:00'),
(27, 24, 'Thursday', '13:59:00', '22:58:00');

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
(3, 'Abigail Wright', 'S020', 'Environmental Science', 'online', 107, '2024-09-20 00:15:17'),
(4, 'Test 2', 'no', 'Testing', 'online', 0, '2024-10-24 21:53:33'),
(5, 'John Doe', 'S001', 'Algebra', 'inperson', 0, '2024-10-25 19:08:07'),
(6, 'Jane Smith', 'S002', 'Geometry', 'online', 0, '2024-10-25 20:55:51'),
(7, 'Tutor Test', '1234', 'Loop', 'online', 0, '2024-10-25 20:56:16'),
(8, 'Ethan Anderson', 'S011', 'Chemistry', 'inperson', 0, '2024-10-25 20:57:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_feedback`
--
ALTER TABLE `student_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutors`
--
ALTER TABLE `tutors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tutor_availability`
--
ALTER TABLE `tutor_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tutor_id` (`tutor_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `student_feedback`
--
ALTER TABLE `student_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutors`
--
ALTER TABLE `tutors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tutor_availability`
--
ALTER TABLE `tutor_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tutor_sessions`
--
ALTER TABLE `tutor_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tutor_availability`
--
ALTER TABLE `tutor_availability`
  ADD CONSTRAINT `tutor_availability_ibfk_1` FOREIGN KEY (`tutor_id`) REFERENCES `tutors` (`id`);
COMMIT;

-- Table for users
CREATE TABLE users (
     clerk_user_id VARCHAR(32) NOT NULL,
     first_name VARCHAR(32) NOT NULL,
     last_name VARCHAR(32) NOT NULL,
     utsa_id VARCHAR(32),
     email VARCHAR(32) NOT NULL,
     PRIMARY KEY (clerk_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Stores all courses
CREATE TABLE courses (
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      course_id VARCHAR(32) NOT NULL,
      course_name VARCHAR(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--Table to store tutor requests
CREATE TABLE requests (
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      student_name VARCHAR(64) NOT NULL,
      student_id VARCHAR(32) NOT NULL,
      course_id VARCHAR(32) NOT NULL,
      topic VARCHAR(64) NOT NULL,
      submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Maps tutors to courses they teach
CREATE TABLE tutor_courses (
    tutor_id VARCHAR(32) NOT NULL,
    course_id VARCHAR(32) NOT NULL,
    id INT(11) AUTO_INCREMENT PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Maps students to courses they take
CREATE TABLE student_course (
    student_id VARCHAR(32) NOT NULL,
    course_id VARCHAR(32) NOT NULL,
    id INT(11) AUTO_INCREMENT PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--List of all registered student ids
CREATE TABLE student_data (
    utsa_id VARCHAR(32) NOT NULL,
    id INT(11) AUTO_INCREMENT PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--List of all registered admin ids
CREATE TABLE admins(
    utsa_id VARCHAR(32) NOT NULL,
    id INT(11) AUTO_INCREMENT PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
