<?php
		$database_change = array(
			// "ALTER TABLE `users`  ADD `branch_id` INT(10) NULL",
			// "ALTER TABLE `users` ADD CONSTRAINT `users_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",

			// "ALTER TABLE `classes`  ADD `branch_id` INT(10) NULL",
			// "ALTER TABLE `classes` ADD CONSTRAINT `classes_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",

			// "ALTER TABLE `price`  ADD `branch_id` INT(10) NULL",
			// "ALTER TABLE `price` ADD CONSTRAINT `price_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",

			// "ALTER TABLE `plan`  ADD `branch_id` INT(10) NULL",
			// "ALTER TABLE `plan` ADD CONSTRAINT `plan_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",

			// "ALTER TABLE `group`  ADD `branch_id` INT(10) NULL",
			// "ALTER TABLE `group` ADD CONSTRAINT `group_branch_id_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branch` (`id`)",

			// //---------------------------------------------------------

			// "update users as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'users' 
			// set us.branch_id = br.branch_id",


			// "update classes as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'classes' 
			// set us.branch_id = br.branch_id",


			// "update place as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'place' 
			// set us.branch_id = br.branch_id",


			// "update price as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'price' 
			// set us.branch_id = br.branch_id",


			// "update plan as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'plan' 
			// set us.branch_id = br.branch_id",


			// "update course as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'course' 
			// set us.branch_id = br.branch_id",


			// "update `group` as us 
			// inner join branch_cash as br on us.id = br.record_id and br.table = 'group' 
			// set us.branch_id = br.branch_id",


			// "ALTER TABLE `users`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",

			// "ALTER TABLE `classes`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",

			// "ALTER TABLE `price`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",

			// "ALTER TABLE `plan`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",

			// "ALTER TABLE `group`  CHANGE `branch_id`  `branch_id` INT(10) NOT NULL",


			//-----------------------------------------------------------------------

			

			"CREATE TABLE IF NOT EXISTS `nezarat_item` (
			  `id` int(10) NOT NULL,
			  `title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
			  `validation` enum('number','text') COLLATE utf8_persian_ci NOT NULL,
			  `group` enum('مالی','ارزیابی','عملکرد','نظرسنجی','خود ارزیابی','شناسه ای','آسیب ها و مشکلات') COLLATE utf8_persian_ci DEFAULT NULL,
			  `description` text COLLATE utf8_persian_ci
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",

	"ALTER TABLE `nezarat_item`
			  ADD PRIMARY KEY (`id`);",
			  	"ALTER TABLE `nezarat_item`
			  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;",


			"DROP TRIGGER IF EXISTS `nezarat_item_delete`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_item_delete` AFTER DELETE ON `nezarat_item`
			 FOR EACH ROW BEGIN
			call setHistory('nezarat_item', 'delete', OLD.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `nezarat_item_insert`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_item_insert` AFTER INSERT ON `nezarat_item`
			 FOR EACH ROW BEGIN
			call setCash('nezarat_item', NEW.id, @branch_id);
			call setHistory('nezarat_item', 'insert', NEW.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `nezarat_item_update`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_item_update` AFTER UPDATE ON `nezarat_item`
			 FOR EACH ROW BEGIN
			call setHistory('nezarat_item', 'update', OLD.id);
			END",


			"CREATE TABLE IF NOT EXISTS `nezarat_program` (
			  `id` int(10) NOT NULL,
			  `title` varchar(255) COLLATE utf8_persian_ci NOT NULL,
			  `parent` int(10) NOT NULL,
			  `description` text COLLATE utf8_persian_ci
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",


			"ALTER TABLE `nezarat_program`
			  ADD PRIMARY KEY (`id`);",
			"ALTER TABLE `nezarat_program`
			  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;",

			"ALTER TABLE  `nezarat_program` CHANGE  `parent`  `parent` INT( 10 ) NULL DEFAULT NULL ;",
			
			"DROP TRIGGER IF EXISTS `nezarat_program_delete`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_program_delete` AFTER DELETE ON `nezarat_program`
			 FOR EACH ROW BEGIN
			call setHistory('nezarat_program', 'delete', OLD.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `nezarat_program_insert`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_program_insert` AFTER INSERT ON `nezarat_program`
			 FOR EACH ROW BEGIN
			call setCash('nezarat_program', NEW.id, @branch_id);
			call setHistory('nezarat_program', 'insert', NEW.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `nezarat_program_update`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_program_update` AFTER UPDATE ON `nezarat_program`
			 FOR EACH ROW BEGIN
			call setHistory('nezarat_program', 'update', OLD.id);
			END",

			"CREATE TABLE IF NOT EXISTS `nezarat_program_item` (
			  `id` int(10) NOT NULL,
			  `nezarat_program_id` int(10) NOT NULL,
			  `nezarat_item_id` int(10) NOT NULL,
			  `value` text COLLATE utf8_persian_ci,
			  `users_id` int(10) DEFAULT NULL,
			  `description` text COLLATE utf8_persian_ci
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;",


			"ALTER TABLE `nezarat_program_item`
			  ADD PRIMARY KEY (`id`),
			  ADD KEY `nezarat_program_id` (`nezarat_program_id`),
			  ADD KEY `nezarat_item_id` (`nezarat_item_id`);",


		

		

			"ALTER TABLE `nezarat_program_item`
			  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;",


			"DROP TRIGGER IF EXISTS `nezarat_program_item_delete`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_program_item_delete` AFTER DELETE ON `nezarat_program_item`
			 FOR EACH ROW BEGIN
			call setHistory('nezarat_program_item', 'delete', OLD.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `nezarat_program_item_insert`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_program_item_insert` AFTER INSERT ON `nezarat_program_item`
			 FOR EACH ROW BEGIN
			call setCash('nezarat_program_item', NEW.id, @branch_id);
			call setHistory('nezarat_program_item', 'insert', NEW.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `nezarat_program_item_update`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `nezarat_program_item_update` AFTER UPDATE ON `nezarat_program_item`
			 FOR EACH ROW BEGIN
			call setHistory('nezarat_program_item', 'update', OLD.id);
			END",

		





			"ALTER TABLE `nezarat_program_item`
			  ADD CONSTRAINT `nezarat_program_item_ibfk_1` FOREIGN KEY (`nezarat_program_id`) REFERENCES `nezarat_program` (`id`),
			  ADD CONSTRAINT `nezarat_program_item_ibfk_2` FOREIGN KEY (`nezarat_item_id`) REFERENCES `nezarat_item` (`id`);"

			//-----------------------------------------------------------------------------
						

		);
?>