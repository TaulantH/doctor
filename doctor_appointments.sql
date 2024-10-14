-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 14, 2024 at 09:56 AM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor_appointments`
--
CREATE DATABASE IF NOT EXISTS `doctor_appointments` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `doctor_appointments`;

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `appointment_id` int NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(255) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  PRIMARY KEY (`appointment_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointment_id`, `patient_name`, `appointment_date`, `appointment_time`, `doctor_id`) VALUES
(1, 'Zena Beasley Key', '2024-02-28', '09:00:00', 5),
(2, 'Zena Beasley Key', '2024-02-28', '09:30:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `doctor_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  PRIMARY KEY (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `name`, `specialization`) VALUES
(1, 'Taulant', 'Infermier'),
(2, 'Rrezart', 'Neurologist'),
(3, 'Taulant', 'Stomatolog'),
(4, 'Filani', 'Cardiologist'),
(5, 'Fisteki', 'Pediatrician'),
(6, 'Endrit', 'Ophthalmologist'),
(7, 'Gazmend', 'Psychiatrist');

-- --------------------------------------------------------

--
-- Table structure for table `public_appointments`
--

DROP TABLE IF EXISTS `public_appointments`;
CREATE TABLE IF NOT EXISTS `public_appointments` (
  `appointment_id` int NOT NULL AUTO_INCREMENT,
  `patient_name` varchar(255) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  `birthday` date NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  PRIMARY KEY (`appointment_id`),
  KEY `doctor_id` (`doctor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `public_appointments`
--

INSERT INTO `public_appointments` (`appointment_id`, `patient_name`, `appointment_date`, `appointment_time`, `doctor_id`, `birthday`, `phone_number`, `email`, `gender`) VALUES
(1, 'Azalia Knight', '2024-02-16', '09:00:00', 1, '1975-10-14', '+1 (858) 721-5889', 'piho@mailinator.com', 'Female'),
(2, 'Azalia Knight', '2024-02-16', '09:30:00', 1, '1975-10-14', '+1 (858) 721-5889', 'piho@mailinator.com', 'Female'),
(3, 'Lars Schroeder', '2024-02-22', '09:00:00', 1, '2024-10-11', '+1 (205) 873-5882', 'cerena@mailinator.com', 'Male'),
(4, 'Justine Dudley', '2024-02-22', '09:30:00', 1, '1997-05-15', '+1 (212) 375-8706', 'tt6592423@gmail.com', 'Female');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone_number` int NOT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `email`, `date_of_birth`, `phone_number`, `gender`, `password`, `role`, `picture`) VALUES
(5, 'Taulant', 'Hoxha', 'taulant', 'htaulant0@gmial.com', '2024-09-06', 41651651, 'male', '29fbec3c6484d16621659eeb31c23d77691810a6341bf91bcf1cd26f515c154c', 'adm', 'avatar.png');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `public_appointments`
--
ALTER TABLE `public_appointments`
  ADD CONSTRAINT `public_appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
