-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2017 at 05:34 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hot-tracks`
--

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genreId` int(11) NOT NULL,
  `genre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genreId`, `genre`) VALUES
(1, 'Pop'),
(2, 'Rock'),
(3, 'Ballard'),
(4, 'Country'),
(5, 'Indie'),
(6, 'Alternative'),
(7, 'Alternative Rock');

-- --------------------------------------------------------

--
-- Table structure for table `hot_tracks`
--

CREATE TABLE `hot_tracks` (
  `hotTrackId` int(11) NOT NULL,
  `songId` int(11) NOT NULL,
  `hotTrackPosition` int(11) NOT NULL,
  `hotTrackTitle` varchar(255) NOT NULL,
  `hotTrackWriter` varchar(255) NOT NULL,
  `hotTrackLink` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `songgenre`
--

CREATE TABLE `songgenre` (
  `songId` int(11) NOT NULL,
  `genreId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `songId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `album` varchar(255) NOT NULL,
  `writer` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `genre2` varchar(255) DEFAULT NULL,
  `year` varchar(255) NOT NULL,
  `songLink` varchar(255) NOT NULL,
  `videoLink` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genreId`);

--
-- Indexes for table `hot_tracks`
--
ALTER TABLE `hot_tracks`
  ADD PRIMARY KEY (`hotTrackId`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`songId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `genreId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `hot_tracks`
--
ALTER TABLE `hot_tracks`
  MODIFY `hotTrackId` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `songId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
