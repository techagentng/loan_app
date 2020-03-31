-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.6.20 - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL Version:             9.1.0.4867
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table loans.kpos_app_config
DROP TABLE IF EXISTS `kpos_app_config`;
CREATE TABLE IF NOT EXISTS `kpos_app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_app_config: ~30 rows (approximately)
/*!40000 ALTER TABLE `kpos_app_config` DISABLE KEYS */;
INSERT INTO `kpos_app_config` (`key`, `value`) VALUES
	('address', '123 Nowhere street'),
	('company', 'SoftReliance I.T. Solutions'),
	('currency_side', '0'),
	('currency_symbol', 'P'),
	('custom10_name', '0'),
	('custom1_name', '0'),
	('custom2_name', '0'),
	('custom3_name', '0'),
	('custom4_name', '0'),
	('custom5_name', '0'),
	('custom6_name', '0'),
	('custom7_name', '0'),
	('custom8_name', '0'),
	('custom9_name', '0'),
	('default_tax_1_name', '0'),
	('default_tax_1_rate', '0'),
	('default_tax_2_name', '0'),
	('default_tax_2_rate', '0'),
	('default_tax_rate', '8'),
	('email', 'admin@pappastech.com'),
	('fax', ''),
	('language', 'en'),
	('phone', '555-555-5555'),
	('print_after_sale', '0'),
	('recv_invoice_format', '0'),
	('return_policy', '0'),
	('sales_invoice_format', '0'),
	('tax_included', '0'),
	('timezone', 'Asia/Hong_Kong'),
	('website', '');
/*!40000 ALTER TABLE `kpos_app_config` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_customers
DROP TABLE IF EXISTS `kpos_customers`;
CREATE TABLE IF NOT EXISTS `kpos_customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `kpos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_customers: ~16 rows (approximately)
/*!40000 ALTER TABLE `kpos_customers` DISABLE KEYS */;
INSERT INTO `kpos_customers` (`person_id`, `account_number`, `taxable`, `deleted`) VALUES
	(3, NULL, 1, 0),
	(4, NULL, 1, 0),
	(5, NULL, 1, 1),
	(6, NULL, 1, 1),
	(7, NULL, 1, 1),
	(8, NULL, 1, 1),
	(9, NULL, 1, 1),
	(10, NULL, 1, 0),
	(11, NULL, 1, 1),
	(18, NULL, 1, 0),
	(19, NULL, 1, 0),
	(20, NULL, 1, 0),
	(21, NULL, 1, 0),
	(22, NULL, 1, 0),
	(46, NULL, 1, 0),
	(48, NULL, 1, 0);
/*!40000 ALTER TABLE `kpos_customers` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_employees
DROP TABLE IF EXISTS `kpos_employees`;
CREATE TABLE IF NOT EXISTS `kpos_employees` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `kpos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_employees: ~16 rows (approximately)
/*!40000 ALTER TABLE `kpos_employees` DISABLE KEYS */;
INSERT INTO `kpos_employees` (`username`, `password`, `person_id`, `deleted`) VALUES
	('213123fsadfas', '25d55ad283aa400af464c76d713c07ad', 47, 0),
	('admin', '0192023a7bbd73250516f069df18b500', 1, 0),
	('dsfasdfsadf', '25d55ad283aa400af464c76d713c07ad', 28, 1),
	('fdfasdfsadf123', '25d55ad283aa400af464c76d713c07ad', 41, 0),
	('fdsfsdfsafs2346', '25d55ad283aa400af464c76d713c07ad', 42, 0),
	('fsdafas', '25d55ad283aa400af464c76d713c07ad', 27, 1),
	('luizsept', '25d55ad283aa400af464c76d713c07ad', 45, 0),
	('mbmg12356', '25d55ad283aa400af464c76d713c07ad', 43, 0),
	('newnew1', '25d55ad283aa400af464c76d713c07ad', 33, 1),
	('newnr123', '25d55ad283aa400af464c76d713c07ad', 37, 1),
	('norman', '5f3cd85febeeefd6c03b6042c19c24bf', 2, 0),
	('regidor', '5f3cd85febeeefd6c03b6042c19c24bf', 23, 0),
	('test1', '25d55ad283aa400af464c76d713c07ad', 29, 1),
	('test3456', '25d55ad283aa400af464c76d713c07ad', 31, 1),
	('testnorman', '5f3cd85febeeefd6c03b6042c19c24bf', 15, 1),
	('work1234', '25d55ad283aa400af464c76d713c07ad', 44, 0);
/*!40000 ALTER TABLE `kpos_employees` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_financial_status
DROP TABLE IF EXISTS `kpos_financial_status`;
CREATE TABLE IF NOT EXISTS `kpos_financial_status` (
  `financial_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `income_sources` text NOT NULL,
  PRIMARY KEY (`financial_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_financial_status: ~8 rows (approximately)
/*!40000 ALTER TABLE `kpos_financial_status` DISABLE KEYS */;
INSERT INTO `kpos_financial_status` (`financial_status_id`, `person_id`, `income_sources`) VALUES
	(1, 21, '["salary=5000"]'),
	(3, 22, '["salary=12000","online=15000","online=15000"]'),
	(4, 19, '["="]'),
	(5, 3, '["="]'),
	(6, 10, '["salary=7000.50"]'),
	(7, 18, '["="]'),
	(8, 46, '["="]'),
	(9, 48, '["="]');
/*!40000 ALTER TABLE `kpos_financial_status` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_giftcards
DROP TABLE IF EXISTS `kpos_giftcards`;
CREATE TABLE IF NOT EXISTS `kpos_giftcards` (
  `record_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `person_id` int(11) NOT NULL,
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`),
  KEY `ospos_giftcards_ibfk_1` (`person_id`),
  CONSTRAINT `kpos_giftcards_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dumping data for table loans.kpos_giftcards: ~9 rows (approximately)
/*!40000 ALTER TABLE `kpos_giftcards` DISABLE KEYS */;
INSERT INTO `kpos_giftcards` (`record_time`, `giftcard_id`, `giftcard_number`, `value`, `deleted`, `person_id`) VALUES
	('2015-04-16 21:36:49', 2, '1', 50.00, 1, 3),
	('2015-04-16 21:58:05', 3, '2', 40.00, 1, 4),
	('2015-04-16 22:40:49', 4, '3', 33.00, 0, 3),
	('2015-04-16 22:41:04', 5, '4', 12.00, 0, 4),
	('2015-04-16 22:41:28', 6, '5', 12.00, 0, 10),
	('2015-04-16 22:57:32', 7, '6', 12.00, 0, 10),
	('2015-04-16 22:58:35', 8, '7', 2.00, 0, 4),
	('2015-04-16 23:01:00', 9, '8', 2.00, 0, 10),
	('2015-04-16 23:01:52', 10, '9', 20.00, 0, 4);
/*!40000 ALTER TABLE `kpos_giftcards` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_grants
DROP TABLE IF EXISTS `kpos_grants`;
CREATE TABLE IF NOT EXISTS `kpos_grants` (
  `permission_id` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`permission_id`,`person_id`),
  KEY `ospos_grants_ibfk_2` (`person_id`),
  CONSTRAINT `kpos_grants_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `kpos_permissions` (`permission_id`),
  CONSTRAINT `kpos_grants_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `kpos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_grants: ~50 rows (approximately)
/*!40000 ALTER TABLE `kpos_grants` DISABLE KEYS */;
INSERT INTO `kpos_grants` (`permission_id`, `person_id`) VALUES
	('config', 1),
	('customers', 1),
	('employees', 1),
	('giftcards', 1),
	('items', 1),
	('items_', 1),
	('items_stock', 1),
	('item_kits', 1),
	('loans', 1),
	('loan_types', 1),
	('payments', 1),
	('receivings', 1),
	('reports', 1),
	('reports_categories', 1),
	('reports_customers', 1),
	('reports_discounts', 1),
	('reports_employees', 1),
	('reports_inventory', 1),
	('reports_items', 1),
	('reports_payments', 1),
	('reports_receivings', 1),
	('reports_sales', 1),
	('reports_suppliers', 1),
	('reports_taxes', 1),
	('sales', 1),
	('suppliers', 1),
	('items', 2),
	('items_', 2),
	('items_stock', 2),
	('item_kits', 2),
	('loans', 2),
	('sales', 2),
	('items_', 23),
	('loans', 23),
	('loan_types', 23),
	('payments', 23),
	('items_', 41),
	('loans', 41),
	('items_', 42),
	('loans', 42),
	('items_', 43),
	('loans', 43),
	('items_', 44),
	('loans', 44),
	('items_', 45),
	('loans', 45),
	('items_', 47),
	('loans', 47),
	('loan_types', 47),
	('payments', 47);
