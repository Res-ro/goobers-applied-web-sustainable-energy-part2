-- phpMyAdmin SQL Dump
-- Goobers DB — updated for Part 2
-- Host: localhost
-- Server version: 12.2.2-MariaDB

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Database: `goobers_db`
-- --------------------------------------------------------

-- Drop and recreate the jobs table with full fields
DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id`                      INT(11)       NOT NULL AUTO_INCREMENT,
  `title`                   VARCHAR(100)  NOT NULL,
  `reference`               VARCHAR(10)   NOT NULL,
  `location`                VARCHAR(100)  NOT NULL,
  `short_description`       TEXT          NOT NULL,
  `salary`                  INT(11)       DEFAULT NULL,
  `reporting_line`          VARCHAR(150)  NOT NULL,
  `key_responsibilities`    TEXT          NOT NULL,
  `essential_requirements`  TEXT          NOT NULL,
  `preferable_requirements` TEXT          NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Data for table `jobs`
-- Each multi-value field (responsibilities, requirements) uses
-- pipe characters ( | ) as delimiters so PHP can explode them
-- into <li> items when rendering.
-- --------------------------------------------------------

INSERT INTO `jobs`
  (`title`, `reference`, `location`, `short_description`, `salary`, `reporting_line`,
   `key_responsibilities`, `essential_requirements`, `preferable_requirements`)
VALUES
(
  'Head of Media',
  'J9K4M',
  'Goobers - Melbourne',
  'Lead media strategy and sustainability communication initiatives.',
  90000,
  'Reports directly to the Marketing Director',
  'Develop and execute media campaigns|Manage external communications|Oversee content production',
  'Degree in Media, Communications, or related field|Experience in media strategy or marketing',
  'Experience in sustainability campaigns|Leadership experience'
),
(
  'Front-End Developer',
  'F8K2M',
  'Goobers - Melbourne',
  'Develop responsive web interfaces for sustainability platforms.',
  85000,
  'Reports to Senior Web Developer',
  'Build responsive UI components|Improve website performance|Ensure cross-browser compatibility',
  'HTML, CSS, JavaScript experience|Responsive design knowledge',
  'React or Vue experience|UI/UX familiarity'
),
(
  'Sustainability Expert',
  'S3T8E',
  'Goobers - Melbourne',
  'Advise on sustainability strategies and implement environmentally responsible solutions.',
  95000,
  'Reports to Head of Sustainability',
  'Develop and implement sustainability initiatives|Conduct environmental impact assessments|Advise teams on sustainable practices and compliance|Monitor and report on sustainability performance',
  'Degree in Environmental Science, Sustainability, or related field|Experience in sustainability consulting or environmental management',
  'Knowledge of Australian environmental regulations|Experience with corporate sustainability reporting frameworks|Strong analytical and problem-solving skills'
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;