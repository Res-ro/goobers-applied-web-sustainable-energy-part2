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

-- --------------------------------------------------------
-- Table: `about`
-- Stores team member contributions for the About Us page.
-- --------------------------------------------------------

DROP TABLE IF EXISTS `about`;

CREATE TABLE `about` (
  `id`                   INT(11)      NOT NULL AUTO_INCREMENT,
  `name`                 VARCHAR(50)  NOT NULL,
  `student_id`           VARCHAR(20)  NOT NULL,
  `quote`                TEXT         NOT NULL,
  `part1_contribution`   TEXT         NOT NULL,
  `part2_contribution`   TEXT         NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- part2_contribution values are placeholders — update once confirmed.

INSERT INTO `about`
  (`name`, `student_id`, `quote`, `part1_contribution`, `part2_contribution`)
VALUES
(
  "Aiden",
  "106501062",
  "Pourquoi les programmeurs préfèrent-ils le mode sombre? Parce que la lumière attire les bugs. (Why do programmers prefer dark mode? Because light attracts bugs.)",
  "Worked on the Home and About Us page.",
  "Part 2 contribution to be updated."
),
(
  "Tyler",
  "106524221",
  "Hay 10 tipos de personas en el mundo: las que entienden el binario y las que no. (There are 10 types of people in the world: those who understand binary, and those who don't.)",
  "Worked on the Apply page.",
  "Part 2 contribution to be updated."
),
(
  "Huw",
  "106515526",
  "SQLクエリがバーに入って2つのテーブルに近づき、『一緒に結合できますか？』と尋ねた (A SQL query goes into a bar, walks up to two tables and asks, 'Can I join you?')",
  "Worked on the Jobs page.",
  "Updated jobs and about page to be dynamically rendered and created a jobs and about table."
),
(
  "Ned",
  "102566889",
  "Um zu verstehen, was Rekursion ist, musst du zuerst Rekursion verstehen. (To understand what recursion is, you must first understand recursion.)",
  "Worked on overall refinement of all pages.",
  "Part 2 contribution to be updated."
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;