/*!40000 ALTER TABLE `kpos_grants` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_inventory
DROP TABLE IF EXISTS `kpos_inventory`;
CREATE TABLE IF NOT EXISTS `kpos_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text NOT NULL,
  `trans_location` int(11) NOT NULL,
  `trans_inventory` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `trans_items` (`trans_items`),
  KEY `trans_user` (`trans_user`),
  KEY `trans_location` (`trans_location`),
  CONSTRAINT `kpos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `kpos_items` (`item_id`),
  CONSTRAINT `kpos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `kpos_employees` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_inventory: ~56 rows (approximately)
/*!40000 ALTER TABLE `kpos_inventory` DISABLE KEYS */;
INSERT INTO `kpos_inventory` (`trans_id`, `trans_items`, `trans_user`, `trans_date`, `trans_comment`, `trans_location`, `trans_inventory`) VALUES
	(1, 1, 1, '2015-04-03 05:46:34', 'Manual Edit of Quantity', 1, 100),
	(2, 1, 1, '2015-04-03 05:48:46', 'POS 1', 1, -1),
	(3, 1, 1, '2015-04-03 06:04:02', 'POS 2', 1, -1),
	(4, 1, 1, '2015-04-03 08:57:49', 'POS 3', 1, -1),
	(5, 1, 1, '2015-04-03 08:59:06', 'POS 4', 1, -2),
	(6, 1, 1, '2015-04-03 09:11:42', 'POS 5', 1, -2),
	(7, 1, 1, '2015-04-04 07:19:33', 'POS 6', 1, -1),
	(8, 1, 1, '2015-04-04 07:21:43', 'POS 7', 1, -1),
	(9, 1, 1, '2015-04-04 07:22:20', 'POS 8', 1, 1),
	(10, 2, 2, '2015-04-04 08:13:53', 'Manual Edit of Quantity', 1, 100),
	(11, 1, 2, '2015-04-04 13:52:05', 'POS 9', 1, -2),
	(12, 2, 2, '2015-04-04 13:52:06', 'POS 9', 1, -1),
	(13, 1, 2, '2015-04-05 15:08:52', 'POS 10', 1, -2),
	(14, 2, 2, '2015-04-05 15:08:52', 'POS 10', 1, -2),
	(15, 1, 2, '2015-04-05 15:10:22', 'POS 11', 1, 1),
	(16, 2, 2, '2015-04-05 15:10:22', 'POS 11', 1, 1),
	(17, 1, 1, '2015-04-05 15:45:22', 'POS 12', 1, -1),
	(18, 2, 1, '2015-04-05 15:45:22', 'POS 12', 1, -1),
	(19, 1, 1, '2015-04-05 16:53:36', 'POS 13', 1, -1),
	(20, 1, 1, '2015-04-05 18:15:16', 'POS 14', 1, -1),
	(21, 2, 1, '2015-04-05 18:15:16', 'POS 14', 1, -1),
	(22, 1, 1, '2015-04-05 18:24:18', 'POS 15', 1, -2),
	(23, 1, 1, '2015-04-05 18:25:20', 'POS 16', 1, -2),
	(24, 2, 1, '2015-04-05 18:25:20', 'POS 16', 1, -1),
	(25, 2, 1, '2015-04-05 18:29:22', 'POS 17', 1, -3),
	(26, 1, 1, '2015-04-05 18:29:22', 'POS 17', 1, -2),
	(27, 1, 1, '2015-04-05 19:20:44', 'POS 18', 1, -1),
	(28, 3, 1, '2015-04-16 20:46:11', 'Manual Edit of Quantity', 1, 50),
	(29, 8, 1, '2015-04-16 23:16:34', 'Manual Edit of Quantity', 1, 5),
	(30, 9, 1, '2015-04-16 23:20:34', 'Manual Edit of Quantity', 1, 5),
	(31, 10, 1, '2015-04-16 23:22:37', 'Manual Edit of Quantity', 1, 1),
	(32, 12, 1, '2015-04-16 23:27:02', 'Manual Edit of Quantity', 1, 4),
	(33, 13, 1, '2015-04-16 23:28:30', 'Manual Edit of Quantity', 1, 5),
	(34, 2, 1, '2015-04-17 21:38:00', '', 1, 2),
	(35, 2, 1, '2015-04-17 21:39:07', '', 1, -4),
	(36, 2, 1, '2015-04-17 21:39:19', '', 1, 2),
	(37, 2, 1, '2015-04-17 21:39:31', '', 1, -2),
	(38, 2, 1, '2015-04-17 21:39:47', '', 1, 5),
	(39, 1, 1, '2015-04-18 21:32:04', '', 1, -9),
	(40, 1, 1, '2015-04-18 21:32:47', '', 1, 10),
	(41, 8, 1, '2015-04-18 22:04:01', 'need to add 10 quantity', 1, 10),
	(42, 13, 1, '2015-04-19 21:47:53', 'RECV 1', 1, 1),
	(43, 1, 1, '2015-04-21 00:26:35', 'POS 19', 1, -6),
	(44, 13, 1, '2015-04-21 00:26:35', 'POS 19', 1, -1),
	(45, 1, 1, '2015-04-23 22:06:15', 'POS 20', 1, -1),
	(46, 1, 1, '2015-04-23 22:08:40', 'POS 21', 1, -3),
	(47, 10, 1, '2015-04-23 22:08:40', 'POS 21', 1, -1),
	(48, 11, 1, '2015-04-23 22:08:40', 'POS 21', 1, -1),
	(49, 13, 1, '2015-04-23 22:08:40', 'POS 21', 1, -10),
	(50, 2, 1, '2015-04-23 22:08:40', 'POS 21', 1, -1),
	(51, 10, 1, '2015-05-04 08:01:57', 'POS 22', 1, -6),
	(52, 1, 1, '2015-05-04 08:01:57', 'POS 22', 1, -70),
	(53, 13, 1, '2015-05-04 08:01:57', 'POS 22', 1, -3),
	(54, 11, 1, '2015-05-04 08:01:57', 'POS 22', 1, -10),
	(55, 7, 1, '2015-05-04 08:01:57', 'POS 22', 1, -1),
	(56, 4, 1, '2015-05-04 08:01:57', 'POS 22', 1, -10);
