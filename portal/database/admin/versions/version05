<?php 
$database_change = array(

			"CREATE TABLE IF NOT EXISTS `report` (
			`id` int(10) NOT NULL,
			  `tables` varchar(64) NOT NULL,
			  `name` varchar(255) NOT NULL,
			  `url` varchar(255) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `report` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `report` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `report_delete`",

			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `report_delete` AFTER DELETE ON `report`
			 FOR EACH ROW BEGIN
			call setHistory('report', 'delete', OLD.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `report_insert`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `report_insert` AFTER INSERT ON `report`
			 FOR EACH ROW BEGIN
			call setCash('report', NEW.id, @branch_id);
			call setHistory('report', 'insert', NEW.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `report_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `report_update` AFTER UPDATE ON `report`
			 FOR EACH ROW BEGIN
			call setHistory('report', 'update', OLD.id);
			END",


			"CREATE TABLE IF NOT EXISTS `drafts` (
			  `id` int(10) NOT NULL,
			  `group` enum('classes', 'classification', 'score', 'person','absence') COLLATE utf32_persian_ci NOT NULL,
			  `tag` varchar(255) NOT NULL,
			  `text` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `drafts` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `drafts` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `drafts_delete`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `drafts_delete` AFTER DELETE ON `drafts`
			 FOR EACH ROW BEGIN
			call setHistory('drafts', 'delete', OLD.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `drafts_insert`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `drafts_insert` AFTER INSERT ON `drafts`
			 FOR EACH ROW BEGIN
			call setCash('drafts', NEW.id, @branch_id);
			call setHistory('drafts', 'insert', NEW.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `drafts_update`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `drafts_update` AFTER UPDATE ON `drafts`
			 FOR EACH ROW BEGIN
			call setHistory('drafts', 'update', OLD.id);
			END",


			"CREATE TABLE IF NOT EXISTS `sms_log` (
			  `id` int(10) NOT NULL,
			  `sender` int(10) NOT NULL,
			  `text` int(10) NOT NULL,
			  `reciver` int(10) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `sms_log` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `sms_log` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",

			"ALTER TABLE `sms_log` ADD CONSTRAINT `sms_log_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `users` (`id`)",
			"ALTER TABLE `sms_log` ADD CONSTRAINT `sms_log_ibfk_2` FOREIGN KEY (`reciver`) REFERENCES `users` (`id`)",
			"ALTER TABLE `sms_log` ADD CONSTRAINT `sms_log_ibfk_3` FOREIGN KEY (`text`) REFERENCES `drafts` (`id`)",

			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `sms_log_delete`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `sms_log_delete` AFTER DELETE ON `sms_log`
			 FOR EACH ROW BEGIN
			call setHistory('sms_log', 'delete', OLD.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `sms_log_insert`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `sms_log_insert` AFTER INSERT ON `sms_log`
			 FOR EACH ROW BEGIN
			call setHistory('sms_log', 'insert', NEW.id);
			END",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `sms_log_update`",
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `sms_log_update` AFTER UPDATE ON `sms_log`
			 FOR EACH ROW BEGIN
			call setHistory('sms_log', 'update', OLD.id);
			END",

			
			"CREATE DEFINER=`root`@`localhost` PROCEDURE `set_sms_log`(IN `_sender` INT(10), IN `_reciver` INT(10), IN `_text` INT(10)) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
			INSERT INTO sms_log 
			(sender,reciver,sms_log.text)
			VALUES
			(_sender, _reciver, _text);
			END",

						/////////////////////////////////////////////////////
			"CREATE TABLE IF NOT EXISTS `file_tag` (
			`id` int(10) NOT NULL,
			`tag` varchar(64) NOT NULL,
			`table` enum('users','posts','plan') NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `file_tag` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `file_tag` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_tag_delete`",

			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_tag_delete` AFTER DELETE ON `file_tag`
			 FOR EACH ROW BEGIN
			call setHistory('file_tag', 'delete', OLD.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_tag_insert`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_tag_insert` AFTER INSERT ON `file_tag`
			 FOR EACH ROW BEGIN
			call setHistory('file_tag', 'insert', NEW.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_tag_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_tag_update` AFTER UPDATE ON `file_tag`
			 FOR EACH ROW BEGIN
			call setHistory('file_tag', 'update', OLD.id);
			END",

			////////////////////////////////////////////////////
			"TRUNCATE table_files",
			"DROP TABLE `table_files`",
			"DROP TABLE `files`",
			"CREATE TABLE IF NOT EXISTS `files` (
			`id` int(10) NOT NULL,
			`title` varchar(255) NOT NULL,
			`size` float NOT NULL,
			`type` varchar(6) NOT NULL,
			`folder` int(4) NOT NULL,
			`file_tag_id` int(10) NOT NULL,
			`description` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `files` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `files` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			"ALTER TABLE `files` ADD CONSTRAINT `files_ibfk_2` FOREIGN KEY (`file_tag_id`) REFERENCES `file_tag` (`id`)",

			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `files_delete`",

			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `files_delete` AFTER DELETE ON `files`
			 FOR EACH ROW BEGIN
			call setHistory('files', 'delete', OLD.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `files_insert`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `files_insert` AFTER INSERT ON `files`
			 FOR EACH ROW BEGIN
			call setCash('files', NEW.id, @branch_id);
			call setHistory('files', 'insert', NEW.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `files_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `files_update` AFTER UPDATE ON `files`
			 FOR EACH ROW BEGIN
			call setHistory('files', 'update', OLD.id);
			END",

			"CREATE TRIGGER `set_folder` BEFORE INSERT ON `files`
				 FOR EACH ROW BEGIN
				SET NEW.folder =1000 + CEILING((SELECT AUTO_INCREMENT FROM
				 information_schema.TABLES WHERE TABLE_SCHEMA
				 =DATABASE() AND TABLE_NAME = 'files')/1000);
				END",

			////////////////////////////////////////////////////
			"CREATE TABLE IF NOT EXISTS `update_log` (
			  `id` int(10) NOT NULL,
			  `users_id` int(10) NOT NULL,
			  `table` varchar(255) NOT NULL,
			  `field` varchar(64) NOT NULL,
			  `record_id` int(10) NOT NULL,
			  `old_value` text NOT NULL,
			  `new_value` text NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `update_log` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `update_log` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",

			"ALTER TABLE `update_log` ADD CONSTRAINT `update_log_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)",
			"ALTER TABLE `update_log` ADD `time` TIMESTAMP NOT NULL ",



			////////////////////////////////////////////////////

			"CREATE TABLE IF NOT EXISTS `file_user` (
			`id` int(10) NOT NULL,
			`file_id` int(10) NOT NULL,
			`users_id` int(10) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `file_user` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `file_user` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			"ALTER TABLE `file_user` ADD CONSTRAINT `file_user_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`)",
			"ALTER TABLE `file_user` ADD CONSTRAINT `file_user_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`)",

			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_user_delete`",

			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_user_delete` AFTER DELETE ON `file_user`
			 FOR EACH ROW BEGIN
			call setHistory('file_user', 'delete', OLD.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_user_insert`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_user_insert` AFTER INSERT ON `file_user`
			 FOR EACH ROW BEGIN
			call setCash('file_user', NEW.id, @branch_id);
			call setHistory('file_user', 'insert', NEW.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_user_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_user_update` AFTER UPDATE ON `file_user`
			 FOR EACH ROW BEGIN
			call setHistory('file_user', 'update', OLD.id);
			END",
			////////////////////////////////////////////////////

			"CREATE TABLE IF NOT EXISTS `file_post` (
			`id` int(10) NOT NULL,
			`file_id` int(10) NOT NULL,
			`posts_id` int(10) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `file_post` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `file_post` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			"ALTER TABLE `file_post` ADD CONSTRAINT `file_post_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`)",
			"ALTER TABLE `file_post` ADD CONSTRAINT `file_post_ibfk_2` FOREIGN KEY (`posts_id`) REFERENCES `posts` (`id`)",

			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_post_delete`",

			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_post_delete` AFTER DELETE ON `file_post`
			 FOR EACH ROW BEGIN
			call setHistory('file_post', 'delete', OLD.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_post_insert`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_post_insert` AFTER INSERT ON `file_post`
			 FOR EACH ROW BEGIN
			call setCash('file_post', NEW.id, @branch_id);
			call setHistory('file_post', 'insert', NEW.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_post_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_post_update` AFTER UPDATE ON `file_post`
			 FOR EACH ROW BEGIN
			call setHistory('file_post', 'update', OLD.id);
			END",
			////////////////////////////////////////////////////
			////////////////////////////////////////////////////

			"CREATE TABLE IF NOT EXISTS `file_plan` (
			`id` int(10) NOT NULL,
			`file_id` int(10) NOT NULL,
			`plan_id` int(10) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci AUTO_INCREMENT=1 ",
			
			"ALTER TABLE `file_plan` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `file_plan` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",
			"ALTER TABLE `file_plan` ADD CONSTRAINT `file_plan_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`)",
			"ALTER TABLE `file_plan` ADD CONSTRAINT `file_plan_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `plan` (`id`)",

			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_plan_delete`",

			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_plan_delete` AFTER DELETE ON `file_plan`
			 FOR EACH ROW BEGIN
			call setHistory('file_plan', 'delete', OLD.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_plan_insert`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_plan_insert` AFTER INSERT ON `file_plan`
			 FOR EACH ROW BEGIN
			call setCash('file_plan', NEW.id, @branch_id);
			call setHistory('file_plan', 'insert', NEW.id);
			END",
		
			//-----------------------------------------------------------------------------
			"DROP TRIGGER IF EXISTS `file_plan_update`",
			
			//-----------------------------------------------------------------------------
			"CREATE TRIGGER `file_plan_update` AFTER UPDATE ON `file_plan`
			 FOR EACH ROW BEGIN
			call setHistory('file_plan', 'update', OLD.id);
			END",
			"ALTER TABLE `price`  ADD `status` enum('active','void') NOT NULL DEFAULT 'active'",
			

			"ALTER TABLE `score_type` CHANGE `min` `min` FLOAT NOT NULL",
			"ALTER TABLE `score_type` CHANGE `max` `max` FLOAT NOT NULL",
			"ALTER TABLE `score_calculation` CHANGE `status` `status` ENUM('active','disactive') CHARACTER SET utf32 COLLATE utf32_persian_ci NULL DEFAULT NULL",
			////////////////////////////////////////////////////			
			);

 ?>