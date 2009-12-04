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
  `user_id` int(11) DEFAULT NULL,
  `cc_number` varchar(20) DEFAULT 'demo' COMMENT 'obviously not secure',
  `cc_type` varchar(20) DEFAULT NULL COMMENT 'visa or mastercard',
  `cc_security_code` int(5) DEFAULT '0',
  `billing_name` varchar(50) DEFAULT NULL,
  `billing_address` text,
  `cc_expiry` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='probably not the proper way to store cc info';


CREATE TABLE `meals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time_started` datetime DEFAULT NULL,
  `time_finished` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='A record for each time a guest comes in for a meal.';


CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `meal_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `total_price` decimal(7,2) DEFAULT '0.00',
  `activated_date` datetime DEFAULT NULL COMMENT 'mark when order is up',
  `filled` tinyint(1) DEFAULT '0' COMMENT 'boolean when order has been fulfilled',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT '0' COMMENT 'vendor for whom the rating is for',
  `user_id` int(11) DEFAULT '0' COMMENT 'guest who is rating',
  `rating` tinyint(1) DEFAULT '0' COMMENT 'star rating 1 to 5 scale',
  `rated_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` text,
  `vendor_type_id` int(11) DEFAULT '0' COMMENT 'link additional service to vendor type',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Additional services that can by offered by vendors.';


CREATE TABLE `services_for_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT '0' COMMENT 'from orders table',
  `service_id` int(11) DEFAULT '0' COMMENT 'from services table',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Lookup between orders and additional services requested.';


CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `giver_account` int(11) DEFAULT NULL COMMENT 'account id of giver of money',
  `recipient_account` int(11) DEFAULT NULL COMMENT 'account id of recipient of funds',
  `order_id` int(11) DEFAULT NULL COMMENT 'careful to distinguish giver when taking total cost of meals',
  `amount` decimal(7,2) DEFAULT '0.00',
  `sale_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='monetary transactions between guests, vendors, and manager.';


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL COMMENT 'md5 encryption',
  `user_type` varchar(20) DEFAULT NULL COMMENT 'guest, vendor, or manager',
  `account_id` int(11) DEFAULT NULL,
  `flag` varchar(1) DEFAULT NULL COMMENT 'to mark special event like needing to change password',
  `deactive` datetime DEFAULT NULL COMMENT 'to expire user account',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `vendor_name` varchar(50) DEFAULT NULL,
  `vendor_type_id` int(11) DEFAULT NULL,
  `vendor_qualifications` mediumtext,
  `offer` varchar(20) DEFAULT NULL COMMENT 'hire or deny',
  `offer_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `vendor_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL COMMENT 'like host, waiter, etc',
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `vendors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL COMMENT 'from vendor_types table',
  `qualifications` mediumtext,
  `user_id` int(11) DEFAULT NULL COMMENT 'user id associated with vendor can change',
  `deactive` datetime DEFAULT NULL COMMENT 'used to fire (1) a vendor',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='vendor information for public listing';




SET FOREIGN_KEY_CHECKS = 0;


LOCK TABLES `accounts` WRITE;
UNLOCK TABLES;


LOCK TABLES `meals` WRITE;
UNLOCK TABLES;


LOCK TABLES `orders` WRITE;
UNLOCK TABLES;


LOCK TABLES `ratings` WRITE;
UNLOCK TABLES;


LOCK TABLES `services` WRITE;
UNLOCK TABLES;


LOCK TABLES `services_for_orders` WRITE;
UNLOCK TABLES;


LOCK TABLES `transactions` WRITE;
UNLOCK TABLES;


LOCK TABLES `users` WRITE;
UNLOCK TABLES;


LOCK TABLES `vendor_applications` WRITE;
UNLOCK TABLES;


LOCK TABLES `vendor_types` WRITE;
UNLOCK TABLES;


LOCK TABLES `vendors` WRITE;
UNLOCK TABLES;




SET FOREIGN_KEY_CHECKS = 1;


