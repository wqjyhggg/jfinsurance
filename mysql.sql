-- Host: aurora1.cluster-coadisrdeyad.us-east-1.rds.amazonaws.com
-- Generation Time: Sep 28, 2025 at 04:46 PM
-- Server version: 8.0.39
-- PHP Version: 7.4.3-4ubuntu2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
CREATE TABLE `activity` (
  `activity_id` int NOT NULL,
  `atype` varchar(16) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `message` text NOT NULL COMMENT 'human readable message',
  `systemlog` text NOT NULL COMMENT 'system active usually is sql'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `activity2017` (
  `activity_id` int NOT NULL,
  `atype` varchar(16) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `message` text NOT NULL COMMENT 'human readable message',
  `systemlog` text NOT NULL COMMENT 'system active usually is sql'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `activity2019` (
  `activity_id` int NOT NULL,
  `atype` varchar(16) NOT NULL,
  `tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `payment_id` int NOT NULL,
  `message` text NOT NULL COMMENT 'human readable message',
  `systemlog` text NOT NULL COMMENT 'system active usually is sql'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `announcement` (
  `announcement_id` int NOT NULL,
  `title` varchar(64) NOT NULL,
  `desc` text,
  `start_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT '1',
  `orderby` tinyint NOT NULL DEFAULT '0',
  `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `announcement_user` (
  `user_id` int NOT NULL,
  `announcement_id` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `app` (
  `user_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `timeout` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `backrun` (
  `backrun_id` int NOT NULL,
  `is_done` tinyint NOT NULL DEFAULT '0',
  `run_type` varchar(16) NOT NULL DEFAULT '',
  `filepath` varchar(128) NOT NULL DEFAULT '',
  `para_data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `require_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `done_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `batch` (
  `batch_number` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `memo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `citem` (
  `citem_id` int NOT NULL,
  `claim_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 means claim is finished; 2 means open',
  `product_short` varchar(16) NOT NULL,
  `policy_number` varchar(64) NOT NULL,
  `claim_number` varchar(64) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(8) NOT NULL,
  `claim_date` date NOT NULL,
  `claimed` decimal(10,2) NOT NULL,
  `paid` decimal(10,2) NOT NULL,
  `pay_to` varchar(255) NOT NULL,
  `cheque_number` varchar(64) NOT NULL,
  `coverage_code_id` varchar(8) NOT NULL,
  `service_date` date NOT NULL,
  `paid_date` date NOT NULL,
  `eob_date` date NOT NULL,
  `received` decimal(10,2) NOT NULL,
  `cashed_date` date NOT NULL,
  `eob_cheque_no` varchar(64) NOT NULL,
  `invoice_number` varchar(64) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province2` char(2) NOT NULL,
  `country2` char(2) NOT NULL,
  `postcode` varchar(16) NOT NULL,
  `diagnosis` text NOT NULL,
  `internal_note` text NOT NULL,
  `external_note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `claim` (
  `claim_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `done` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 means claim is finished; 2 means open',
  `product_short` varchar(16) NOT NULL,
  `policy_number` varchar(64) NOT NULL,
  `claim_number` varchar(64) NOT NULL,
  `lastname` varchar(128) NOT NULL,
  `firstname` varchar(128) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(8) NOT NULL,
  `claim_date` date NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `country` (
  `country_id` int NOT NULL,
  `num_code` varchar(3) NOT NULL,
  `name` varchar(128) NOT NULL,
  `iso_code_2` varchar(2) NOT NULL,
  `iso_code_3` varchar(3) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `coverage_code` (
  `coverage_code_id` varchar(8) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `customer` (
  `customer_id` int NOT NULL,
  `plan_id` int NOT NULL COMMENT 'basic function group base on user level',
  `parent_customer_id` int NOT NULL COMMENT 'user parent user ID for agent brokerage',
  `relationship` varchar(16) NOT NULL DEFAULT '',
  `gender` varchar(8) NOT NULL COMMENT 'M F',
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `birthday` date NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `forgetpwd` (
  `forgetid` varchar(64) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int NOT NULL,
  `is_app` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=armscii8;

CREATE TABLE `myhome` (
  `user_id` int NOT NULL,
  `myname` varchar(65) NOT NULL,
  `logo` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `qr` varchar(128) NOT NULL,
  `qr_desc` varchar(255) NOT NULL,
  `top_title` varchar(64) NOT NULL,
  `top_desc` text NOT NULL,
  `about_title` varchar(64) NOT NULL,
  `about_short` varchar(255) NOT NULL,
  `about_desc` text NOT NULL,
  `foot_title` varchar(64) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city_province` varchar(255) NOT NULL,
  `post_code` varchar(255) NOT NULL,
  `phone` varchar(128) NOT NULL,
  `fax` varchar(128) NOT NULL,
  `toll_free` varchar(128) NOT NULL,
  `toll_free_fax` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `payment` (
  `payment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `premium_payment_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `admin_fee` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `ispaid` tinyint NOT NULL,
  `pay_mothed` varchar(16) NOT NULL,
  `pay_type` varchar(32) NOT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'CAD',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `added` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `invoice_num` varchar(32) NOT NULL,
  `pay_date` date NOT NULL DEFAULT '2000-01-01',
  `bank_name` varchar(128) NOT NULL,
  `payor_name` varchar(128) NOT NULL,
  `cheque_number` varchar(64) NOT NULL,
  `cheque_cash_date` date NOT NULL DEFAULT '2010-01-01',
  `pay_to` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL,
  `first5` varchar(5) NOT NULL,
  `last4` varchar(4) NOT NULL,
  `expiry_month` varchar(4) NOT NULL,
  `expiry_year` varchar(4) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `payment2017` (
  `payment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `premium_payment_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `admin_fee` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `ispaid` tinyint NOT NULL,
  `pay_mothed` varchar(16) NOT NULL,
  `pay_type` varchar(32) NOT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'CAD',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `added` timestamp NOT NULL DEFAULT '1970-01-01 00:00:01',
  `invoice_num` varchar(32) NOT NULL,
  `pay_date` date NOT NULL DEFAULT '1970-01-01',
  `bank_name` varchar(128) NOT NULL,
  `payor_name` varchar(128) NOT NULL,
  `cheque_number` varchar(64) NOT NULL,
  `cheque_cash_date` date NOT NULL DEFAULT '1970-01-01',
  `pay_to` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL,
  `first5` varchar(5) NOT NULL,
  `last4` varchar(4) NOT NULL,
  `expiry_month` varchar(4) NOT NULL,
  `expiry_year` varchar(4) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `payment2019` (
  `payment_id` int NOT NULL,
  `user_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `premium_payment_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `admin_fee` decimal(10,2) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `ispaid` tinyint NOT NULL,
  `pay_mothed` varchar(16) NOT NULL,
  `pay_type` varchar(32) NOT NULL,
  `currency` varchar(8) NOT NULL DEFAULT 'CAD',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `added` timestamp NOT NULL DEFAULT '1970-01-01 00:00:01',
  `invoice_num` varchar(32) NOT NULL,
  `pay_date` date NOT NULL DEFAULT '1970-01-01',
  `bank_name` varchar(128) NOT NULL,
  `payor_name` varchar(128) NOT NULL,
  `cheque_number` varchar(64) NOT NULL,
  `cheque_cash_date` date NOT NULL DEFAULT '1970-01-01',
  `pay_to` varchar(128) NOT NULL,
  `name` varchar(255) NOT NULL,
  `first5` varchar(5) NOT NULL,
  `last4` varchar(4) NOT NULL,
  `expiry_month` varchar(4) NOT NULL,
  `expiry_year` varchar(4) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `plan` (
  `plan_id` int NOT NULL COMMENT 'basic function group base on user level',
  `customer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status_id` tinyint NOT NULL,
  `region_id` int NOT NULL,
  `policy` varchar(32) NOT NULL,
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `api` varchar(32) NOT NULL,
  `product_short` varchar(16) NOT NULL COMMENT 'product unique short name',
  `batch_number` int NOT NULL COMMENT 'usually is the first plan_id',
  `apply_date` date NOT NULL,
  `isfamilyplan` tinyint(1) NOT NULL,
  `arrival_date` date NOT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `stable_condition` tinyint(1) NOT NULL,
  `stable_condition_confirm` tinyint NOT NULL DEFAULT '0',
  `rate_options` tinyint NOT NULL,
  `holiday_rate` smallint NOT NULL DEFAULT '0',
  `spouse` tinyint NOT NULL DEFAULT '0',
  `sum_insured` int NOT NULL,
  `deductible_amount` int NOT NULL,
  `dailyrate` decimal(10,2) NOT NULL,
  `totaldays` int NOT NULL,
  `totalyears` int NOT NULL,
  `premium` decimal(10,2) NOT NULL,
	`monthlypay` tinyint NULL DEFAULT 0 COMMENT 'the plan is monthly pay or not, 1 yes',
  `tax` decimal(10,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `street_number` varchar(16) NOT NULL,
  `street_name` varchar(255) NOT NULL,
  `suite_number` varchar(16) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province2` char(2) NOT NULL,
  `country2` char(2) NOT NULL,
  `postcode` varchar(16) NOT NULL,
  `phone1` varchar(16) NOT NULL,
  `phone2` varchar(16) NOT NULL,
  `institution` varchar(128) NOT NULL,
  `institution_addr` varchar(255) NOT NULL,
  `student_id` varchar(64) NOT NULL,
  `institution_phone` varchar(16) NOT NULL,
  `institution_fax` varchar(16) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `contact_phone` varchar(16) NOT NULL,
  `contact_language` varchar(64) DEFAULT '',
  `toll_free` varchar(16) NOT NULL,
  `residence` varchar(255) NOT NULL,
  `payment_id` int NOT NULL DEFAULT '0',
  `commission_payment_id` int NOT NULL DEFAULT '0',
  `payinfo` varchar(255) NOT NULL,
  `note` text NOT NULL,
  `ip` varchar(40) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `refund_date` date NOT NULL,
  `package` varchar(32) NOT NULL COMMENT 'For TOP plan',
  `free_cancel` tinyint NOT NULL COMMENT 'For TOP plan',
  `annual_plan_days` smallint NOT NULL COMMENT 'For TOP plan',
  `ad_and_d_ck` tinyint NOT NULL COMMENT 'For TOP plan',
  `ad_and_d_insured` int NOT NULL COMMENT 'For TOP plan',
  `flight_accident_ck` tinyint NOT NULL COMMENT 'For TOP plan',
  `flight_accident_insured` int NOT NULL COMMENT 'For TOP plan',
  `trip_cancellation_ck` tinyint NOT NULL COMMENT 'For TOP plan',
  `trip_cancellation_insured` int NOT NULL COMMENT 'For TOP plan',
  `questionnaire` tinyint NOT NULL COMMENT 'For TOP plan',
  `question1` tinyint NOT NULL COMMENT 'For TOP plan',
  `question1_lung` tinyint NOT NULL,
  `question1_diabets` tinyint NOT NULL,
  `question1_heart` tinyint NOT NULL,
  `question2` tinyint NOT NULL COMMENT 'For TOP plan',
  `question3` tinyint NOT NULL COMMENT 'For TOP plan',
  `question3_bowel` varchar(1) NOT NULL,
  `question3_cancer` varchar(1) NOT NULL,
  `question3_diabetes` varchar(1) NOT NULL,
  `question3_diverticu` varchar(1) NOT NULL,
  `question3_gerd` varchar(1) NOT NULL,
  `question3_heart` varchar(1) NOT NULL,
  `question3_hyper` varchar(1) NOT NULL,
  `question3_kidney` varchar(1) NOT NULL,
  `question3_lung` varchar(1) NOT NULL,
  `question3_peptic` varchar(1) NOT NULL,
  `question4` tinyint NOT NULL COMMENT 'For TOP plan',
  `question5` tinyint NOT NULL COMMENT 'For TOP plan',
  `claim_flag` tinyint NOT NULL DEFAULT '0' COMMENT '0: no claim; 1; <= 500; 2: > 500',
  `claim_allow_by` int NOT NULL DEFAULT '0' COMMENT 'user_id',
  `claim_allow_note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `plan_history` (
  `plan_history_id` int NOT NULL,
  `plan_id` int NOT NULL COMMENT 'from plan table',
  `ishead` tinyint(1) NOT NULL DEFAULT '0',
  `add_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `status_id` tinyint NOT NULL,
  `region_id` int NOT NULL,
  `policy` varchar(32) NOT NULL,
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `product_short` varchar(16) NOT NULL COMMENT 'product unique short name',
  `batch_number` int NOT NULL COMMENT 'usually is the first plan_id',
  `apply_date` date NOT NULL,
  `isfamilyplan` tinyint(1) NOT NULL,
  `arrival_date` date NOT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `stable_condition` tinyint(1) NOT NULL,
  `stable_condition_confirm` tinyint NOT NULL DEFAULT '0',
  `rate_options` tinyint NOT NULL,
  `holiday_rate` smallint NOT NULL DEFAULT '0',
  `spouse` tinyint NOT NULL DEFAULT '0',
  `sum_insured` int NOT NULL,
  `deductible_amount` int NOT NULL,
  `dailyrate` decimal(10,2) NOT NULL,
  `actualrate` decimal(10,2) NOT NULL,
  `totaldays` int NOT NULL,
  `totalyears` int NOT NULL,
  `premium` decimal(10,2) NOT NULL,
	`monthlypay` tinyint NULL DEFAULT 0 COMMENT 'the plan is monthly pay or not, 1 yes',
  `tax` decimal(10,2) NOT NULL,
  `commission_amount` decimal(10,2) NOT NULL,
  `street_number` varchar(16) NOT NULL,
  `street_name` varchar(255) NOT NULL,
  `suite_number` varchar(16) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province2` char(2) NOT NULL,
  `country2` char(2) NOT NULL,
  `postcode` varchar(16) NOT NULL,
  `payment_id` int NOT NULL DEFAULT '0',
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `product` (
  `product_short` varchar(16) NOT NULL COMMENT 'short name',
  `calculate` tinyint NOT NULL COMMENT '1 means has program to calculate premium',
  `commission` decimal(10,2) NOT NULL COMMENT '50 means 50%',
  `min_premium` decimal(10,2) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `commission_max_limit` int NOT NULL DEFAULT '100000',
  `commission_max_days` int NOT NULL DEFAULT '3650',
  `qoute_pre` varchar(8) NOT NULL,
  `plan_pre` varchar(8) NOT NULL,
  `up_insuer` varchar(255) NOT NULL COMMENT 'Original Product come from',
  `up_pay_rate` decimal(10,2) NOT NULL,
  `prepare_rate` decimal(10,2) NOT NULL,
  `merchent_id` varchar(64) NOT NULL,
  `apikey` varchar(64) NOT NULL,
  `currency` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `product_customize` (
  `product_customize_id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_short` varchar(16) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `product_deductible` (
  `product_deductible_id` int NOT NULL,
  `product_short` varchar(16) NOT NULL,
  `amount` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `product_insured` (
  `product_insured_id` int NOT NULL,
  `product_short` varchar(16) NOT NULL,
  `amount` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `province` (
  `province_id` int NOT NULL,
  `country2` char(2) NOT NULL,
  `province2` char(2) NOT NULL,
  `name` varchar(16) NOT NULL,
  `orderby` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `psigate` (
  `psigate_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `TransTime` varchar(64) NOT NULL,
  `OrderID` varchar(128) NOT NULL,
  `Approved` varchar(16) NOT NULL,
  `ReturnCode` varchar(64) NOT NULL,
  `ErrMsg` varchar(128) NOT NULL,
  `TaxTotal` decimal(10,2) NOT NULL,
  `ShipTotal` decimal(10,2) NOT NULL,
  `SubTotal` decimal(10,2) NOT NULL,
  `FullTotal` decimal(10,2) NOT NULL,
  `PaymentType` varchar(8) NOT NULL,
  `DebitType` varchar(32) NOT NULL,
  `CardNumber` varchar(32) NOT NULL,
  `CardExpMonth` varchar(3) NOT NULL,
  `CardExpYear` varchar(5) NOT NULL,
  `TransRefNumber` varchar(128) NOT NULL,
  `CardIDResult` varchar(8) NOT NULL,
  `IPResult` varchar(4) NOT NULL,
  `IPCity` varchar(64) NOT NULL,
  `IPRegion` varchar(64) NOT NULL,
  `IPCountry` varchar(2) NOT NULL,
  `AVSResult` varchar(8) NOT NULL,
  `IssuerName` varchar(128) NOT NULL,
  `IssuerConfCode` varchar(32) NOT NULL,
  `AcquirerCode` varchar(128) NOT NULL,
  `CustomerIssLang` varchar(8) NOT NULL,
  `TransType` varchar(16) NOT NULL,
  `rowdata` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `ptmp` (
  `plan_id` int NOT NULL,
  `cnt` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `region` (
  `region_id` int NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `setting` (
  `setting_id` int NOT NULL,
  `parent_id` int NOT NULL,
  `type` varchar(16) NOT NULL,
  `name` varchar(64) NOT NULL,
  `value` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `snappay_postback` (
  `postback_id` int NOT NULL,
  `trans_no` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '',
  `plan_id` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `trans_status` varchar(32) NOT NULL DEFAULT '',
  `recv` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `snappay_trans` (
  `trans_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `send` text,
  `recv` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `status` (
  `status_id` int NOT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `training` (
  `training_id` int NOT NULL,
  `title` varchar(64) NOT NULL,
  `desc` text,
  `links` text,
  `start_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT '1',
  `orderby` tinyint NOT NULL DEFAULT '0',
  `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `training_user` (
  `user_id` int NOT NULL,
  `training_id` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `user_group_id` int NOT NULL COMMENT 'basic function group base on user level',
  `parent_user_id` int NOT NULL COMMENT 'user parent user ID for agent brokerage',
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `forcepw` tinyint NOT NULL DEFAULT '0',
  `region_id` int NOT NULL COMMENT 'Region for agent belong to',
  `business` varchar(255) NOT NULL,
  `gender` varchar(8) NOT NULL COMMENT 'Mr Miss Mrs',
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(96) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(64) NOT NULL,
  `province2` char(2) NOT NULL COMMENT 'ID from province table',
  `country2` char(2) NOT NULL,
  `postcode` varchar(16) NOT NULL,
  `mail_name` varchar(255) NOT NULL,
  `mail_address` varchar(255) NOT NULL,
  `mail_city` varchar(64) NOT NULL,
  `mail_province2` char(2) NOT NULL,
  `mail_country2` char(2) NOT NULL,
  `mail_postcode` varchar(16) NOT NULL,
  `website` varchar(255) NOT NULL,
  `licence_number` varchar(64) NOT NULL,
  `licence_expire` date NOT NULL,
  `business_phone` varchar(32) NOT NULL,
  `mobile_phone` varchar(16) NOT NULL,
  `fax_number` varchar(16) NOT NULL,
  `toll_free` varchar(16) NOT NULL,
  `pay_type` varchar(255) NOT NULL COMMENT 'payment method list split by space',
  `receive_type` varchar(32) NOT NULL,
  `ip` varchar(40) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `enable_pdf` tinyint NOT NULL,
  `pdf_logo` varchar(255) NOT NULL,
  `pdf_qr` varchar(255) NOT NULL,
  `pdf_qr2` varchar(255) NOT NULL,
  `pdf_f_left1` varchar(255) NOT NULL,
  `pdf_f_left2` varchar(255) NOT NULL,
  `pdf_f_left3` varchar(255) NOT NULL,
  `pdf_f_left4` varchar(255) NOT NULL,
  `pdf_f_left5` varchar(255) NOT NULL,
  `pdf_f_left6` varchar(255) NOT NULL,
  `pdf_f_right1` varchar(255) NOT NULL,
  `pdf_f_right2` varchar(255) NOT NULL,
  `pdf_f_right3` varchar(255) NOT NULL,
  `pdf_f_right4` varchar(255) NOT NULL,
  `pdf_f_right5` varchar(255) NOT NULL,
  `pdf_f_right6` varchar(255) NOT NULL,
  `pdf_product` varchar(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text NOT NULL,
  `note2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_annual_report` (
  `user_id` int NOT NULL,
  `year` varchar(4) NOT NULL,
  `by_id` int NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_group` (
  `user_group_id` int NOT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_home` (
  `user_id` int NOT NULL,
  `paras` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_meta` (
  `user_id` int NOT NULL,
  `type` varchar(16) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_nofify` (
  `user_id` int NOT NULL,
  `notify_type` tinyint NOT NULL DEFAULT '1' COMMENT '0: none; 1: monthly, 2: every half month',
  `for_type` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Expire' COMMENT 'Expire, Effect'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_product` (
  `user_id` int NOT NULL,
  `product_short` varchar(16) NOT NULL COMMENT 'short name',
  `commission` decimal(10,2) NOT NULL COMMENT '50 means 50%'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `user_province` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `province2` char(2) NOT NULL COMMENT 'ID from province table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `wording` (
  `word` varchar(64) NOT NULL,
  `desc` varchar(128) NOT NULL,
  `update_tm` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

ALTER TABLE `activity` ADD PRIMARY KEY (`activity_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `atype` (`activity_id`);
ALTER TABLE `activity2017` ADD PRIMARY KEY (`activity_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `atype` (`activity_id`);
ALTER TABLE `activity2019` ADD PRIMARY KEY (`activity_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `atype` (`activity_id`);
ALTER TABLE `announcement` ADD PRIMARY KEY (`announcement_id`);
ALTER TABLE `announcement_user` ADD PRIMARY KEY (`user_id`,`announcement_id`);
ALTER TABLE `app` ADD PRIMARY KEY (`user_id`), ADD KEY `token` (`token`);
ALTER TABLE `backrun` ADD PRIMARY KEY (`backrun_id`);
ALTER TABLE `batch` ADD PRIMARY KEY (`batch_number`);
ALTER TABLE `citem` ADD PRIMARY KEY (`citem_id`);
ALTER TABLE `claim` ADD PRIMARY KEY (`claim_id`);
ALTER TABLE `country` ADD PRIMARY KEY (`country_id`);
ALTER TABLE `coverage_code` ADD PRIMARY KEY (`coverage_code_id`);
ALTER TABLE `customer` ADD PRIMARY KEY (`customer_id`), ADD KEY `policy_id` (`plan_id`), ADD KEY `parent_customer_id` (`parent_customer_id`);
ALTER TABLE `forgetpwd` ADD PRIMARY KEY (`forgetid`);
ALTER TABLE `myhome` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `myname` (`myname`);
ALTER TABLE `payment` ADD PRIMARY KEY (`payment_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `added` (`added`), ADD KEY `pay_type` (`pay_type`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `payment2017` ADD PRIMARY KEY (`payment_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `added` (`added`), ADD KEY `pay_type` (`pay_type`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `payment2019` ADD PRIMARY KEY (`payment_id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `added` (`added`), ADD KEY `pay_type` (`pay_type`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `plan` ADD PRIMARY KEY (`plan_id`) USING BTREE, ADD KEY `customer_id` (`customer_id`) USING BTREE, ADD KEY `status` (`status_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE, ADD KEY `policy` (`policy`), ADD KEY `product_short` (`product_short`) USING BTREE, ADD KEY `batch_number` (`batch_number`) USING BTREE;
ALTER TABLE `plan_history` ADD PRIMARY KEY (`plan_history_id`) USING BTREE, ADD KEY `plan_id` (`plan_id`) USING BTREE, 
	ADD KEY `customer_id` (`customer_id`) USING BTREE, ADD KEY `status` (`status_id`) USING BTREE, ADD KEY `user_id` (`user_id`) USING BTREE, 
	ADD KEY `policy` (`policy`), ADD KEY `product_short` (`product_short`) USING BTREE, ADD KEY `ishead` (`ishead`), ADD KEY `add_time` (`add_time`);
ALTER TABLE `product` ADD PRIMARY KEY (`product_short`);
ALTER TABLE `product_customize` ADD PRIMARY KEY (`product_customize_id`), ADD UNIQUE KEY `prod` (`user_id`,`product_short`);
ALTER TABLE `product_deductible` ADD PRIMARY KEY (`product_deductible_id`), ADD KEY `product_short` (`product_deductible_id`);
ALTER TABLE `product_insured` ADD PRIMARY KEY (`product_insured_id`), ADD KEY `product_short` (`product_insured_id`);
ALTER TABLE `province` ADD PRIMARY KEY (`province_id`), ADD UNIQUE KEY `country2` (`country2`,`province2`) USING BTREE;
ALTER TABLE `psigate` ADD PRIMARY KEY (`psigate_id`);
ALTER TABLE `region` ADD PRIMARY KEY (`region_id`);
ALTER TABLE `setting` ADD PRIMARY KEY (`setting_id`), ADD UNIQUE KEY `name` (`name`,`type`);
ALTER TABLE `snappay_postback` ADD PRIMARY KEY (`postback_id`), ADD KEY `trans_no` (`trans_no`);
ALTER TABLE `snappay_trans` ADD PRIMARY KEY (`trans_id`), ADD KEY `plan_id` (`plan_id`);
ALTER TABLE `status` ADD PRIMARY KEY (`status_id`);
ALTER TABLE `training` ADD PRIMARY KEY (`training_id`);
ALTER TABLE `training_user` ADD PRIMARY KEY (`user_id`,`training_id`);
ALTER TABLE `user` ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`) USING BTREE, ADD KEY `parent` (`parent_user_id`), ADD KEY `user_group_id` (`user_group_id`);
ALTER TABLE `user_annual_report` ADD PRIMARY KEY (`user_id`,`year`);
ALTER TABLE `user_group` ADD PRIMARY KEY (`user_group_id`);
ALTER TABLE `user_home` ADD PRIMARY KEY (`user_id`);
ALTER TABLE `user_meta` ADD PRIMARY KEY (`user_id`,`type`);
ALTER TABLE `user_nofify` ADD PRIMARY KEY (`user_id`,`notify_type`) USING BTREE;
ALTER TABLE `user_product` ADD PRIMARY KEY (`user_id`,`product_short`), ADD KEY `user_id` (`user_id`), ADD KEY `product_short` (`product_short`);
ALTER TABLE `user_province` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_prov` (`user_id`,`province2`);
ALTER TABLE `wording` ADD PRIMARY KEY (`word`);
ALTER TABLE `activity` MODIFY `activity_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `activity2017` MODIFY `activity_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `activity2019` MODIFY `activity_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `announcement` MODIFY `announcement_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `backrun` MODIFY `backrun_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `batch` MODIFY `batch_number` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `citem` MODIFY `citem_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `claim` MODIFY `claim_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `country` MODIFY `country_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `customer` MODIFY `customer_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `payment` MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `payment2017` MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `payment2019` MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `plan` MODIFY `plan_id` int NOT NULL AUTO_INCREMENT COMMENT 'basic function group base on user level';
ALTER TABLE `plan_history` MODIFY `plan_history_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `product_customize` MODIFY `product_customize_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `product_deductible` MODIFY `product_deductible_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `product_insured` MODIFY `product_insured_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `province` MODIFY `province_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `psigate` MODIFY `psigate_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `region` MODIFY `region_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `setting` MODIFY `setting_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `snappay_postback` MODIFY `postback_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `snappay_trans` MODIFY `trans_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `status` MODIFY `status_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `training` MODIFY `training_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `user` MODIFY `user_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_group` MODIFY `user_group_id` int NOT NULL AUTO_INCREMENT;
ALTER TABLE `user_province` MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

-- 2025-09-28
CREATE TABLE maintain (
  maintain_id int NOT NULL AUTO_INCREMENT,
	status tinyint NOT NULL DEFAULT 0 COMMENT '0: no ready, others ready to active, 1: in maitain (combain with start_time and end_time)',
	active tinyint NOT NULL DEFAULT 0 COMMENT '0: no, 1: yes maintain event is active',
  start_time datetime NULL,
  end_time datetime NULL,
  reason text,
  notes varchar(255) NULL COMMENT 'For human read as notes',
 PRIMARY KEY (maintain_id) );

-- 2025-10-11
DROP TABLE monthly_payment;
CREATE TABLE monthly_payment (
  monthly_payment_id int NOT NULL AUTO_INCREMENT,
	plan_id int NOT NULL DEFAULT 0,
	payment_id int NOT NULL DEFAULT 0,
	profile_id varchar(64) NOT NULL DEFAULT '' COMMENT 'for recurrent payment profile id',
	trans_id varchar(64) NOT NULL DEFAULT '' COMMENT 'from processor transaction ID',
	pay_type tinyint NOT NULL DEFAULT 1 COMMENT '0: for first pay. 1: for recurrent',
	paid tinyint NOT NULL DEFAULT 0 COMMENT '0: no, 1: yes, -1: void, -2: pay error',
	retry tinyint NOT NULL DEFAULT 0 COMMENT 'tried times',
  retry_date date NULL COMMENT 'retry date',
	refund_amount decimal(10,2) NOT NULL DEFAULT 0,
	amount decimal(10,2) NOT NULL DEFAULT 0,
	admin_fee decimal(10,2) NOT NULL DEFAULT 0,
  pay_date date NULL COMMENT 'pay date',
  pay_time datetime NULL COMMENT 'paid real time',
  postdata text,
  rawdata text,
 PRIMARY KEY (monthly_payment_id) );
CREATE INDEX monthly_payment_plan_id ON monthly_payment (plan_id);
CREATE INDEX monthly_payment_payment_id ON monthly_payment (payment_id);
CREATE INDEX monthly_payment_paid ON monthly_payment (paid);
ALTER TABLE `plan` ADD `monthlypay` tinyint NULL DEFAULT 0 COMMENT 'the plan is monthly pay or not, 1 yes' AFTER `premium`;

-- 2025-12-06
ALTER TABLE `product` ADD `profile_key` varchar(64) NOT NULL DEFAULT '' COMMENT 'key for create profile' AFTER `apikey`;
ALTER TABLE `product` ADD `hash_key` varchar(64) NOT NULL DEFAULT '' COMMENT 'hash_key for create iframe link' AFTER `apikey`;
ALTER TABLE `product` ADD `payment_key` VARCHAR(64) NOT NULL DEFAULT '' AFTER `profile_key`;
ALTER TABLE `monthly_payment` ADD `admin_fee` decimal(10,2) NOT NULL DEFAULT 0 AFTER `amount`;
ALTER TABLE `monthly_payment` ADD `retry_date` date NULL COMMENT 'retry date' AFTER `retry`;
ALTER TABLE `monthly_payment` ADD `refund_amount` decimal(10,2) NOT NULL DEFAULT 0 AFTER `retry_date`;
-- 2026-02-07
ALTER TABLE `plan_history` ADD `monthlypay` tinyint NULL DEFAULT 0 COMMENT 'the plan is monthly pay or not, 1 yes' AFTER `premium`;
