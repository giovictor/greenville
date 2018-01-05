-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2018 at 10:44 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenville`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendanceID` int(11) NOT NULL,
  `IDNumber` varchar(20) DEFAULT NULL,
  `logindatetime` varchar(30) DEFAULT NULL,
  `logoutdatetime` varchar(30) DEFAULT NULL,
  `showstatus` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendanceID`, `IDNumber`, `logindatetime`, `logoutdatetime`, `showstatus`) VALUES
(1, '20140142412', '2017-09-10 08:29', '2017-09-10 08:29', 1),
(2, '20140159031', '2017-09-10 08:30', '2017-09-10 08:30', 1),
(3, '20140142412', '2017-09-12 15:06', '2017-09-12 15:11', 1),
(4, '20140142412', '2017-09-12 15:37', '2017-09-12 15:38', 0),
(5, '20140142412', '2017-09-12 18:36', '2017-09-12 20:01', 1),
(6, '20140142412', '2017-09-15 09:47', '2017-09-15 09:47', 1),
(7, '20140111893', '2017-09-16 01:04', '2017-09-16 01:07', 1),
(8, '20140142412', '2017-09-16 01:23', '2017-09-16 01:38', 1),
(9, '20140142412', '2017-09-16 02:23', '2017-09-16 02:24', 0),
(10, '20140159031', '2017-09-16 02:24', '2017-09-16 02:24', 1),
(11, '20140111893', '2017-09-16 02:25', '2017-09-16 02:26', 1),
(12, '20140139124', '2017-09-16 02:26', '2017-09-16 02:27', 1),
(13, '20140142489', '2017-09-16 02:31', '2017-09-16 02:33', 1),
(14, '20140142412', '2017-09-21 11:00', '2017-09-21 11:01', 0),
(15, '20140140927', '2017-09-21 11:01', '2017-09-21 11:01', 1),
(16, '20140140927', '2017-09-17 09:54', '2017-09-17 09:54', 1),
(17, '20140142412', '2017-09-20 11:29', '2017-09-20 11:30', 1),
(18, '20140139339', '2017-09-20 11:30', '2017-09-20 11:30', 1),
(19, '20140139339', '2017-09-22 11:31', '2017-09-22 11:31', 1),
(20, '20140140927', '2017-09-21 13:19', '2017-09-21 13:19', 1),
(21, '20140139124', '2017-09-21 13:19', '2017-09-23 13:21', 1),
(22, '20140142412', '2017-10-23 17:46', '2017-10-23 17:49', 1),
(23, '20140142412', '2017-10-31 21:50', '2017-10-31 21:51', 1),
(24, '20140142412', '2017-12-11 00:51', '2017-12-11 00:54', 1),
(25, '20140142412', '2017-12-11 00:57', '2017-12-11 01:01', 1),
(26, '20140142412', '2017-12-11 01:01', '2017-12-11 01:02', 1),
(27, '20140142412', '2017-12-11 01:16', '2017-12-11 01:16', 1),
(28, '20140142412', '2017-12-11 15:11', '2017-12-11 15:11', 1),
(29, '20140142489', '2017-12-11 15:13', '2017-12-11 15:14', 1),
(30, '20140142412', '2017-12-11 15:16', '2017-12-11 15:17', 1),
(31, '20140142412', '2017-12-12 11:02', '2017-12-12 11:02', 1),
(32, '20140142412', '2017-12-13 11:14', '2017-12-13 11:14', 1),
(33, '20140142412', '2017-12-13 13:20', '2017-12-13 13:27', 1),
(34, '20140142412', '2017-12-13 13:27', '2017-12-13 13:27', 1),
(35, '20140142412', '2017-12-13 13:28', NULL, 1),
(36, '20140142412', '2017-12-13 14:19', '2017-12-13 17:04', 1),
(37, '20140142412', '2017-12-13 17:04', '2017-12-13 17:14', 1),
(38, '20140142412', '2017-12-13 21:39', '2017-12-13 22:02', 1),
(39, '20140111893', '2017-12-13 22:02', '2017-12-13 22:05', 1),
(40, '20140142412', '2017-12-13 22:06', '2017-12-13 22:06', 1),
(41, '20140111893', '2017-12-13 22:06', '2017-12-13 22:09', 1),
(42, '20140142412', '2017-12-13 22:09', '2017-12-13 22:10', 1),
(43, '20140111893', '2017-12-13 22:10', '2017-12-13 22:37', 1),
(44, '20140142412', '2017-12-13 22:58', '2017-12-13 23:02', 1),
(45, '20140111893', '2017-12-13 23:02', '2017-12-13 23:03', 1),
(46, '20140142412', '2017-12-13 23:03', '2017-12-13 23:05', 1),
(47, '20140142412', '2017-12-15 12:52', '2017-12-15 12:53', 1),
(48, '20140142412', '2017-12-18 01:22', '2017-12-18 01:24', 1),
(49, '20140111893', '2017-12-18 01:24', '2017-12-18 01:25', 1),
(50, '20140142412', '2017-12-18 01:25', '2017-12-18 01:42', 1),
(51, '20140142412', '2017-12-18 01:54', '2017-12-18 01:55', 1),
(52, '20140111893', '2017-12-18 01:55', '2017-12-18 01:55', 1),
(53, '20140142412', '2017-12-18 01:55', '2017-12-18 01:55', 1),
(54, '20140111893', '2017-12-18 01:55', '2017-12-18 01:56', 1),
(55, '20140142412', '2017-12-18 01:56', '2017-12-18 02:04', 1),
(56, '20140142412', '2017-12-19 23:57', '2017-12-19 23:57', 1),
(57, '20140142412', '2017-12-20 00:15', '2017-12-20 00:20', 1),
(58, '20140142412', '2017-12-27 15:13', '2017-12-27 15:26', 1),
(59, '20140142412', '2017-12-29 14:20', '2017-12-29 14:20', 1),
(60, '20140142412', '2017-12-29 20:22', '2017-12-29 20:22', 1),
(61, '20140142412', '2017-12-31 14:55', '2017-12-31 14:56', 1),
(62, '20140159031', '2017-12-31 14:57', '2017-12-31 14:57', 1),
(63, '20140111893', '2017-12-31 14:57', '2017-12-31 14:57', 1),
(64, '20140142412', '2017-12-31 16:45', '2017-12-31 16:57', 1),
(65, '20140142412', '2017-12-31 16:58', '2017-12-31 16:59', 1),
(66, '20140142489', '2017-12-31 16:59', '2017-12-31 16:59', 1),
(67, '20140159031', '2017-12-31 16:59', '2017-12-31 17:00', 1),
(68, '20140111893', '2017-12-31 17:00', '2017-12-31 17:00', 1),
(69, '20140142412', '2018-01-05 19:34', '2018-01-06 19:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `authorID` int(11) NOT NULL,
  `author` varchar(300) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`authorID`, `author`, `status`) VALUES
(1, 'Paras, A.', 1),
(2, 'Corpuz, Et Al', 1),
(3, 'Halili, Ma. C', 1),
(4, 'Nem Singh', 1),
(5, 'Perdon, R.', 1),
(6, 'De Viana, A.', 1),
(7, 'Calilung, Jaime', 1),
(8, 'Gripaldo, R.', 1),
(9, 'Marcos, Et Al', 1),
(10, 'Agoncillo, Mangahas', 1),
(11, 'Quiason, Et Al', 1),
(12, 'Garcia, H.', 1),
(13, 'Cambers.Sibley', 1),
(14, 'Agoncillo, T.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `accession_no` int(11) NOT NULL,
  `booktitle` varchar(500) NOT NULL,
  `classificationID` int(11) DEFAULT NULL,
  `publisherID` int(11) DEFAULT NULL,
  `publishingyear` int(11) DEFAULT NULL,
  `bookID` varchar(100) NOT NULL,
  `barcode` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'On Shelf',
  `bookcondition` varchar(50) NOT NULL DEFAULT 'On Shelf',
  `callnumber` varchar(30) DEFAULT NULL,
  `ISBN` varchar(50) DEFAULT NULL,
  `pages` varchar(20) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `borrowcounter` int(11) NOT NULL DEFAULT '0',
  `acquisitiondate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`accession_no`, `booktitle`, `classificationID`, `publisherID`, `publishingyear`, `bookID`, `barcode`, `status`, `bookcondition`, `callnumber`, `ISBN`, `pages`, `price`, `borrowcounter`, `acquisitiondate`) VALUES
