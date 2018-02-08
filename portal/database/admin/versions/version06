<?php 
$database_change = array(
			"SET GLOBAL event_scheduler = 'ON'",
			"DROP EVENT `price_active_expired` ",
			"CREATE DEFINER=`root`@`localhost` EVENT `price_active_expired` ON SCHEDULE EVERY 1 DAY STARTS '2015-05-07 23:00:00' ON COMPLETION NOT PRESERVE ENABLE DO update `price` set visible = 1 where (visible = 0 and price.date = dateNow())",
			"ALTER TABLE `plan`  ADD `expired_price` INT(3) NULL",
			"ALTER TABLE `plan`  ADD `payment_count` INT(2) NULL",
			"ALTER TABLE `price`  ADD `visible` BOOLEAN DEFAULT 1",
			"ALTER TABLE `score` ADD UNIQUE `unique_index`(`classification_id`, `score_type_id`);",
			"ALTER TABLE `certification` ADD UNIQUE `unique_index`(`classification_id`);",
			"ALTER TABLE `file_tag`  ADD `type` ENUM('image','multimedia','doc','zip','binary') NULL,  ADD `max_size` INT(10) NULL,  ADD `condition` VARCHAR(255) NULL;",
		);

?>