-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Εξυπηρετητής: 127.0.0.1
-- Χρόνος δημιουργίας: 16 Δεκ 2024 στις 14:06:25
-- Έκδοση διακομιστή: 10.4.28-MariaDB
-- Έκδοση PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Βάση δεδομένων: `bluebirdhotel`
--

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `emp_login`
--

CREATE TABLE `emp_login` (
  `empid` int(100) NOT NULL,
  `Emp_Email` varchar(50) NOT NULL,
  `Emp_Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `emp_login`
--

INSERT INTO `emp_login` (`empid`, `Emp_Email`, `Emp_Password`) VALUES
(1, 'melina@hotmail.com', '0000');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `payment`
--

CREATE TABLE `payment` (
  `id` int(30) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `RoomType` varchar(30) NOT NULL,
  `Bed` varchar(30) NOT NULL,
  `NoofRoom` int(30) NOT NULL,
  `cin` date NOT NULL,
  `cout` date NOT NULL,
  `noofdays` int(30) NOT NULL,
  `roomtotal` double(8,2) NOT NULL,
  `bedtotal` double(8,2) NOT NULL,
  `meal` varchar(30) NOT NULL,
  `mealtotal` double(8,2) NOT NULL,
  `finaltotal` double(8,2) NOT NULL,
  `Noofadult` varchar(50) NOT NULL,
  `Noofchild` varchar(50) NOT NULL,
  `Noofinfant` varchar(50) NOT NULL,
  `din` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `payment`
--

INSERT INTO `payment` (`id`, `Name`, `Email`, `RoomType`, `Bed`, `NoofRoom`, `cin`, `cout`, `noofdays`, `roomtotal`, `bedtotal`, `meal`, `mealtotal`, `finaltotal`, `Noofadult`, `Noofchild`, `Noofinfant`, `din`) VALUES
(41, 'Tushar pankhaniya', 'pankhaniyatushar9@gmail.com', 'Single Room', 'Single', 1, '2022-11-09', '2022-11-10', 1, 1000.00, 10.00, 'Room only', 0.00, 1010.00, '', '', '', NULL),
(51, 'Melina Douka', '', 'Superior Room', 'Single', 1, '2024-11-13', '2024-11-06', -7, -21000.00, -210.00, 'Room only', 0.00, -21210.00, '', '', '', NULL),
(52, 'gd', '', 'Superior Room', 'Single', 1, '2024-11-19', '2024-11-18', -1, -3000.00, -30.00, 'Room only', 0.00, -3030.00, '', '', '', NULL),
(54, 'ii', '', 'Superior Room', 'Single', 1, '2024-11-21', '2024-11-07', -14, -42000.00, -420.00, 'Room only', 0.00, -42420.00, '', '', '', NULL),
(56, 'kidofk', '', 'Superior Room', 'Double', 5, '2024-11-21', '2024-11-21', 0, 0.00, 0.00, 'Room only', 0.00, 0.00, '', '', '', NULL),
(58, 'ii', '', 'Superior Room', 'Single', 2, '2024-11-21', '2024-11-14', -7, -42000.00, -210.00, 'Room only', 0.00, -42210.00, '', '', '', NULL);

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `room`
--

CREATE TABLE `room` (
  `id` int(30) NOT NULL,
  `rtype` varchar(50) NOT NULL,
  `bedding` varchar(50) NOT NULL,
  `typeofroom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `room`
--

INSERT INTO `room` (`id`, `rtype`, `bedding`, `typeofroom`) VALUES
(82, 'APTS SEA VIEW', '101', ''),
(83, 'APTS SEA VIEW', '102', ''),
(84, 'APTS GARDEN VIEW', '202', ''),
(85, 'APTS GARDEN VIEW', '201', ''),
(86, 'APTS SEA VIEW', '0110', '');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `roombook`
--

CREATE TABLE `roombook` (
  `id` int(10) NOT NULL,
  `idname` varchar(50) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Noofadult` varchar(30) NOT NULL,
  `Noofchild` varchar(30) NOT NULL DEFAULT '0',
  `Noofinfant` varchar(30) NOT NULL DEFAULT '0',
  `price` int(50) NOT NULL,
  `RoomType` varchar(30) NOT NULL,
  `Bed` varchar(30) NOT NULL,
  `Meal` varchar(30) NOT NULL,
  `BeddingNumber` varchar(50) NOT NULL,
  `cin` date NOT NULL,
  `cout` date NOT NULL,
  `nodays` int(50) NOT NULL,
  `stat` varchar(30) NOT NULL,
  `din` date NOT NULL,
  `last` varchar(50) NOT NULL,
  `room_id` int(11) NOT NULL,
  `selectedType` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `roombook`
--

INSERT INTO `roombook` (`id`, `idname`, `Name`, `Noofadult`, `Noofchild`, `Noofinfant`, `price`, `RoomType`, `Bed`, `Meal`, `BeddingNumber`, `cin`, `cout`, `nodays`, `stat`, `din`, `last`, `room_id`, `selectedType`) VALUES
(90, '123526', 'Melina Douka', '1', '', '', 0, 'APTS SEA VIEW', '', '', '101', '2024-12-08', '2024-12-13', 5, 'NotConfirm', '0000-00-00', '', 0, ''),
(91, '123526', 'poo', '1', '', '', 0, 'APTS SEA VIEW', '', '', '101', '2024-12-13', '2024-12-15', 2, 'NotConfirm', '0000-00-00', '', 0, ''),
(92, '3552552', 'ii', '2', '', '', 0, 'APTS GARDEN VIEW', '', '', '201', '2024-12-19', '2024-12-26', 7, 'NotConfirm', '0000-00-00', '', 0, '');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `signup`
--

CREATE TABLE `signup` (
  `UserID` int(100) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `signup`
--

INSERT INTO `signup` (`UserID`, `Username`, `Email`, `Password`) VALUES
(1, 'irene-beach', 'irene-beach@gmail.com', '0000');

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `staff`
--

CREATE TABLE `staff` (
  `id` int(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `work` varchar(30) NOT NULL,
  `reservations` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Άδειασμα δεδομένων του πίνακα `staff`
--

INSERT INTO `staff` (`id`, `name`, `work`, `reservations`) VALUES
(1, 'Tushar pankhaniya', 'Manager', ''),
(3, 'rohit patel', 'Cook', ''),
(4, 'Dipak', 'Cook', ''),
(5, 'tirth', 'Helper', ''),
(6, 'mohan', 'Helper', ''),
(7, 'shyam', 'cleaner', ''),
(8, 'rohan', 'weighter', ''),
(9, 'hiren', 'weighter', ''),
(10, 'nikunj', 'weighter', ''),
(11, 'rekha', 'Cook', '');

--
-- Ευρετήρια για άχρηστους πίνακες
--

--
-- Ευρετήρια για πίνακα `emp_login`
--
ALTER TABLE `emp_login`
  ADD PRIMARY KEY (`empid`);

--
-- Ευρετήρια για πίνακα `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `roombook`
--
ALTER TABLE `roombook`
  ADD PRIMARY KEY (`id`);

--
-- Ευρετήρια για πίνακα `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`UserID`);

--
-- Ευρετήρια για πίνακα `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT για άχρηστους πίνακες
--

--
-- AUTO_INCREMENT για πίνακα `emp_login`
--
ALTER TABLE `emp_login`
  MODIFY `empid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT για πίνακα `room`
--
ALTER TABLE `room`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT για πίνακα `roombook`
--
ALTER TABLE `roombook`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT για πίνακα `signup`
--
ALTER TABLE `signup`
  MODIFY `UserID` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT για πίνακα `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
