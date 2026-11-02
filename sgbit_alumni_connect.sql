-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2025 at 04:20 PM
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
-- Database: `sgbit_alumni_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `adminmember`
--

CREATE TABLE `adminmember` (
  `ad_id` varchar(5) NOT NULL,
  `ad_password` varchar(50) NOT NULL,
  `ad_fullname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adminmember`
--

INSERT INTO `adminmember` (`ad_id`, `ad_password`, `ad_fullname`) VALUES
('AD1', 'admin123', 'Admin User'),
('AD2', 'admin456', 'Secondary Admin');

-- --------------------------------------------------------

--
-- Table structure for table `alumni`
--

CREATE TABLE `alumni` (
  `alumni_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `session_year` varchar(20) NOT NULL,
  `graduation_year` int(11) DEFAULT NULL,
  `current_company` varchar(255) DEFAULT NULL,
  `current_position` varchar(255) DEFAULT NULL,
  `years_of_experience` int(11) DEFAULT 0,
  `linkedin_profile` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `expertise` varchar(255) DEFAULT NULL,
  `is_mentor` tinyint(1) DEFAULT 0,
  `pi_register` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alumni`
--

INSERT INTO `alumni` (`alumni_id`, `user_id`, `branch`, `session_year`, `graduation_year`, `current_company`, `current_position`, `years_of_experience`, `linkedin_profile`, `address`, `city`, `state`, `country`, `expertise`, `is_mentor`, `pi_register`) VALUES
(1, 2, 'Computer Science', '2016-2020', 2020, 'Google', 'Software Engineer', 0, NULL, NULL, NULL, NULL, 'India', NULL, 1, NULL),
(2, 3, 'Electronics', '2015-2019', 2019, 'Microsoft', 'Senior Engineer', 0, NULL, NULL, NULL, NULL, 'India', NULL, 1, NULL),
(3, 4, 'Mechanical', '2017-2021', 2021, 'Tesla', 'Design Engineer', 0, NULL, NULL, NULL, NULL, 'India', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `alumniinfo`
--

CREATE TABLE `alumniinfo` (
  `pi_name` varchar(100) NOT NULL,
  `pi_gender` varchar(20) NOT NULL,
  `pi_address` varchar(100) NOT NULL,
  `pi_email` varchar(100) NOT NULL,
  `pi_linkedin` varchar(255) DEFAULT NULL,
  `pi_instagram` varchar(255) DEFAULT NULL,
  `pi_whatsapp` varchar(20) DEFAULT NULL,
  `pi_facebook` varchar(255) DEFAULT NULL,
  `pi_twitter` varchar(255) DEFAULT NULL,
  `pi_github` varchar(255) DEFAULT NULL,
  `pi_portfolio` varchar(255) DEFAULT NULL,
  `pi_picture` longblob DEFAULT NULL,
  `pi_session` varchar(50) NOT NULL,
  `pi_branch` varchar(50) NOT NULL,
  `pi_yop` year(4) DEFAULT NULL,
  `pi_mobile` varchar(50) NOT NULL,
  `pi_company` varchar(50) NOT NULL,
  `pi_designation` varchar(100) DEFAULT NULL,
  `pi_register` varchar(100) NOT NULL,
  `show_contact` tinyint(1) DEFAULT 1 COMMENT 'Show contact info to students',
  `available_for_mentorship` tinyint(1) DEFAULT 1 COMMENT 'Available as mentor'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alumniinfo`
--

