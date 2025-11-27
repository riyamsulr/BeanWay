-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 27, 2025 at 09:04 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beanway`
--

-- --------------------------------------------------------

--
-- Table structure for table `beans`
--

CREATE TABLE `beans` (
  `BeanID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Shape` varchar(100) DEFAULT NULL,
  `Taste` varchar(100) DEFAULT NULL,
  `Caffeine` varchar(50) DEFAULT NULL,
  `Aroma` varchar(100) DEFAULT NULL,
  `BestBrewing` varchar(100) DEFAULT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `beans`
--

INSERT INTO `beans` (`BeanID`, `Name`, `Description`, `Shape`, `Taste`, `Caffeine`, `Aroma`, `BestBrewing`, `Image`) VALUES
(1, 'Arabica Coffee', 'The most popular type in the world—representing 60–70% of global production. Grown in high-altitude regions.', 'Oval and slightly elongated.', 'Smooth, sweet, and mildly acidic.', 'Lower compared to others.', 'Fruity, floral, sometimes nutty.', 'V60, Chemex, French Press.', 'images/arabica.jpg'),
(2, 'Robusta Coffee', 'The second most common variety, cultivated mainly in Africa and Southeast Asia. Known for its bold flavor.', 'Rounder and smaller.', 'Strong, bitter, with earthy notes.', 'Very high.', NULL, 'Espresso, Cappuccino, Latte blends.', 'images/robusta.jpg'),
(3, 'Liberica Coffee', 'A rare and distinctive coffee bean grown mostly in the Philippines and Malaysia.', NULL, 'Floral, fruity, slightly woody.', 'Moderate', 'Bold and complex.', 'Manual Brew, Cold Brew.', 'images/liberica.jpg'),
(4, 'Excelsa Coffee', 'A unique variant of the Liberica family, adds depth and fruitiness to coffee blends.', 'Small, teardrop-like beans.', 'Fruity, tangy, layered notes.', 'Low.', NULL, 'Pour Over, Blend Roasts.', 'images/excelsa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `brewing_methods`
--

CREATE TABLE `brewing_methods` (
  `MethodID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Description` text NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brewing_methods`
--

INSERT INTO `brewing_methods` (`MethodID`, `Name`, `Description`, `Image`) VALUES
(1, 'French Press', 'Rich and full-bodied coffee made by immersing grounds in hot water before pressing.', 'images/frenchpress.jpg'),
(2, 'V60 Pour Over', 'Produces a clean, balanced cup through controlled circular pouring.', 'images/v60.jpg'),
(3, 'Espresso', 'Bold, intense coffee extracted under pressure for a rich crema and flavor.', 'images/espresso.jpg'),
(4, 'Chemex', 'Elegant manual pour-over that delivers a bright, aromatic, and clear brew.', 'images/chemex.jpg'),
(5, 'Cold Brew', 'Slowly steeped in cold water for 12+ hours, yielding a sweet and smooth coffee.', 'images/coldbrew.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `RecipeID` int(11) NOT NULL,
  `TagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`RecipeID`, `TagID`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `Text` text NOT NULL,
  `Time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UserID` int(11) NOT NULL,
  `RecipeID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`CommentID`, `Text`, `Time`, `UserID`, `RecipeID`) VALUES
(1, 'Just made this, it was perfect! Thanks for the tip about fresh beans.', '2025-10-21 09:30:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `recipe`
--

CREATE TABLE `recipe` (
  `RecipeID` int(11) NOT NULL,
  `Title` varchar(100) NOT NULL,
  `Time` int(11) NOT NULL,
  `Servings` int(11) NOT NULL,
  `Calories` int(11) NOT NULL,
  `Taste` varchar(100) NOT NULL,
  `Ingredients` text NOT NULL,
  `Steps` text NOT NULL,
  `Tip` varchar(255) DEFAULT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `AdminFeedback` text,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recipe`
--

INSERT INTO `recipe` (`RecipeID`, `Title`, `Time`, `Servings`, `Calories`, `Taste`, `Ingredients`, `Steps`, `Tip`, `Image`, `Status`, `AdminFeedback`, `UserID`) VALUES
(1, 'Classic Cappuccino', 7, 1, 150, 'Rich, foamy, and balanced', '30 ml espresso\n100 ml steamed milk\nMilk foam topping\nPinch of cocoa powder (optional)', '1. Brew an espresso shot (30 ml) into a small cup.\n2. Steam milk to around 65°C using a frother or steamer.\n3. Pour steamed milk gently over espresso.\n4. Spoon the milk foam on top for a thick layer.\n5. Sprinkle cocoa powder or cinnamon if desired.', 'Use freshly ground coffee beans for a rich flavor and creamy foam.', 'images/cappuccino.jpg', 'approved', NULL, 1),
(2, 'موكا', 4, 2, 3, 'حالي', 'حليب وسكر وكاكاو', 'اخفق القهوه مع الكاكاو ثم اضيف الحليبkkk', '', 'images/1764272281_mocha.jpg', 'rejected', 'hki', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `TagID` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`TagID`, `Name`) VALUES
(1, 'espresso');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `UserID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Name`, `Email`, `Password`, `Role`) VALUES
(1, 'Laila M.', 'laila@gmail.com', 'hashed_password_placeholder', 'user'),
(2, 'leena', 'leena@gmail.com', '$2y$10$aECb/EzLcrtC.1F55wMjLec/utzeObF7w2UFWV5DScgPm/eyTZ91e', 'user'),
(3, 'Admin', 'admin@beanway.com', '$2y$10$aECb/EzLcrtC.1F55wMjLec/utzeObF7w2UFWV5DScgPm/eyTZ91e', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `beans`
--
ALTER TABLE `beans`
  ADD PRIMARY KEY (`BeanID`);

--
-- Indexes for table `brewing_methods`
--
ALTER TABLE `brewing_methods`
  ADD PRIMARY KEY (`MethodID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD KEY `RecipeID` (`RecipeID`),
  ADD KEY `TagID` (`TagID`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `RecipeID` (`RecipeID`);

--
-- Indexes for table `recipe`
--
ALTER TABLE `recipe`
  ADD PRIMARY KEY (`RecipeID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`TagID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `beans`
--
ALTER TABLE `beans`
  MODIFY `BeanID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `brewing_methods`
--
ALTER TABLE `brewing_methods`
  MODIFY `MethodID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recipe`
--
ALTER TABLE `recipe`
  MODIFY `RecipeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `TagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`RecipeID`) REFERENCES `recipe` (`RecipeID`),
  ADD CONSTRAINT `category_ibfk_2` FOREIGN KEY (`TagID`) REFERENCES `tag` (`TagID`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`RecipeID`) REFERENCES `recipe` (`RecipeID`);

--
-- Constraints for table `recipe`
--
ALTER TABLE `recipe`
  ADD CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `user` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
