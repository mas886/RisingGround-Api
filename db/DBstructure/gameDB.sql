-- phpMyAdmin SQL Dump
-- version 4.6.4deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 01, 2017 at 10:29 AM
-- Server version: 10.0.29-MariaDB-0ubuntu0.16.10.1
-- PHP Version: 7.0.15-0ubuntu0.16.10.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gameDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `battle_status`
--

CREATE TABLE `battle_status` (
  `characterId` int(12) NOT NULL,
  `restUntil` datetime NOT NULL,
  `inBattle` tinyint(1) NOT NULL DEFAULT '0',
  `reward` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Control user rest times and battle rewards.';

-- --------------------------------------------------------

--
-- Table structure for table `character_build`
--

CREATE TABLE `character_build` (
  `id` int(20) NOT NULL,
  `characterId` int(12) NOT NULL,
  `monster1` int(18) NOT NULL,
  `monster2` int(18) DEFAULT NULL,
  `monster3` int(18) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `character_item`
--

CREATE TABLE `character_item` (
  `characterId` int(12) NOT NULL,
  `itemId` int(5) NOT NULL,
  `amount` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='This is the inventory';

-- --------------------------------------------------------

--
-- Table structure for table `character_monster`
--

CREATE TABLE `character_monster` (
  `characterId` int(12) NOT NULL,
  `monsterId` int(5) NOT NULL,
  `id` int(18) NOT NULL,
  `experience` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='This contains the monsters owned by characters.';

-- --------------------------------------------------------

--
-- Table structure for table `character_monster_stats`
--

CREATE TABLE `character_monster_stats` (
  `characterMonsterId` int(5) NOT NULL,
  `accuracy` int(11) DEFAULT '0',
  `speed` int(11) NOT NULL DEFAULT '0',
  `strength` int(11) NOT NULL DEFAULT '0',
  `vitality` int(11) NOT NULL DEFAULT '0',
  `defence` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dungeon`
--

CREATE TABLE `dungeon` (
  `id` int(5) NOT NULL,
  `name` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `minLevel` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Dungeon list.';

-- --------------------------------------------------------

--
-- Table structure for table `dungeon_character_status`
--

CREATE TABLE `dungeon_character_status` (
  `dungeonId` int(5) NOT NULL,
  `characterId` int(12) NOT NULL,
  `levelId` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Know which stage of the dungeon the character is.';

-- --------------------------------------------------------

--
-- Table structure for table `dungeon_level`
--

CREATE TABLE `dungeon_level` (
  `id` int(5) NOT NULL,
  `dungeonId` int(5) NOT NULL,
  `name` varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='List of the dungeon sub-levels.';

-- --------------------------------------------------------

--
-- Table structure for table `dungeon_level_stages`
--

CREATE TABLE `dungeon_level_stages` (
  `id` int(5) NOT NULL,
  `dungeonLevelId` int(5) NOT NULL,
  `number` int(3) NOT NULL,
  `content` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Stages/Phases or "screens" of each dungeon level.';

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `item` (
  `id` int(5) NOT NULL,
  `name` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `properties` text COLLATE utf8_spanish_ci NOT NULL,
  `category` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `sprite` varchar(90) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Item table.';

-- --------------------------------------------------------

--
-- Table structure for table `monster`
--

CREATE TABLE `monster` (
  `id` int(5) NOT NULL,
  `name` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `sprite` varchar(90) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Monster table.';

--
-- Dumping data for table `monster`
--

INSERT INTO `monster` (`id`, `name`, `description`, `sprite`) VALUES
(1, 'Lucio', 'This is a sample monster, say hi to him, he\'s willing to cooperate with the development.', '/anywhere.png'),
(2, 'Udong', 'The second test subject, he wants to be as helpfull as the first.', '/nothingv2.jpg'),
(3, 'Fierro', 'Third monster to help with.', '/unespecified.png');

-- --------------------------------------------------------

--
-- Table structure for table `monster_stats`
--

CREATE TABLE `monster_stats` (
  `monsterId` int(5) NOT NULL,
  `accuracy` int(8) NOT NULL,
  `speed` int(8) NOT NULL,
  `strength` int(8) NOT NULL,
  `vitality` int(8) NOT NULL,
  `defence` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Monster base stats';

--
-- Dumping data for table `monster_stats`
--

INSERT INTO `monster_stats` (`monsterId`, `accuracy`, `speed`, `strength`, `vitality`, `defence`) VALUES
(1, 10, 10, 10, 40, 10),
(2, 12, 8, 11, 36, 9),
(3, 7, 13, 9, 46, 9);

-- --------------------------------------------------------

--
-- Table structure for table `shop_gems`
--

CREATE TABLE `shop_gems` (
  `id` int(5) NOT NULL,
  `itemId` int(5) NOT NULL,
  `discount` int(3) NOT NULL,
  `amount` int(5) NOT NULL,
  `sprite` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `value` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Gem shop.';

-- --------------------------------------------------------

--
-- Table structure for table `shop_gold`
--

CREATE TABLE `shop_gold` (
  `id` int(5) NOT NULL,
  `itemId` int(5) NOT NULL,
  `discount` int(3) NOT NULL DEFAULT '0',
  `amount` int(5) NOT NULL,
  `sprite` varchar(90) COLLATE utf8_spanish_ci NOT NULL,
  `value` int(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Gold shop.';

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(9) NOT NULL,
  `name` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `gold` int(9) NOT NULL DEFAULT '500',
  `gems` int(9) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `characterSlots` int(3) NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contains the users.';

-- --------------------------------------------------------

--
-- Table structure for table `user_character`
--

CREATE TABLE `user_character` (
  `id` int(12) NOT NULL,
  `name` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `experience` int(30) NOT NULL DEFAULT '0',
  `buildSlots` int(4) NOT NULL DEFAULT '3',
  `amulet` int(5) DEFAULT NULL,
  `userId` int(9) NOT NULL,
  `buildId` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contais all characters owned by users.';

-- --------------------------------------------------------

--
-- Table structure for table `user_game_inbox`
--

CREATE TABLE `user_game_inbox` (
  `userId` int(9) NOT NULL,
  `nameSender` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  `id` int(30) NOT NULL,
  `sendDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contains the messages sentby the internal messaging system (System to player).';

-- --------------------------------------------------------

--
-- Table structure for table `user_inbox`
--

CREATE TABLE `user_inbox` (
  `userSendId` int(9) NOT NULL,
  `userReceiveId` int(9) NOT NULL,
  `id` int(20) NOT NULL,
  `sendDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contains the messages sent between users (User <--> User).';

-- --------------------------------------------------------

--
-- Table structure for table `user_login_tokens`
--

CREATE TABLE `user_login_tokens` (
  `userId` int(9) NOT NULL,
  `token` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `expireDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci COMMENT='Contains user login tokens.';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `battle_status`
--
ALTER TABLE `battle_status`
  ADD PRIMARY KEY (`characterId`),
  ADD KEY `characterId` (`characterId`);

--
-- Indexes for table `character_build`
--
ALTER TABLE `character_build`
  ADD PRIMARY KEY (`id`),
  ADD KEY `characterId` (`characterId`),
  ADD KEY `monster1` (`monster1`),
  ADD KEY `monster2` (`monster2`),
  ADD KEY `monster3` (`monster3`);

--
-- Indexes for table `character_item`
--
ALTER TABLE `character_item`
  ADD PRIMARY KEY (`characterId`,`itemId`),
  ADD KEY `characterId` (`characterId`,`itemId`),
  ADD KEY `characterId_2` (`characterId`,`itemId`),
  ADD KEY `itemId` (`itemId`);

--
-- Indexes for table `character_monster`
--
ALTER TABLE `character_monster`
  ADD PRIMARY KEY (`id`),
  ADD KEY `characterId` (`characterId`),
  ADD KEY `monsterId` (`monsterId`);

--
-- Indexes for table `character_monster_stats`
--
ALTER TABLE `character_monster_stats`
  ADD PRIMARY KEY (`characterMonsterId`);

--
-- Indexes for table `dungeon`
--
ALTER TABLE `dungeon`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dungeon_character_status`
--
ALTER TABLE `dungeon_character_status`
  ADD PRIMARY KEY (`dungeonId`,`characterId`),
  ADD KEY `dungeonId` (`dungeonId`),
  ADD KEY `characterId` (`characterId`),
  ADD KEY `level` (`levelId`);

--
-- Indexes for table `dungeon_level`
--
ALTER TABLE `dungeon_level`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dungeonId` (`dungeonId`);

--
-- Indexes for table `dungeon_level_stages`
--
ALTER TABLE `dungeon_level_stages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dungeonLevelId` (`dungeonLevelId`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monster`
--
ALTER TABLE `monster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monster_stats`
--
ALTER TABLE `monster_stats`
  ADD PRIMARY KEY (`monsterId`);

--
-- Indexes for table `shop_gems`
--
ALTER TABLE `shop_gems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `itemId` (`itemId`);

--
-- Indexes for table `shop_gold`
--
ALTER TABLE `shop_gold`
  ADD PRIMARY KEY (`id`),
  ADD KEY `itemId` (`itemId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `user_character`
--
ALTER TABLE `user_character`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`),
  ADD KEY `amulet` (`amulet`),
  ADD KEY `selectedBuildId` (`buildId`);

--
-- Indexes for table `user_game_inbox`
--
ALTER TABLE `user_game_inbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `user_inbox`
--
ALTER TABLE `user_inbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userSendId` (`userSendId`),
  ADD KEY `userRecieveId` (`userReceiveId`);

--
-- Indexes for table `user_login_tokens`
--
ALTER TABLE `user_login_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `user_id` (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `character_build`
--
ALTER TABLE `character_build`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `character_monster`
--
ALTER TABLE `character_monster`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dungeon`
--
ALTER TABLE `dungeon`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dungeon_level`
--
ALTER TABLE `dungeon_level`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `dungeon_level_stages`
--
ALTER TABLE `dungeon_level_stages`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `monster`
--
ALTER TABLE `monster`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `shop_gems`
--
ALTER TABLE `shop_gems`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `shop_gold`
--
ALTER TABLE `shop_gold`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_character`
--
ALTER TABLE `user_character`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_game_inbox`
--
ALTER TABLE `user_game_inbox`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_inbox`
--
ALTER TABLE `user_inbox`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `battle_status`
--
ALTER TABLE `battle_status`
  ADD CONSTRAINT `battle_status_ibfk_1` FOREIGN KEY (`characterId`) REFERENCES `user_character` (`id`);

--
-- Constraints for table `character_build`
--
ALTER TABLE `character_build`
  ADD CONSTRAINT `character_build_ibfk_1` FOREIGN KEY (`characterId`) REFERENCES `user_character` (`id`),
  ADD CONSTRAINT `character_build_ibfk_2` FOREIGN KEY (`monster1`) REFERENCES `character_monster` (`id`),
  ADD CONSTRAINT `character_build_ibfk_3` FOREIGN KEY (`monster2`) REFERENCES `character_monster` (`id`),
  ADD CONSTRAINT `character_build_ibfk_4` FOREIGN KEY (`monster3`) REFERENCES `character_monster` (`id`);

--
-- Constraints for table `character_item`
--
ALTER TABLE `character_item`
  ADD CONSTRAINT `character_item_ibfk_1` FOREIGN KEY (`characterId`) REFERENCES `user_character` (`id`),
  ADD CONSTRAINT `character_item_ibfk_2` FOREIGN KEY (`itemId`) REFERENCES `items` (`id`);

--
-- Constraints for table `character_monster`
--
ALTER TABLE `character_monster`
  ADD CONSTRAINT `character_monster_ibfk_1` FOREIGN KEY (`characterId`) REFERENCES `user_character` (`id`),
  ADD CONSTRAINT `character_monster_ibfk_2` FOREIGN KEY (`monsterId`) REFERENCES `monster` (`id`);

--
-- Constraints for table `character_monster_stats`
--
ALTER TABLE `character_monster_stats`
  ADD CONSTRAINT `character_monster_stats_ibfk_1` FOREIGN KEY (`characterMonsterId`) REFERENCES `character_monster` (`id`);

--
-- Constraints for table `dungeon_character_status`
--
ALTER TABLE `dungeon_character_status`
  ADD CONSTRAINT `dungeon_character_status_ibfk_1` FOREIGN KEY (`dungeonId`) REFERENCES `dungeon` (`id`),
  ADD CONSTRAINT `dungeon_character_status_ibfk_2` FOREIGN KEY (`characterId`) REFERENCES `user_character` (`id`),
  ADD CONSTRAINT `dungeon_character_status_ibfk_3` FOREIGN KEY (`levelId`) REFERENCES `dungeon_level` (`id`);

--
-- Constraints for table `dungeon_level`
--
ALTER TABLE `dungeon_level`
  ADD CONSTRAINT `dungeon_level_ibfk_1` FOREIGN KEY (`dungeonId`) REFERENCES `dungeon` (`id`);

--
-- Constraints for table `dungeon_level_stages`
--
ALTER TABLE `dungeon_level_stages`
  ADD CONSTRAINT `dungeon_level_stages_ibfk_1` FOREIGN KEY (`dungeonLevelId`) REFERENCES `dungeon_level` (`id`);

--
-- Constraints for table `monster_stats`
--
ALTER TABLE `monster_stats`
  ADD CONSTRAINT `monster_stats_ibfk_1` FOREIGN KEY (`monsterId`) REFERENCES `monster` (`id`);

--
-- Constraints for table `shop_gems`
--
ALTER TABLE `shop_gems`
  ADD CONSTRAINT `shop_gems_ibfk_1` FOREIGN KEY (`itemId`) REFERENCES `items` (`id`);

--
-- Constraints for table `shop_gold`
--
ALTER TABLE `shop_gold`
  ADD CONSTRAINT `shop_gold_ibfk_1` FOREIGN KEY (`itemId`) REFERENCES `items` (`id`);

--
-- Constraints for table `user_character`
--
ALTER TABLE `user_character`
  ADD CONSTRAINT `user_character_ibfk_1` FOREIGN KEY (`amulet`) REFERENCES `character_item` (`itemId`),
  ADD CONSTRAINT `user_character_ibfk_2` FOREIGN KEY (`buildId`) REFERENCES `character_build` (`id`),
  ADD CONSTRAINT `user_character_ibfk_3` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_game_inbox`
--
ALTER TABLE `user_game_inbox`
  ADD CONSTRAINT `user_game_inbox_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_inbox`
--
ALTER TABLE `user_inbox`
  ADD CONSTRAINT `user_inbox_ibfk_1` FOREIGN KEY (`userSendId`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `user_inbox_ibfk_2` FOREIGN KEY (`userReceiveId`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_login_tokens`
--
ALTER TABLE `user_login_tokens`
  ADD CONSTRAINT `user_login_tokens_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
