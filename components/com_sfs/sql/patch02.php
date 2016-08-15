<?php /*
    -- This is db update for Geo Map
    -- Please don't clean this file

CREATE TABLE IF NOT EXISTS `jos_sfs_airport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `airport_id` int(11) NOT NULL,
  `geo_location_latitude` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `geo_location_longitude` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

 INSERT INTO `jos_sfs_airport` (`id`, `airport_id`, `geo_location_latitude`, `geo_location_longitude`) VALUES
(1, 1, '50.905341', '4.486539');

ALTER TABLE  `jos_sfs_iatacodes` ADD  `geo_lat` VARCHAR( 30 ) NULL AFTER  `comment` ,
ADD  `geo_lon` VARCHAR( 30 ) NULL AFTER  `geo_lat`;

ALTER TABLE  `jos_sfs_airline_details` ADD  `airport_ring_1_mile` DOUBLE NULL AFTER  `airport_id` ,
ADD  `airport_ring_2_mile` DOUBLE NULL AFTER  `airport_ring_1_mile`;

ALTER TABLE  `jos_sfs_passengers` ADD  `voucher_room_id` INT( 11 ) AFTER  `room_type`;

 CREATE TABLE IF NOT EXISTS `jos_sfs_voucher_rooms` (
  `voucher_room_id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(11) NOT NULL,
  PRIMARY KEY (`voucher_room_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

 CREATE TABLE IF NOT EXISTS `jos_sfs_voucher_groups` (
  `voucher_id` int(11) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `voucher_group_id` int(11) unsigned NOT NULL,
  `room_type` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `passenger_email` text NOT NULL,
  `cancel_reason` text NOT NULL,
  `handled_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `jos_sfs_voucher_groups`
  ADD PRIMARY KEY (`voucher_id`), ADD KEY `voucher_group_id` (`voucher_group_id`);


ALTER TABLE `jos_sfs_voucher_groups`
  MODIFY `voucher_id` int(11) unsigned NOT NULL AUTO_INCREMENT;

 ALTER TABLE  `jos_sfs_reservations` ADD  `ws_sd_rate` DECIMAL( 10, 2 ) UNSIGNED NOT NULL AFTER  `claimed_rooms` ,
ADD  `ws_t_rate` DECIMAL( 10, 2 ) UNSIGNED NOT NULL AFTER  `ws_sd_rate` ,
ADD  `ws_s_rate` DECIMAL( 10, 2 ) UNSIGNED NOT NULL AFTER  `ws_t_rate` ,
ADD  `ws_q_rate` DECIMAL( 10, 2 ) UNSIGNED NOT NULL AFTER  `ws_s_rate`;

CREATE TABLE IF NOT EXISTS `jos_sfs_airline_airport` (
  `airline_detail_id` int(11) NOT NULL,
  `airport_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 ALTER TABLE  `jos_sfs_taxi_hotel_rates` ADD  `km_rate` DECIMAL( 12, 2 ) UNSIGNED NOT NULL ,
ADD  `starting_tariff` DECIMAL( 12, 2 ) UNSIGNED NOT NULL;

 ALTER TABLE  `jos_sfs_iatacodes` ADD  `starting_tariff` DECIMAL( 10, 2 ) UNSIGNED NOT NULL ,
ADD  `km_rate` DECIMAL( 10, 2 ) UNSIGNED NOT NULL;


 ALTER TABLE  `jos_sfs_passengers` ADD  `type` SMALLINT( 1 ) UNSIGNED NOT NULL DEFAULT  '0' COMMENT  '0: aldult, 1: child, 2: infant' AFTER  `last_name`


ALTER TABLE  `jos_sfs_hotel` ADD  `total` DECIMAL( 10, 2 ) UNSIGNED NOT NULL COMMENT  'estimated price';

ALTER TABLE  `jos_sfs_reservations` ADD  `airport_code` VARCHAR( 50 ) NOT NULL;

ALTER TABLE  `jos_sfs_passengers` ADD  `id` INT NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY (  `id` );

ALTER TABLE  `jos_sfs_flights_seats` ADD  `airport_id` INT NOT NULL AFTER  `airline_id`;

ALTER TABLE  `jos_users` ADD  `airport` MEDIUMTEXT NULL;

ALTER TABLE  `jos_sfs_reservations` ADD  `url_code` VARCHAR( 5 ) NULL ,
ADD UNIQUE (`url_code`);

ALTER TABLE  `jos_sfs_passengers` ADD  `hotel_confirmed` TINYINT( 1 ) NOT NULL DEFAULT  '1' COMMENT  'Is hotel confirmed about this name or not' AFTER  `level`;
ALTER TABLE  `jos_sfs_passengers` CHANGE  `hotel_confirmed`  `hotel_confirmed` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  'Is hotel confirmed about this name or not';

CREATE TABLE IF NOT EXISTS `jos_sfs_airline_notification_tracking` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `hotel_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

 DROP TABLE `jos_sfs_trace_passengers`;

CREATE TABLE IF NOT EXISTS `jos_sfs_trace_passengers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` int(10) unsigned NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `individual_voucher` int(10) unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(1) unsigned NOT NULL COMMENT '0: aldult, 1: child, 2: infant',
  `phone_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `room_type` smallint(1) unsigned NOT NULL,
  `voucher_room_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

ALTER TABLE `jos_sfs_passengers`
  DROP `hotel_confirmed`;



ALTER TABLE  `jos_sfs_iatacodes` ADD  `currency_code` VARCHAR( 3 ) NOT NULL DEFAULT  'EUR';

ALTER TABLE  `jos_sfs_airline_details` ADD  `gh_airline` BOOLEAN NOT NULL AFTER  `airport_ring_2_mile`;

ALTER TABLE  `jos_sfs_airline_details` ADD  `airline_airplusws_id` INT(11) NULL COMMENT  'airplus ws params';
 
ALTER TABLE  `jos_sfs_trace_passengers` ADD  `mealplan` BOOLEAN NOT NULL COMMENT  'meallplan service',
ADD  `taxi` BOOLEAN NOT NULL COMMENT  'taxi service',
ADD  `cash` BOOLEAN NOT NULL COMMENT  'cash service',
ADD  `phone` BOOLEAN NOT NULL COMMENT  'telephone service';


CREATE TABLE `jos_sfs_airline_airplusws` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `dbi_fee` decimal(10,2) NOT NULL COMMENT 'Common fee. But I don''t think this is usable in the future',
 `dbi_ae` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_ak` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_au` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_bd` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_ds` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_ik` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_ks` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_pk` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_pr` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `dbi_rz` varchar(17) COLLATE utf8_unicode_ci NOT NULL,
 `hotel_gn` tinyint(1) NOT NULL,
 `hotel_cid` tinyint(1) NOT NULL,
 `hotel_cod` tinyint(1) NOT NULL,
 `hotel_rn` tinyint(1) NOT NULL,
 `other_nm` tinyint(1) NOT NULL COMMENT 'Use airplus NM field or not',
 `other_ft` tinyint(1) NOT NULL COMMENT 'Use airplus FT field or not',
 `rail_pn` tinyint(1) NOT NULL,
 `rail_dd` tinyint(1) NOT NULL,
 `rail_dptc` tinyint(1) NOT NULL,
 `rail_dtc` tinyint(1) NOT NULL,
 `rail_sc` tinyint(1) NOT NULL,
 `car_dr` tinyint(1) NOT NULL,
 `car_pd` tinyint(1) NOT NULL,
 `car_pl` tinyint(1) NOT NULL,
 `car_dl` tinyint(1) NOT NULL,
 `car_dc` tinyint(1) NOT NULL,
 `bus_enabled` tinyint(4) NOT NULL,
 `bus_fee` decimal(10,2) NOT NULL,
 `bus_nm` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
 `bus_ft` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `cashreim_enabled` tinyint(4) NOT NULL,
 `cashreim_fee` decimal(10,2) NOT NULL,
 `cashreim_nm` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
 `cashreim_ft` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `taxi_enabled` tinyint(4) NOT NULL,
 `taxi_fee` decimal(10,2) NOT NULL,
 `taxi_nm` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
 `taxi_ft` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `meal_enabled` tinyint(4) NOT NULL,
 `meal_fee` decimal(10,2) NOT NULL,
 `meal_nm` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
 `meal_ft` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 `telcard_enabled` tinyint(1) NOT NULL,
 `telcard_fee` decimal(10,2) NOT NULL,
 `telcard_nm` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
 `telcard_ft` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\

ALTER TABLE  `jos_sfs_airline_details` ADD `gh_airline` tinyint(1) NOT NULL;


CREATE TABLE IF NOT EXISTS `jos_sfs_passengers_airplusws` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passenger_id` int(11) NOT NULL,
  `cvc` int(11) NOT NULL,
  `card_number` varchar(255) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `type_of_service` varchar(255) NOT NULL,
  `valid_thru` varchar(100) NOT NULL,
  `valid_from` varchar(100) NOT NULL,
  `passenger_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `jos_sfs_contractedrates` ADD `breakfast` DECIMAL(11,2) NOT NULL , ADD `lunch` DECIMAL(11,2) NOT NULL , ADD `dinner` DECIMAL(11,2) NOT NULL ;
ALTER TABLE  `jos_sfs_trace_passengers` ADD  `flight_number` VARCHAR( 10 ) NOT NULL AFTER  `voucher_id`;
ALTER TABLE  `jos_sfs_passengers_airplusws` ADD  `value` DECIMAL( 10, 2 ) UNSIGNED NOT NULL;
ALTER TABLE  `jos_sfs_airline_airplusws` ADD  `meal_values` VARCHAR( 20 ) NOT NULL AFTER  `meal_ft`;
ALTER TABLE  `jos_sfs_airline_airplusws` ADD  `meal_first_limit` DECIMAL( 10, 2 ) NOT NULL AFTER  `meal_fee` ,
ADD  `meal_second_limit` DECIMAL( 10, 2 ) NOT NULL AFTER  `meal_first_limit`;

ALTER TABLE  `jos_sfs_trace_passengers` ADD  `created` DATE NOT NULL;
-- Update flight_number for each trace passengers which has voucher_id
UPDATE `jos_sfs_trace_passengers` AS p
INNER JOIN `jos_sfs_voucher_codes` AS v ON v.id= p.voucher_id
INNER JOIN `jos_sfs_flights_seats` AS f ON f.id = v.flight_id
SET p.flight_number = f.flight_code
WHERE p.voucher_id <> 0;

ALTER TABLE  `jos_sfs_trace_passengers` ADD  `airport_id` INT( 11 ) NULL AFTER  `flight_number`;

-- Update airport_id for each trace passengers
UPDATE `jos_sfs_trace_passengers` AS p
INNER JOIN `jos_sfs_voucher_codes` AS v ON v.id= p.voucher_id
INNER JOIN `jos_sfs_reservations` AS r ON r.id = v.booking_id
INNER JOIN `jos_sfs_iatacodes` AS ia ON ia.code = r.airport_code
SET p.airport_id = ia.id
WHERE p.voucher_id <> 0;

-- Update created_date for each trace passengers
UPDATE `jos_sfs_trace_passengers` AS p
INNER JOIN `jos_sfs_voucher_codes` AS v ON v.id= p.voucher_id
SET p.created = DATE(v.created)
WHERE p.voucher_id <> 0;

ALTER TABLE  `jos_sfs_trace_passengers` ADD  `airplus_id` INT NOT NULL AFTER  `voucher_room_id`;

-- Update new DB for airplus
RENAME TABLE `jos_sfs_passengers_airplusws` TO jos_sfs_airplusws_creditcard_detail` ;
ALTER TABLE `jos_sfs_airplusws_creditcard_detail`
  DROP `passenger_id`;
ALTER TABLE `jos_sfs_airplusws_creditcard_detail` ADD `unique_id` VARCHAR(17) NOT NULL AFTER `value`;

CREATE TABLE IF NOT EXISTS `jos_sfs_passengers_airplus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `airplus_id` int(11) NOT NULL,
  `airplus_mealplan` tinyint(1) NOT NULL,
  `airplus_taxi` tinyint(1) NOT NULL,
  `airplus_cash` tinyint(1) NOT NULL,
  `airplus_phone` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

ALTER TABLE  `jos_sfs_trace_passengers` CHANGE  `created`  `created_date` DATE NOT NULL;
ALTER TABLE  `jos_sfs_trace_passengers` DROP  `airplus_mealplan`  ,
DROP  `airplus_taxi` ,
DROP  `airplus_cash` ,
DROP  `airplus_phone`;

-- lchung
CREATE TABLE IF NOT EXISTS `jos_sfs_passengers_airplus_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `aida_number` varchar(255) NOT NULL,
  `curr` int(11) NOT NULL,
  `dbi_ae` varchar(255) NOT NULL,
  `dbi_ak` varchar(255) NOT NULL,
  `dbi_au` varchar(255) NOT NULL,
  `dbi_bd` varchar(255) NOT NULL,
  `dbi_ds` varchar(255) NOT NULL,
  `dbi_ik` varchar(255) NOT NULL,
  `dbi_ks` varchar(255) NOT NULL,
  `dbi_pk` varchar(255) NOT NULL,
  `dbi_pr` varchar(255) NOT NULL,
  `dbi_rz` varchar(255) NOT NULL,
  `iata_number` int(11) NOT NULL,
  `passenger` varchar(255) NOT NULL,
  `portal_user_id` int(11) NOT NULL,
  `purchase_date` int(11) NOT NULL,
  `travel_date` int(6) NOT NULL,
  `service_desc` text NOT NULL,
  `supplier` text NOT NULL,
  `ticket_number` varchar(255) NOT NULL,
  `travel_agency` varchar(255) NOT NULL,
  `transaction_type` varchar(255) NOT NULL,
  `amount_conv` decimal(10,2) NOT NULL,
  `amount_curr` decimal(10,2) NOT NULL,
  `fe_amount` decimal(10,2) NOT NULL,
  `fe_curr` decimal(10,2) NOT NULL,
  `start_date` varchar(50) NOT NULL,
  `end_date` varchar(50) NOT NULL,
  `days_nights_count` int(11) NOT NULL,
  `dept_city` varchar(255) NOT NULL,
  `dest_city` varchar(255) NOT NULL,
  `participant_count` int(11) NOT NULL,
  `carrier_code` varchar(255) NOT NULL,
  `service_class` varchar(255) NOT NULL,
  `routing` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE  `jos_sfs_passengers_airplus` CHANGE  `airplus_id`  `airplus_id` INT( 11 ) NOT NULL DEFAULT  '0',
CHANGE  `airplus_mealplan`  `airplus_mealplan` TINYINT( 1 ) NOT NULL DEFAULT  '0',
CHANGE  `airplus_taxi`  `airplus_taxi` TINYINT( 1 ) NOT NULL DEFAULT  '0',
CHANGE  `airplus_cash`  `airplus_cash` TINYINT( 1 ) NOT NULL DEFAULT  '0',
CHANGE  `airplus_phone`  `airplus_phone` TINYINT( 1 ) NOT NULL DEFAULT  '0';

ALTER TABLE  `jos_sfs_airplusws_creditcard_detail` CHANGE  `cvc`  `cvc` INT( 11 ) NOT NULL ,
CHANGE  `card_number`  `card_number` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `session_id`  `session_id` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `type_of_service`  `type_of_service` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `valid_thru`  `valid_thru` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `valid_from`  `valid_from` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `passenger_name`  `passenger_name` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `value`  `value` DECIMAL( 10, 2 ) UNSIGNED NULL ,
CHANGE  `unique_id`  `unique_id` VARCHAR( 17 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;

--Lchung 24-10-2015
ALTER TABLE `jos_sfs_airplusws_creditcard_detail` ADD `airplus_id` INT(11) NOT NULL AFTER `id`;

ALTER TABLE  `jos_sfs_passengers_airplus` ADD  `voucher_id` INT NULL AFTER  `id` ,
ADD  `startdate` DATETIME NULL AFTER  `voucher_id` ,
ADD  `expiredate` DATETIME NULL AFTER  `startdate`;

ALTER TABLE  `jos_sfs_passengers_airplus` ADD  `blockcode` VARCHAR( 255 ) NULL COMMENT  'blockcode or unique key' AFTER  `expiredate`;

ALTER TABLE  `jos_sfs_passengers_airplus` ADD  `airport_code` VARCHAR( 255 ) NULL AFTER  `blockcode` ,
ADD  `flight_number` VARCHAR( 255 ) NULL AFTER  `airport_code` ,
ADD  `hotel_id` INT NULL AFTER  `flight_number`;

ALTER TABLE  `jos_sfs_passengers_airplus` ADD  `pnr` VARCHAR( 255 ) NULL AFTER  `hotel_id`;

ALTER TABLE  `jos_sfs_passengers_airplus` ADD  `airline_id` INT( 11 ) NOT NULL DEFAULT  '0' AFTER  `id`;

ALTER TABLE  `jos_sfs_passengers_airplus` ADD  `user_id` INT NULL AFTER  `airline_id`;

-- lchung
ALTER TABLE `jos_sfs_passengers` ADD `flight_number` VARCHAR( 10 ) NOT NULL AFTER `voucher_id`; 
ALTER TABLE `jos_sfs_passengers` ADD `created` DATE NOT NULL;
ALTER TABLE `jos_sfs_passengers` ADD `airport_id` INT( 11 ) NULL AFTER `flight_number`;
ALTER TABLE `jos_sfs_passengers` ADD `airplus_id` INT NOT NULL AFTER `voucher_room_id`;
ALTER TABLE `jos_sfs_passengers` CHANGE `created` `created_date` DATE NOT NULL;

ALTER TABLE  `jos_sfs_hotel` ADD  `airport_code` VARCHAR( 20 ) NOT NULL;
ALTER TABLE  `jos_sfs_iatacodes` ADD  `time_zone` VARCHAR( 40 ) NOT NULL;


ALTER TABLE `jos_sfs_contractedrates` ADD `breakfast` DECIMAL(11,2) NOT NULL , ADD `lunch` DECIMAL(11,2) NOT NULL , ADD `dinner` DECIMAL(11,2) NOT NULL ;

//lchung 03-11-2015
ALTER TABLE `jos_sfs_airline_details` ADD `unique_token` VARCHAR(250) NOT NULL AFTER `airline_airplusws_id`;

ALTER TABLE `jos_sfs_trace_passengers` ADD `airline_id` INT(11) NOT NULL AFTER `created`;

ALTER TABLE `jos_sfs_trace_passengers` ADD `flight_code` VARCHAR(150) NOT NULL AFTER `airline_id`, ADD `flight_iatacode` INT(11) NOT NULL AFTER `flight_code`;

-- lchung 12-11-2015 #301 setup WS
ALTER TABLE `jos_sfs_iatacodes` ADD `status` INT(1) NOT NULL DEFAULT '1' AFTER `currency_code`;

//pham vu

CREATE TABLE IF NOT EXISTS ` jos_sfs_codecanyon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `comment` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- lchung 24-11-2015
CREATE TABLE IF NOT EXISTS `jos_availability_totalstay_hotel_searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `roomtype` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `number_of_rooms` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 

//lchung add of task /340-make-sure-the-site-takes-as-wel-as
ALTER TABLE `jos_sfs_hotel_mealplans` ADD `custom_comma_decimal` TEXT NOT NULL AFTER `course_3`;
ALTER TABLE `jos_sfs_hotel_mealplans` CHANGE `course_1` `course_1` DECIMAL(11,2) UNSIGNED NOT NULL, CHANGE `course_2` `course_2` DECIMAL(11,2) UNSIGNED NOT NULL, CHANGE `course_3` `course_3` DECIMAL(11,2) UNSIGNED NOT NULL, CHANGE `bf_standard_price` `bf_standard_price` DECIMAL(11,2) UNSIGNED NOT NULL DEFAULT '0', CHANGE `bf_layover_price` `bf_layover_price` DECIMAL(11,2) UNSIGNED NOT NULL DEFAULT '0', CHANGE `lunch_standard_price` `lunch_standard_price` DECIMAL(10,2) UNSIGNED NOT NULL;

--lchung 23/12/2015
ALTER TABLE `jos_sfs_contractedrates` ADD `custom_comma_decimal` TEXT NOT NULL AFTER `dinner`;

ALTER TABLE `jos_sfs_contractedrates` ADD `max_rate` TEXT NOT NULL AFTER `custom_comma_decimal`;



-- lchung 09/03/2-16
ALTER TABLE `jos_sfs_hotel` ADD `main_24h_address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `fax`;


-- #322 21/01/2016
ALTER TABLE `jos_sfs_trace_passengers` ADD `invoice_number` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `flight_iatacode`, ADD `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `invoice_number`, ADD `insurance` TINYINT(1) NOT NULL AFTER `comment`, ADD `touroperator_client` TINYINT(1) NOT NULL AFTER `insurance`;

ALTER TABLE `jos_sfs_trace_passengers` ADD `invoice_status` TINYINT(1) NOT NULL AFTER `flight_iatacode`;
// Begin Managetemplate


CREATE TABLE IF NOT EXISTS `jos_sfs_managetemplate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name_airline` varchar(100) NOT NULL,
  `logo_airline` varchar(100) NOT NULL,
  `header_airline` varchar(100) NOT NULL,
  `color` varchar(50) NOT NULL,
  `created` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `jos_sfs_managetemplate`
--

INSERT INTO `jos_sfs_managetemplate` (`id`, `name_airline`, `logo_airline`, `header_airline`, `color`, `created`) VALUES
(1, 'airline1', 'media/upload/navigation_logos.png', 'media/upload/retina-header-bg.png', '#CC1F2F', '2016-01-19');

// End Managetemplate

-- lchung 03/02/2016
ALTER TABLE `jos_sfs_trace_passengers` CHANGE `invoice_status` `invoice_status` TINYINT(1) NOT NULL DEFAULT '0', CHANGE `insurance` `insurance` TINYINT(1) NOT NULL DEFAULT '3', CHANGE `touroperator_client` `touroperator_client` TINYINT(1) NOT NULL DEFAULT '3';

--lchung add columns 03/03/2016
ALTER TABLE `jos_sfs_trace_passengers`  ADD `pnr` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `invoice_number`,  ADD `booking_class` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `pnr`,  ADD `booking_id_import` BIGINT NOT NULL  AFTER `booking_class`,  ADD `booking_name_id` BIGINT NOT NULL  AFTER `booking_id_import`,  ADD `booking_name_item_id` BIGINT NOT NULL  AFTER `booking_name_id`,  ADD `cabin_class` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `booking_name_item_id`,  ADD `gender` VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `cabin_class`,  ADD `language` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `gender`,  ADD `office_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `language`,  ADD `ticket_number` BIGINT NOT NULL  AFTER `office_id`,  ADD `ticket_status` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `ticket_number`,  ADD `tour_operator` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  AFTER `ticket_status`,  ADD `ssr` INT(11) NOT NULL  AFTER `tour_operator`,  ADD `value001` INT(11) NOT NULL  AFTER `ssr`,  ADD `value002` INT(11) NOT NULL  AFTER `value001`,  ADD `value003` INT(11) NOT NULL  AFTER `value002`,  ADD `value004` INT(11) NOT NULL  AFTER `value003`,  ADD `value005` INT(11) NOT NULL  AFTER `value004`,  ADD `value006` INT(11) NOT NULL  AFTER `value005`;

ALTER TABLE `jos_sfs_trace_passengers` ADD `fqtv_number` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `value006`, ADD `fqtv_program` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `fqtv_number`;
--End lchung

--lchung add columns 24/03/2016
ALTER TABLE `jos_sfs_airline_airport` ADD `user_id` INT(11) NOT NULL AFTER `airport_id`;
--End lchung

-- lchung create 11/04/2016
CREATE TABLE IF NOT EXISTS `jos_sfs_flightinfo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `flight_date` varchar(255) NOT NULL,
  `fltref` varchar(255) NOT NULL,
  `carrier` varchar(255) NOT NULL,
  `flight_no` varchar(255) NOT NULL,
  `flight_no_suffix` varchar(255) NOT NULL,
  `registration` varchar(255) NOT NULL,
  `ac_type` varchar(255) NOT NULL,
  `ac_operator` varchar(255) NOT NULL,
  `ac_seats_total` varchar(255) NOT NULL,
  `dep` varchar(255) NOT NULL,
  `arr` varchar(255) NOT NULL,
  `div` varchar(255) NOT NULL,
  `std` varchar(255) NOT NULL,
  `sta` varchar(255) NOT NULL,
  `etd` varchar(255) NOT NULL,
  `eta` varchar(255) NOT NULL,
  `atd` varchar(255) NOT NULL,
  `ata` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `pax_exp_total` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
-- End lchung
--lchung add columns 05/04/2016
ALTER TABLE `jos_users` ADD `distance` TINYINT(1) NOT NULL AFTER `requireReset`;
--End lchung
-- lchung add more column 14/04/2016
ALTER TABLE `jos_sfs_flightinfo` ADD `airline_id` INT NOT NULL AFTER `id`;
-- End lchung 14/04/2016
=======
=======
--congphuc add table 6/4/2016
CREATE TABLE `sfs_dev7`.`jos_sfs_airline_trains` ( `id` INT(10) NOT NULL AUTO_INCREMENT , `iata_airportcode` VARCHAR(10) NOT NULL , `stationname` VARCHAR(50) NULL DEFAULT NULL , `cityname` VARCHAR(50) NULL DEFAULT NULL , `country` VARCHAR(50) NULL DEFAULT NULL,`status` TINYINT(1) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
---------------
---Begin CPhuc add table 21/04/2016
CREATE TABLE `jos_sfs_book_issue_trains` (
  `id` int(10) NOT NULL,
  `flight_number` varchar(10) NOT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `id_from_trainstation` int(10) NOT NULL,
  `id_to_trainstation` int(10) NOT NULL,
  `travel_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `jos_sfs_book_issue_trains`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flight_number` (`flight_number`);
ALTER TABLE `jos_sfs_book_issue_trains`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
---End CPhuc


--lchung add columns 29/03/2016

ALTER TABLE `jos_sfs_trace_passengers` ADD `fltref` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `title`, ADD `rebooked_fltno` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `fltref`, ADD `rebooked_fltref` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `rebooked_fltno`, ADD `email_address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `rebooked_fltref`, ADD `email_address` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `jos_sfs_trace_passengers` CHANGE `ssr` `ssrs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `jos_sfs_trace_passengers` ADD `baggage_status` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `ssrs`, ADD `checkin_status` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `baggage_status`, ADD `maas` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `checkin_status`;

-- End lchung
-------------Begin CPhuc Create table jos_sfs_title_of _airline 14/04/2016
CREATE TABLE `jos_sfs_title_of_airline` (
  `id` int(10) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `id_ariline` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `jos_sfs_title_of_airline`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jos_sfs_title_of_airline`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
-----------------------------------------------------end CPhuc
--------------Begin CPhuc Create table jos_sfs_info_issue_vouchers 15/04/2016
CREATE TABLE `jos_sfs_info_issue_vouchers` (
  `id` int(10) NOT NULL,
  `id_title_airline` int(10) NOT NULL,
  `id_passenger` int(10) NOT NULL,
  `description` text NOT NULL,
  `number_costs` float NOT NULL,
  `code_currency` varchar(5) NOT NULL,
  `iternal_coment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `jos_sfs_info_issue_vouchers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_title_airline` (`id_title_airline`),
  ADD KEY `code_currency` (`code_currency`);
ALTER TABLE `jos_sfs_info_issue_vouchers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jos_sfs_info_issue_vouchers` CHANGE `number_costs` `number_costs` DECIMAL(10,2) NOT NULL;
-------------------------end CPhuc
--------------Begin CPhuc Create table jos_sfs_title_of_airline 19/04/2016
CREATE TABLE `jos_sfs_title_of_airline` (
  `id` int(10) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `id_ariline` int(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `jos_sfs_title_of_airline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ariline` (`id_ariline`);
ALTER TABLE `jos_sfs_title_of_airline`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
-------------------------end CPhuc

--lchung add columns 05/04/2016
ALTER TABLE `jos_users` ADD `distance` TINYINT(1) NOT NULL AFTER `requireReset`;
--End lchung

----Cphuc add coluum 04/22/20016
ALTER TABLE `jos_sfs_currency` ADD `exchange_rate` FLOAT NULL AFTER `symbol`;
---end Cphuc
--minhtran
CREATE TABLE `jos_sfs_company_rental_car` (
  `id` int(11) NOT NULL,
  `company` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `airport_code` int(11) NOT NULL,
  `location_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `location_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `jos_sfs_company_rental_car`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jos_sfs_company_rental_car`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `jos_sfs_rental_car_location` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `agency` int(11) NOT NULL,
  `airportcode` int(11) NOT NULL,
  `locationname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `jos_sfs_rental_car_location`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jos_sfs_rental_car_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
DROP TABLE `jos_sfs_service_rental_car`;
CREATE TABLE `jos_sfs_service_rental_car` (
  `id` int(11) NOT NULL,
  `blockcode` varchar(30) CHARACTER SET latin1 NOT NULL,
  `blockdate` date NOT NULL,
  `rental_id` int(11) NOT NULL,
  `airline_id` int(11) NOT NULL,
  `booked_by` int(11) NOT NULL,
  `booked_date` datetime NOT NULL,
  `airport_code` int(11) NOT NULL,
  `pick_up` int(11) NOT NULL,
  `drop_off` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `jos_sfs_service_rental_car`
  ADD PRIMARY KEY (`id`);

CREATE TABLE `jos_sfs_internal_comment` (
  `id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `comment` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `airline_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `jos_sfs_internal_comment`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jos_sfs_internal_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
  -------create group on page passenger import
  CREATE TABLE `jos_sfs_group_share_room` (
  `id` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
ALTER TABLE `jos_sfs_group_share_room`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jos_sfs_group_share_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `jos_sfs_group_share_room` ADD `pnr` VARCHAR(50) NOT NULL AFTER `created_by`;
  
CREATE TABLE `jos_sfs_status_share_room` (
  `id` int(11) NOT NULL,
  `type_room` int(4) NOT NULL,
  `status_share_room` int(1) NOT NULL,
  `travel_together` int(1) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `jos_sfs_status_share_room`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jos_sfs_status_share_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  CREATE TABLE `jos_sfs_detail_group_share_room` (
  `id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pnr` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` int(11) NOT NULL,
  `share_room_id` int(11) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `jos_sfs_detail_group_share_room`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jos_sfs_detail_group_share_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-------End create group on page passenger import
------- Create table service
CREATE TABLE `jos_sfs_services` (
  `id` int(11) NOT NULL,
  `name_service` varchar(255) CHARACTER SET latin1 NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `create_date` date NOT NULL,
  `status` int(1) NOT NULL,
  `order_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `jos_sfs_services`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jos_sfs_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
------- End Create table service

---- Create table passenger service

CREATE TABLE `jos_sfs_passenger_service` (
  `id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `jos_sfs_passenger_service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

ALTER TABLE `jos_sfs_passenger_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

---- End Create table passenger service

----Create table passenger location station
CREATE TABLE `jos_sfs_passenger_local_station` (
  `id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `jos_sfs_passenger_local_station`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jos_sfs_passenger_local_station`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

----Create table passenger location station

--- Create table assign passenger for user
CREATE TABLE `jos_sfs_assign_station_airline_add_issue` (
  `id` int(11) NOT NULL,
  `passenger_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Use function add issue passenger import';

ALTER TABLE `jos_sfs_assign_station_airline_add_issue`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jos_sfs_assign_station_airline_add_issue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--- Create table assign passenger for user

---create new_pnr
ALTER TABLE `jos_sfs_trace_passengers` ADD `new_pnr` VARCHAR(50) NOT NULL AFTER `pnr`;
---create new_pnr

ALTER TABLE `jos_sfs_company_rental_car` ADD `price_default` DECIMAL(10,3) NOT NULL AFTER `location_code`;
ALTER TABLE `jos_sfs_company_rental_car` ADD `currency_id` INT(11) NOT NULL AFTER `price_default`;
ALTER TABLE `jos_sfs_group_share_room` ADD `price_rental_car` DECIMAL(10,3) NOT NULL AFTER `pnr`;
ALTER TABLE `jos_sfs_group_share_room` ADD `price_rental_car_default` DECIMAL(10,3) NOT NULL AFTER `price_rental_car`;
ALTER TABLE `jos_sfs_group_share_room` ADD `total` INT(11) NOT NULL AFTER `price_rental_car_default`;

ALTER TABLE `jos_sfs_trace_passengers` ADD `guest_relations_id` INT(11) NOT NULL AFTER `touroperator_client`;

ALTER TABLE `jos_sfs_contractedrates` ADD `two_course_dinner` DECIMAL(11,2) NOT NULL AFTER `dinner`;
  ALTER TABLE `jos_sfs_contractedrates` ADD `three_course_dinner` DECIMAL(11,2) NOT NULL AFTER `two_course_dinner`;
  
ALTER TABLE `jos_sfs_trace_passengers` ADD `status_group` TINYINT(1) NOT NULL COMMENT '0 : ungroup; 1: group' AFTER `phonenumber_ssr`;



CREATE TABLE `jos_sfs_point_priority` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type_group` int(10) NOT NULL COMMENT '1: fqtvStatus; 2:IrregReason; 3:SSR',
  `point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `jos_sfs_point_priority`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `jos_sfs_point_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--End minhtran
----------CPhuc add column 22/04/2016
-----------------Creater Table
CREATE TABLE `jos_sfs_option_title_airline` (
  `id` int(10) NOT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `jos_sfs_option_title_airline`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `jos_sfs_option_title_airline`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
-----------------Alter Table
ALTER TABLE `jos_sfs_info_issue_vouchers` ADD `service_type` TINYINT(10) NULL COMMENT 'Other:1,Cash:2' AFTER `iternal_coment`;

ALTER TABLE `jos_sfs_title_of_airline` ADD `option` TINYINT(10) NULL AFTER `status`;
ALTER TABLE `jos_sfs_title_of_airline` CHANGE `option` `id_option` INT(10) NULL DEFAULT NULL;
-----------End CPhuc
-----------End CPhuc

ALTER TABLE `jos_sfs_title_of_airline` ADD `option` TINYINT(10) NULL AFTER `status`;
ALTER TABLE `jos_sfs_title_of_airline` CHANGE `option` `id_option` INT(10) NULL DEFAULT NULL;
-----------End CPhuc

ALTER TABLE `jos_sfs_currency` ADD `exchange_rate` FLOAT NULL AFTER `symbol`;
ALTER TABLE `jos_sfs_currency` CHANGE `exchange_rate` `exchange_rate` DECIMAL(10,3) NULL DEFAULT NULL;
ALTER TABLE `jos_sfs_title_of_airline` ADD `option` TINYINT(10) NULL AFTER `status`;
ALTER TABLE `jos_sfs_title_of_airline` CHANGE `option` `id_option` INT(10) NULL DEFAULT NULL;
-----------End CPhuc

//lchung add more 04/05/2016
ALTER TABLE `jos_sfs_flightinfo` ADD `return_to_ramp` TINYTEXT NOT NULL AFTER `div`;
ALTER TABLE `jos_sfs_flightinfo` ADD `delay` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `ata`;
ALTER TABLE `jos_sfs_flightinfo` ADD `irreg_reason` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `pax_exp_total`, ADD `irreg_message` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `irreg_reason`, ADD `gate_info` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `irreg_message`;
//End lchung add more 04/05/2016

ALTER TABLE `jos_sfs_title_of_airline` ADD `option` TINYINT(10) NULL AFTER `status`;
ALTER TABLE `jos_sfs_title_of_airline` CHANGE `option` `id_option` INT(10) NULL DEFAULT NULL;
-----------End CPhuc

ALTER TABLE `jos_sfs_currency` ADD `exchange_rate` FLOAT NULL AFTER `symbol`;
ALTER TABLE `jos_sfs_currency` CHANGE `exchange_rate` `exchange_rate` DECIMAL(10,3) NULL DEFAULT NULL;

ALTER TABLE `jos_sfs_currency` ADD `exchange_rate` FLOAT NULL AFTER `symbol`;
ALTER TABLE `jos_sfs_currency` CHANGE `exchange_rate` `exchange_rate` DECIMAL(10,3) NULL DEFAULT NULL;

---------------CPhuc add column 9/5/2016
ALTER TABLE `jos_sfs_book_issue_trains` ADD `type` TINYINT(1) NOT NULL COMMENT 'Issue Train(1) TrainTicket(2)' AFTER `travel_date`;
ALTER TABLE `jos_sfs_book_issue_trains` CHANGE `type` `type` TINYINT(1) NOT NULL COMMENT '1:Issue Train, 2:TrainTicket';
---------------end column
--//lchung 12/05/2016

CREATE TABLE IF NOT EXISTS `jos_sfs_group_transport_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `airline_id` int(11) NOT NULL,
  `booked_by_phone` tinyint(1) NOT NULL,
  `name_company` varchar(255) CHARACTER SET utf8 NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 NOT NULL,
  `numseater` int(11) NOT NULL,
  `priceseater` decimal(10,2) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `date_expire_time` datetime NOT NULL,
  `airline_airport_id` int(11) NOT NULL,
  `airport_id` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jos_sfs_group_transport_company_other_price` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `group_transport_company_id` bigint(20) NOT NULL,
  `priceseater` decimal(10,2) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `airline_airport_id` int(11) NOT NULL,
  `airport_id` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `jos_sfs_passenger_group_transport_company_map` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `passenger_id` int(11) NOT NULL,
  `passenger_group_transport_company_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--/End lchung 12/05/2016

// Begin CPhuc 13/05/2016
CREATE TABLE `jos_sfs_book_issue_taxi` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `airport_id` INT(11) NULL , `hotel_id` INT(11) NULL , `option_taxi` TINYINT(1) NULL COMMENT '1:Airport_to_hotel,2:return_to_airport,3:only_hotel_to_airport,4:other_option_taxi' , `total_price` DECIMAL(10,3) NULL , `taxi_id` INT(11) NULL , `first_name` VARCHAR(50) NULL , `last_name` VARCHAR(50) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
// End CPhuc
CREATE TABLE `jos_sfs_issue_refreshment` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `valamount` DECIMAL(10,2) NULL , `currency` VARCHAR(5) NULL , `flight_num` VARCHAR(10) NULL , `delaytime` INT(2) NULL , `first_name` VARCHAR(50) NULL , `last_name` VARCHAR(50) NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `jos_sfs_issue_refreshment` DROP `first_name`;
ALTER TABLE `jos_sfs_issue_refreshment` CHANGE `last_name` `passenger_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `jos_sfs_issue_refreshment` DROP `flight_num`;

ALTER TABLE `jos_sfs_book_issue_taxi` DROP `first_name`;
ALTER TABLE `jos_sfs_book_issue_taxi` CHANGE `last_name` `passenger_id` INT(11) NULL DEFAULT NULL;
// End CPhuc
// begin CPhuc 14/05/2016
ALTER TABLE `jos_sfs_issue_refreshment` ADD `textfresh` TEXT NULL AFTER `currency`;
ALTER TABLE `jos_sfs_issue_refreshment` CHANGE `delaytime` `delaytime` VARCHAR(10) NULL DEFAULT NULL;
// end CPhuc
// Begin CPHuc
ALTER TABLE `jos_sfs_book_issue_trains` CHANGE `first_name` `passenger_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `jos_sfs_book_issue_trains` DROP `last_name`;
ALTER TABLE `jos_sfs_book_issue_trains` DROP `flight_number`;
ALTER TABLE `jos_sfs_book_issue_trains` CHANGE `id` `id` BIGINT NOT NULL AUTO_INCREMENT;
// End CPhuc
// Begin Phuc
ALTER TABLE `jos_sfs_book_issue_taxi` ADD `from_andress` VARCHAR(50) NULL AFTER `distance`, ADD `to_address` VARCHAR(50) NULL AFTER `from_andress`;
ALTER TABLE `jos_sfs_info_issue_vouchers` CHANGE `group_id` `passenger_id` INT(11) NOT NULL;
ALTER TABLE `jos_sfs_info_issue_vouchers` ADD `title_airline` VARCHAR(50) NULL AFTER `service_type`;
// End Phuc
// Begin Phuc 24/05/2016
CREATE TABLE `jos_sfs_airport_taxicompany_map` ( `airport_id` INT(11) NOT NULL , `taxi_id` INT(11) NOT NULL ) ENGINE = InnoDB;

ALTER TABLE `jos_sfs_taxi_companies` ADD `contact_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `name`;
ALTER TABLE `jos_sfs_taxi_companies` ADD `address2` VARCHAR(120) NULL AFTER `address`;
ALTER TABLE `jos_sfs_taxi_companies` ADD `mobile_phone` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `telephone`;
ALTER TABLE `jos_sfs_taxi_companies` ADD `billing_mobile_phone` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `billing_telephone`;
ALTER TABLE `jos_sfs_taxi_companies` ADD `billing_address_name1` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `billing_address`, ADD `billing_address_name2` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `billing_address_name1`;
ALTER TABLE `jos_sfs_taxi_companies` ADD `billing_mail` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `billing_fax`;
//end Phuc
// Begin Phuc 25/05/2016
ALTER TABLE `jos_sfs_book_issue_taxi` ADD `way_option` TINYINT(1) NOT NULL COMMENT '1:One Way , 2:Two Way' AFTER `to_address`;
//end Phuc
//Begin Phuc 01/06/20016
ALTER TABLE `jos_sfs_services` ADD `parent_id` TINYINT(2) NULL AFTER `order_by`;
ALTER TABLE `jos_sfs_trace_passengers` CHANGE `irreg_reason` `irreg_reason` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Save ID sub other service';
ALTER TABLE `jos_sfs_trace_passengers` CHANGE `irreg_reason` `irreg_reason` BIGINT NOT NULL COMMENT 'Save ID sub other service';
// end Phuc
//begin Phuc 3/6/2016
RENAME TABLE  jos_sfs_info_issue_vouchers TO jos_sfs_info_other_services
ALTER TABLE `jos_sfs_info_issue_vouchers` DROP `id_title_airline`;
ALTER TABLE `jos_sfs_info_issue_vouchers` CHANGE `description` `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `jos_sfs_info_issue_vouchers` DROP `number_costs`, DROP `code_currency`, DROP `iternal_coment`, DROP `service_type`, DROP `title_airline`;
ALTER TABLE `jos_sfs_info_other_services` CHANGE `id` `id` BIGINT NOT NULL AUTO_INCREMENT;
RENAME TABLE `jos_sfs_info_issue_vouchers` TO `jos_sfs_info_other_services`;
ALTER TABLE `jos_sfs_info_other_services` ADD `sub_service_id` BIGINT NULL AFTER `passenger_id`;
ALTER TABLE `jos_sfs_passenger_service` CHANGE `id` `id` BIGINT NOT NULL AUTO_INCREMENT;
//end Phuc

//begin Phuc 08/06/2016
ALTER TABLE `jos_sfs_trace_passengers` CHANGE `irreg_reason` `irreg_reason` VARCHAR(500) NOT NULL COMMENT 'Save ID sub other service';
//end Phuc
//begin Phuc 13/06/2016
ALTER TABLE `jos_sfs_issue_refreshment` ADD `block_code` VARCHAR(100) NULL AFTER `passenger_id`;
ALTER TABLE `jos_sfs_issue_refreshment` DROP `block_code`;
ALTER TABLE `jos_sfs_passenger_service` ADD `block_code` VARCHAR(100) NULL AFTER `service_id`;
//end Phuc

--- lchung 14/06/2016
ALTER TABLE `jos_sfs_communication_partners` ADD `nametype` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `name`;

ALTER TABLE `jos_sfs_communication_partners` ADD `department` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `stationcode`;

ALTER TABLE `jos_sfs_communication_partners` ADD `grouptype` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `department`;

ALTER TABLE `jos_sfs_communication_partners` ADD `category` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `grouptype`;

ALTER TABLE `jos_sfs_communication_partners` ADD `tourop` VARCHAR(3) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `nametype`;

--- End lchung 14/06/2016

//begin 23/06/2016
ALTER TABLE `jos_sfs_services` ADD `icon_service` VARCHAR(255) NOT NULL AFTER `logo`;
//end

--- lchung 30/06/2016
ALTER TABLE `jos_sfs_communication_partners` ADD `typ` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `sitamessage`;
--- End lchung 30/06/2016
//Begin CPhuc 01/07/2016
CREATE TABLE `jos_sfs_airline_currency` ( `user_id` INT NOT NULL , `currency_id` INT NOT NULL ) ENGINE = InnoDB;
//end

//begin Cphuc 06/07/2016
ALTER TABLE `jos_sfs_passenger_service` ADD `vouchercodes` VARCHAR(20) NULL AFTER `price_per_person`;
//end 

//lchung add 11/07/2016
ALTER TABLE `jos_sfs_flightinfo` ADD `date_timestamp` DATETIME NOT NULL AFTER `id`;
ALTER TABLE `jos_sfs_trace_passengers` ADD `date_timestamp` DATETIME NOT NULL AFTER `id`;
//End lchung 11/07/2016

//begin CPhuc 11/07/2016
ALTER TABLE `jos_sfs_country` ADD `country_numeric` INT(3) NULL AFTER `flag`;
ALTER TABLE `jos_sfs_country` ADD `default_currency` VARCHAR(3) NULL AFTER `country_numeric`;
//end

//lchung add 14/07/2016
DROP TABLE jos_sfs_history_api_flightinfo;

CREATE TABLE IF NOT EXISTS `jos_sfs_history_api_flightinfo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_timestamp` datetime NOT NULL,
  `airline_id` int(11) NOT NULL,
  `flight_date` varchar(255) NOT NULL,
  `fltref` varchar(255) NOT NULL,
  `carrier` varchar(255) NOT NULL,
  `flight_no` varchar(255) NOT NULL,
  `flight_no_suffix` varchar(255) NOT NULL,
  `registration` varchar(255) NOT NULL,
  `ac_type` varchar(255) NOT NULL,
  `ac_operator` varchar(255) NOT NULL,
  `ac_seats_total` varchar(255) NOT NULL,
  `dep` varchar(3) CHARACTER SET utf8 NOT NULL,
  `arr` varchar(3) CHARACTER SET utf8 NOT NULL,
  `div` varchar(255) NOT NULL,
  `return_to_ramp` tinytext NOT NULL,
  `std` varchar(255) NOT NULL,
  `sta` varchar(255) NOT NULL,
  `etd` varchar(255) NOT NULL,
  `eta` varchar(255) NOT NULL,
  `atd` varchar(255) NOT NULL,
  `ata` varchar(255) NOT NULL,
  `delay` text CHARACTER SET utf8 NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `pax_exp_total` varchar(255) NOT NULL,
  `irreg_reason` varchar(500) CHARACTER SET utf8 NOT NULL,
  `irreg_message` text CHARACTER SET utf8 NOT NULL,
  `gate_info` varchar(255) CHARACTER SET utf8 NOT NULL,
  `invoicing_flag` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
//End lchung add 14/07/2016

//lchung add 15/07/2016
DROP TABLE IF EXISTS `jos_sfs_history_api_passenger_push`;
CREATE TABLE IF NOT EXISTS `jos_sfs_history_api_passenger_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_timestamp` datetime NOT NULL,
  `voucher_id` int(10) unsigned NOT NULL,
  `flight_number` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `airport_id` int(11) NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rawname` varchar(255) CHARACTER SET utf8 NOT NULL,
  `fltno` varchar(255) CHARACTER SET utf8 NOT NULL,
  `connections` text CHARACTER SET utf8 NOT NULL,
  `irreg_message_sent` tinyint(1) NOT NULL,
  `fltref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `rebooked_fltno` varchar(255) CHARACTER SET utf8 NOT NULL,
  `rebooked_fltref` varchar(255) CHARACTER SET utf8 NOT NULL,
  `email_address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `individual_voucher` int(10) unsigned NOT NULL DEFAULT '0',
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(1) unsigned NOT NULL COMMENT '0: aldult, 1: child, 2: infant',
  `phone_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `room_type` smallint(1) unsigned NOT NULL,
  `voucher_room_id` int(11) DEFAULT NULL,
  `airplus_id` int(11) NOT NULL,
  `created_date` date NOT NULL,
  `created` date NOT NULL,
  `airline_id` int(11) NOT NULL,
  `flight_code` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `flight_iatacode` int(11) NOT NULL,
  `invoice_status` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_number` varchar(150) CHARACTER SET utf8 NOT NULL,
  `pnr` varchar(50) CHARACTER SET utf8 NOT NULL,
  `new_pnr` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `booking_class` varchar(10) CHARACTER SET utf8 NOT NULL,
  `booking_id_import` bigint(20) NOT NULL,
  `booking_name_id` bigint(20) NOT NULL,
  `booking_name_item_id` bigint(20) NOT NULL,
  `cabin_class` varchar(10) CHARACTER SET utf8 NOT NULL,
  `gender` varchar(11) CHARACTER SET utf8 NOT NULL,
  `language` varchar(255) CHARACTER SET utf8 NOT NULL,
  `office_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ticket_number` bigint(20) NOT NULL,
  `ticket_status` varchar(10) CHARACTER SET utf8 NOT NULL,
  `tour_operator` varchar(10) CHARACTER SET utf8 NOT NULL,
  `ssrs` text CHARACTER SET utf8 NOT NULL,
  `baggage_status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `checkin_status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `irreg_reason` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Save ID sub other service',
  `maas` varchar(255) CHARACTER SET utf8 NOT NULL,
  `value001` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value002` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value003` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value004` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value005` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value006` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status_issuevoucher` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if value == 0 the default, 1 == send email, 2 == send SMS, 3== printed',
  `fqtv_number` varchar(50) CHARACTER SET utf8 NOT NULL,
  `fqtv_program` varchar(50) CHARACTER SET utf8 NOT NULL,
  `fqtv_status` varchar(150) CHARACTER SET utf8 NOT NULL,
  `comment` text CHARACTER SET utf8 NOT NULL,
  `insurance` tinyint(1) NOT NULL DEFAULT '3',
  `touroperator_client` tinyint(1) NOT NULL DEFAULT '3',
  `guest_relations_id` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `emailaddress_ssr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phonenumber_ssr` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status_group` tinyint(1) NOT NULL COMMENT '0 : ungroup; 1: group',
  `invoicing_flag` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
//End lchung add 15/07/2016
