-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 25, 2026 at 03:04 PM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `goobers_LOCAL_db`
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
(1, 'Aiden', '106501062', 'Pourquoi les programmeurs préfèrent-ils le mode sombre? Parce que la lumière attire les bugs. (Why do programmers prefer dark mode? Because light attracts bugs.)', 'Worked on the Home and About Us page.', 'Part 2 contribution to be updated.'),
(2, 'Tyler', '106524221', 'Hay 10 tipos de personas en el mundo: las que entienden el binario y las que no. (There are 10 types of people in the world: those who understand binary, and those who don\'t.)', 'Worked on the Apply page.', 'Part 2 contribution to be updated.'),
(3, 'Huw', '106515526', 'SQLクエリがバーに入って2つのテーブルに近づき、『一緒に結合できますか？』と尋ねた (A SQL query goes into a bar, walks up to two tables and asks, \'Can I join you?\')', 'Worked on the Jobs page.', 'Updated jobs and about page to be dynamically rendered and created a jobs and about table.'),
(4, 'Ned', '102566889', 'Um zu verstehen, was Rekursion ist, musst du zuerst Rekursion verstehen. (To understand what recursion is, you must first understand recursion.)', 'Worked on overall refinement of all pages.', 'Part 2 contribution to be updated.');

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
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `email`) VALUES
('nedaltmann', '102566889', 'bob@mail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about`
--
ALTER TABLE `about`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
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
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
