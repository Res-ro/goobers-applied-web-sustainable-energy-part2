-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 01, 2026 at 10:15 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goobers_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `quote` text NOT NULL,
  `part1_contribution` text NOT NULL,
  `part2_contribution` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`id`, `name`, `student_id`, `quote`, `part1_contribution`, `part2_contribution`) VALUES
(1, 'Aiden', '106501062', 'Pourquoi les programmeurs préfèrent-ils le mode sombre? Parce que la lumière attire les bugs. (Why do programmers prefer dark mode? Because light attracts bugs.)', 'Worked on the Home and About Us page.', 'Worked on 50% of the Process EOI page, all of the Manage page and applied minor contributions to the Apply Page.\r\n'),
(2, 'Tyler', '106524221', 'Hay 10 tipos de personas en el mundo: las que entienden el binario y las que no. (There are 10 types of people in the world: those who understand binary, and those who don\'t.)', 'Worked on the Apply page & Developed the Jira Page.', 'Part 2 contribution: Worked on Sanitation & Validation for Process_EOI, Designed CSS for Process_EOI, Changed Apply Page from client side validation to server side.'),
(3, 'Huw', '106515526', 'SQLクエリがバーに入って2つのテーブルに近づき、『一緒に結合できますか？』と尋ねた (A SQL query goes into a bar, walks up to two tables and asks, \'Can I join you?\')', 'Worked on the Jobs page.', 'Updated jobs and about page to be dynamically rendered and created a jobs and about table.'),
(4, 'Ned', '102566889', 'Um zu verstehen, was Rekursion ist, musst du zuerst Rekursion verstehen. (To understand what recursion is, you must first understand recursion.)', 'Worked on overall refinement of all pages.', 'Login and profile pages\r\nUpdate details pages\r\nreorganised CSS \r\nConverted HTML to modular PHP pages ');

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `EOInumber` int(11) NOT NULL,
  `ref_number` varchar(5) DEFAULT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `date_of_birth` varchar(10) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `street_address` varchar(40) DEFAULT NULL,
  `suburb_or_town` varchar(40) DEFAULT NULL,
  `state` varchar(3) DEFAULT NULL,
  `postcode` varchar(4) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `status` varchar(10) DEFAULT 'New'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`EOInumber`, `ref_number`, `first_name`, `last_name`, `date_of_birth`, `gender`, `street_address`, `suburb_or_town`, `state`, `postcode`, `email`, `phone_number`, `skills`, `other_skills`, `status`) VALUES