/*!40000 ALTER TABLE `kpos_inventory` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_items
DROP TABLE IF EXISTS `kpos_items`;
CREATE TABLE IF NOT EXISTS `kpos_items` (
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `reorder_level` decimal(15,2) NOT NULL DEFAULT '0.00',
  `receiving_quantity` int(11) NOT NULL DEFAULT '1',
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `custom1` varchar(25) NOT NULL,
  `custom2` varchar(25) NOT NULL,
  `custom3` varchar(25) NOT NULL,
  `custom4` varchar(25) NOT NULL,
  `custom5` varchar(25) NOT NULL,
  `custom6` varchar(25) NOT NULL,
  `custom7` varchar(25) NOT NULL,
  `custom8` varchar(25) NOT NULL,
  `custom9` varchar(25) NOT NULL,
  `custom10` varchar(25) NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `ospos_items_ibfk_1` (`supplier_id`),
  CONSTRAINT `kpos_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `kpos_suppliers` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_items: ~13 rows (approximately)
/*!40000 ALTER TABLE `kpos_items` DISABLE KEYS */;
INSERT INTO `kpos_items` (`name`, `category`, `supplier_id`, `item_number`, `description`, `cost_price`, `unit_price`, `reorder_level`, `receiving_quantity`, `item_id`, `allow_alt_description`, `is_serialized`, `deleted`, `custom1`, `custom2`, `custom3`, `custom4`, `custom5`, `custom6`, `custom7`, `custom8`, `custom9`, `custom10`) VALUES
	('my item', 'default', NULL, '001', 'hello world', 45.75, 46.51, 0.00, 100, 1, 0, 0, 0, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('test1', 'default', NULL, '002', '', 35.00, 36.00, 0.00, 0, 2, 0, 0, 0, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('sadfas fa', 'default', 13, '1234', '', 35.00, 44.00, 1.00, 0, 3, 0, 0, 1, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('fasdfsafsa', 'default', 12, '004', '', 0.00, 0.00, 0.00, 0, 4, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('', '', NULL, NULL, '', 0.00, 0.00, 0.00, 0, 5, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('', '', NULL, NULL, '', 0.00, 0.00, 0.00, 0, 6, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('dfasf', 'fasdf', NULL, NULL, '', 40.00, 45.00, 0.00, 0, 7, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('fasdfasdfaf', 'default', NULL, NULL, '', 45.00, 46.00, 0.00, 0, 8, 0, 0, 1, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('sadfasdfa', 'default', NULL, NULL, '', 12.00, 13.00, 0.00, 0, 9, 0, 0, 1, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('asdfas', 'default', NULL, '006', '', 11.00, 12.00, 1.00, 0, 10, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('rewrqwer', 'default', NULL, '007', '', 5.00, 6.00, 0.00, 0, 11, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('dsfaf', 'default', NULL, NULL, '', 3.00, 4.00, 0.00, 0, 12, 0, 0, 1, '', '0', '0', '0', '0', '0', '0', '0', '0', '0'),
	('norman item', 'default', NULL, '008', '', 4.00, 5.00, 0.00, 0, 13, 0, 0, 0, '', '0', '0', '0', '0', '0', '0', '0', '0', '0');
/*!40000 ALTER TABLE `kpos_items` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_items_taxes
DROP TABLE IF EXISTS `kpos_items_taxes`;
CREATE TABLE IF NOT EXISTS `kpos_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` decimal(15,2) NOT NULL,
  PRIMARY KEY (`item_id`,`name`,`percent`),
  CONSTRAINT `kpos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_items_taxes: ~13 rows (approximately)
/*!40000 ALTER TABLE `kpos_items_taxes` DISABLE KEYS */;
INSERT INTO `kpos_items_taxes` (`item_id`, `name`, `percent`) VALUES
	(1, '', 12.00),
	(2, 'Sales Tax', 5.00),
	(3, 'None', 0.00),
	(4, '', 0.00),
	(5, '', 0.00),
	(6, '', 0.00),
	(7, '', 0.00),
	(8, '', 0.00),
	(9, '', 0.00),
	(10, '', 0.00),
	(11, '', 0.00),
	(12, '', 0.00),
	(13, '', 0.00);
/*!40000 ALTER TABLE `kpos_items_taxes` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_item_kits
DROP TABLE IF EXISTS `kpos_item_kits`;
CREATE TABLE IF NOT EXISTS `kpos_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`item_kit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_item_kits: ~2 rows (approximately)
/*!40000 ALTER TABLE `kpos_item_kits` DISABLE KEYS */;
INSERT INTO `kpos_item_kits` (`item_kit_id`, `name`, `description`) VALUES
	(1, 'test123', 'test'),
	(2, 'hello world', 'fasdf asdf asdf');
/*!40000 ALTER TABLE `kpos_item_kits` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_item_kit_items
DROP TABLE IF EXISTS `kpos_item_kit_items`;
CREATE TABLE IF NOT EXISTS `kpos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(15,2) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `ospos_item_kit_items_ibfk_2` (`item_id`),
  CONSTRAINT `kpos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `kpos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  CONSTRAINT `kpos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_item_kit_items: ~4 rows (approximately)
/*!40000 ALTER TABLE `kpos_item_kit_items` DISABLE KEYS */;
INSERT INTO `kpos_item_kit_items` (`item_kit_id`, `item_id`, `quantity`) VALUES
	(1, 1, 2.00),
	(2, 1, 1.00),
	(1, 10, 1.00),
	(2, 13, 1.00);
/*!40000 ALTER TABLE `kpos_item_kit_items` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_item_quantities
DROP TABLE IF EXISTS `kpos_item_quantities`;
CREATE TABLE IF NOT EXISTS `kpos_item_quantities` (
  `item_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`,`location_id`),
  KEY `item_id` (`item_id`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `kpos_item_quantities_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  CONSTRAINT `kpos_item_quantities_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `kpos_stock_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_item_quantities: ~20 rows (approximately)
/*!40000 ALTER TABLE `kpos_item_quantities` DISABLE KEYS */;
INSERT INTO `kpos_item_quantities` (`item_id`, `location_id`, `quantity`) VALUES
	(1, 1, 0),
	(1, 2, 0),
	(2, 1, 94),
	(2, 2, 0),
	(3, 1, 50),
	(4, 1, -10),
	(4, 2, 0),
	(5, 2, 0),
	(6, 2, 0),
	(7, 1, -1),
	(7, 2, 0),
	(8, 1, 15),
	(9, 1, 5),
	(10, 1, -6),
	(10, 2, 0),
	(11, 1, -11),
	(11, 2, 0),
	(12, 1, 4),
	(13, 1, -8),
	(13, 2, 0);
/*!40000 ALTER TABLE `kpos_item_quantities` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_loans
DROP TABLE IF EXISTS `kpos_loans`;
CREATE TABLE IF NOT EXISTS `kpos_loans` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL,
  `description` varchar(300) NOT NULL,
  `remarks` varchar(300) NOT NULL,
  `loan_type_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `loan_balance` decimal(10,2) NOT NULL,
  `loan_status` enum('pending','approved','on going','paid') NOT NULL,
  `loan_agent_id` int(11) NOT NULL,
  `loan_approved_by_id` int(11) NOT NULL,
  `loan_reviewed_by_id` int(11) NOT NULL,
  `loan_applied_date` int(11) NOT NULL,
  `loan_payment_date` int(11) NOT NULL,
  `misc_fees` text NOT NULL,
  `delete_flag` int(11) NOT NULL,
  PRIMARY KEY (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_loans: ~14 rows (approximately)
/*!40000 ALTER TABLE `kpos_loans` DISABLE KEYS */;
INSERT INTO `kpos_loans` (`loan_id`, `account`, `description`, `remarks`, `loan_type_id`, `customer_id`, `loan_amount`, `loan_balance`, `loan_status`, `loan_agent_id`, `loan_approved_by_id`, `loan_reviewed_by_id`, `loan_applied_date`, `loan_payment_date`, `misc_fees`, `delete_flag`) VALUES
	(1, '0', '0', '0', 0, 0, 0.00, 0.00, 'pending', 0, 0, 1, 0, 0, '', 1),
	(2, 'fgfh123', 'fsdf sad f', '0', 0, 3, 0.00, 0.00, 'pending', 0, 0, 0, 0, 0, '', 1),
	(3, '2356', 'fasdfasdfas', '0', 0, 0, 0.00, 0.00, 'pending', 0, 0, 0, 0, 0, '', 1),
	(4, 'dfgt565', 'fgsdfgdsg', '0', 0, 0, 0.00, 0.00, 'pending', 0, 0, 0, 0, 0, '', 1),
	(5, 'gdfg', 'gfd', 'fasdf as fsa fa as f s', 7, 20, 50.00, 0.00, 'pending', 1, 0, 0, 1430928000, 1433606400, '{"service charge":"100","office charge":"50"}', 0),
	(6, 'fds34', 'fasdfas dfsa', '0', 5, 22, 100.00, 85.00, 'approved', 1, 1, 0, 1430841600, 1433839575, '', 0),
	(7, '23fds', 'fasdfa sdf', 'he need it for his business thank you', 5, 22, 50.00, 0.00, 'pending', 0, 0, 0, 1431014400, 0, '', 1),
	(8, 'test-account-123', 'dfasdf asd fsad fasd', '0', 5, 4, 250000.00, 50000.00, 'pending', 1, 0, 0, 1431014400, 1433839467, '{"service fee":"50.50","office supplies":"70.50"}', 0),
	(9, '0', '0', '0', 0, 0, 0.00, 0.00, 'pending', 0, 0, 0, 0, 0, '', 1),
	(10, '12323', 'fsd fas', 'fsda fsadfsadfs fsdf sadf saf sadfas', 7, 18, 2000.00, 2000.00, 'approved', 1, 1, 0, 1430841600, 0, '{"notarial fee":"100","service charge":"50.75"}', 0),
	(11, 'fdsf', 'fsdf', '', 7, 3, 2000.00, 2000.00, 'approved', 1, 1, 0, 1430668800, 0, '', 0),
	(12, '4423df', 'fasdf', '', 7, 22, 1000.00, 700.00, 'approved', 1, 1, 0, 1430668800, 1434087629, '', 0),
	(13, 'df', '', '', 0, 0, 0.00, 0.00, 'pending', 0, 0, 0, 0, 0, '', 1),
	(14, '2345', 'fsa fasdfs', 'kenahanglanun kaayo', 7, 4, 2500.00, 2500.00, 'approved', 1, 1, 0, 1432483200, 1435161600, '{"Service Charge":"50.75","Maintenance Fee":"25"}', 0);
/*!40000 ALTER TABLE `kpos_loans` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_loan_payments
DROP TABLE IF EXISTS `kpos_loan_payments`;
CREATE TABLE IF NOT EXISTS `kpos_loan_payments` (
  `loan_payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(50) NOT NULL DEFAULT '0',
  `loan_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `balance_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL,
  `teller_id` int(11) NOT NULL,
  `date_paid` int(11) NOT NULL,
  `date_modified` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `remarks` varchar(2000) NOT NULL,
  `delete_flag` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loan_payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_loan_payments: ~19 rows (approximately)
/*!40000 ALTER TABLE `kpos_loan_payments` DISABLE KEYS */;
INSERT INTO `kpos_loan_payments` (`loan_payment_id`, `account`, `loan_id`, `customer_id`, `balance_amount`, `paid_amount`, `teller_id`, `date_paid`, `date_modified`, `modified_by`, `remarks`, `delete_flag`) VALUES
	(10, '', 6, 22, 0.00, 100.00, 1, 1431100800, 0, 0, '', 0),
	(11, '', 12, 22, 0.00, 3.00, 1, 1430755200, 1430835819, 1, 'fasd', 0),
	(12, '', 12, 22, 0.00, 12.00, 1, 1431100800, 1431136553, 1, '', 0),
	(13, '', 12, 22, 0.00, 15.00, 1, 1431100800, 1431136566, 1, '', 0),
	(14, '', 12, 22, 0.00, 15.00, 1, 1431100800, 1431137673, 1, '', 0),
	(15, '', 12, 22, 0.00, 43.00, 1, 1431100800, 0, 0, '', 0),
	(16, '', 12, 22, 0.00, 10.00, 1, 1431100800, 0, 0, '', 0),
	(17, '', 12, 22, 0.00, 5.00, 1, 1431100800, 0, 0, '', 0),
	(18, '', 8, 4, 194900.00, 500.00, 1, 1431100800, 1431159935, 1, '', 0),
	(19, '', 8, 4, 245000.00, 50000.00, 1, 1431100800, 0, 0, '', 0),
	(20, '', 8, 4, 194500.00, 5000.00, 1, 1431100800, 1431159954, 1, '', 0),
	(21, '', 8, 4, 194500.00, 500.00, 1, 1431100800, 0, 0, '', 0),
	(22, '', 8, 4, 194000.00, 4000.00, 1, 1431100800, 0, 0, '', 0),
	(23, '', 8, 4, 190000.00, 90000.00, 1, 1431100800, 0, 0, '', 0),
	(24, '', 8, 4, 100000.00, 50000.00, 1, 1431100800, 0, 0, '', 0),
	(25, '', 6, 22, 100.00, 10.00, 1, 1431100800, 0, 0, '', 0),
	(26, '', 6, 22, 90.00, 5.00, 1, 1431100800, 0, 0, '', 0),
	(27, '', 12, 22, 880.00, 80.00, 1, 1431360000, 0, 0, '', 0),
	(28, '', 12, 22, 800.00, 100.00, 1, 1431360000, 0, 0, '', 0);
/*!40000 ALTER TABLE `kpos_loan_payments` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_loan_types
DROP TABLE IF EXISTS `kpos_loan_types`;
CREATE TABLE IF NOT EXISTS `kpos_loan_types` (
  `loan_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `term` int(11) NOT NULL,
  `term_period_type` varchar(50) NOT NULL,
  `percent_charge1` decimal(10,2) NOT NULL,
  `period_charge1` int(11) NOT NULL,
  `period_type1` varchar(50) NOT NULL,
  `percent_charge2` decimal(10,2) NOT NULL,
  `period_charge2` int(11) NOT NULL,
  `period_type2` varchar(50) NOT NULL,
  `percent_charge3` decimal(10,2) NOT NULL,
  `period_charge3` int(11) NOT NULL,
  `period_type3` varchar(50) NOT NULL,
  `percent_charge4` decimal(10,2) NOT NULL,
  `period_charge4` int(11) NOT NULL,
  `period_type4` varchar(50) NOT NULL,
  `percent_charge5` decimal(10,2) NOT NULL,
  `period_charge5` int(11) NOT NULL,
  `period_type5` varchar(50) NOT NULL,
  `percent_charge6` decimal(10,2) NOT NULL,
  `period_charge6` int(11) NOT NULL,
  `period_type6` varchar(50) NOT NULL,
  `percent_charge7` decimal(10,2) NOT NULL,
  `period_charge7` int(11) NOT NULL,
  `period_type7` varchar(50) NOT NULL,
  `percent_charge8` decimal(10,2) NOT NULL,
  `period_charge8` int(11) NOT NULL,
  `period_type8` varchar(50) NOT NULL,
  `percent_charge9` decimal(10,2) NOT NULL,
  `period_charge9` int(11) NOT NULL,
  `period_type9` varchar(50) NOT NULL,
  `percent_charge10` decimal(10,2) NOT NULL,
  `period_charge10` int(11) NOT NULL,
  `period_type10` varchar(50) NOT NULL,
  `added_by` int(11) NOT NULL,
  `date_added` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `date_modified` int(11) NOT NULL,
  PRIMARY KEY (`loan_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_loan_types: ~2 rows (approximately)
/*!40000 ALTER TABLE `kpos_loan_types` DISABLE KEYS */;
INSERT INTO `kpos_loan_types` (`loan_type_id`, `name`, `description`, `term`, `term_period_type`, `percent_charge1`, `period_charge1`, `period_type1`, `percent_charge2`, `period_charge2`, `period_type2`, `percent_charge3`, `period_charge3`, `period_type3`, `percent_charge4`, `period_charge4`, `period_type4`, `percent_charge5`, `period_charge5`, `period_type5`, `percent_charge6`, `period_charge6`, `period_type6`, `percent_charge7`, `period_charge7`, `period_type7`, `percent_charge8`, `period_charge8`, `period_type8`, `percent_charge9`, `period_charge9`, `period_type9`, `percent_charge10`, `period_charge10`, `period_type10`, `added_by`, `date_added`, `modified_by`, `date_modified`) VALUES
	(5, 'mais loan', 'for maisan loan, etc.', 1, 'monthly', 1.20, 1, 'year', 2.00, 1, 'month', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0, 0, 0, 0),
	(7, 'tubo loans', 'loans for tubo, etc.', 1, 'monthly', 1.20, 1, 'year', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0.00, 0, 'week', 0, 0, 0, 0);
/*!40000 ALTER TABLE `kpos_loan_types` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_modules
DROP TABLE IF EXISTS `kpos_modules`;
CREATE TABLE IF NOT EXISTS `kpos_modules` (
  `module_id` varchar(255) NOT NULL,
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `icons` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_modules: ~13 rows (approximately)
/*!40000 ALTER TABLE `kpos_modules` DISABLE KEYS */;
INSERT INTO `kpos_modules` (`module_id`, `name_lang_key`, `desc_lang_key`, `sort`, `icons`, `is_active`) VALUES
	('config', 'module_config', 'module_config_desc', 100, '<i class="fa fa-cogs" style="font-size: 50px; color:#26B6AE"></i>', 1),
	('customers', 'module_customers', 'module_customers_desc', 10, '<i class="fa fa-smile-o" style="font-size: 50px; color:#FF5400"></i>', 1),
	('employees', 'module_employees', 'module_employees_desc', 80, '<i class="fa fa-users" style="font-size: 50px; color:#FF5400"></i>', 1),
	('giftcards', 'module_giftcards', 'module_giftcards_desc', 90, '', 0),
	('items', 'module_items', 'module_items_desc', 20, '', 0),
	('item_kits', 'module_item_kits', 'module_item_kits_desc', 30, '', 0),
	('loans', 'module_loans', 'module_loans_desc', 80, '<i class="fa fa-money" style="font-size: 50px; color:#4EA216"></i>', 1),
	('loan_types', 'module_loan_types', 'module_loan_types_desc', 79, '<i class="fa fa-sitemap" style="font-size: 50px; color:#e80a0a"></i>', 1),
	('payments', 'module_payments', 'module_payments_desc', 80, '<i class="fa fa-paypal" style="font-size: 50px; color:#2B9EC4"></i>', 1),
	('receivings', 'module_receivings', 'module_receivings_desc', 60, '', 0),
	('reports', 'module_reports', 'module_reports_desc', 50, '<i class="fa fa-bar-chart" style="font-size: 50px; color:#2B9EC4"></i>', 0),
	('sales', 'module_sales', 'module_sales_desc', 70, '<i class="fa fa-usd" style="font-size: 50px; color:#e80a0a"></i>', 0),
	('suppliers', 'module_suppliers', 'module_suppliers_desc', 40, '', 0);
/*!40000 ALTER TABLE `kpos_modules` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_payment_schedules
DROP TABLE IF EXISTS `kpos_payment_schedules`;
CREATE TABLE IF NOT EXISTS `kpos_payment_schedules` (
  `payment_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `recurrence` int(11) NOT NULL DEFAULT '0',
  `delete_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`payment_schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_payment_schedules: ~4 rows (approximately)
/*!40000 ALTER TABLE `kpos_payment_schedules` DISABLE KEYS */;
INSERT INTO `kpos_payment_schedules` (`payment_schedule_id`, `name`, `recurrence`, `delete_flag`) VALUES
	(1, 'weekly', 0, 0),
	(2, 'biweekly', 0, 0),
	(3, 'monthly', 0, 0),
	(4, 'bimonthly', 0, 0);
/*!40000 ALTER TABLE `kpos_payment_schedules` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_people
DROP TABLE IF EXISTS `kpos_people`;
CREATE TABLE IF NOT EXISTS `kpos_people` (
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `photo_url` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_people: ~37 rows (approximately)
/*!40000 ALTER TABLE `kpos_people` DISABLE KEYS */;
INSERT INTO `kpos_people` (`person_id`, `first_name`, `last_name`, `photo_url`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`) VALUES
	(1, 'Admin', 'Marino', '', '421-2583', 'admin@loans.com', 'nowhere', '0', '0', '0', '0', '0', '0'),
	(2, 'norman', 'marino', '100_81433.jpg', '', '', '', '', '', '', '', '', ''),
	(3, 'customer 123', 'customer name', '', '', '', '', '', '', '', '', '', ''),
	(4, 'norman1', 'marino', 'tree.jpg', '421-2583', 'norman.marino@gmail.com', 'fsdf asdfasdfas df', '', '', '', '', '', ''),
	(5, 'John', 'doe', '', '', '', '', '', '', '', '', '', ''),
	(6, 'John', 'doe', '', '', '', '', '', '', '', '', '', ''),
	(7, 'John', 'doe', '', '', '', '', '', '', '', '', '', ''),
	(8, 'test', 'test', '', '', '', '', '', '', '', '', '', ''),
	(9, 'j', 'j', '', '', '', '', '', '', '', '', '', ''),
	(10, 'dsfas123', 'fasdf123', '', '', '', 'Upper Cantil-e Dumaguete City, Neg. Or. Philippines 6200', '', '', '', '', '', ''),
	(11, 'hello', 'world', '', '', '', '', '', '', '', '', '', ''),
	(12, 'rodrigo', 'zuellig', '', '', '', '', '', '', '', '', '', ''),
	(13, 'norman', 'marino', '', '', 'norman.marino@gmail.com', '', '', '', '', '', '', ''),
	(14, 'ronard', 'cauba', '', '', '', '', '', '', '', '', '', ''),
	(15, 'normantest', 'testnorman', '', '', '', '', '', '', '', '', '', ''),
	(16, 'beboy', 'boboy', '', '', '', '', '', '', '', '', '', ''),
	(17, 'jb', 'jb', '', '', '', '', '', '', '', '', '', ''),
	(18, 'Chad', 'Alvarez', '', '', 'chad@alvarez.com', '', '', '', '', '', '', ''),
	(19, 'regidor', 'marino', 'norman.jpg', '', '', '', '', '', '', '', '', ''),
	(20, 'dsfasdfa', 'fasdfa', 'tree.jpg', '', '', '', '', '', '', '', '', ''),
	(21, 'test', 'sfdas', 'norman.jpg', '', '', '', '', '', '', '', '', ''),
	(22, 'ronard', 'cauba', '', '', '', '', '', '', '', '', '', ''),
	(23, 'regidor', 'marino', '1609802_666202490102593_1919724290_n (1).jpg', '', '', '', '', '', '', '', '', ''),
	(27, 'fasdfasdf', 'fasdfasdf', '', '', '', '', '', '', '', '', '', ''),
	(28, 'fsdf asdf as', 'df asdfs', 'me_bata.jpg', '', '', '', '', '', '', '', '', ''),
	(29, 'fsadf asdf asf as ', 'f asdfsadf sdf s', 'selfie2.jpg', '', '', '', '', '', '', '', '', ''),
	(31, 'fsadf asdf asf as ', 'f asdfsadf sdf s', 'selfie2.jpg', '', '', '', '', '', '', '', '', ''),
	(33, 'new1', 'fsadfsdf', 'selfie2.jpg', '', '', '', '', '', '', '', '', ''),
	(37, 'dgdsfgsdgd', 'gsdfgsdfgsdg', 'selfie2.jpg', '', '', '', '', '', '', '', '', ''),
	(41, 'asdfasdf sdaf sdf', 'fa sfsdfsafad fsdf', 'o_19l3917rl1h4rq908emsae36jh.jpg', '', '', '', '', '', '', '', '', ''),
	(42, 'fsd fsa dfasdf', 'fs adfsdfsdf', '', '', '', '', '', '', '', '', '', ''),
	(43, 'sdfds fas', 'tertert', 'images.jpg', '', '', '', '', '', '', '', '', ''),
	(44, 'finally', 'working', 'images.jpg', '', '', '', '', '', '', '', '', ''),
	(45, 'luiz', 'beck', 'o_19l38lien83a1toip8kakh30t7.jpg', '', '', '', '', '', '', '', '', ''),
	(46, 'luiz', 'beck', '20150112_155934-1.jpg', '', '', '', '', '', '', '', '', ''),
	(47, 'fasdfsad', 'fsadfsadf', 'o_19l38mgeo1uan9s61p1cp1l1isc7.jpg', '', '', '', '', '', '', '', '', ''),
	(48, 'new cust', 'fsdf', 'o_19l3a54bv1pne1g0e1nvp1ji11qfp7.jpg', '', '', '', '', '', '', '', '', '');
/*!40000 ALTER TABLE `kpos_people` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_permissions
DROP TABLE IF EXISTS `kpos_permissions`;
CREATE TABLE IF NOT EXISTS `kpos_permissions` (
  `permission_id` varchar(255) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  `location_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `module_id` (`module_id`),
  KEY `ospos_permissions_ibfk_2` (`location_id`),
  CONSTRAINT `kpos_permissions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `kpos_modules` (`module_id`) ON DELETE CASCADE,
  CONSTRAINT `kpos_permissions_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `kpos_stock_locations` (`location_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_permissions: ~26 rows (approximately)
/*!40000 ALTER TABLE `kpos_permissions` DISABLE KEYS */;
INSERT INTO `kpos_permissions` (`permission_id`, `module_id`, `location_id`) VALUES
	('config', 'config', NULL),
	('customers', 'customers', NULL),
	('employees', 'employees', NULL),
	('giftcards', 'giftcards', NULL),
	('items', 'items', NULL),
	('items_', 'items', 2),
	('items_stock', 'items', 1),
	('item_kits', 'item_kits', NULL),
	('loans', 'loans', NULL),
	('loan_types', 'loan_types', NULL),
	('payments', 'payments', NULL),
	('receivings', 'receivings', NULL),
	('reports', 'reports', NULL),
	('reports_categories', 'reports', NULL),
	('reports_customers', 'reports', NULL),
	('reports_discounts', 'reports', NULL),
	('reports_employees', 'reports', NULL),
	('reports_inventory', 'reports', NULL),
	('reports_items', 'reports', NULL),
	('reports_payments', 'reports', NULL),
	('reports_receivings', 'reports', NULL),
	('reports_sales', 'reports', NULL),
	('reports_suppliers', 'reports', NULL),
	('reports_taxes', 'reports', NULL),
	('sales', 'sales', NULL),
	('suppliers', 'suppliers', NULL);
/*!40000 ALTER TABLE `kpos_permissions` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_receivings
DROP TABLE IF EXISTS `kpos_receivings`;
CREATE TABLE IF NOT EXISTS `kpos_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) DEFAULT NULL,
  `invoice_number` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `kpos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `kpos_employees` (`person_id`),
  CONSTRAINT `kpos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `kpos_suppliers` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_receivings: ~1 rows (approximately)
/*!40000 ALTER TABLE `kpos_receivings` DISABLE KEYS */;
INSERT INTO `kpos_receivings` (`receiving_time`, `supplier_id`, `employee_id`, `comment`, `receiving_id`, `payment_type`, `invoice_number`) VALUES
	('2015-04-19 21:47:53', NULL, 1, '', 1, 'Cash', NULL);
/*!40000 ALTER TABLE `kpos_receivings` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_receivings_items
DROP TABLE IF EXISTS `kpos_receivings_items`;
CREATE TABLE IF NOT EXISTS `kpos_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_location` int(11) NOT NULL,
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `kpos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  CONSTRAINT `kpos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `kpos_receivings` (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_receivings_items: ~1 rows (approximately)
/*!40000 ALTER TABLE `kpos_receivings_items` DISABLE KEYS */;
INSERT INTO `kpos_receivings_items` (`receiving_id`, `item_id`, `description`, `serialnumber`, `line`, `quantity_purchased`, `item_cost_price`, `item_unit_price`, `discount_percent`, `item_location`) VALUES
	(1, 13, '', '', 1, 1.00, 4.00, 4.00, 0.00, 1);
/*!40000 ALTER TABLE `kpos_receivings_items` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales
DROP TABLE IF EXISTS `kpos_sales`;
CREATE TABLE IF NOT EXISTS `kpos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `invoice_number` varchar(32) DEFAULT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `kpos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `kpos_employees` (`person_id`),
  CONSTRAINT `kpos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `kpos_customers` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales: ~22 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales` DISABLE KEYS */;
INSERT INTO `kpos_sales` (`sale_time`, `customer_id`, `employee_id`, `comment`, `invoice_number`, `sale_id`, `payment_type`) VALUES
	('2015-04-03 05:48:46', NULL, 1, '0', NULL, 1, 'Cash: $100.00<br />'),
	('2015-04-03 06:04:01', NULL, 1, '0', NULL, 2, 'Cash: $52.08<br />'),
	('2015-04-03 08:57:49', NULL, 1, '0', NULL, 3, 'Cash: $52.08<br />'),
	('2015-04-03 08:59:05', NULL, 1, '0', NULL, 4, 'Cash: $104.16<br />'),
	('2015-04-03 09:11:42', NULL, 1, '0', NULL, 5, 'Cash: P102.08<br />'),
	('2015-04-04 07:19:33', NULL, 1, 'sf safs afas fs fsad sqf', NULL, 6, 'Cash: P52.08<br />'),
	('2015-04-04 07:21:42', NULL, 1, '0', NULL, 7, 'Cash: P52.08<br />'),
	('2015-04-04 07:22:20', NULL, 1, '0', NULL, 8, 'Cash: -P52.08<br />'),
	('2015-04-04 13:52:05', NULL, 2, '0', NULL, 9, 'Cash: P140.16<br />'),
	('2015-04-05 15:08:52', NULL, 2, '0', '11', 10, 'Cash: P176.16<br />'),
	('2015-04-05 15:10:21', NULL, 2, '0', '1', 11, 'Cash: -P88.08<br />'),
	('2015-04-05 15:45:21', 10, 1, '0', NULL, 12, 'Cash: P88.08<br />'),
	('2015-04-05 16:53:35', NULL, 1, '0', NULL, 13, 'Cash: P52.08<br />'),
	('2015-04-05 18:15:16', 9, 1, '0', '25', 14, 'Cash: P88.08<br />'),
	('2015-04-05 18:24:18', NULL, 1, '0', '28', 15, 'Cash: P104.16<br />'),
	('2015-04-05 18:25:19', NULL, 1, '0', '4', 16, 'Cash: P140.16<br />'),
	('2015-04-05 18:29:22', NULL, 1, '0', '5', 17, 'Cash: P212.16<br />'),
	('2015-04-05 19:20:44', NULL, 1, '0', NULL, 18, 'Cash: P20.00<br />Credit Card: P32.08<br />'),
	('2015-04-21 00:26:35', NULL, 1, '0', '6', 19, 'Check: P610.10<br />Credit Card: P40.00<br />Cash: -P345.05<br />'),
	('2015-04-23 22:06:15', NULL, 1, '0', NULL, 20, 'Cash: P60.00<br />'),
	('2015-04-23 22:08:40', NULL, 1, '0', NULL, 21, 'Cash: P500.00<br />'),
	('2015-05-04 08:01:57', NULL, 1, '0', NULL, 22, '');
/*!40000 ALTER TABLE `kpos_sales` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_items
DROP TABLE IF EXISTS `kpos_sales_items`;
CREATE TABLE IF NOT EXISTS `kpos_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_location` int(11) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`),
  KEY `item_location` (`item_location`),
  CONSTRAINT `kpos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  CONSTRAINT `kpos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales` (`sale_id`),
  CONSTRAINT `kpos_sales_items_ibfk_3` FOREIGN KEY (`item_location`) REFERENCES `kpos_stock_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_items: ~39 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_items` DISABLE KEYS */;
INSERT INTO `kpos_sales_items` (`sale_id`, `item_id`, `description`, `serialnumber`, `line`, `quantity_purchased`, `item_cost_price`, `item_unit_price`, `discount_percent`, `item_location`) VALUES
	(1, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(2, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(3, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(4, 1, 'hello world', '', 1, 2.00, 45.75, 46.50, 0.00, 1),
	(5, 1, 'hello world', '', 1, 2.00, 45.75, 46.50, 2.00, 1),
	(6, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(7, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(8, 1, 'hello world', '', 1, -1.00, 45.75, 46.50, 0.00, 1),
	(9, 1, 'hello world', '', 1, 2.00, 45.75, 46.50, 0.00, 1),
	(9, 2, '', '', 2, 1.00, 35.00, 36.00, 0.00, 1),
	(10, 1, 'hello world', '', 1, 2.00, 45.75, 46.50, 0.00, 1),
	(10, 2, '', '', 2, 2.00, 35.00, 36.00, 0.00, 1),
	(11, 1, 'hello world', '', 1, -1.00, 45.75, 46.50, 0.00, 1),
	(11, 2, '', '', 2, -1.00, 35.00, 36.00, 0.00, 1),
	(12, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(12, 2, '', '', 2, 1.00, 35.00, 36.00, 0.00, 1),
	(13, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(14, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(14, 2, '', '', 2, 1.00, 35.00, 36.00, 0.00, 1),
	(15, 1, 'hello world', '', 1, 2.00, 45.75, 46.50, 0.00, 1),
	(16, 1, 'hello world', '', 1, 2.00, 45.75, 46.50, 0.00, 1),
	(16, 2, '', '', 2, 1.00, 35.00, 36.00, 0.00, 1),
	(17, 1, 'hello world', '', 2, 2.00, 45.75, 46.50, 0.00, 1),
	(17, 2, '', '', 1, 3.00, 35.00, 36.00, 0.00, 1),
	(18, 1, 'hello world', '', 1, 1.00, 45.75, 46.50, 0.00, 1),
	(19, 1, '0', '0', 1, 6.00, 45.75, 46.51, 4.00, 1),
	(19, 13, '', '', 2, 1.00, 4.00, 5.00, 0.00, 1),
	(20, 1, 'hello world', '', 1, 1.00, 45.75, 46.51, 0.00, 1),
	(21, 1, 'hello world', '', 1, 3.00, 45.75, 46.51, 0.00, 1),
	(21, 2, '', '', 5, 1.00, 35.00, 36.00, 0.00, 1),
	(21, 10, '', '', 2, 1.00, 11.00, 12.00, 0.00, 1),
	(21, 11, '', '', 3, 1.00, 5.00, 6.00, 0.00, 1),
	(21, 13, '0', '0', 4, 10.00, 4.00, 5.00, 0.00, 1),
	(22, 1, '0', '0', 7, 70.00, 45.75, 46.51, 0.00, 1),
	(22, 4, '0', '0', 11, 10.00, 0.00, 0.00, 0.00, 1),
	(22, 7, '', '', 10, 1.00, 40.00, 45.00, 0.00, 1),
	(22, 10, '0', '0', 6, 6.00, 11.00, 12.00, 0.00, 1),
	(22, 11, '0', '0', 9, 10.00, 5.00, 6.00, 0.00, 1),
	(22, 13, '', '', 8, 3.00, 4.00, 5.00, 0.00, 1);
/*!40000 ALTER TABLE `kpos_sales_items` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_items_taxes
DROP TABLE IF EXISTS `kpos_sales_items_taxes`;
CREATE TABLE IF NOT EXISTS `kpos_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `kpos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_items` (`sale_id`),
  CONSTRAINT `kpos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_items_taxes: ~32 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_items_taxes` DISABLE KEYS */;
INSERT INTO `kpos_sales_items_taxes` (`sale_id`, `item_id`, `line`, `name`, `percent`) VALUES
	(1, 1, 1, '', 12.00),
	(2, 1, 1, '', 12.00),
	(3, 1, 1, '', 12.00),
	(4, 1, 1, '', 12.00),
	(5, 1, 1, '', 12.00),
	(6, 1, 1, '', 12.00),
	(7, 1, 1, '', 12.00),
	(8, 1, 1, '', 12.00),
	(9, 1, 1, '', 12.00),
	(10, 1, 1, '', 12.00),
	(11, 1, 1, '', 12.00),
	(12, 1, 1, '', 12.00),
	(13, 1, 1, '', 12.00),
	(14, 1, 1, '', 12.00),
	(15, 1, 1, '', 12.00),
	(16, 1, 1, '', 12.00),
	(17, 1, 2, '', 12.00),
	(18, 1, 1, '', 12.00),
	(19, 1, 1, '', 12.00),
	(19, 13, 2, '', 0.00),
	(20, 1, 1, '', 12.00),
	(21, 1, 1, '', 12.00),
	(21, 2, 5, 'Sales Tax', 5.00),
	(21, 10, 2, '', 0.00),
	(21, 11, 3, '', 0.00),
	(21, 13, 4, '', 0.00),
	(22, 1, 7, '', 12.00),
	(22, 4, 11, '', 0.00),
	(22, 7, 10, '', 0.00),
	(22, 10, 6, '', 0.00),
	(22, 11, 9, '', 0.00),
	(22, 13, 8, '', 0.00);
/*!40000 ALTER TABLE `kpos_sales_items_taxes` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_payments
DROP TABLE IF EXISTS `kpos_sales_payments`;
CREATE TABLE IF NOT EXISTS `kpos_sales_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`),
  KEY `sale_id` (`sale_id`),
  CONSTRAINT `kpos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_payments: ~24 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_payments` DISABLE KEYS */;
INSERT INTO `kpos_sales_payments` (`sale_id`, `payment_type`, `payment_amount`) VALUES
	(1, 'Cash', 100.00),
	(2, 'Cash', 52.08),
	(3, 'Cash', 52.08),
	(4, 'Cash', 104.16),
	(5, 'Cash', 102.08),
	(6, 'Cash', 52.08),
	(7, 'Cash', 52.08),
	(8, 'Cash', -52.08),
	(9, 'Cash', 140.16),
	(10, 'Cash', 176.16),
	(11, 'Cash', -88.08),
	(12, 'Cash', 88.08),
	(13, 'Cash', 52.08),
	(14, 'Cash', 88.08),
	(15, 'Cash', 104.16),
	(16, 'Cash', 140.16),
	(17, 'Cash', 212.16),
	(18, 'Cash', 20.00),
	(18, 'Credit Card', 32.08),
	(19, 'Cash', -345.05),
	(19, 'Check', 610.10),
	(19, 'Credit Card', 40.00),
	(20, 'Cash', 60.00),
	(21, 'Cash', 500.00);
/*!40000 ALTER TABLE `kpos_sales_payments` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_suspended
DROP TABLE IF EXISTS `kpos_sales_suspended`;
CREATE TABLE IF NOT EXISTS `kpos_sales_suspended` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text NOT NULL,
  `invoice_number` varchar(32) DEFAULT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `kpos_sales_suspended_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `kpos_employees` (`person_id`),
  CONSTRAINT `kpos_sales_suspended_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `kpos_customers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_suspended: ~0 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_suspended` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpos_sales_suspended` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_suspended_items
DROP TABLE IF EXISTS `kpos_sales_suspended_items`;
CREATE TABLE IF NOT EXISTS `kpos_sales_suspended_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_location` int(11) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`),
  KEY `ospos_sales_suspended_items_ibfk_3` (`item_location`),
  CONSTRAINT `kpos_sales_suspended_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`),
  CONSTRAINT `kpos_sales_suspended_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_suspended` (`sale_id`),
  CONSTRAINT `kpos_sales_suspended_items_ibfk_3` FOREIGN KEY (`item_location`) REFERENCES `kpos_stock_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_suspended_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_suspended_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpos_sales_suspended_items` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_suspended_items_taxes
DROP TABLE IF EXISTS `kpos_sales_suspended_items_taxes`;
CREATE TABLE IF NOT EXISTS `kpos_sales_suspended_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `percent` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `kpos_sales_suspended_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_suspended_items` (`sale_id`),
  CONSTRAINT `kpos_sales_suspended_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `kpos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_suspended_items_taxes: ~0 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_suspended_items_taxes` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpos_sales_suspended_items_taxes` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sales_suspended_payments
DROP TABLE IF EXISTS `kpos_sales_suspended_payments`;
CREATE TABLE IF NOT EXISTS `kpos_sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`),
  CONSTRAINT `kpos_sales_suspended_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `kpos_sales_suspended` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sales_suspended_payments: ~0 rows (approximately)
/*!40000 ALTER TABLE `kpos_sales_suspended_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `kpos_sales_suspended_payments` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_sessions
DROP TABLE IF EXISTS `kpos_sessions`;
CREATE TABLE IF NOT EXISTS `kpos_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_sessions: ~43 rows (approximately)
/*!40000 ALTER TABLE `kpos_sessions` DISABLE KEYS */;
INSERT INTO `kpos_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
	('057eadc56c5675c94434c64ac7e97a7c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134875, ''),
	('08c5c6ab0d5228797e908783ae718d42', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429797733, ''),
	('0c7eb0f4000e06e3526a45bde9cc2464', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1430727972, 'a:8:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";s:13:"sale_location";s:1:"1";s:4:"cart";a:6:{i:1;a:14:{s:7:"item_id";s:1:"1";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:1;s:4:"name";s:7:"my item";s:11:"item_number";s:3:"001";s:11:"description";b:0;s:12:"serialnumber";b:0;s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";d:11;s:8:"discount";s:1:"0";s:8:"in_stock";s:1:"0";s:5:"price";s:5:"46.51";}i:2;a:14:{s:7:"item_id";s:1:"2";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:2;s:4:"name";s:5:"test1";s:11:"item_number";s:3:"002";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:2;s:8:"discount";i:0;s:8:"in_stock";s:2:"94";s:5:"price";s:5:"36.00";}i:3;a:14:{s:7:"item_id";s:2:"13";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:3;s:4:"name";s:11:"norman item";s:11:"item_number";s:3:"008";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";d:6;s:8:"discount";i:0;s:8:"in_stock";s:2:"-8";s:5:"price";s:4:"5.00";}i:4;a:14:{s:7:"item_id";s:2:"11";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:4;s:4:"name";s:8:"rewrqwer";s:11:"item_number";s:3:"007";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";s:3:"-11";s:5:"price";s:4:"6.00";}i:5;a:14:{s:7:"item_id";s:2:"10";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:5;s:4:"name";s:6:"asdfas";s:11:"item_number";s:3:"006";s:11:"description";b:0;s:12:"serialnumber";b:0;s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";s:1:"5";s:8:"discount";s:1:"0";s:8:"in_stock";s:2:"-6";s:5:"price";s:5:"12.00";}i:6;a:14:{s:7:"item_id";s:1:"7";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:6;s:4:"name";s:5:"dfasf";s:11:"item_number";N;s:11:"description";b:0;s:12:"serialnumber";b:0;s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";s:1:"4";s:8:"discount";s:1:"0";s:8:"in_stock";s:2:"-1";s:5:"price";s:5:"45.00";}}s:9:"sale_mode";s:4:"sale";s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"7";}'),
	('0ca8c23fe774dc286edb4ad61a887175', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134826, ''),
	('0ee0bf063bccb339b1db574a89fcc3b6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428297168, 'a:9:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";s:13:"sale_location";s:1:"1";s:28:"sales_invoice_number_enabled";s:4:"true";s:4:"cart";a:2:{i:1;a:14:{s:7:"item_id";s:1:"2";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:1;s:4:"name";s:5:"test1";s:11:"item_number";s:3:"002";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:2:"92";s:5:"price";s:5:"36.00";}i:2;a:14:{s:7:"item_id";s:1:"1";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:2;s:4:"name";s:7:"my item";s:11:"item_number";s:3:"001";s:11:"description";s:11:"hello world";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:2:"79";s:5:"price";s:5:"46.50";}}s:9:"sale_mode";s:4:"sale";s:8:"customer";i:-1;s:8:"payments";a:1:{s:4:"Cash";a:2:{s:12:"payment_type";s:4:"Cash";s:14:"payment_amount";s:5:"88.08";}}s:20:"sales_invoice_number";s:1:"6";}'),
	('19acf810ec0d1a0e54cae4d7d5afc0a6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1430007557, 'a:9:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";s:13:"sale_location";s:1:"1";s:4:"cart";a:2:{i:3;a:14:{s:7:"item_id";s:1:"2";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:3;s:4:"name";s:5:"test1";s:11:"item_number";s:3:"002";s:11:"description";b:0;s:12:"serialnumber";b:0;s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";s:1:"4";s:8:"discount";s:1:"0";s:8:"in_stock";s:2:"94";s:5:"price";s:5:"36.00";}i:4;a:14:{s:7:"item_id";s:1:"1";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:4;s:4:"name";s:7:"my item";s:11:"item_number";s:3:"001";s:11:"description";s:11:"hello world";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:2:"70";s:5:"price";s:5:"46.51";}}s:8:"payments";a:1:{s:4:"Cash";a:2:{s:12:"payment_type";s:4:"Cash";s:14:"payment_amount";s:5:"36.00";}}s:8:"customer";i:-1;s:7:"comment";s:1:"0";s:20:"sales_invoice_number";s:1:"5";s:9:"sale_mode";s:4:"sale";}'),
	('357de6ba50bf442eb759228061bfcf0b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429435438, ''),
	('39117afc447eab3162071add3362b17e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429709373, ''),
	('463d3c795e728723f8b849048af3f9aa', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429622188, ''),
	('470ddd9a9996c4ad6cc533db085ce934', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429622565, ''),
	('4e18c23dc17f94b25628f422f46f4c26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429413183, ''),
	('4faff9afface83d0680669ae30afe625', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429622578, ''),
	('527515eeb5c8fe912a163f5748babe0c', '::1', 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 1431415910, 'a:1:{s:9:"person_id";s:1:"1";}'),
	('54fc4c8f43575203c848e42faa05ca8b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429621960, ''),
	('597c9f2dd2bd3b4b48ffa74068a66cf5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134802, ''),
	('5aa2b87d0f17aa09c3d39e9336a8e711', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429278399, ''),
	('5bc76e37cc1f811c8fe3a9dcd1c8c653', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428155620, 'a:7:{s:9:"person_id";s:1:"1";s:13:"sale_location";s:1:"1";s:9:"sale_mode";s:6:"return";s:4:"cart";a:0:{}s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"0";}'),
	('607a0126f265985d3204d555d7ef9b9d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428142589, 'a:8:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"2";s:13:"sale_location";s:1:"1";s:9:"sale_mode";s:4:"sale";s:4:"cart";a:0:{}s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"0";}'),
	('67611fed7ca2f4f5ad6655689bc0abe6', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 1431355115, 'a:9:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";s:4:"data";a:2:{s:6:"params";a:2:{s:4:"name";s:19:"20141216_194844.jpg";s:7:"user_id";s:2:"41";}s:8:"filename";s:19:"20141216_194844.jpg";}s:4:"cart";a:10:{i:1;a:14:{s:7:"item_id";s:1:"1";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:1;s:4:"name";s:7:"my item";s:11:"item_number";s:3:"001";s:11:"description";b:0;s:12:"serialnumber";b:0;s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";s:2:"10";s:8:"discount";s:1:"0";s:8:"in_stock";s:1:"0";s:5:"price";s:5:"46.51";}i:2;a:14:{s:7:"item_id";s:1:"2";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:2;s:4:"name";s:5:"test1";s:11:"item_number";s:3:"002";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";s:2:"94";s:5:"price";s:5:"36.00";}i:3;a:14:{s:7:"item_id";s:1:"3";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:3;s:4:"name";s:9:"sadfas fa";s:11:"item_number";s:4:"1234";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";s:2:"50";s:5:"price";s:5:"44.00";}i:4;a:14:{s:7:"item_id";s:1:"4";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:4;s:4:"name";s:10:"fasdfsafsa";s:11:"item_number";s:3:"004";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";s:3:"-10";s:5:"price";s:4:"0.00";}i:5;a:14:{s:7:"item_id";s:1:"5";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:5;s:4:"name";s:0:"";s:11:"item_number";N;s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";i:0;s:5:"price";s:4:"0.00";}i:6;a:14:{s:7:"item_id";s:1:"6";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:6;s:4:"name";s:0:"";s:11:"item_number";N;s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:2;s:8:"discount";i:0;s:8:"in_stock";i:0;s:5:"price";s:4:"0.00";}i:7;a:14:{s:7:"item_id";s:1:"7";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:7;s:4:"name";s:5:"dfasf";s:11:"item_number";N;s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";s:2:"-1";s:5:"price";s:5:"45.00";}i:8;a:14:{s:7:"item_id";s:1:"8";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:8;s:4:"name";s:11:"fasdfasdfaf";s:11:"item_number";N;s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:3;s:8:"discount";i:0;s:8:"in_stock";s:2:"15";s:5:"price";s:5:"46.00";}i:9;a:14:{s:7:"item_id";s:1:"9";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:9;s:4:"name";s:9:"sadfasdfa";s:11:"item_number";N;s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:1:"5";s:5:"price";s:5:"13.00";}i:10;a:14:{s:7:"item_id";s:2:"10";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:10;s:4:"name";s:6:"asdfas";s:11:"item_number";s:3:"006";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:2:"-6";s:5:"price";s:5:"12.00";}}s:9:"sale_mode";s:4:"sale";s:13:"sale_location";s:1:"1";s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"7";}'),
	('67cc3cf4f14adad910713af31cb8e2fd', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428103414, 'a:10:{s:9:"person_id";s:1:"1";s:13:"item_location";s:1:"1";s:9:"recv_mode";s:7:"receive";s:28:"sales_invoice_number_enabled";s:5:"false";s:13:"sale_location";s:1:"1";s:9:"sale_mode";s:4:"sale";s:4:"cart";a:0:{}s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"0";}'),
	('6949581619fde46e15ec9b1975f5ebe4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428135830, 'a:9:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"2";s:13:"sale_location";s:1:"1";s:28:"sales_invoice_number_enabled";s:5:"false";s:9:"sale_mode";s:6:"return";s:4:"cart";a:0:{}s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"0";}'),
	('8df70369e263ee2a5f48ddaf453f877d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429705027, ''),
	('9050c573daf3e35b93764b2a354e0553', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1430316875, 'a:1:{s:9:"person_id";s:1:"1";}'),
	('9a0aa2fef7e4b3776f5cd705e39e551d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428230844, ''),
	('9bcf27fbdb201fc80a073921f29ab490', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134815, ''),
	('9ff2e8295dfd5768949e3bef620b80ca', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36', 1431141564, 'a:2:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";}'),
	('a444885ee5f5410d0d64ed71d5de6c0c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:35.0) Gecko/20100101 Firefox/35.0', 1429625589, ''),
	('accd4b05b93b01d8502190db93db3e57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429708979, ''),
	('ad564c163d347fc78d64a8083a991e43', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134887, ''),
	('b0500ad70f43bcbaa069d83723eeacea', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134793, ''),
	('b177801d888ad9e65c532f2dc05d431a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429620677, ''),
	('b619691943479fd0c8c3c9871d7655ef', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429619807, ''),
	('bd5e1ffa0b165c63fa14d2468b7e02d3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429622055, ''),
	('c968e8b31a102d8cccd844e664c5813b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429621220, ''),
	('cc3bede06d884e85160bb124ec9aecde', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36', 1428134871, ''),
	('d491907beb17b6bd79a30d5a1bfd07eb', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428236546, ''),
	('d54a702ae8b3f1fba712fc73308bdd48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428230840, ''),
	('da4b851f30c894cfeec07cad4770098d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1428224021, ''),
	('db1aa6b858c2c1e68fcf544a5b732918', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0', 1429626888, 'a:13:{s:9:"user_data";s:0:"";s:9:"person_id";s:1:"1";s:13:"sale_location";s:1:"1";s:4:"cart";a:2:{i:1;a:14:{s:7:"item_id";s:1:"2";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:1;s:4:"name";s:5:"test1";s:11:"item_number";s:3:"002";s:11:"description";b:0;s:12:"serialnumber";b:0;s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";s:1:"1";s:8:"discount";s:1:"0";s:8:"in_stock";s:2:"92";s:5:"price";s:5:"36.00";}i:2;a:14:{s:7:"item_id";s:1:"1";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:2;s:4:"name";s:7:"my item";s:11:"item_number";s:3:"001";s:11:"description";s:11:"hello world";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:2:"74";s:5:"price";s:5:"46.51";}}s:9:"sale_mode";s:4:"sale";s:8:"customer";i:-1;s:8:"payments";a:3:{s:4:"Cash";a:2:{s:12:"payment_type";s:4:"Cash";s:14:"payment_amount";d:8.1099999999999994315658113919198513031005859375;}s:11:"Credit Card";a:2:{s:12:"payment_type";s:11:"Credit Card";s:14:"payment_amount";s:2:"20";}s:5:"Check";a:2:{s:12:"payment_type";s:5:"Check";s:14:"payment_amount";s:5:"67.78";}}s:20:"sales_invoice_number";s:1:"6";s:13:"item_location";s:1:"1";s:8:"cartRecv";a:0:{}s:9:"recv_mode";s:7:"receive";s:8:"supplier";i:-1;s:19:"recv_invoice_number";s:1:"0";}'),
	('db52b6cae1deee7bffa393f63e541ccd', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429797680, ''),
	('e44db3114e9bc852ecdf6c1d29a05dee', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36', 1429709005, 'a:15:{s:9:"person_id";s:1:"1";s:13:"item_location";s:1:"1";s:9:"recv_mode";s:7:"receive";s:13:"sale_location";s:1:"1";s:17:"recv_stock_source";s:1:"1";s:27:"recv_invoice_number_enabled";s:5:"false";s:8:"cartRecv";a:0:{}s:8:"supplier";i:-1;s:19:"recv_invoice_number";s:1:"0";s:28:"sales_invoice_number_enabled";s:4:"true";s:4:"cart";a:2:{i:4;a:14:{s:7:"item_id";s:1:"2";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:4;s:4:"name";s:5:"test1";s:11:"item_number";s:3:"002";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:2:"95";s:5:"price";s:5:"36.00";}i:5;a:14:{s:7:"item_id";s:2:"13";s:13:"item_location";s:1:"1";s:10:"stock_name";s:5:"stock";s:4:"line";i:5;s:4:"name";s:11:"norman item";s:11:"item_number";s:3:"008";s:11:"description";s:0:"";s:12:"serialnumber";s:0:"";s:21:"allow_alt_description";s:1:"0";s:13:"is_serialized";s:1:"0";s:8:"quantity";i:1;s:8:"discount";i:0;s:8:"in_stock";s:1:"5";s:5:"price";s:4:"5.00";}}s:9:"sale_mode";s:4:"sale";s:8:"customer";i:-1;s:8:"payments";a:0:{}s:20:"sales_invoice_number";s:1:"7";}'),
	('ef3217c9051fb05d3c905fef9991c075', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429620922, ''),
	('fd5fc6cd60c475bcdbbeac520d61b007', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36', 1429883089, '');
/*!40000 ALTER TABLE `kpos_sessions` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_stock_locations
DROP TABLE IF EXISTS `kpos_stock_locations`;
CREATE TABLE IF NOT EXISTS `kpos_stock_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table loans.kpos_stock_locations: ~2 rows (approximately)
/*!40000 ALTER TABLE `kpos_stock_locations` DISABLE KEYS */;
INSERT INTO `kpos_stock_locations` (`location_id`, `location_name`, `deleted`) VALUES
	(1, 'stock', 1),
	(2, '', 0);
/*!40000 ALTER TABLE `kpos_stock_locations` ENABLE KEYS */;


-- Dumping structure for table loans.kpos_suppliers
DROP TABLE IF EXISTS `kpos_suppliers`;
CREATE TABLE IF NOT EXISTS `kpos_suppliers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `kpos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kpos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table loans.kpos_suppliers: ~5 rows (approximately)
/*!40000 ALTER TABLE `kpos_suppliers` DISABLE KEYS */;
INSERT INTO `kpos_suppliers` (`person_id`, `company_name`, `account_number`, `deleted`) VALUES
	(12, 'zuellig pharma', NULL, 0),
	(13, 'test123', NULL, 1),
	(14, 'new company', NULL, 0),
	(16, 'bacolod columbia', NULL, 0),
	(17, 'jb marketing', NULL, 1);
/*!40000 ALTER TABLE `kpos_suppliers` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
