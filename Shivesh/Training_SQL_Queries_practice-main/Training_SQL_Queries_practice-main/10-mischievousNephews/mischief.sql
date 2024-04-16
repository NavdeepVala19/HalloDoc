-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jan 19, 2024 at 10:06 AM
-- Server version: 8.0.18
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `training practice queries`
--

-- --------------------------------------------------------

--
-- Table structure for table `mischief`
--

DROP TABLE IF EXISTS `mischief`;
CREATE TABLE IF NOT EXISTS `mischief` (
  `mischief_date` date DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mischief`
--

INSERT INTO `mischief` (`mischief_date`, `author`, `title`) VALUES
('2016-12-01', 'Dewey', 'Cook the golden fish in a bucket'),
('2016-12-01', 'Dewey', 'Paint the walls pink'),
('2016-12-01', 'Huey', 'Eat all the candies'),
('2016-12-01', 'Louie', 'Wrap the cat in toilet paper'),
('2016-12-08', 'Louie', 'Play hockey on linoleum'),
('2017-01-01', 'Huey', 'Smash a window'),
('2017-02-06', 'Dewey', 'Create a rink on the porch');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