(1, '00004', 'James', 'Anderson', '1995-03-14', 'male', '12 Harbour St', 'Sydney', 'NSW', '2000', 'james.anderson@gmail.com', '0412345678', 'HTML, CSS, JavaScript', 'Photoshop', 'New'),
(2, '00004', 'Sarah', 'Mitchell', '1998-07-22', 'female', '45 Collins Ave', 'Melbourne', 'VIC', '3000', 'sarah.mitchell@outlook.com', '0423456789', 'Python, SQL', 'Data Analysis', 'Current'),
(3, '00005', 'Liam', 'Thompson', '2000-11-05', 'male', '8 Queen St', 'Brisbane', 'QLD', '4000', 'liam.thompson@yahoo.com', '0434567890', 'HTML, CSS', '', 'New'),
(4, '00005', 'Emily', 'Clarke', '1993-01-30', 'female', '99 King William Rd', 'Adelaide', 'SA', '5000', 'emily.clarke@gmail.com', '0445678901', 'JavaScript, PHP', 'WordPress', 'Final'),
(5, '00006', 'Noah', 'Williams', '1997-09-18', 'male', '33 Murray St', 'Perth', 'WA', '6000', 'noah.williams@hotmail.com', '0456789012', 'SQL, Python', 'Machine Learning', 'Current'),
(6, '00006', 'Olivia', 'Brown', '2001-04-12', 'female', '7 Smith St', 'Hobart', 'TAS', '7000', 'olivia.brown@gmail.com', '0467890123', 'HTML, CSS, PHP', '', 'New'),
(7, '00007', 'Ethan', 'Davis', '1996-06-25', 'male', '21 George St', 'Sydney', 'NSW', '2000', 'ethan.davis@outlook.com', '0478901234', 'JavaScript, SQL', 'React', 'New'),
(8, '00007', 'Chloe', 'Wilson', '1999-08-09', 'female', '56 Flinders Lane', 'Melbourne', 'VIC', '3000', 'chloe.wilson@gmail.com', '0489012345', 'Python, HTML', 'Django', 'Final'),
(9, '00008', 'Mason', 'Taylor', '1994-12-01', 'male', '14 Pirie St', 'Adelaide', 'SA', '5000', 'mason.taylor@yahoo.com', '0490123456', 'CSS, JavaScript', '', 'Current'),
(10, '00008', 'Isabella', 'Martin', '2002-02-17', 'female', '88 Hay St', 'Perth', 'WA', '6000', 'isabella.martin@gmail.com', '0401234567', 'HTML, CSS, SQL', 'Figma', 'New'),
(11, '00004', 'James', 'Anderson', '1995-03-14', 'male', '12 Harbour St', 'Sydney', 'NSW', '2000', 'james.anderson@gmail.com', '0412345678', 'HTML, CSS, JavaScript', 'Photoshop', 'New'),
(12, '00004', 'Sarah', 'Mitchell', '1998-07-22', 'female', '45 Collins Ave', 'Melbourne', 'VIC', '3000', 'sarah.mitchell@outlook.com', '0423456789', 'Python, SQL', 'Data Analysis', 'Current'),
(13, '00005', 'Liam', 'Thompson', '2000-11-05', 'male', '8 Queen St', 'Brisbane', 'QLD', '4000', 'liam.thompson@yahoo.com', '0434567890', 'HTML, CSS', '', 'New'),
(14, '00005', 'Emily', 'Clarke', '1993-01-30', 'female', '99 King William Rd', 'Adelaide', 'SA', '5000', 'emily.clarke@gmail.com', '0445678901', 'JavaScript, PHP', 'WordPress', 'Final'),
(15, '00006', 'Noah', 'Williams', '1997-09-18', 'male', '33 Murray St', 'Perth', 'WA', '6000', 'noah.williams@hotmail.com', '0456789012', 'SQL, Python', 'Machine Learning', 'Current'),
(16, '00006', 'Olivia', 'Brown', '2001-04-12', 'female', '7 Smith St', 'Hobart', 'TAS', '7000', 'olivia.brown@gmail.com', '0467890123', 'HTML, CSS, PHP', '', 'New'),
(17, '00007', 'Ethan', 'Davis', '1996-06-25', 'male', '21 George St', 'Sydney', 'NSW', '2000', 'ethan.davis@outlook.com', '0478901234', 'JavaScript, SQL', 'React', 'New'),
(18, '00007', 'Chloe', 'Wilson', '1999-08-09', 'female', '56 Flinders Lane', 'Melbourne', 'VIC', '3000', 'chloe.wilson@gmail.com', '0489012345', 'Python, HTML', 'Django', 'Final'),
(19, '00008', 'Mason', 'Taylor', '1994-12-01', 'male', '14 Pirie St', 'Adelaide', 'SA', '5000', 'mason.taylor@yahoo.com', '0490123456', 'CSS, JavaScript', '', 'Current'),
(20, '00008', 'Isabella', 'Martin', '2002-02-17', 'female', '88 Hay St', 'Perth', 'WA', '6000', 'isabella.martin@gmail.com', '0401234567', 'HTML, CSS, SQL', 'Figma', 'New');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `reference` varchar(10) NOT NULL,
  `location` varchar(100) NOT NULL,
  `short_description` text NOT NULL,
  `salary` int(11) DEFAULT NULL,
  `reporting_line` varchar(150) NOT NULL,
  `key_responsibilities` text NOT NULL,
  `essential_requirements` text NOT NULL,
  `preferable_requirements` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `title`, `reference`, `location`, `short_description`, `salary`, `reporting_line`, `key_responsibilities`, `essential_requirements`, `preferable_requirements`) VALUES
