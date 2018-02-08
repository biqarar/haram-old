<?php 

$database_change = array(
			"ALTER TABLE `person`  ADD `education_howzah_id` INT(10)  AFTER `education_id`",
			"ALTER TABLE `person` ADD CONSTRAINT `person_ibfk_10` FOREIGN KEY (`education_howzah_id`) REFERENCES `education` (`id`)",


			"DROP TABLE IF EXISTS `score`" , 

		"CREATE TABLE IF NOT EXISTS `score` (
			  `id` int(10) NOT NULL,
			  `classification_id` int(10) NOT NULL,
			  `score_type_id` int(10) NOT NULL,
			  `value` FLOAT(3) NOT NULL
			  ) ENGINE=InnoDB  DEFAULT CHARSET=utf32 COLLATE=utf32_persian_ci  AUTO_INCREMENT=1",

			"ALTER TABLE `score` ADD PRIMARY KEY(`id`)",
			"ALTER TABLE `score` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT",

			"ALTER TABLE `score` ADD CONSTRAINT `score_ibfk_2` FOREIGN KEY (`classification_id`) REFERENCES `classification` (`id`)",
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

			"ALTER TABLE `plan` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL COMMENT 'نام طرح'");
?>