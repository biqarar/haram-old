<?php 

$database_change = array(
			//-----------------------------------------------------------------------------
			"ALTER TABLE `price`  ADD `date` INT(8) NOT NULL  AFTER `users_id`",
			"ALTER TABLE `price` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_persian_ci NULL",
			"ALTER TABLE `price` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `price` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			"ALTER TABLE `price` ADD CONSTRAINT `price_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)",
			"ALTER TABLE `price` ADD `title` ENUM('deposit','reference','block','use_in_classes') NOT NULL AFTER `pay_type`",
			"CREATE TABLE IF NOT EXISTS `price_change` (
			`id` int(10) NOT NULL,
			  `name` varchar(64) COLLATE utf8_persian_ci NOT NULL,
			  `type` enum('price_add','price_low') COLLATE utf8_persian_ci NOT NULL,
			  `branch_id` int(10) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1",
			"ALTER TABLE `price_change`  ADD PRIMARY KEY (`id`)",
			"ALTER TABLE `price_change` MODIFY `id` int(10) NOT NULL AUTO_INCREMENT",
			"ALTER TABLE `price_change` ADD CONSTRAINT `price_change_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",
			"ALTER TABLE `price` DROP `type`",
			"ALTER TABLE `price` CHANGE `title` `title` INT(10) NOT NULL",
			"ALTER TABLE `price` ADD CONSTRAINT `price_ibfk_2` FOREIGN KEY (`title`) REFERENCES `price_change` (`id`)",
			"DROP TRIGGER IF EXISTS `price_change_delete`",
			"CREATE TRIGGER `price_change_delete` AFTER DELETE ON `price_change`
			 FOR EACH ROW BEGIN
			call setHistory('price_change', 'delete', OLD.id);
			END",
			"DROP TRIGGER IF EXISTS `price_change_insert`",
			"CREATE TRIGGER `price_change_insert` AFTER INSERT ON `price_change`
			 FOR EACH ROW BEGIN
			call setCash('price_change', NEW.id, @branch_id);
			call setHistory('price_change', 'insert', NEW.id);
			END",
			"DROP TRIGGER IF EXISTS `price_change_update`",
			"CREATE TRIGGER `price_change_update` AFTER UPDATE ON `price_change`
			 FOR EACH ROW BEGIN
			call setHistory('price_change', 'update', OLD.id);
			END",
			"ALTER TABLE `price` CHANGE `pay_type` `pay_type` ENUM('bank','pos_mellat','cash','rule','pos_melli') CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL",
			"ALTER TABLE `price` ADD `card` INT(7) NOT NULL AFTER `title`",
			//---------------- new tables
			"CREATE TABLE IF NOT EXISTS `score_type` (
			  `id` int(10) NOT NULL,
			  `plan_id` int(10) NOT NULL,
			  `title` varchar(255) COLLATE utf32_persian_ci NOT NULL,
			  `min` int(2) NOT NULL,
			  `max` int(3) NOT NULL,
			  `description` varchar(255) COLLATE utf32_persian_ci DEFAULT NULL
			  ) ENGINE=InnoDB  DEFAULT CHARSET=utf32 COLLATE=utf32_persian_ci  AUTO_INCREMENT=1",

			"ALTER TABLE `score_type` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `score_type` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",

			"ALTER TABLE `score_type` ADD CONSTRAINT `score_type_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`)",
			
			"DROP TRIGGER IF EXISTS `score_type_delete`",
			"CREATE TRIGGER `score_type_delete` AFTER DELETE ON `score_type`
			 FOR EACH ROW BEGIN
			call setHistory('score_type', 'delete', OLD.id);
			END",
			"DROP TRIGGER IF EXISTS `score_type_insert`",
			"CREATE TRIGGER `score_type_insert` AFTER INSERT ON `score_type`
			 FOR EACH ROW BEGIN
			call setCash('score_type', NEW.id, @branch_id);
			call setHistory('score_type', 'insert', NEW.id);
			END",
			"DROP TRIGGER IF EXISTS `score_type_update`",
			"CREATE TRIGGER `score_type_update` AFTER UPDATE ON `score_type`
			 FOR EACH ROW BEGIN
			call setHistory('score_type', 'update', OLD.id);
			END",
			
			"CREATE TABLE IF NOT EXISTS `score_calculation` (
			  `id` int(10) NOT NULL,
			  `plan_id` int(10) NOT NULL,
			  `calculation` text COLLATE utf32_persian_ci NOT NULL,
			  `status` enum('active','desactive') COLLATE utf32_persian_ci DEFAULT NULL,
			  `description` varchar(255) COLLATE utf32_persian_ci DEFAULT NULL
			  ) ENGINE=InnoDB  DEFAULT CHARSET=utf32 COLLATE=utf32_persian_ci  AUTO_INCREMENT=1",

			"ALTER TABLE `score_calculation` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `score_calculation` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",

			"ALTER TABLE `score_calculation` ADD CONSTRAINT `score_calculation_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`)",
			
			"DROP TRIGGER IF EXISTS `score_calculation_delete`",
			"CREATE TRIGGER `score_calculation_delete` AFTER DELETE ON `score_calculation`
			 FOR EACH ROW BEGIN
			call setHistory('score_calculation', 'delete', OLD.id);
			END",
			"DROP TRIGGER IF EXISTS `score_calculation_insert`",
			"CREATE TRIGGER `score_calculation_insert` AFTER INSERT ON `score_calculation`
			 FOR EACH ROW BEGIN
			call setCash('score_calculation', NEW.id, @branch_id);
			call setHistory('score_calculation', 'insert', NEW.id);
			END",
			"DROP TRIGGER IF EXISTS `score_calculation_update`",
			"CREATE TRIGGER `score_calculation_update` AFTER UPDATE ON `score_calculation`
			 FOR EACH ROW BEGIN
			call setHistory('score_calculation', 'update', OLD.id);
			END",

			"CREATE TABLE IF NOT EXISTS `score` (
			  `id` int(10) NOT NULL,
			  `users_id` int(10) NOT NULL,
			  `classes_id` int(10) NOT NULL,
			  `score_type_id` int(10) NOT NULL,
			  `value` int(3) NOT NULL
			  ) ENGINE=InnoDB  DEFAULT CHARSET=utf32 COLLATE=utf32_persian_ci  AUTO_INCREMENT=1",

			"ALTER TABLE `score` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `score` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",

			"ALTER TABLE `score` ADD CONSTRAINT `score_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)",
			"ALTER TABLE `score` ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`classes_id`) REFERENCES `classes` (`id`)",
			"ALTER TABLE `score` ADD CONSTRAINT `score_ibfk_3` FOREIGN KEY (`score_type_id`) REFERENCES `score_type` (`id`)",
			
			"DROP TRIGGER IF EXISTS `score_delete`",
			"CREATE TRIGGER `score_delete` AFTER DELETE ON `score`
			 FOR EACH ROW BEGIN
			call setHistory('score', 'delete', OLD.id);
			END",
			"DROP TRIGGER IF EXISTS `score_insert`",
			"CREATE TRIGGER `score_insert` AFTER INSERT ON `score`
			 FOR EACH ROW BEGIN
			call setCash('score', NEW.id, @branch_id);
			call setHistory('score', 'insert', NEW.id);
			END",
			"DROP TRIGGER IF EXISTS `score_update`",
			"CREATE TRIGGER `score_update` AFTER UPDATE ON `score`
			 FOR EACH ROW BEGIN
			call setHistory('score', 'update', OLD.id);
			END",

			
			//----------- end of new table

			"ALTER TABLE `plan` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'نام طرح',"
			);
 ?>