(2470, 'History Of The Filipino People', 10, 11, 1990, 'EF7CDB3F8C487FA9AEAF281BAADE163EAGOGAR1990', '24705205659', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 4, NULL),
(6443, 'Kasaysayan Ng Daigdig', 10, 8, 2008, '98F6A3FB3B54FC9A861DD69BC96D6543QUIC&E2008', '64433482509', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 2, NULL),
(6446, 'History, Philosophy & Culture', 10, 8, 2010, 'ADA93414F517BEE63BB6808C1FBC9364GRIC&E2010', '64466928332', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 5, NULL),
(6451, 'Philippine History : Expanded And Updated Ed.', 10, 8, 2010, '282308A39278580B815578C3F16A9D09AGOC&E2010', '64513143086', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 14, NULL),
(6989, 'The Philippine Presidents And Other Nation Builders…', 10, 5, 2010, '51D0083F341D43724779624946F35991NEMISA2010', '69897604132', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 1, NULL),
(7020, 'Philippine History And Constitution', 10, 3, 2008, '7ED842FDC0D622664DC69C90A9332CA1CORMIN2008', '70204919938', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 2, NULL),
(7023, 'Philippine Literary Heritage(from Spanish Period To Present)', 10, 3, 2009, 'D97EE9CD720063C6B9D2942D556C4B97MARMIN2009', '70235676094', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7049, 'Kamalayan, Kultura\'t Kasaysayan: A College Textbook In Philippine History', 10, 7, 2013, '861C8D4B938DEB400F96C5795439F3EBCALBOO2013', '70492304503', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7076, 'Philippine History : Footnotes', 10, 6, 2008, '5445F42CE281D317668FD780EBB01B73PERMAN2008', '70762132403', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7077, 'The Philippines : A Story Of A Nation ', 10, 4, 2011, '96EA879C7CBF56AEE7E2A82E7B1EF057DE RBS2011', '70777048734', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7078, 'Philippine History', 10, 4, 2010, '792785F232B146DA8FA56AF8FEA1C847HALRBS2010', '70786360955', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7182, 'Geography  : Course Book', 10, 10, 2010, '6E56EB0137724359A2B1C2D9587232D3CAMCAM2010', '71823062103', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7207, 'Environmental Geography', 10, 9, 2010, '8BC9F434E6E285C514AC0B20CCBA1B02GARAPP2010', '72072127100', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7336, 'History Of The Sovereign Filipino', 10, 2, 2011, '408CCD47E81C62129F227F8959E91425PARHIS2011', '73362577863', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7376, 'Pocket World Atlas', 10, 1, 2012, '6A41CDA3696FFAE200914F1C4E69AA4C***PHI2012', '73763032306', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 0, NULL),
(7391, 'Philippine History W/ Politics And Governance', 10, 3, 2012, '663149193106000691FD9AA76EC31168CORMIN2012', '73918394224', 'On Shelf', 'On Shelf', NULL, NULL, NULL, NULL, 20, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bookauthor`
--

CREATE TABLE `bookauthor` (
  `bookauthorID` int(11) NOT NULL,
  `accession_no` int(11) DEFAULT NULL,
  `authorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bookauthor`
--

INSERT INTO `bookauthor` (`bookauthorID`, `accession_no`, `authorID`) VALUES
(1, 7376, NULL),
(2, 7336, 1),
(3, 7391, 2),
(4, 7078, 3),
(5, 6989, 4),
(6, 7076, 5),
(7, 7077, 6),
(8, 7049, 7),
(9, 6446, 8),
(10, 7023, 9),
(11, 6451, 10),
(12, 7020, 2),
(13, 6443, 11),
(14, 7207, 12),
(15, 7182, 13),
(16, 2470, 14);

-- --------------------------------------------------------

--
-- Table structure for table `booklog`
--

CREATE TABLE `booklog` (
  `booklogID` int(11) NOT NULL,
  `IDNumber` varchar(20) DEFAULT NULL,
  `accession_no` int(11) DEFAULT NULL,
  `dateborrowed` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `datereturned` date DEFAULT NULL,
  `penalty` decimal(10,2) DEFAULT '0.00',
  `userID` int(11) DEFAULT NULL,
  `showstatus` tinyint(4) NOT NULL DEFAULT '1',
  `borrowsessionID` varchar(100) NOT NULL,
  `returnsessionID` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `borrowcart`
--

CREATE TABLE `borrowcart` (
  `borrowcartID` int(11) NOT NULL,
  `accession_no` int(11) DEFAULT NULL,
  `IDNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `borrower`
--

CREATE TABLE `borrower` (
  `IDNumber` varchar(20) NOT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `mi` varchar(10) DEFAULT NULL,
  `contactnumber` varchar(20) NOT NULL,
  `course` varchar(50) NOT NULL,
  `dateregistered` date NOT NULL,
  `accounttype` varchar(20) NOT NULL,
  `accountbalance` decimal(10,2) DEFAULT '0.00',
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `password` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `borrower`
--

INSERT INTO `borrower` (`IDNumber`, `lastname`, `firstname`, `mi`, `contactnumber`, `course`, `dateregistered`, `accounttype`, `accountbalance`, `status`, `password`) VALUES
('20140111893', 'Otic', 'Trixie Ann', 'H.', '09212212121', 'AB Public Administration', '2017-09-10', 'Student', '0.00', 'Active', '0f95f7ad389a372d9876a2ddb2551a43'),
('20140139124', 'Ygar', 'Jhon Lenuel ', 'B.', '09212212121', 'AB Public Administration', '2017-02-03', 'Student', '0.00', 'Active', 'fdf1f7b57190c3ca663b9ccd301168f8'),
('20140139339', 'Nidua', 'Manuel', 'L.', '09211234567', 'AB Public Administration', '2017-09-10', 'Student', '0.00', 'Active', '30b156aaa9e421081ba1235658abc523'),
('20140139497', 'Bernardo', 'Queennie', 'L.', '09212212121', 'BSED', '2017-06-13', 'Student', '0.00', 'Active', '4564fdd57f5486b8207d166d33bb937a'),
('20140140927', 'Borleo', 'Michael', 'C.', '09172542525', 'AB Public Administration', '2017-01-16', 'Student', '0.00', 'Active', '827ccb0eea8a706c4c34a16891f84e7b'),
('20140141760', 'Borboran', 'Diana Jane', 'T.', '09062656895', 'BS Psychology', '2017-06-12', 'Student', '0.00', 'Inactive', '193c41415729c936d40a3a927872bef3'),
('20140142412', 'Rodriguez', 'Gio Victor', 'A.', '09068688300', 'AB English', '2017-01-03', 'Student', '0.00', 'Active', '0f5aaaf14d9a2d371853e46119abba27'),
('20140142489', 'Labso', 'Ken Ryan', 'V.', '09212212121', 'AB Public Administration', '2017-01-28', 'Faculty', '40.00', 'Active', '5152b6ca192c7c14bc740c30954cadb9'),
('20140159031', 'Ollero', 'Benjamin', 'A.', '09062656895', 'BEED', '2017-01-16', 'Student', '0.00', 'Active', 'bf7de88b32f40b59fd69d0becc662ee4'),
('20140159111', 'Dy', 'Ashly', 'I.', '09221212121', 'AB Public Administration', '2017-09-10', 'Student', '0.00', 'Active', '8f760a47611a6bcba9cf971b5f7bcc5b');

-- --------------------------------------------------------

--
-- Table structure for table `classification`
--

CREATE TABLE `classification` (
  `classificationID` int(11) NOT NULL,
  `classification` varchar(200) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `classification`
--

INSERT INTO `classification` (`classificationID`, `classification`, `status`) VALUES
(1, 'General Works', 1),
(2, 'Philosophy & Psychology', 1),
(3, 'Religion', 1),
(4, 'Social Sciences', 1),
(5, 'Language', 1),
(6, 'Science', 1),
(7, 'Mathematics', 1),
(8, 'Arts ', 1),
(9, 'Literature', 1),
(10, 'History & Geography', 1),
(11, 'Management', 1),
(12, 'Filipiniana', 1);

-- --------------------------------------------------------

--
-- Table structure for table `holiday`
--

CREATE TABLE `holiday` (
  `holidayID` int(11) NOT NULL,
  `holiday` varchar(100) DEFAULT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `publisherID` int(11) NOT NULL,
  `publisher` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`publisherID`, `publisher`, `status`) VALUES
(1, 'Philips', 1),
(2, 'HisGo Phil.', 1),
(3, 'Mindshapers', 1),
(4, 'RBS', 1),
(5, 'ISA-JECHO', 1),
(6, 'Manila  Prints', 1),
(7, 'Books Atbp.', 1),
(8, 'C&E', 1),
(9, 'Apple Academics', 1),
(10, 'Cambridge', 1),
(11, 'Garotech ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservationID` int(11) NOT NULL,
  `IDNumber` varchar(20) DEFAULT NULL,
  `accession_no` int(11) NOT NULL,
  `reservationdate` date DEFAULT NULL,
  `expdate` date DEFAULT NULL,
  `showstatus` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `returncart`
--

CREATE TABLE `returncart` (
  `returncartID` int(11) NOT NULL,
  `accession_no` int(11) DEFAULT NULL,
  `IDNumber` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settingID` int(11) NOT NULL,
  `duedays` int(11) DEFAULT NULL,
  `penalty` decimal(10,2) DEFAULT NULL,
  `reservelimit` int(11) DEFAULT NULL,
  `borrowlimit` int(11) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settingID`, `duedays`, `penalty`, `reservelimit`, `borrowlimit`, `userID`) VALUES
(1, 3, '10.00', 3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`) VALUES
(1, 'Gio', '827ccb0eea8a706c4c34a16891f84e7b'),
(5, 'Michael', '827ccb0eea8a706c4c34a16891f84e7b'),
(6, 'Ben', '827ccb0eea8a706c4c34a16891f84e7b'),
(7, 'Trixie', '827ccb0eea8a706c4c34a16891f84e7b'),
(8, 'Aina', '827ccb0eea8a706c4c34a16891f84e7b'),
(9, 'admin', '827ccb0eea8a706c4c34a16891f84e7b');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendanceID`),
  ADD KEY `IDNumber` (`IDNumber`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`authorID`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`accession_no`),
  ADD KEY `booktypeID` (`classificationID`),
  ADD KEY `publisherID` (`publisherID`);

--
-- Indexes for table `bookauthor`
--
ALTER TABLE `bookauthor`
  ADD PRIMARY KEY (`bookauthorID`),
  ADD KEY `accession_no` (`accession_no`),
  ADD KEY `authorID` (`authorID`);

--
-- Indexes for table `booklog`
--
ALTER TABLE `booklog`
  ADD PRIMARY KEY (`booklogID`),
  ADD KEY `IDNumber` (`IDNumber`),
  ADD KEY `userID` (`userID`),
  ADD KEY `accession_no` (`accession_no`);

--
-- Indexes for table `borrowcart`
--
ALTER TABLE `borrowcart`
  ADD PRIMARY KEY (`borrowcartID`),
  ADD KEY `accession_no` (`accession_no`),
  ADD KEY `IDNumber` (`IDNumber`);

--
-- Indexes for table `borrower`
--
ALTER TABLE `borrower`
  ADD PRIMARY KEY (`IDNumber`);

--
-- Indexes for table `classification`
--
ALTER TABLE `classification`
  ADD PRIMARY KEY (`classificationID`);

--
-- Indexes for table `holiday`
--
ALTER TABLE `holiday`
  ADD PRIMARY KEY (`holidayID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`publisherID`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservationID`),
  ADD KEY `IDNumber` (`IDNumber`),
  ADD KEY `accession_no` (`accession_no`);

--
-- Indexes for table `returncart`
--
ALTER TABLE `returncart`
  ADD PRIMARY KEY (`returncartID`),
  ADD KEY `accession_no` (`accession_no`),
  ADD KEY `IDNumber` (`IDNumber`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `authorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `bookauthor`
--
ALTER TABLE `bookauthor`
  MODIFY `bookauthorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `booklog`
--
ALTER TABLE `booklog`
  MODIFY `booklogID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `borrowcart`
--
ALTER TABLE `borrowcart`
  MODIFY `borrowcartID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `classification`
--
ALTER TABLE `classification`
  MODIFY `classificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `holiday`
--
ALTER TABLE `holiday`
  MODIFY `holidayID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `publisherID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `returncart`
--
ALTER TABLE `returncart`
  MODIFY `returncartID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`IDNumber`) REFERENCES `borrower` (`IDNumber`);

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`classificationID`) REFERENCES `classification` (`classificationID`),
  ADD CONSTRAINT `book_ibfk_3` FOREIGN KEY (`publisherID`) REFERENCES `publisher` (`publisherID`);

--
-- Constraints for table `bookauthor`
--
ALTER TABLE `bookauthor`
  ADD CONSTRAINT `bookauthor_ibfk_2` FOREIGN KEY (`authorID`) REFERENCES `author` (`authorID`),
  ADD CONSTRAINT `bookauthor_ibfk_3` FOREIGN KEY (`accession_no`) REFERENCES `book` (`accession_no`);

--
-- Constraints for table `booklog`
--
ALTER TABLE `booklog`
  ADD CONSTRAINT `booklog_ibfk_1` FOREIGN KEY (`IDNumber`) REFERENCES `borrower` (`IDNumber`),
  ADD CONSTRAINT `booklog_ibfk_3` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `booklog_ibfk_4` FOREIGN KEY (`accession_no`) REFERENCES `book` (`accession_no`);

--
-- Constraints for table `borrowcart`
--
ALTER TABLE `borrowcart`
  ADD CONSTRAINT `borrowcart_ibfk_2` FOREIGN KEY (`IDNumber`) REFERENCES `borrower` (`IDNumber`),
  ADD CONSTRAINT `borrowcart_ibfk_3` FOREIGN KEY (`accession_no`) REFERENCES `book` (`accession_no`);

--
-- Constraints for table `holiday`
--
ALTER TABLE `holiday`
  ADD CONSTRAINT `holiday_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`IDNumber`) REFERENCES `borrower` (`IDNumber`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`accession_no`) REFERENCES `book` (`accession_no`);

--
-- Constraints for table `returncart`
--
ALTER TABLE `returncart`
  ADD CONSTRAINT `returncart_ibfk_2` FOREIGN KEY (`IDNumber`) REFERENCES `borrower` (`IDNumber`),
  ADD CONSTRAINT `returncart_ibfk_3` FOREIGN KEY (`accession_no`) REFERENCES `book` (`accession_no`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
