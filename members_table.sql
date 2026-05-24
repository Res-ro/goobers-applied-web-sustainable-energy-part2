-- --------------------------------------------------------
-- Table: members
-- Stores team member contributions for the About Us page.
-- --------------------------------------------------------

CREATE TABLE `members` (
  `id`                   INT(11)      NOT NULL AUTO_INCREMENT,
  `name`                 VARCHAR(50)  NOT NULL,
  `student_id`           VARCHAR(20)  NOT NULL,
  `quote`                TEXT         NOT NULL,
  `part1_contribution`   TEXT         NOT NULL,
  `part2_contribution`   TEXT         NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Data for table `members`
-- part2_contribution values are placeholders — update these
-- once each member's Part 2 work is confirmed.
-- --------------------------------------------------------

INSERT INTO `members`
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
  "SQLクエリがバーに入って2つのテーブルに近づき、『一緒に結合できますか?』と尋ねた (A SQL query goes into a bar, walks up to two tables and asks, 'Can I join you?')",
  "Worked on the Jobs page.",
  "Part 2 contribution to be updated."
),
(
  "Ned",
  "102566889",
  "Um zu verstehen, was Rekursion ist, musst du zuerst Rekursion verstehen. (To understand what recursion is, you must first understand recursion.)",
  "Worked on overall refinement of all pages.",
  "Part 2 contribution to be updated."
);