(1, 'Head of Media', 'J9K4M', 'Goobers - Melbourne', 'Lead media strategy and sustainability communication initiatives.', 90000, 'Reports directly to the Marketing Director', 'Develop and execute media campaigns|Manage external communications|Oversee content production', 'Degree in Media, Communications, or related field|Experience in media strategy or marketing', 'Experience in sustainability campaigns|Leadership experience'),
(2, 'Front-End Developer', 'F8K2M', 'Goobers - Melbourne', 'Develop responsive web interfaces for sustainability platforms.', 85000, 'Reports to Senior Web Developer', 'Build responsive UI components|Improve website performance|Ensure cross-browser compatibility', 'HTML, CSS, JavaScript experience|Responsive design knowledge', 'React or Vue experience|UI/UX familiarity'),
(3, 'Sustainability Expert', 'S3T8E', 'Goobers - Melbourne', 'Advise on sustainability strategies and implement environmentally responsible solutions.', 95000, 'Reports to Head of Sustainability', 'Develop and implement sustainability initiatives|Conduct environmental impact assessments|Advise teams on sustainable practices and compliance|Monitor and report on sustainability performance', 'Degree in Environmental Science, Sustainability, or related field|Experience in sustainability consulting or environmental management', 'Knowledge of Australian environmental regulations|Experience with corporate sustainability reporting frameworks|Strong analytical and problem-solving skills');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `login_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `username`, `login_time`) VALUES
(1, 'nedaltmann', '2026-05-30 00:15:22'),
(2, 'nedaltmann', '2026-05-30 13:21:07'),
(3, 'nedaltmann', '2026-05-30 14:29:06'),
(4, 'nedaltmann', '2026-05-30 14:33:18'),
(5, 'Ned', '2026-05-31 00:40:05'),
(6, 'Aiden', '2026-05-31 00:40:38'),
(7, 'Ned', '2026-05-31 00:42:49'),
(8, 'Ned', '2026-05-31 03:05:00'),
(9, 'Ned', '2026-05-31 03:38:47'),
(10, 'Ned', '2026-05-31 03:58:25'),
(11, 'Ned', '2026-05-31 04:15:05'),
(12, 'Ned', '2026-05-31 04:26:36'),
(13, 'Ned', '2026-05-31 04:50:14'),
(14, 'Ned', '2026-05-31 05:50:45'),
(15, 'Ned', '2026-05-31 06:10:26'),
(16, 'Ned', '2026-05-31 06:12:58'),
(17, 'Ned', '2026-05-31 06:48:21');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `email`) VALUES
('Aiden', '$2y$10$OvcuSrvEVnMUTlrE42gvSe2N7S4rIIEUOpqe2/IB4DjJDK.0jvd3C', 'aiden@swinburne.com'),
('Huw', '$2y$10$tJ.nwtgEh9FZGizDFqKLzeaQLkyZdC7z06vMm3LRKDPd1UXol/nLG', 'huw@swinburne.com'),
('Ned', '$2y$10$FFhW0Nn5clRD/OEO2qQ52.AnT8C5o61ZQpcsWpHPI7G59VaNyLLcu', 'ned@swinburne.com'),
('Tyler', '$2y$10$S45plo382FUp7MHXlMeMf.vQvtBiBm3rGrxT4lILeKzFJrL8Juzru', 'tyler@swinburne.com'),
('admin', '$2y$10$9bFuUfIaWHgcqxSjAWcMl.K.5hGzCyikszfOVP1dH2ySn7VqK20oS', 'admin@goobers.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`EOInumber`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about`
--
ALTER TABLE `about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `EOInumber` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
