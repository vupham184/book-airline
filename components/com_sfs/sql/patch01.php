<?php /*

-- This is db update for WS
-- Please don't clean this file

ALTER TABLE  `jos_sfs_hotel` ADD  `ws_type` VARCHAR( 20 ) NULL DEFAULT NULL AFTER  `id` ,
ADD  `ws_id` VARCHAR( 21 ) NULL DEFAULT NULL AFTER  `ws_type`;

ALTER TABLE  `jos_sfs_hotel` ADD UNIQUE `ws_unique_reference` (
`ws_type` ,
`ws_id`
);

ALTER TABLE  `jos_sfs_reservations` ADD  `ws_room_type` TEXT NULL DEFAULT NULL AFTER  `association_id` ,
ADD  `ws_prebooking` TEXT NULL DEFAULT NULL AFTER  `ws_room_type` ,
ADD  `ws_booking` TEXT NULL DEFAULT NULL AFTER  `ws_prebooking` ,
ADD  `ws_room` INT UNSIGNED NULL DEFAULT NULL AFTER  `ws_booking`;

ALTER TABLE  `jos_sfs_passengers` ADD  `title` VARCHAR( 20 ) NULL AFTER  `voucher_id`;
ALTER TABLE  `jos_sfs_airline_details` ADD  `partner_limit_for_extra_search` INT NULL COMMENT 'When the search result has more than XX partner limited by this setting, the extra search won''t be triggered automatically.' AFTER  `airport_ring_2_mile`;

ALTER TABLE  `jos_sfs_hotel` ADD  `last_room_load_request_date` DATETIME NULL;
