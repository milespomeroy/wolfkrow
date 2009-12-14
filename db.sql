#
# Encoding: Unicode (UTF-8)
#


DROP TABLE IF EXISTS `accounts`;
DROP TABLE IF EXISTS `meals`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `ratings`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `services_for_orders`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `vendor_applications`;
DROP TABLE IF EXISTS `vendor_types`;
DROP TABLE IF EXISTS `vendors`;


CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cc_number` varchar(20) DEFAULT 'demo' COMMENT 'obviously not secure',
  `cc_type` varchar(20) DEFAULT NULL COMMENT 'visa or mastercard',
  `cc_security_code` int(5) DEFAULT '0',
  `billing_name` varchar(50) DEFAULT NULL,
  `billing_address` text,
  `cc_exp_month` int(2) DEFAULT NULL,
  `cc_exp_year` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COMMENT='probably not the proper way to store cc info';


CREATE TABLE `meals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time_started` datetime DEFAULT NULL,
  `time_finished` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meals_user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8 COMMENT='A record for each time a guest comes in for a meal.';


CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `meal_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `total_price` decimal(7,2) DEFAULT '0.00',
  `activated_date` datetime DEFAULT NULL COMMENT 'mark when order is up',
  `filled` datetime DEFAULT NULL COMMENT 'boolean when order has been fulfilled',
  PRIMARY KEY (`id`),
  KEY `orders_user_id` (`user_id`) USING BTREE,
  KEY `orders_meal_id` (`meal_id`) USING BTREE,
  KEY `orders_vendor_id` (`vendor_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;


CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT '0' COMMENT 'vendor for whom the rating is for',
  `user_id` int(11) DEFAULT '0' COMMENT 'guest who is rating',
  `order_id` int(11) DEFAULT '0',
  `rating` tinyint(1) DEFAULT '0' COMMENT 'star rating 1 to 5 scale',
  `rated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rating_vendor_id` (`vendor_id`) USING BTREE,
  KEY `rating_order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;


CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `vendor_type_id` int(11) DEFAULT '0' COMMENT 'link additional service to vendor type',
  `price` decimal(7,2) DEFAULT '0.00' COMMENT 'only for non-standard additional services. currently fixed price for these services, could change to allow vendors to configure',
  `standard` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `s_vendor_type_id` (`vendor_type_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='Additional services that can by offered by vendors.';


CREATE TABLE `services_for_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT '0' COMMENT 'from orders table',
  `service_id` int(11) DEFAULT '0' COMMENT 'from services table',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE,
  KEY `service_id` (`service_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=195 DEFAULT CHARSET=utf8 COMMENT='Lookup between orders and additional services requested.';


CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giver_account` int(11) DEFAULT NULL COMMENT 'account id of giver of money',
  `recipient_account` int(11) DEFAULT NULL COMMENT 'account id of recipient of funds',
  `order_id` int(11) DEFAULT NULL COMMENT 'careful to distinguish giver when taking total cost of meals',
  `amount` decimal(7,2) DEFAULT '0.00',
  `sale_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `giver_account` (`giver_account`) USING BTREE,
  KEY `recipient_account` (`recipient_account`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COMMENT='monetary transactions between guests, vendors, and manager.';


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(32) DEFAULT NULL COMMENT 'md5 encryption',
  `user_type` varchar(20) DEFAULT NULL COMMENT 'guest, vendor, or manager',
  `account_id` int(11) DEFAULT NULL,
  `flag` varchar(1) DEFAULT NULL COMMENT 'to mark special event like needing to change password',
  `deactive` datetime DEFAULT NULL COMMENT 'to expire user account',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `vendor_name` varchar(50) DEFAULT NULL,
  `vendor_type_id` int(11) DEFAULT NULL,
  `vendor_qualifications` mediumtext,
  `offer` varchar(20) DEFAULT NULL COMMENT 'hire or deny',
  `offer_date` datetime DEFAULT NULL,
  `activated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'like host, waiter, etc',
  `description` text,
  `workflow_order` int(2) DEFAULT '99' COMMENT 'could be used to allow configuration of vendor order in meal workflow',
  `percent_fee` decimal(5,2) DEFAULT '0.00' COMMENT 'Percentage of each transaction that goes to the vendor.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL COMMENT 'from vendor_types table',
  `qualifications` mediumtext,
  `price` decimal(7,2) DEFAULT '0.00' COMMENT 'for standard package',
  `user_id` int(11) DEFAULT NULL COMMENT 'user id associated with vendor can change',
  `deactive` datetime DEFAULT NULL COMMENT 'used to fire (1) a vendor',
  PRIMARY KEY (`id`),
  KEY `v_type_id` (`type_id`) USING BTREE,
  KEY `v_user_id` (`user_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='vendor information for public listing';




SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `accounts` WRITE;
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (4, 'demo', 'visa', 0, 'demo name', '123 address', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (3, 'demo', 'visa', 0, 'demo name', '123 address', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (5, 'demo', 'visa', 0, 'demo name', '123 address', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (6, 'demo', 'visa', 0, 'demo name', '123 address', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (7, 'demo', 'visa', 0, 'demo name', '123 address', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (8, 'demo', 'visa', 0, 'demo name', '123 address', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (9, 'admin', 'mastercard', 0, 'The Admin', 'here', 12, 2010);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (11, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (12, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (13, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (14, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (15, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (16, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (17, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (18, '0', '0', 0, '0', '0', 0, 0);
INSERT INTO `accounts` (`id`, `cc_number`, `cc_type`, `cc_security_code`, `billing_name`, `billing_address`, `cc_exp_month`, `cc_exp_year`) VALUES (19, '0', '0', 0, '0', '0', 0, 0);
UNLOCK TABLES;


LOCK TABLES `meals` WRITE;
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (22, 26, '2009-12-12 19:55:13', NULL);
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (26, 12, '2009-12-13 01:10:54', '2009-12-13 21:11:22');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (25, 12, '2009-12-13 00:27:12', '2009-12-13 01:10:53');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (24, 12, '2009-12-13 00:25:04', '2009-12-13 00:27:12');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (23, 12, '2009-12-12 23:17:47', '2009-12-13 00:25:04');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (20, 13, '2009-12-12 01:13:20', NULL);
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (21, 25, '2009-12-12 19:54:53', NULL);
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (14, 13, '2009-12-07 20:42:07', '2009-12-12 01:13:20');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (16, 12, '2009-12-07 20:45:31', '2009-12-12 14:06:01');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (27, 12, '2009-12-13 21:11:22', '2009-12-13 21:42:41');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (28, 12, '2009-12-13 21:42:41', '2009-12-13 21:44:49');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (29, 12, '2009-12-13 21:44:49', '2009-12-13 21:52:07');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (30, 12, '2009-12-13 21:52:07', '2009-12-13 22:20:24');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (31, 12, '2009-12-13 22:20:25', '2009-12-13 23:53:43');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (32, 12, '2009-12-13 23:53:43', '2009-12-14 01:48:52');
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (33, 12, '2009-12-14 01:48:52', NULL);
INSERT INTO `meals` (`id`, `user_id`, `time_started`, `time_finished`) VALUES (34, 29, '2009-12-14 02:19:49', NULL);
UNLOCK TABLES;


LOCK TABLES `orders` WRITE;
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (45, 12, 16, 4, 1.23, '2009-12-09 22:19:38', '0000-00-00 00:00:00');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (56, 13, 20, 2, 78.56, NULL, NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (36, 13, 14, 4, 1.23, '2009-12-12 01:13:20', '2009-12-12 01:13:20');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (55, 13, 20, 8, 3.50, '2009-12-12 01:15:32', NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (40, 12, 16, 6, 5.89, '2009-12-07 20:45:38', '2009-12-08 00:09:35');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (43, 12, 16, 2, 78.56, '2009-12-08 00:09:35', '2009-12-09 22:19:38');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (54, 13, 20, 9, 8.65, '2009-12-12 01:14:40', '2009-12-12 01:15:32');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (53, 13, 14, 11, 7.00, '2009-12-12 01:13:20', '2009-12-12 01:13:20');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (52, 13, 14, 10, 20.59, '2009-12-11 23:48:48', '2009-12-12 01:13:20');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (51, 12, 16, 11, 7.00, '0000-00-00 00:00:00', '2009-12-12 14:06:01');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (50, 13, 14, 2, 78.56, '2009-12-12 01:13:20', '2009-12-12 01:13:20');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (57, 13, 20, 11, 7.00, NULL, NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (58, 12, 23, 9, 8.65, '2009-12-12 23:54:56', '2009-12-13 00:25:04');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (59, 12, 23, 8, 3.50, '2009-12-13 00:25:04', '2009-12-13 00:25:04');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (60, 12, 23, 2, 78.56, '2009-12-13 00:25:04', '2009-12-13 00:25:04');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (61, 12, 23, 11, 7.00, '2009-12-13 00:25:04', '2009-12-13 00:25:04');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (62, 12, 24, 9, 8.65, '2009-12-13 00:25:07', '2009-12-13 00:27:12');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (63, 12, 24, 4, 1.23, '2009-12-13 00:27:12', '2009-12-13 00:27:12');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (64, 12, 24, 2, 78.56, '2009-12-13 00:27:12', '2009-12-13 00:27:12');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (65, 12, 24, 11, 7.00, '2009-12-13 00:27:12', '2009-12-13 00:27:12');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (66, 12, 25, 10, 20.59, '2009-12-13 00:27:15', '2009-12-13 01:10:53');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (67, 12, 25, 8, 3.50, '2009-12-13 01:10:53', '2009-12-13 01:10:53');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (68, 12, 25, 2, 78.56, '2009-12-13 01:10:53', '2009-12-13 01:10:53');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (69, 12, 25, 11, 7.00, '2009-12-13 01:10:53', '2009-12-13 01:10:53');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (70, 12, 26, 9, 8.65, '2009-12-13 01:10:56', '2009-12-13 21:11:22');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (71, 12, 26, 4, 1.23, '2009-12-13 21:11:22', '2009-12-13 21:11:22');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (72, 12, 26, 2, 78.56, '2009-12-13 21:11:22', '2009-12-13 21:11:22');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (73, 12, 26, 11, 7.00, '2009-12-13 21:11:22', '2009-12-13 21:11:22');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (74, 12, 27, 9, 8.65, '2009-12-13 21:11:25', '2009-12-13 21:42:41');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (75, 12, 27, 8, 3.50, '2009-12-13 21:42:41', '2009-12-13 21:42:41');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (76, 12, 27, 2, 78.56, '2009-12-13 21:42:41', '2009-12-13 21:42:41');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (77, 12, 27, 11, 7.00, '2009-12-13 21:42:41', '2009-12-13 21:42:41');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (78, 12, 28, 9, 8.65, '2009-12-13 21:42:44', '2009-12-13 21:44:49');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (79, 12, 28, 3, 8.52, '2009-12-13 21:44:49', '2009-12-13 21:44:49');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (80, 12, 28, 2, 78.56, '2009-12-13 21:44:49', '2009-12-13 21:44:49');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (81, 12, 28, 11, 7.00, '2009-12-13 21:44:49', '2009-12-13 21:44:49');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (82, 12, 29, 6, 5.89, '2009-12-13 21:44:54', '2009-12-13 21:52:07');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (83, 12, 29, 3, 8.52, '2009-12-13 21:52:07', '2009-12-13 21:52:07');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (84, 12, 29, 2, 78.56, '2009-12-13 21:52:07', '2009-12-13 21:52:07');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (85, 12, 29, 11, 7.00, '2009-12-13 21:52:07', '2009-12-13 21:52:07');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (86, 12, 30, 9, 8.65, '2009-12-13 21:52:34', '2009-12-13 22:20:24');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (87, 12, 30, 4, 1.23, '2009-12-13 21:56:44', '2009-12-13 22:20:24');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (88, 12, 30, 2, 78.56, '2009-12-13 22:20:24', '2009-12-13 22:20:24');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (89, 12, 30, 11, 7.00, '2009-12-13 22:20:24', '2009-12-13 22:20:24');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (90, 12, 31, 9, 8.65, '2009-12-13 22:29:22', '2009-12-13 23:53:43');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (91, 12, 31, 8, 3.50, '2009-12-13 23:53:43', '2009-12-13 23:53:43');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (92, 12, 31, 12, 90.23, '2009-12-13 23:53:43', '2009-12-13 23:53:43');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (93, 12, 31, 11, 7.00, '2009-12-13 23:53:43', '2009-12-13 23:53:43');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (95, 12, 32, 10, 20.59, '2009-12-14 00:35:43', '2009-12-14 01:48:52');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (96, 12, 32, 8, 3.50, '2009-12-14 01:48:52', '2009-12-14 01:48:52');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (97, 12, 32, 13, 1.99, '2009-12-14 01:48:52', '2009-12-14 01:48:52');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (98, 12, 32, 11, 7.00, '2009-12-14 01:48:52', '2009-12-14 01:48:52');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (99, 12, 33, 6, 5.89, '2009-12-14 01:51:49', '2009-12-14 02:31:47');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (100, 12, 33, 3, 8.52, '2009-12-14 02:31:47', '2009-12-14 02:48:18');
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (101, 12, 33, 2, 78.56, '2009-12-14 02:48:18', NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (102, 29, 34, 6, 5.89, '2009-12-14 02:19:54', NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (103, 29, 34, 4, 1.23, NULL, NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (104, 29, 34, 2, 78.56, NULL, NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (105, 29, 34, 14, 6.50, NULL, NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (107, 12, 33, 14, 6.50, NULL, NULL);
INSERT INTO `orders` (`id`, `user_id`, `meal_id`, `vendor_id`, `total_price`, `activated_date`, `filled`) VALUES (108, 25, 21, 10, 20.59, '2009-12-14 03:43:46', NULL);
UNLOCK TABLES;


LOCK TABLES `ratings` WRITE;
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (1, 8, 12, 67, 4, '2009-12-13 00:38:32');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (2, 2, 12, 68, 4, '2009-12-13 00:41:27');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (3, 11, 12, 69, 5, '2009-12-13 00:41:33');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (4, 10, 12, 66, 3, '2009-12-13 01:02:22');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (5, 9, 12, 70, 4, '2009-12-13 01:20:05');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (6, 11, 12, 73, 4, '2009-12-13 01:20:18');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (7, 4, 12, 71, 3, '2009-12-13 01:26:19');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (8, 2, 12, 72, 4, '2009-12-13 21:00:21');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (9, 8, 12, 75, 3, '2009-12-13 21:19:51');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (11, 2, 12, 76, 4, '2009-12-13 21:37:09');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (12, 9, 12, 74, 3, '2009-12-13 21:41:18');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (13, 11, 12, 77, 4, '2009-12-13 21:42:13');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (14, 9, 12, 78, 3, '2009-12-13 21:42:53');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (15, 3, 12, 79, 4, '2009-12-13 21:42:56');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (16, 2, 12, 80, 4, '2009-12-13 21:43:00');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (17, 11, 12, 81, 3, '2009-12-13 21:43:04');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (18, 3, 12, 83, 4, '2009-12-13 21:45:53');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (19, 6, 12, 82, 3, '2009-12-13 21:47:43');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (20, 2, 12, 84, 3, '2009-12-13 21:47:54');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (22, 11, 12, 85, 3, '2009-12-13 21:50:11');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (23, 9, 12, 86, 3, '2009-12-13 22:20:18');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (24, 4, 12, 87, 5, '2009-12-13 22:20:19');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (25, 2, 12, 88, 4, '2009-12-13 22:20:20');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (26, 11, 12, 89, 3, '2009-12-13 22:20:21');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (27, 9, 12, 90, 3, '2009-12-13 22:33:49');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (28, 8, 12, 91, 4, '2009-12-13 22:33:50');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (29, 12, 12, 92, 5, '2009-12-13 22:33:51');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (30, 11, 12, 93, 3, '2009-12-13 22:33:52');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (31, 10, 12, 95, 3, '2009-12-14 01:48:41');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (32, 8, 12, 96, 5, '2009-12-14 01:48:43');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (33, 13, 12, 97, 4, '2009-12-14 01:48:44');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (34, 11, 12, 98, 2, '2009-12-14 01:48:46');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (35, 14, 29, 105, 2, '2009-12-14 02:20:11');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (36, 6, 29, 102, 3, '2009-12-14 02:20:13');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (37, 4, 29, 103, 5, '2009-12-14 02:20:14');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (38, 2, 29, 104, 1, '2009-12-14 02:20:15');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (39, 3, 12, 100, 3, '2009-12-14 02:47:39');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (40, 6, 12, 99, 5, '2009-12-14 02:47:41');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (41, 2, 12, 101, 2, '2009-12-14 02:47:43');
INSERT INTO `ratings` (`id`, `vendor_id`, `user_id`, `order_id`, `rating`, `rated_date`) VALUES (42, 14, 12, 107, 3, '2009-12-14 02:47:45');
UNLOCK TABLES;


LOCK TABLES `services` WRITE;
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (1, 'High Chair', NULL, 1, 0.00, 0);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (2, 'Do a Little Dance', NULL, 1, 50.00, 0);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (3, 'Birthday Song', NULL, 2, 10.00, 0);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (4, 'Extra Sides', NULL, 3, 6.99, 0);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (5, 'Specialty Drink', NULL, 3, 3.29, 0);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (6, 'Seat you at your choice of table or booth.', NULL, 1, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (7, 'Provide menus.', NULL, 1, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (8, 'Take your order.', NULL, 2, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (9, 'Make sure your glass is full.', NULL, 2, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (10, 'Bring you your order.', NULL, 2, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (11, 'Cook tonight’s meal.', NULL, 3, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (12, 'Clean up your mess.', NULL, 4, 0.00, 1);
INSERT INTO `services` (`id`, `name`, `description`, `vendor_type_id`, `price`, `standard`) VALUES (13, 'Take the tip.', NULL, 4, 0.00, 1);
UNLOCK TABLES;


LOCK TABLES `services_for_orders` WRITE;
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (126, 74, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (125, 74, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (124, 73, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (123, 73, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (122, 72, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (121, 71, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (120, 71, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (119, 71, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (118, 70, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (117, 70, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (116, 69, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (115, 69, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (114, 68, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (113, 67, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (112, 67, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (111, 67, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (110, 66, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (109, 66, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (108, 65, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (107, 65, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (106, 64, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (105, 63, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (104, 63, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (103, 63, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (102, 62, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (101, 62, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (100, 61, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (99, 61, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (98, 60, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (97, 59, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (96, 59, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (95, 59, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (94, 58, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (93, 58, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (92, 57, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (91, 57, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (78, 50, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (88, 55, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (87, 55, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (86, 54, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (85, 54, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (84, 53, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (83, 53, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (82, 52, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (81, 52, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (90, 56, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (89, 55, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (48, 36, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (49, 36, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (50, 36, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (64, 43, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (69, 45, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (68, 45, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (67, 45, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (80, 51, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (79, 51, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (57, 40, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (58, 40, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (127, 75, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (128, 75, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (129, 75, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (130, 76, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (131, 77, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (132, 77, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (133, 78, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (134, 78, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (135, 79, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (136, 79, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (137, 79, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (138, 80, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (139, 81, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (140, 81, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (141, 82, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (142, 82, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (143, 83, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (144, 83, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (145, 83, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (146, 84, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (147, 85, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (148, 85, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (149, 86, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (150, 86, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (151, 87, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (152, 87, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (153, 87, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (154, 88, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (155, 89, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (156, 89, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (157, 90, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (158, 90, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (159, 91, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (160, 91, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (161, 91, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (162, 92, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (163, 93, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (164, 93, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (165, 94, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (166, 94, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (167, 95, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (168, 95, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (169, 96, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (170, 96, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (171, 96, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (172, 97, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (173, 98, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (174, 98, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (175, 99, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (176, 99, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (177, 100, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (178, 100, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (179, 100, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (180, 101, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (181, 102, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (182, 102, 7);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (183, 103, 8);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (184, 103, 9);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (185, 103, 10);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (186, 104, 11);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (187, 105, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (188, 105, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (189, 106, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (190, 106, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (191, 107, 12);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (192, 107, 13);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (193, 108, 6);
INSERT INTO `services_for_orders` (`id`, `order_id`, `service_id`) VALUES (194, 108, 7);
UNLOCK TABLES;


LOCK TABLES `transactions` WRITE;
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (74, 3, 7, 64, 76.91, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (73, 3, 9, 63, 0.10, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (72, 3, 5, 63, 1.13, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (71, 3, 9, 62, 0.87, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (70, 3, 12, 62, 7.79, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (69, 3, 9, 61, 1.05, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (68, 3, 14, 61, 5.95, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (67, 3, 9, 60, 1.65, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (66, 3, 7, 60, 76.91, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (65, 3, 9, 59, 0.30, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (64, 3, 11, 59, 3.20, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (63, 3, 9, 58, 0.87, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (62, 3, 12, 58, 7.79, '2009-12-13 00:25:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (61, 3, 9, 51, 1.40, '2009-12-12 14:06:01');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (46, 4, 7, 50, 78.56, '2009-12-09 22:20:42');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (60, 3, 14, 51, 5.60, '2009-12-12 14:06:01');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (59, 4, 12, 54, 8.65, '2009-12-12 01:15:32');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (55, 4, 13, 52, 20.59, '2009-12-12 01:13:20');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (56, 4, 5, 36, 1.23, '2009-12-12 01:13:20');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (39, 3, 7, 43, 78.56, '2009-12-07 23:01:09');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (57, 4, 7, 50, 78.56, '2009-12-12 01:13:20');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (41, 3, 5, 45, 1.23, '2009-12-07 23:33:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (58, 4, 14, 53, 7.00, '2009-12-12 01:13:20');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (50, 4, 13, 52, 20.59, '2009-12-12 00:34:04');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (32, 4, 5, 36, 1.23, '2009-12-07 20:42:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (49, 4, 14, 53, 7.00, '2009-12-11 23:48:58');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (48, 4, 13, 52, 20.59, '2009-12-11 23:48:48');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (47, 3, 14, 51, 7.00, '2009-12-11 22:45:48');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (36, 3, 8, 40, 5.89, '2009-12-07 20:45:38');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (75, 3, 9, 64, 1.65, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (76, 3, 14, 65, 5.95, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (77, 3, 9, 65, 1.05, '2009-12-13 00:27:12');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (78, 3, 13, 66, 18.53, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (79, 3, 9, 66, 2.06, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (80, 3, 11, 67, 3.20, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (81, 3, 9, 67, 0.30, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (82, 3, 7, 68, 76.91, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (83, 3, 9, 68, 1.65, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (84, 3, 14, 69, 5.95, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (85, 3, 9, 69, 1.05, '2009-12-13 01:10:53');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (86, 3, 12, 70, 7.79, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (87, 3, 9, 70, 0.87, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (88, 3, 5, 71, 1.13, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (89, 3, 9, 71, 0.10, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (90, 3, 7, 72, 76.91, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (91, 3, 9, 72, 1.65, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (92, 3, 14, 73, 5.95, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (93, 3, 9, 73, 1.05, '2009-12-13 21:11:22');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (94, 3, 12, 74, 7.79, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (95, 3, 9, 74, 0.87, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (96, 3, 11, 75, 3.20, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (97, 3, 9, 75, 0.30, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (98, 3, 7, 76, 76.91, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (99, 3, 9, 76, 1.65, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (100, 3, 14, 77, 5.95, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (101, 3, 9, 77, 1.05, '2009-12-13 21:42:41');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (102, 3, 12, 78, 7.79, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (103, 3, 9, 78, 0.87, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (104, 3, 6, 79, 7.80, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (105, 3, 9, 79, 0.72, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (106, 3, 7, 80, 76.91, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (107, 3, 9, 80, 1.65, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (108, 3, 14, 81, 5.95, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (109, 3, 9, 81, 1.05, '2009-12-13 21:44:49');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (110, 3, 8, 82, 5.30, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (111, 3, 9, 82, 0.59, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (112, 3, 6, 83, 7.80, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (113, 3, 9, 83, 0.72, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (114, 3, 7, 84, 76.91, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (115, 3, 9, 84, 1.65, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (116, 3, 14, 85, 5.95, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (117, 3, 9, 85, 1.05, '2009-12-13 21:52:07');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (118, 3, 12, 86, 7.79, '2009-12-13 21:56:44');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (119, 3, 9, 86, 0.87, '2009-12-13 21:56:44');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (120, 3, 12, 86, 7.79, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (121, 3, 9, 86, 0.87, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (122, 3, 5, 87, 1.13, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (123, 3, 9, 87, 0.10, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (124, 3, 7, 88, 76.91, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (125, 3, 9, 88, 1.65, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (126, 3, 14, 89, 5.95, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (127, 3, 9, 89, 1.05, '2009-12-13 22:20:24');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (128, 3, 12, 90, 7.79, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (129, 3, 9, 90, 0.87, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (130, 3, 11, 91, 3.20, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (131, 3, 9, 91, 0.30, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (132, 3, 17, 92, 88.34, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (133, 3, 9, 92, 1.89, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (134, 3, 14, 93, 5.95, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (135, 3, 9, 93, 1.05, '2009-12-13 23:53:43');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (136, 3, 13, 95, 18.53, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (137, 3, 9, 95, 2.06, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (138, 3, 11, 96, 3.20, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (139, 3, 9, 96, 0.30, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (140, 3, 18, 97, 1.95, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (141, 3, 9, 97, 0.04, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (142, 3, 14, 98, 5.95, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (143, 3, 9, 98, 1.05, '2009-12-14 01:48:52');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (144, 3, 8, 99, 5.30, '2009-12-14 02:31:47');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (145, 3, 9, 99, 0.59, '2009-12-14 02:31:47');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (146, 3, 6, 100, 7.80, '2009-12-14 02:48:18');
INSERT INTO `transactions` (`id`, `giver_account`, `recipient_account`, `order_id`, `amount`, `sale_time`) VALUES (147, 3, 9, 100, 0.72, '2009-12-14 02:48:18');
UNLOCK TABLES;


LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (17, 'Joe Dirt', 'joe@dirt.com', '8ff32489f92f33416694be8fdc2d4c22', 'vendor', 8, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (16, 'Wolfgang Puck', 'wolfgang@puck.com', '3720f54e919b22cce392b05de57102dd', 'vendor', 7, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (15, 'Test Person', 'test@test.com', '098f6bcd4621d373cade4e832627b4f6', 'vendor', 6, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (14, 'Lincoln Pomeroy', 'lincoln@byu.edu', '098f6bcd4621d373cade4e832627b4f6', 'guest', 5, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (13, 'Michelle Pomeroy', 'michellepomeroy@gmail.com', '58d2770722ebccc66ef50bbf16021332', 'guest', 4, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (12, 'Miles Pomeroy', 'miles@byu.net', '58d2770722ebccc66ef50bbf16021332', 'guest', 3, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (19, 'The Admin', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', 'manager', 9, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (21, 'Scott Baio', 'scott@baio.com', '21f63c6e971cd913a9c147e8652ca659', 'vendor', 11, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (22, 'Neil Patrick Harris', 'neil@harris.com', 'd68e939882371200637d5024b360fc20', 'vendor', 12, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (23, 'Ryan Seacrest', 'ryan@seacrest.com', '10c7ccc7a4f0aff03c915c485565b9da', 'vendor', 13, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (24, 'Ricky Stratton', 'ricky@stratton.com', '56ea8b83122449e814e0fd7bfb5f220a', 'vendor', 14, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (25, 'Straw Berry', 'straw@berry.com', '1933600a817d0c85790f2f2427517ef1', 'guest', 15, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (26, 'Blue Berry', 'blue@berry.com', '48d6215903dff56238e52e8891380c8f', 'guest', 16, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (27, 'Ina Garten', 'ina@garten.com', 'a0fb2daa33c637d078d1d276dd453ea2', 'vendor', 17, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (28, 'Uncle Buck', 'uncle@buck.com', '18486728d14269ff2367eb29f9fc7aea', 'vendor', 18, NULL, NULL);
INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `user_type`, `account_id`, `flag`, `deactive`) VALUES (29, 'Verne Troyer', 'verne@troyer.com', 'd2174aa078eb3903527691dfb3b63c48', 'vendor', 19, NULL, NULL);
UNLOCK TABLES;


LOCK TABLES `vendor_applications` WRITE;
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (1, 'Neil Patrick Harris', 'neil@harris.com', 'Neil', 1, 'I starred in "Dr Horrible’s Sing-Along Blog."', 'Hire', '2009-12-09 20:11:28', '2009-12-10 01:42:04');
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (4, 'Scott Baio', 'scott@baio.com', 'Scotty', 2, 'I’m Charles and I’m in charge.

Of our days.

And our nights.', 'Hire', '2009-12-09 20:11:30', '2009-12-10 01:26:07');
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (5, 'Ricky Stratton', 'ricky@stratton.com', 'Ricky Schroder', 4, 'Silver spoons, baby!', 'Hire', '2009-12-10 01:47:21', '2009-12-10 01:49:07');
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (6, 'Ryan Seacrest', 'ryan@seacrest.com', 'Seacrest', 1, 'I hosted American Idol.', 'Hire', '2009-12-09 22:15:18', '2009-12-10 01:45:55');
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (7, 'Ina Garten', 'ina@garten.com', 'The Barefoot Contessa', 3, 'I have a Food Network TV show.', 'Hire', '2009-12-13 22:06:52', '2009-12-13 22:14:21');
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (8, 'Uncle Buck', 'uncle@buck.com', 'Uncle Buck', 3, 'You should see the toast. I couldn\'t even get it through the <strong>door</strong>.', 'Hire', '2009-12-14 01:01:01', '2009-12-14 01:03:25');
INSERT INTO `vendor_applications` (`id`, `full_name`, `email`, `vendor_name`, `vendor_type_id`, `vendor_qualifications`, `offer`, `offer_date`, `activated`) VALUES (9, 'Verne Troyer', 'verne@troyer.com', 'Mini-Me', 4, 'I complete you.', 'Hire', '2009-12-14 01:57:34', '2009-12-14 02:00:17');
UNLOCK TABLES;


LOCK TABLES `vendor_types` WRITE;
INSERT INTO `vendor_types` (`id`, `name`, `description`, `workflow_order`, `percent_fee`) VALUES (1, 'host', NULL, 1, 10.00);
INSERT INTO `vendor_types` (`id`, `name`, `description`, `workflow_order`, `percent_fee`) VALUES (2, 'waiter', NULL, 2, 8.50);
INSERT INTO `vendor_types` (`id`, `name`, `description`, `workflow_order`, `percent_fee`) VALUES (3, 'cook', NULL, 3, 2.10);
INSERT INTO `vendor_types` (`id`, `name`, `description`, `workflow_order`, `percent_fee`) VALUES (4, 'busboy', NULL, 4, 15.00);
UNLOCK TABLES;


LOCK TABLES `vendors` WRITE;
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (2, 'Wolfgang Puck', 3, 'Spago Beverly Hills received a James Beard Foundation Outstanding Service Award in 2005. In November 2007 it was awarded 2 Michelin Stars in the 2008 Los Angeles Michelin Guide.

In 1993, Spago Hollywood was inducted into the Nation\'s Restaurant News Fine Dining Hall of Fame. The next year it received the James Beard Restaurant of the Year Award.

In 2002 Puck received the 2001-2002 Daytime Emmy Award for Outstanding Service Show, Wolfgang Puck.', 78.56, 16, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (3, 'Test Vendor', 2, 'I have no qualifications.', 8.52, 15, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (4, 'Abe Lincoln', 2, 'I’m a baby.', 1.23, 14, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (12, 'The Barefoot Contessa', 3, 'I have a Food Network TV show.', 90.23, 27, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (6, 'Joey', 1, 'yeah baby', 5.89, 17, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (8, 'Scotty', 2, 'I’m Charles and I’m in charge.

Of our days.

And our nights.', 3.50, 21, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (9, 'Neil', 1, 'I starred in "Dr Horrible’s Sing-Along Blog."', 8.65, 22, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (10, 'Seacrest', 1, 'I hosted American Idol.', 20.59, 23, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (11, 'Ricky Schroder', 4, 'Silver spoons, baby!', 7.00, 24, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (13, 'Uncle Buck', 3, 'You should see the toast. I couldn\'t even get it through the <strong>door</strong>.', 1.99, 28, NULL);
INSERT INTO `vendors` (`id`, `name`, `type_id`, `qualifications`, `price`, `user_id`, `deactive`) VALUES (14, 'Mini-Me', 4, 'I complete you.', 6.50, 29, NULL);
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = 1;


