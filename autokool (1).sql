-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Loomise aeg: Nov 13, 2026 kell 10:13 EL
-- Serveri versioon: 10.4.32-MariaDB
-- PHP versioon: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Andmebaas: `autokool`
--

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(100) NOT NULL,
  `picture` longblob DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `description_en` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `title`, `description`, `price`, `duration`, `picture`, `title_en`, `description_en`) VALUES
(1, 1, 'B-kategooria algkursus', 'Täielik B-kategooria juhiloa kursus, mis sisaldab teooriaõpet ja sõiduõpet.', 350.00, '2 kuud', NULL, 'B Category Basic Course', 'Complete B category driving course, including theoretical and practical training.'),
(2, 1, 'B-kategooria intensiivkursus', 'Kiirendatud õppeprogramm neile, kes soovivad juhiloa omandada lühema ajaga.', 450.00, '1 kuu', NULL, 'B Category Intensive Course', 'Accelerated training program for those who want to obtain a driving license quickly.'),
(3, 2, 'A-kategooria kursus', 'Mootorratta juhtimise teooria- ja sõiduõpe.', 300.00, '2 kuud', NULL, 'A Category Course', 'Motorcycle driving theory and practical training.'),
(4, 3, 'C-kategooria kursus', 'Veoki juhtimise õpe ja ettevalmistus C-kategooria juhiloa eksamiks.', 600.00, '3 kuud', NULL, 'C Category Course', 'Truck driving training and preparation for the C category.');

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `course_categories`
--

CREATE TABLE `course_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `course_categories`
--

INSERT INTO `course_categories` (`id`, `name`, `description`) VALUES
(1, 'B-kategooria', 'Sõiduauto juhtimise õpe'),
(2, 'A-kategooria', 'Mootorratta juhtimise õpe'),
(3, 'C-kategooria', 'Veoki juhtimise õpe'),
(4, 'Teooriaõpe', 'Liiklusteooria ja liikluseeskirjade õpe');

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `instructors`
--

INSERT INTO `instructors` (`id`, `name`, `email`, `phone`, `description`, `picture`) VALUES
(1, 'Jaan Tamm', 'jaan@autokool.ee', '+372 5555 1111', 'Kogenud B-kategooria sõiduõpetaja.', NULL),
(2, 'Mari Saar', 'mari@autokool.ee', '+372 5555 2222', 'Teooriaõpetaja ja liiklusekspert.', NULL),
(3, 'Karl Mets', 'karl@autokool.ee', '+372 5555 3333', 'A- ja C-kategooria sõiduõpetaja.', NULL);

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `registration_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `course_id`, `registration_date`, `status`) VALUES
(1, 3, 4, '2026-10-23 10:44:13', 'pending'),
(2, 3, 3, '2026-11-13 10:00:48', 'pending'),
(3, 3, 2, '2026-11-13 10:08:16', 'pending');

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `reviews`
--

INSERT INTO `reviews` (`id`, `course_id`, `user_id`, `text`, `date`) VALUES
(1, 4, NULL, 'выфвфы', '2026-10-09 10:40:30'),
(2, 4, NULL, 'выфвыф', '2026-10-09 10:40:33'),
(3, 4, NULL, 'вывфы', '2026-10-09 10:40:46'),
(4, 4, NULL, 'вфывфы', '2026-10-09 10:40:48'),
(5, 4, NULL, 'выфвыф', '2026-10-09 10:40:50'),
(6, 4, NULL, 'выфвфы', '2026-10-19 12:47:16'),
(8, 2, NULL, 'вфы', '2026-11-13 10:08:18');

-- --------------------------------------------------------

--
-- Tabeli struktuur tabelile `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('student','admin') NOT NULL DEFAULT 'student',
  `registration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Andmete tõmmistamine tabelile `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `status`, `registration_date`) VALUES
(1, 'dsadsa', 'ddsadsada@gmail.com', '$2y$10$2ooxQS0g7VI5UVH3sPzZgOQKbyh86qTAtyljajoQYe.AqUitNdnjW', 'student', '2026-10-09'),
(2, 'dsadsa', 'dsadsa@gmail.com', '$2y$10$cCeA.eUd14hs/wi9wVuqMeoQUSRT2QgeeqJvrpVDKW/fC.dh70Sp6', 'student', '2026-10-19'),
(3, 'dsadsa', 'dsadsa1@gmail.com', '$2y$10$vIVGKXQYRReai8MJrQ2TNOPPtUpO7gWli/Y7DheB7ZkJCw9pXtMna', 'admin', '2026-10-19');

--
-- Indeksid tõmmistatud tabelitele
--

--
-- Indeksid tabelile `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeksid tabelile `course_categories`
--
ALTER TABLE `course_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeksid tabelile `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`);

--
-- Indeksid tabelile `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_course` (`user_id`,`course_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indeksid tabelile `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeksid tabelile `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT tõmmistatud tabelitele
--

--
-- AUTO_INCREMENT tabelile `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT tabelile `course_categories`
--
ALTER TABLE `course_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT tabelile `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT tabelile `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT tabelile `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT tabelile `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tõmmistatud tabelite piirangud
--

--
-- Piirangud tabelile `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `course_categories` (`id`) ON DELETE CASCADE;

--
-- Piirangud tabelile `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Piirangud tabelile `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
