INSERT INTO `product` (`product_short`, `calculate`, `commission`, `min_premium`, `full_name`, `commission_max_limit`, `commission_max_days`, `qoute_pre`, `plan_pre`, `up_insuer`, `up_pay_rate`, `prepare_rate`, `merchent_id`, `apikey`, `currency`) VALUES('TOP', 1, '35.00', '0.00', 'JF Canadian Travel Insurance', 100000, 3650, 'QTOP', 'TOP', 'Blue Cross', '40.000', '50.00', '300203256', '634E4AFd7Eda4dcEaA2976207A7C92bb', 'CAD');
ALTER TABLE `plan` ADD `package` VARCHAR(32) NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `free_cancel` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `annual_plan_days` SMALLINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `ad_and_d_ck` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `ad_and_d_insured` INT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `flight_accident_ck` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `flight_accident_insured` INT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `trip_cancellation_ck` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `trip_cancellation_insured` INT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `questionnaire` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `question1` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `question2` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `question3` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `question4` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `question5` TINYINT NOT NULL COMMENT 'For TOP plan';
ALTER TABLE `plan` ADD `tax` DECIMAL(10,2) NOT NULL AFTER `premium`;