INSERT INTO `alumniinfo` (`pi_name`, `pi_gender`, `pi_address`, `pi_email`, `pi_linkedin`, `pi_instagram`, `pi_whatsapp`, `pi_facebook`, `pi_twitter`, `pi_github`, `pi_portfolio`, `pi_picture`, `pi_session`, `pi_branch`, `pi_yop`, `pi_mobile`, `pi_company`, `pi_designation`, `pi_register`, `show_contact`, `available_for_mentorship`) VALUES
('Aksha', 'Female', 'chikkodi', 'aksha@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-2026', 'CSE', NULL, '3456787262', 'microsoft', NULL, 'R059', 1, 1),
('Mahalakshmi G', 'Female', 'Belagavi', 'mahalakshmi.g.appu@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-2027', 'CSE', NULL, '7259327387', '', NULL, 'R069', 1, 1),
('akshara', 'Female', 'hubbali', 'ak@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-2026', 'CSE', NULL, '4567892341', 'cognizant', NULL, 'R1', 1, 1),
('kaveri', 'Female', 'Belagavi', 'k@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-2026', 'IT', NULL, '2345678912', 'microsoft', NULL, 'R4', 1, 1),
('Aditi', 'Female', 'Belagavi', 'a@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2023-2027', 'CSE', NULL, '3456789544', 'microsoft', NULL, 'R5', 1, 1),
('apoorva', 'Female', 'Belagavi', 'ap@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-2026', 'CSE', NULL, '2345678911', 'microsoft', NULL, 'R7', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `alumnimember`
--

CREATE TABLE `alumnimember` (
  `pi_register` varchar(20) NOT NULL,
  `al_password` varchar(20) NOT NULL,
  `al_status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `alumnimember`
--

INSERT INTO `alumnimember` (`pi_register`, `al_password`, `al_status`) VALUES
('R059', '', 'Pending'),
('R069', 'Maha@123', 'Approve'),
('R1', 'akshara123', 'Approve'),
('R4', '', 'Approve'),
('R5', '', 'Approve'),
('R7', 'apoorva123', 'Approve');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `ann_id` varchar(10) NOT NULL,
  `ann_name` varchar(100) NOT NULL,
  `ann_desc` text NOT NULL,
  `ann_time` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `target_audience` enum('all','alumni','students') DEFAULT 'all',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcement_id`, `title`, `content`, `created_by`, `target_audience`, `created_at`, `expires_at`, `is_active`) VALUES
(1, 'Welcome to Alumni Connect!', 'We are excited to launch the new Alumni Connect platform.', 1, 'all', '2025-11-01 08:09:08', NULL, 1),
(2, 'Upcoming Tech Workshop', 'Register now for the upcoming technology workshop on AI and Machine Learning.', 1, 'students', '2025-11-01 08:09:08', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `message_id` int(11) NOT NULL,
  `sender_type` enum('student','alumni') NOT NULL,
  `sender_register` varchar(100) NOT NULL,
  `receiver_type` enum('student','alumni') NOT NULL,
  `receiver_register` varchar(100) NOT NULL,
  `message_text` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `is_deleted_sender` tinyint(1) DEFAULT 0,
  `is_deleted_receiver` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`message_id`, `sender_type`, `sender_register`, `receiver_type`, `receiver_register`, `message_text`, `sent_at`, `is_read`, `read_at`, `is_deleted_sender`, `is_deleted_receiver`) VALUES
(1, 'alumni', 'R069', 'student', 'S5', 'hiiii', '2025-11-07 05:58:10', 1, '2025-11-07 06:01:46', 0, 0),
(2, 'student', 'S5', 'alumni', 'R069', 'hlo', '2025-11-09 13:41:41', 0, NULL, 0, 0),
(3, 'alumni', 'R1', 'student', 'S4', 'hlo', '2025-11-09 14:13:11', 0, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `connection_requests`
--

CREATE TABLE `connection_requests` (
  `request_id` int(11) NOT NULL,
  `from_type` enum('student','alumni') NOT NULL,
  `from_register` varchar(100) NOT NULL,
  `to_type` enum('student','alumni') NOT NULL,
  `to_register` varchar(100) NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `response_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `connection_requests`
--

INSERT INTO `connection_requests` (`request_id`, `from_type`, `from_register`, `to_type`, `to_register`, `message`, `status`, `request_date`, `response_date`) VALUES
(1, 'student', 'S5', 'alumni', 'R069', 'i would like to connect with you sister, pls accept my request\r\n', 'accepted', '2025-11-06 15:49:04', '2025-11-07 05:04:17'),
(2, 'student', 'S5', 'alumni', 'R1', 'hii sister, connect with me ', 'pending', '2025-11-07 05:00:57', NULL),
(3, 'student', 'S4', 'alumni', 'R1', 'hlo akshara mam,i like to connect with you', 'accepted', '2025-11-09 14:11:47', '2025-11-09 14:13:02');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `e_id` varchar(50) NOT NULL,
  `e_name` varchar(100) NOT NULL,
  `e_date` date NOT NULL,
  `e_time` time NOT NULL,
  `e_desc` longtext NOT NULL,
  `e_venue` varchar(50) NOT NULL,
  `e_pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`e_id`, `e_name`, `e_date`, `e_time`, `e_desc`, `e_venue`, `e_pic`) VALUES
('E1', 'SGBIT Alumni Meeting', '2025-11-10', '03:30:00', 'it is an alumni meeeting and get together event,everyone participate.', 'seminal hall,CSE Department', 'Admin Admin User'),
('E2', 'alumni function', '2025-11-11', '10:46:00', 'alumni function is arranged in seminar hall,please do attend', 'CSE,seminar hall', 'Admin Admin User');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_description` text DEFAULT NULL,
  `event_type` enum('reunion','workshop','seminar','sports','cultural','other') DEFAULT 'other',
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `is_virtual` tinyint(1) DEFAULT 0,
  `virtual_link` varchar(255) DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `registration_deadline` date DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_description`, `event_type`, `event_date`, `event_time`, `venue`, `is_virtual`, `virtual_link`, `max_participants`, `registration_deadline`, `created_by`, `created_at`, `is_active`) VALUES
(1, 'Annual Alumni Reunion 2025', 'Join us for our grand annual reunion!', 'reunion', '2025-12-15', '10:00:00', 'SGBIT Campus', 0, NULL, NULL, NULL, 1, '2025-11-01 08:09:08', 1),
(2, 'Tech Talk: AI & ML', 'Industry experts share insights on AI trends', 'workshop', '2025-11-20', '18:00:00', 'Virtual Event', 0, NULL, NULL, NULL, 1, '2025-11-01 08:09:08', 1),
(3, 'Career Mentorship Program', 'Alumni-Student mentorship session', 'seminar', '2025-11-30', '14:00:00', 'Seminar Hall', 0, NULL, NULL, NULL, 1, '2025-11-01 08:09:08', 1),
(4, 'SGBIT Alumni Meeting', 'alumni meeting for the betterment of students.', 'seminar', '2025-11-10', '03:01:00', 'seminal hall,CSE Department', 0, NULL, NULL, NULL, 1, '2025-11-09 14:24:06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `event_registrations`
--

CREATE TABLE `event_registrations` (
  `registration_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `attendance_status` enum('registered','attended','cancelled') DEFAULT 'registered',
  `st_register` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event_registrations`
--

INSERT INTO `event_registrations` (`registration_id`, `event_id`, `user_id`, `registration_date`, `attendance_status`, `st_register`) VALUES
(2, 2, 0, '2025-11-07 05:13:49', 'registered', 'S5'),
(3, 3, 0, '2025-11-09 13:30:55', 'registered', 'S5'),
(4, 4, 0, '2025-11-09 14:25:01', 'registered', 'S5');

-- --------------------------------------------------------

--
-- Table structure for table `financialdata`
--

CREATE TABLE `financialdata` (
  `payment_id` varchar(10) NOT NULL,
  `total_payment` double(10,2) NOT NULL,
  `payment_purpose` varchar(50) NOT NULL,
  `pi_register` varchar(20) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_records`
--

CREATE TABLE `financial_records` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_type` enum('yearly','lifetime') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'completed',
  `valid_from` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forumdata`
--

CREATE TABLE `forumdata` (
  `eforum_id` int(11) NOT NULL,
  `eforum_title` varchar(250) NOT NULL,
  `eforum_author` varchar(50) DEFAULT NULL,
  `eforum_content` text DEFAULT NULL,
  `eforum_tags` varchar(50) DEFAULT NULL,
  `eforum_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `forumdata`
--

INSERT INTO `forumdata` (`eforum_id`, `eforum_title`, `eforum_author`, `eforum_content`, `eforum_tags`, `eforum_time`) VALUES
(1, 'full stack developer intern', 'apoorva', 'we need an intern who can work fulltime.', 'HTML,CSS,JS,PHP,MYSQL', '2025-11-02 18:47:23'),
(2, 'internship opportunity at cognizant.', 'apoorva', 'interns require for the role of system engineer,interested students can regisetr.', '', '2025-11-09 14:27:42'),
(3, 'internship at infosys', 'apoorva', 'internship opportunity at infosys.', 'Career & Jobs', '2025-11-09 14:42:55'),
(4, 'i am good at frontend development and i am good at communication.', 'maala', 'i need internship.', 'Other', '2025-11-09 14:47:50');

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `st_register` varchar(50) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_likes`
--

CREATE TABLE `forum_likes` (
  `like_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `st_register` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) DEFAULT 'General',
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `views` int(11) DEFAULT 0,
  `is_pinned` tinyint(1) DEFAULT 0,
  `st_register` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_replies`
--

CREATE TABLE `forum_replies` (
  `reply_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_reply`
--

CREATE TABLE `forum_reply` (
  `forum_topic` varchar(250) NOT NULL,
  `forum_message` text NOT NULL,
  `forum_reply_id` int(11) NOT NULL,
  `forum_reply_time` varchar(50) NOT NULL,
  `forum_reply_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mentorship`
--

CREATE TABLE `mentorship` (
  `mentorship_id` int(11) NOT NULL,
  `mentor_pi_register` varchar(100) NOT NULL COMMENT 'References alumniinfo.pi_register',
  `mentee_st_register` varchar(50) NOT NULL COMMENT 'References studentinfo.st_register',
  `status` enum('active','completed','terminated') DEFAULT 'active',
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentorship`
--

INSERT INTO `mentorship` (`mentorship_id`, `mentor_pi_register`, `mentee_st_register`, `status`, `start_date`, `end_date`, `notes`) VALUES
(1, 'R069', 'S5', 'active', '2025-11-06 10:15:38', NULL, NULL),
(2, 'R7', 'S5', 'active', '2025-11-09 13:16:02', NULL, NULL),
(3, 'R1', 'S4', 'active', '2025-11-09 14:03:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mentorship_requests`
--

CREATE TABLE `mentorship_requests` (
  `request_id` int(11) NOT NULL,
  `st_register` varchar(50) NOT NULL COMMENT 'References studentinfo.st_register',
  `mentor_pi_register` varchar(100) NOT NULL COMMENT 'References alumniinfo.pi_register',
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `response_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mentorship_requests`
--

INSERT INTO `mentorship_requests` (`request_id`, `st_register`, `mentor_pi_register`, `status`, `request_date`, `response_date`) VALUES
(1, 'S5', 'R069', 'Accepted', '2025-11-06 10:13:08', '2025-11-06 10:15:38'),
(2, 'S5', 'R4', 'Pending', '2025-11-06 10:13:14', NULL),
(3, 'S5', 'R7', 'Accepted', '2025-11-06 10:13:29', '2025-11-09 13:16:02'),
(4, 'S5', 'R1', 'Pending', '2025-11-06 14:47:33', NULL),
(5, 'S4', 'R1', 'Accepted', '2025-11-09 14:01:52', '2025-11-09 14:03:56');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message_body` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL,
  `is_deleted_sender` tinyint(1) DEFAULT 0,
  `is_deleted_receiver` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `sender_id`, `receiver_id`, `subject`, `message_body`, `is_read`, `sent_at`, `read_at`, `is_deleted_sender`, `is_deleted_receiver`) VALUES
(1, 2, 5, 'Career Guidance Request', 'Hi! I am interested in learning more about software development career paths. Could you share your experience?', 0, '2025-11-01 08:09:08', NULL, 0, 0),
(2, 3, 5, 'Internship Opportunity', 'Hello! Our company is looking for interns in the ECE department. Are you interested?', 0, '2025-11-01 08:09:08', NULL, 0, 0),
(3, 1, 5, 'Upcoming Alumni Meet', 'We are organizing an alumni meet on December 15th. Do not miss it!', 0, '2025-11-01 08:09:08', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_type` enum('student','alumni','admin') NOT NULL,
  `user_register` varchar(100) NOT NULL,
  `notification_type` enum('connection_request','message','mentorship_request','mentorship_accepted','profile_view','other') NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_type`, `user_register`, `notification_type`, `title`, `message`, `link`, `is_read`, `created_at`, `read_at`) VALUES
(1, 'alumni', 'R1', 'mentorship_request', 'New Mentorship Request', 'A student has requested you as a mentor', 'alumni_requests.php', 0, '2025-11-06 14:47:33', NULL),
(2, 'alumni', 'R069', 'connection_request', 'New Connection Request', 'Student maala wants to connect with you', 'alumni_connections.php', 1, '2025-11-06 15:49:04', '2025-11-07 06:00:44'),
(3, 'alumni', 'R1', 'connection_request', 'New Connection Request', 'Student maala wants to connect with you', 'alumni_connections.php', 0, '2025-11-07 05:00:57', NULL),
(4, 'student', 'S5', 'connection_request', 'Connection Accepted!', 'Alumni Mahalakshmi G accepted your connection request', 'student_connections.php', 0, '2025-11-07 05:04:17', NULL),
(5, 'student', 'S5', 'message', 'New Message', 'Alumni Mahalakshmi G sent you a message', 'student_chat.php?user=R069', 0, '2025-11-07 05:58:10', NULL),
(6, 'alumni', 'R069', 'message', 'New Message', 'maala sent you a message', 'alumni_chat.php?user=S5', 0, '2025-11-09 13:41:41', NULL),
(7, 'alumni', 'R1', 'mentorship_request', 'New Mentorship Request', 'A student has requested you as a mentor', 'alumni_requests.php', 0, '2025-11-09 14:01:52', NULL),
(8, 'alumni', 'R1', 'connection_request', 'New Connection Request', 'Student kaveri wants to connect with you', 'alumni_connections.php', 0, '2025-11-09 14:11:47', NULL),
(9, 'student', 'S4', 'connection_request', 'Connection Accepted!', 'Alumni akshara accepted your connection request', 'student_connections.php', 0, '2025-11-09 14:13:02', NULL),
(10, 'student', 'S4', 'message', 'New Message', 'Alumni akshara sent you a message', 'student_chat.php?user=R1', 0, '2025-11-09 14:13:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `st_register` varchar(20) NOT NULL,
  `st_name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `batch` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`st_register`, `st_name`, `department`, `batch`, `email`, `password`) VALUES
('STU001', 'John Doe', 'CSE', '2025', 'john@example.com', 'hashedpassword');

-- --------------------------------------------------------

--
-- Table structure for table `studentinfo`
--

CREATE TABLE `studentinfo` (
  `st_name` varchar(100) NOT NULL,
  `st_gender` varchar(20) NOT NULL,
  `st_address` varchar(100) NOT NULL,
  `st_email` varchar(100) NOT NULL,
  `st_linkedin` varchar(255) DEFAULT NULL,
  `st_instagram` varchar(255) DEFAULT NULL,
  `st_whatsapp` varchar(20) DEFAULT NULL,
  `st_facebook` varchar(255) DEFAULT NULL,
  `st_twitter` varchar(255) DEFAULT NULL,
  `st_github` varchar(255) DEFAULT NULL,
  `st_portfolio` varchar(255) DEFAULT NULL,
  `st_picture` longblob DEFAULT NULL,
  `st_branch` varchar(50) NOT NULL,
  `st_year` int(11) NOT NULL,
  `st_mobile` varchar(50) NOT NULL,
  `st_register` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `studentinfo`
--

INSERT INTO `studentinfo` (`st_name`, `st_gender`, `st_address`, `st_email`, `st_linkedin`, `st_instagram`, `st_whatsapp`, `st_facebook`, `st_twitter`, `st_github`, `st_portfolio`, `st_picture`, `st_branch`, `st_year`, `st_mobile`, `st_register`) VALUES
('Alex Kumar', 'Male', 'Belagavi, Karnataka', 'alex.kumar@sgbit.edu.in', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSE', 3, '9876543215', 'S1'),
('Priya Sharma', 'Female', 'Bangalore, Karnataka', 'priya.sharma@sgbit.edu.in', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ECE', 2, '9876543216', 'S2'),
('Rahul Verma', 'Male', 'Mumbai, Maharashtra', 'rahul.verma@sgbit.edu.in', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'IT', 4, '9876543217', 'S3'),
('kaveri', '', 'Belagavi', 'k@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSE', 3, '2345678912', 'S4'),
('R11', 'Female', 'Belagavi', 'bhavana@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSE', 3, '7894561230', 'S48'),
('maala', 'Female', 'Belagavi', 'm@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'CSE', 3, '1234567896', 'S5');

-- --------------------------------------------------------

--
-- Table structure for table `studentmember`
--

CREATE TABLE `studentmember` (
  `st_register` varchar(20) NOT NULL,
  `st_password` varchar(20) NOT NULL,
  `st_status` varchar(20) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `studentmember`
--

INSERT INTO `studentmember` (`st_register`, `st_password`, `st_status`) VALUES
('S1', 'student123', 'Active'),
('S2', 'student123', 'Active'),
('S3', 'student123', 'Active'),
('S4', 'kaveri123', 'Active'),
('S48', 'bhavana123', 'Active'),
('S5', 'maala123', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `current_year` int(11) NOT NULL,
  `enrollment_year` int(11) NOT NULL,
  `roll_number` varchar(50) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `parent_contact` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `user_id`, `branch`, `current_year`, `enrollment_year`, `roll_number`, `semester`, `cgpa`, `address`, `parent_contact`) VALUES
(1, 5, 'Computer Science', 3, 2022, 'CSE2022001', 5, 8.50, NULL, NULL),
(2, 6, 'Electronics', 2, 2023, 'ECE2023001', 3, 8.80, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_type` enum('admin','alumni','student') NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `mobile_number` varchar(15) DEFAULT NULL,
  `registration_number` varchar(50) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'default-avatar.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_type`, `full_name`, `email`, `password`, `gender`, `mobile_number`, `registration_number`, `profile_picture`, `created_at`, `last_login`, `is_active`) VALUES
(1, 'admin', 'Admin User', 'admin@sgbit.edu.in', '0192023a7bbd73250516f069df18b500', 'Male', '9876543210', NULL, 'default-avatar.jpg', '2025-11-01 08:09:08', NULL, 1),
(2, 'alumni', 'John Doe', 'john.doe@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 'Male', '9876543211', 'SGBIT2020001', 'default-avatar.jpg', '2025-11-01 08:09:08', NULL, 1),
(3, 'alumni', 'Jane Smith', 'jane.smith@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 'Female', '9876543212', 'SGBIT2019001', 'default-avatar.jpg', '2025-11-01 08:09:08', NULL, 1),
(4, 'alumni', 'Mike Johnson', 'mike.johnson@gmail.com', '482c811da5d5b4bc6d497ffa98491e38', 'Male', '9876543213', 'SGBIT2021001', 'default-avatar.jpg', '2025-11-01 08:09:08', NULL, 1),
(5, 'student', 'Alex Kumar', 'alex.kumar@sgbit.edu.in', '482c811da5d5b4bc6d497ffa98491e38', 'Male', '9876543214', 'SGBIT2023001', 'default-avatar.jpg', '2025-11-01 08:09:08', NULL, 1),
(6, 'student', 'Priya Sharma', 'priya.sharma@sgbit.edu.in', '482c811da5d5b4bc6d497ffa98491e38', 'Female', '9876543215', 'SGBIT2023002', 'default-avatar.jpg', '2025-11-01 08:09:08', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_connections`
--

CREATE TABLE `user_connections` (
  `connection_id` int(11) NOT NULL,
  `user1_type` enum('student','alumni') NOT NULL,
  `user1_register` varchar(100) NOT NULL,
  `user2_type` enum('student','alumni') NOT NULL,
  `user2_register` varchar(100) NOT NULL,
  `connection_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_interaction` timestamp NULL DEFAULT NULL,
  `interaction_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_connections`
--

INSERT INTO `user_connections` (`connection_id`, `user1_type`, `user1_register`, `user2_type`, `user2_register`, `connection_date`, `last_interaction`, `interaction_count`) VALUES
(1, 'student', 'S5', 'alumni', 'R069', '2025-11-07 05:04:17', '2025-11-09 13:41:41', 2),
(2, 'student', 'S4', 'alumni', 'R1', '2025-11-09 14:13:02', '2025-11-09 14:13:11', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_interactions`
--

CREATE TABLE `user_interactions` (
  `interaction_id` int(11) NOT NULL,
  `from_type` enum('student','alumni') NOT NULL,
  `from_register` varchar(100) NOT NULL COMMENT 'st_register or pi_register',
  `to_type` enum('student','alumni') NOT NULL,
  `to_register` varchar(100) NOT NULL COMMENT 'st_register or pi_register',
  `interaction_type` enum('view_profile','click_linkedin','click_whatsapp','click_email','click_phone','send_message') NOT NULL,
  `interaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `metadata` text DEFAULT NULL COMMENT 'Additional interaction data in JSON'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_interactions`
--

INSERT INTO `user_interactions` (`interaction_id`, `from_type`, `from_register`, `to_type`, `to_register`, `interaction_type`, `interaction_date`, `metadata`) VALUES
(1, 'student', 'S5', 'alumni', 'R069', 'view_profile', '2025-11-07 05:40:24', NULL),
(2, 'student', 'S5', 'alumni', 'R069', 'view_profile', '2025-11-07 06:01:56', NULL),
(3, 'student', 'S5', 'alumni', 'R069', 'view_profile', '2025-11-09 13:14:58', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_active_mentorships`
-- (See below for the actual view)
--
CREATE TABLE `v_active_mentorships` (
`mentorship_id` int(11)
,`mentor_pi_register` varchar(100)
,`mentee_st_register` varchar(50)
,`mentor_name` varchar(100)
,`mentor_email` varchar(100)
,`mentor_phone` varchar(50)
,`mentor_company` varchar(50)
,`mentor_position` varchar(100)
,`mentor_branch` varchar(50)
,`mentee_name` varchar(100)
,`mentee_email` varchar(100)
,`mentee_phone` varchar(50)
,`mentee_branch` varchar(50)
,`mentee_year` int(11)
,`start_date` timestamp
,`status` enum('active','completed','terminated')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_pending_requests`
-- (See below for the actual view)
--
CREATE TABLE `v_pending_requests` (
`request_id` int(11)
,`st_register` varchar(50)
,`mentor_pi_register` varchar(100)
,`mentor_name` varchar(100)
,`mentor_email` varchar(100)
,`mentor_company` varchar(50)
,`mentor_position` varchar(100)
,`mentor_branch` varchar(50)
,`student_name` varchar(100)
,`student_email` varchar(100)
,`student_branch` varchar(50)
,`student_year` int(11)
,`request_date` timestamp
,`status` enum('Pending','Accepted','Rejected')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_recent_interactions`
-- (See below for the actual view)
--
CREATE TABLE `v_recent_interactions` (
`interaction_id` int(11)
,`from_type` enum('student','alumni')
,`from_register` varchar(100)
,`from_name` varchar(100)
,`to_type` enum('student','alumni')
,`to_register` varchar(100)
,`to_name` varchar(100)
,`interaction_type` enum('view_profile','click_linkedin','click_whatsapp','click_email','click_phone','send_message')
,`interaction_date` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_connections`
-- (See below for the actual view)
--
CREATE TABLE `v_user_connections` (
`connection_id` int(11)
,`user1_register` varchar(100)
,`user1_type` enum('student','alumni')
,`user1_name` varchar(100)
,`user1_email` varchar(100)
,`user2_register` varchar(100)
,`user2_type` enum('student','alumni')
,`user2_name` varchar(100)
,`user2_email` varchar(100)
,`connection_date` timestamp
,`last_interaction` timestamp
,`interaction_count` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `v_active_mentorships`
--
DROP TABLE IF EXISTS `v_active_mentorships`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_active_mentorships`  AS SELECT `m`.`mentorship_id` AS `mentorship_id`, `m`.`mentor_pi_register` AS `mentor_pi_register`, `m`.`mentee_st_register` AS `mentee_st_register`, `ai`.`pi_name` AS `mentor_name`, `ai`.`pi_email` AS `mentor_email`, `ai`.`pi_mobile` AS `mentor_phone`, `ai`.`pi_company` AS `mentor_company`, `ai`.`pi_designation` AS `mentor_position`, `ai`.`pi_branch` AS `mentor_branch`, `si`.`st_name` AS `mentee_name`, `si`.`st_email` AS `mentee_email`, `si`.`st_mobile` AS `mentee_phone`, `si`.`st_branch` AS `mentee_branch`, `si`.`st_year` AS `mentee_year`, `m`.`start_date` AS `start_date`, `m`.`status` AS `status` FROM ((`mentorship` `m` join `alumniinfo` `ai` on(`m`.`mentor_pi_register` = `ai`.`pi_register`)) join `studentinfo` `si` on(`m`.`mentee_st_register` = `si`.`st_register`)) WHERE `m`.`status` = 'active' ;

-- --------------------------------------------------------

--
-- Structure for view `v_pending_requests`
--
DROP TABLE IF EXISTS `v_pending_requests`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pending_requests`  AS SELECT `r`.`request_id` AS `request_id`, `r`.`st_register` AS `st_register`, `r`.`mentor_pi_register` AS `mentor_pi_register`, `ai`.`pi_name` AS `mentor_name`, `ai`.`pi_email` AS `mentor_email`, `ai`.`pi_company` AS `mentor_company`, `ai`.`pi_designation` AS `mentor_position`, `ai`.`pi_branch` AS `mentor_branch`, `si`.`st_name` AS `student_name`, `si`.`st_email` AS `student_email`, `si`.`st_branch` AS `student_branch`, `si`.`st_year` AS `student_year`, `r`.`request_date` AS `request_date`, `r`.`status` AS `status` FROM ((`mentorship_requests` `r` join `alumniinfo` `ai` on(`r`.`mentor_pi_register` = `ai`.`pi_register`)) join `studentinfo` `si` on(`r`.`st_register` = `si`.`st_register`)) WHERE `r`.`status` = 'Pending' ;

-- --------------------------------------------------------

--
-- Structure for view `v_recent_interactions`
--
DROP TABLE IF EXISTS `v_recent_interactions`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_recent_interactions`  AS SELECT `i`.`interaction_id` AS `interaction_id`, `i`.`from_type` AS `from_type`, `i`.`from_register` AS `from_register`, CASE WHEN `i`.`from_type` = 'student' THEN `si`.`st_name` ELSE `ai`.`pi_name` END AS `from_name`, `i`.`to_type` AS `to_type`, `i`.`to_register` AS `to_register`, CASE WHEN `i`.`to_type` = 'student' THEN `si2`.`st_name` ELSE `ai2`.`pi_name` END AS `to_name`, `i`.`interaction_type` AS `interaction_type`, `i`.`interaction_date` AS `interaction_date` FROM ((((`user_interactions` `i` left join `studentinfo` `si` on(`i`.`from_register` = `si`.`st_register` and `i`.`from_type` = 'student')) left join `alumniinfo` `ai` on(`i`.`from_register` = `ai`.`pi_register` and `i`.`from_type` = 'alumni')) left join `studentinfo` `si2` on(`i`.`to_register` = `si2`.`st_register` and `i`.`to_type` = 'student')) left join `alumniinfo` `ai2` on(`i`.`to_register` = `ai2`.`pi_register` and `i`.`to_type` = 'alumni')) ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_connections`
--
DROP TABLE IF EXISTS `v_user_connections`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_connections`  AS SELECT `c`.`connection_id` AS `connection_id`, `c`.`user1_register` AS `user1_register`, `c`.`user1_type` AS `user1_type`, CASE WHEN `c`.`user1_type` = 'student' THEN `si1`.`st_name` ELSE `ai1`.`pi_name` END AS `user1_name`, CASE WHEN `c`.`user1_type` = 'student' THEN `si1`.`st_email` ELSE `ai1`.`pi_email` END AS `user1_email`, `c`.`user2_register` AS `user2_register`, `c`.`user2_type` AS `user2_type`, CASE WHEN `c`.`user2_type` = 'student' THEN `si2`.`st_name` ELSE `ai2`.`pi_name` END AS `user2_name`, CASE WHEN `c`.`user2_type` = 'student' THEN `si2`.`st_email` ELSE `ai2`.`pi_email` END AS `user2_email`, `c`.`connection_date` AS `connection_date`, `c`.`last_interaction` AS `last_interaction`, `c`.`interaction_count` AS `interaction_count` FROM ((((`user_connections` `c` left join `studentinfo` `si1` on(`c`.`user1_register` = `si1`.`st_register` and `c`.`user1_type` = 'student')) left join `alumniinfo` `ai1` on(`c`.`user1_register` = `ai1`.`pi_register` and `c`.`user1_type` = 'alumni')) left join `studentinfo` `si2` on(`c`.`user2_register` = `si2`.`st_register` and `c`.`user2_type` = 'student')) left join `alumniinfo` `ai2` on(`c`.`user2_register` = `ai2`.`pi_register` and `c`.`user2_type` = 'alumni')) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adminmember`
--
ALTER TABLE `adminmember`
  ADD PRIMARY KEY (`ad_id`);

--
-- Indexes for table `alumni`
--
ALTER TABLE `alumni`
  ADD PRIMARY KEY (`alumni_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_session` (`session_year`),
  ADD KEY `idx_branch` (`branch`);

--
-- Indexes for table `alumniinfo`
--
ALTER TABLE `alumniinfo`
  ADD PRIMARY KEY (`pi_register`),
  ADD KEY `idx_alumni_register` (`pi_register`),
  ADD KEY `idx_alumni_linkedin` (`pi_linkedin`);

--
-- Indexes for table `alumnimember`
--
ALTER TABLE `alumnimember`
  ADD PRIMARY KEY (`pi_register`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`ann_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_audience` (`target_audience`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_conversation` (`sender_register`,`receiver_register`),
  ADD KEY `idx_read` (`is_read`),
  ADD KEY `idx_chat_unread` (`receiver_register`,`is_read`);

--
-- Indexes for table `connection_requests`
--
ALTER TABLE `connection_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `unique_connection` (`from_register`,`to_register`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`e_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_date` (`event_date`),
  ADD KEY `idx_type` (`event_type`);

--
-- Indexes for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `unique_student_registration` (`event_id`,`st_register`),
  ADD UNIQUE KEY `unique_student_event` (`event_id`,`st_register`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_event` (`event_id`);

--
-- Indexes for table `financialdata`
--
ALTER TABLE `financialdata`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_status` (`payment_status`);

--
-- Indexes for table `forumdata`
--
ALTER TABLE `forumdata`
  ADD PRIMARY KEY (`eforum_id`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_st_register` (`st_register`);

--
-- Indexes for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`st_register`),
  ADD KEY `idx_post_id` (`post_id`),
  ADD KEY `idx_st_register` (`st_register`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_post` (`post_id`);

--
-- Indexes for table `forum_reply`
--
ALTER TABLE `forum_reply`
  ADD PRIMARY KEY (`forum_reply_id`);

--
-- Indexes for table `mentorship`
--
ALTER TABLE `mentorship`
  ADD PRIMARY KEY (`mentorship_id`),
  ADD KEY `mentor_pi_register` (`mentor_pi_register`),
  ADD KEY `mentee_st_register` (`mentee_st_register`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_mentorship_active` (`status`,`mentor_pi_register`);

--
-- Indexes for table `mentorship_requests`
--
ALTER TABLE `mentorship_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `st_register` (`st_register`),
  ADD KEY `mentor_pi_register` (`mentor_pi_register`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_requests_pending` (`status`,`mentor_pi_register`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_sender` (`sender_id`),
  ADD KEY `idx_receiver` (`receiver_id`),
  ADD KEY `idx_read` (`is_read`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_user` (`user_register`,`user_type`),
  ADD KEY `idx_unread` (`is_read`,`created_at`),
  ADD KEY `idx_notifications_unread` (`user_register`,`is_read`,`created_at`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`st_register`);

--
-- Indexes for table `studentinfo`
--
ALTER TABLE `studentinfo`
  ADD PRIMARY KEY (`st_register`),
  ADD KEY `idx_student_register` (`st_register`),
  ADD KEY `idx_student_linkedin` (`st_linkedin`);

--
-- Indexes for table `studentmember`
--
ALTER TABLE `studentmember`
  ADD PRIMARY KEY (`st_register`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `roll_number` (`roll_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_branch` (`branch`),
  ADD KEY `idx_year` (`current_year`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD KEY `idx_user_type` (`user_type`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `user_connections`
--
ALTER TABLE `user_connections`
  ADD PRIMARY KEY (`connection_id`),
  ADD UNIQUE KEY `unique_pair` (`user1_register`,`user2_register`),
  ADD KEY `idx_user1` (`user1_register`),
  ADD KEY `idx_user2` (`user2_register`);

--
-- Indexes for table `user_interactions`
--
ALTER TABLE `user_interactions`
  ADD PRIMARY KEY (`interaction_id`),
  ADD KEY `idx_from` (`from_register`,`from_type`),
  ADD KEY `idx_to` (`to_register`,`to_type`),
  ADD KEY `idx_date` (`interaction_date`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alumni`
--
ALTER TABLE `alumni`
  MODIFY `alumni_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `connection_requests`
--
ALTER TABLE `connection_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `event_registrations`
--
ALTER TABLE `event_registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `financial_records`
--
ALTER TABLE `financial_records`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forumdata`
--
ALTER TABLE `forumdata`
  MODIFY `eforum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forum_replies`
--
ALTER TABLE `forum_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_reply`
--
ALTER TABLE `forum_reply`
  MODIFY `forum_reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mentorship`
--
ALTER TABLE `mentorship`
  MODIFY `mentorship_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mentorship_requests`
--
ALTER TABLE `mentorship_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_connections`
--
ALTER TABLE `user_connections`
  MODIFY `connection_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_interactions`
--
ALTER TABLE `user_interactions`
  MODIFY `interaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alumni`
--
ALTER TABLE `alumni`
  ADD CONSTRAINT `alumni_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `alumniinfo`
--
ALTER TABLE `alumniinfo`
  ADD CONSTRAINT `foreign_key_fk` FOREIGN KEY (`pi_register`) REFERENCES `alumnimember` (`pi_register`);

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `event_registrations`
--
ALTER TABLE `event_registrations`
  ADD CONSTRAINT `event_registrations_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_records`
--
ALTER TABLE `financial_records`
  ADD CONSTRAINT `financial_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `fk_forum_comments_post` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD CONSTRAINT `fk_forum_likes_post` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_replies`
--
ALTER TABLE `forum_replies`
  ADD CONSTRAINT `forum_replies_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `studentinfo`
--
ALTER TABLE `studentinfo`
  ADD CONSTRAINT `student_fk` FOREIGN KEY (`st_register`) REFERENCES `studentmember` (`st_register`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
