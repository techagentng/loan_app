/*norman*/
set foreign_key_checks=0;
-- Dumping structure for table loans.c19_app_config
DROP TABLE IF EXISTS `c19_app_config`;
CREATE TABLE IF NOT EXISTS `c19_app_config` (
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_app_config: ~32 rows (approximately)
/*!40000 ALTER TABLE `c19_app_config` DISABLE KEYS */;
INSERT INTO `c19_app_config` (`key`, `value`) VALUES
	('address', 'Your Address'),
	('company', 'SoftReliance'),
	('currency_side', ''),
	('currency_symbol', '$'),
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
	('date_format', 'd/m/Y'),
	('default_tax_1_name', '0'),
	('default_tax_1_rate', '0'),
	('default_tax_2_name', '0'),
	('default_tax_2_rate', '0'),
	('email', ''),
	('fax', ''),
	('language', 'en'),
	('language_used', 'au'),
	('logo', 'o_1av64kmfd1r2t1d1c7uiksd19tt7.jpg'),
	('phone', '111-2222'),
	('print_after_sale', '0'),
	('recv_invoice_format', '0'),
	('return_policy', '0'),
	('sales_invoice_format', '0'),
	('tax_included', '0'),
	('timezone', 'America/New_York'),
	('website', '');
/*!40000 ALTER TABLE `c19_app_config` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_attachments
DROP TABLE IF EXISTS `c19_attachments`;
CREATE TABLE IF NOT EXISTS `c19_attachments` (
  `attachment_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `descriptions` varchar(100) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  PRIMARY KEY (`attachment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_attachments: ~0 rows (approximately)
/*!40000 ALTER TABLE `c19_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_attachments` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_customers
DROP TABLE IF EXISTS `c19_customers`;
CREATE TABLE IF NOT EXISTS `c19_customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `credit_limit` decimal(10,2) NOT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  `added_by` int(5) DEFAULT NULL,
  `leads_id` INT(10) NULL DEFAULT NULL,
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `c19_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `c19_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_customers: ~8 rows (approximately)
/*!40000 ALTER TABLE `c19_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_customers` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_documents
CREATE TABLE IF NOT EXISTS `c19_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_type` varchar(100) NOT NULL,
  `foreign_id` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `last_modified_by` int(11) DEFAULT NULL,
  `last_modified_date` datetime DEFAULT NULL,
  `hide_flag` tinyint(1) DEFAULT '0',
  `check_flag` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping structure for table kloans.c19_emails
DROP TABLE IF EXISTS `c19_emails`;
CREATE TABLE IF NOT EXISTS `c19_emails` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `template_name` varchar(300) DEFAULT NULL,
  `templates` text,
  `descriptions` text NOT NULL,
  `person_id` int(11) NOT NULL,
  PRIMARY KEY (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table kloans.c19_emails: ~0 rows (approximately)
/*!40000 ALTER TABLE `c19_emails` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_emails` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_employees
DROP TABLE IF EXISTS `c19_employees`;
CREATE TABLE IF NOT EXISTS `c19_employees` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `added_by` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `can_approve_loan` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `c19_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `c19_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_employees: ~4 rows (approximately)
/*!40000 ALTER TABLE `c19_employees` DISABLE KEYS */;
INSERT INTO `c19_employees` (`username`, `password`, `person_id`, `added_by`, `deleted`, `can_approve_loan`) VALUES
	('admin', '0192023a7bbd73250516f069df18b500', 1, 1, 0, 0);
/*!40000 ALTER TABLE `c19_employees` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_financial_status
DROP TABLE IF EXISTS `c19_financial_status`;
CREATE TABLE IF NOT EXISTS `c19_financial_status` (
  `financial_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL,
  `income_sources` text NOT NULL,
  PRIMARY KEY (`financial_status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- Dumping structure for table kloans.c19_grants
DROP TABLE IF EXISTS `c19_grants`;
CREATE TABLE IF NOT EXISTS `c19_grants` (
  `permission_id` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`permission_id`,`person_id`),
  KEY `ospos_grants_ibfk_2` (`person_id`),
  CONSTRAINT `c19_grants_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `c19_permissions` (`permission_id`),
  CONSTRAINT `c19_grants_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `c19_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_grants: ~0 rows (approximately)
/*!40000 ALTER TABLE `c19_grants` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_grants` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_guarantee
DROP TABLE IF EXISTS `c19_guarantee`;
CREATE TABLE IF NOT EXISTS `c19_guarantee` (
  `guarantee_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(300) NOT NULL,
  `type` varchar(300) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `make` varchar(50) NOT NULL,
  `serial` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `proof` varchar(1000) NOT NULL,
  `images` varchar(1000) NOT NULL,
  `observations` varchar(1000) NOT NULL,
  PRIMARY KEY (`guarantee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_guarantee: ~42 rows (approximately)






-- Dumping structure for table kloans.c19_loans
DROP TABLE IF EXISTS `c19_loans`;
CREATE TABLE IF NOT EXISTS `c19_loans` (
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
  `payment_scheds` text NOT NULL,
  `periodic_loan_table` text NOT NULL,
  `apply_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(10,2) NOT NULL,
  `interest_type` varchar(100) NOT NULL,
  `term_period` varchar(100) NOT NULL,
  `payment_term` int(11) NOT NULL,
  `payment_start_date` int(11) NOT NULL,
  `loan_approved_date` int(11) NOT NULL,
  `exclude_sundays` tinyint(4) NOT NULL DEFAULT '0',
  `penalty_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `penalty_type` varchar(50) DEFAULT 'percentage',
  PRIMARY KEY (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- Dumping structure for table kloans.c19_loan_payments
DROP TABLE IF EXISTS `c19_loan_payments`;
CREATE TABLE IF NOT EXISTS `c19_loan_payments` (
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
  `payment_due` int(1) NOT NULL DEFAULT '0',
  `lpp_amount` decimal(10,2) DEFAULT '0.00' COMMENT 'Late Payment Penalty Amount',
  PRIMARY KEY (`loan_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



-- Dumping structure for table kloans.c19_loan_types
DROP TABLE IF EXISTS `c19_loan_types`;
CREATE TABLE IF NOT EXISTS `c19_loan_types` (
  `loan_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `term` int(11) NOT NULL,
  `term_period_type` varchar(50) NOT NULL,
  `payment_schedule` varchar(50) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_loan_types: ~2 rows (approximately)
/*!40000 ALTER TABLE `c19_loan_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_loan_types` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_messages
DROP TABLE IF EXISTS `c19_messages`;
CREATE TABLE IF NOT EXISTS `c19_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL DEFAULT '0',
  `recipient_id` int(11) NOT NULL DEFAULT '0',
  `mark_as_read` tinyint(1) NOT NULL DEFAULT '0',
  `header` varchar(300) NOT NULL,
  `body` text NOT NULL,
  `send_date` date NOT NULL,
  `receive_date` date NOT NULL,
  `sender_delete_flag` tinyint(4) NOT NULL DEFAULT '0',
  `recipient_delete_flag` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_messages: ~0 rows (approximately)
/*!40000 ALTER TABLE `c19_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_messages` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_modules
DROP TABLE IF EXISTS `c19_modules`;
CREATE TABLE IF NOT EXISTS `c19_modules` (
  `module_id` varchar(255) NOT NULL,
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `icons` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `label` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `sub_menus` varchar(2000) NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_modules: ~14 rows (approximately)
/*!40000 ALTER TABLE `c19_modules` DISABLE KEYS */;
INSERT INTO `c19_modules` (`module_id`, `name_lang_key`, `desc_lang_key`, `sort`, `icons`, `is_active`, `label`, `description`, `sub_menus`) VALUES
	('config', 'module_config', 'module_config_desc', 100, '<i class="fa fa-cogs"></i>', 1, '', '', ''),
	('customers', 'module_customers', 'module_customers_desc', 10, '<i class="fa fa-smile-o"></i>', 1, '', '', '{"New Item":"view/-1","Item List":"index"}'),
	('emails', 'module_email', 'module_email_desc', 0, '<i class="fa fa-envelope"></i>', 1, '', '', '{"New Item":"view/-1","Item List":"index"}'),
	('employees', 'module_employees', 'module_employees_desc', 80, '<i class="fa fa-users"></i>', 1, '', '', '{"New Item":"view/-1","Item List":"index"}'),
	('home', 'module_home', 'module_home_desc', 0, '<i class="fa fa-home"></i>', 1, 'Dashboard', '', ''),
	('loans', 'module_loans', 'module_loans_desc', 80, '<i class="fa fa-money"></i>', 1, '', '', '{"New Item":"view/-1","Item List":"index"}'),
	('loan_types', 'module_loan_types', 'module_loan_types_desc', 79, '<i class="fa fa-sitemap"></i>', 0, '', '', '{"New Item":"view/-1","Item List":"index"}'),
	('messages', 'module_messages', 'module_messages_desc', 80, '<i class="fa fa-envelope"></i>', 1, '', '', '{"Compose":"view/-1","Inbox":"inbox","Outbox":"outbox"}'),
	('my_wallets', 'module_my_wallets', 'module_my_wallets_desc', 79, '<i class="fa fa-briefcase"></i>', 1, '', '', ''),
	('overdues', 'module_overdues', 'module_overdues_desc', 80, '<i class="fa fa-file"></i>', 1, '', '', ''),
	('payments', 'module_payments', 'module_payments_desc', 80, '<i class="fa fa-paypal"></i>', 1, '', '', '{"New Item":"view/-1","Item List":"index"}'),
	('plugins', 'module_plugins', 'module_plugin_desc', 90, '<i class="fa fa-wrench"></i>', 1, '', '', ''),
	('roles', 'module_roles', 'module_roles_desc', 79, '<i class="fa fa-cogs"></i>', 1, '', '', '{"New Item":"view/-1","Item List":"index"}');
/*!40000 ALTER TABLE `c19_modules` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_payment_schedules
DROP TABLE IF EXISTS `c19_payment_schedules`;
CREATE TABLE IF NOT EXISTS `c19_payment_schedules` (
  `payment_schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `recurrence` int(11) NOT NULL DEFAULT '0',
  `delete_flag` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`payment_schedule_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_payment_schedules: ~4 rows (approximately)
/*!40000 ALTER TABLE `c19_payment_schedules` DISABLE KEYS */;
INSERT INTO `c19_payment_schedules` (`payment_schedule_id`, `name`, `recurrence`, `delete_flag`) VALUES
	(1, 'weekly', 0, 0),
	(2, 'biweekly', 0, 0),
	(3, 'monthly', 0, 0),
	(4, 'bimonthly', 0, 0);
/*!40000 ALTER TABLE `c19_payment_schedules` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_people
DROP TABLE IF EXISTS `c19_people`;
CREATE TABLE IF NOT EXISTS `c19_people` (
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
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_people: ~2 rows (approximately)
/*!40000 ALTER TABLE `c19_people` DISABLE KEYS */;
INSERT INTO `c19_people` (`person_id`, `first_name`, `last_name`, `photo_url`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `role_id`) VALUES
	(1, 'Admin1', 'Admin', 'o_1dvvde5ol1jnh1p9ma66gfafcm8.jpg', '421-2583', 'admin@loans.com', 'nowhere', '', '', '', '', '', '', 13);
/*!40000 ALTER TABLE `c19_people` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_permissions
DROP TABLE IF EXISTS `c19_permissions`;
CREATE TABLE IF NOT EXISTS `c19_permissions` (
  `permission_id` varchar(255) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  `location_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`permission_id`),
  KEY `module_id` (`module_id`),
  KEY `ospos_permissions_ibfk_2` (`location_id`),
  CONSTRAINT `c19_permissions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `c19_modules` (`module_id`) ON DELETE CASCADE,
  CONSTRAINT `c19_permissions_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `kpos_stock_locations` (`location_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_permissions: ~11 rows (approximately)
/*!40000 ALTER TABLE `c19_permissions` DISABLE KEYS */;
INSERT INTO `c19_permissions` (`permission_id`, `module_id`, `location_id`) VALUES
	('config', 'config', NULL),
	('customers', 'customers', NULL),
	('emails', 'emails', NULL),
	('employees', 'employees', NULL),
	('loans', 'loans', NULL),
	('loan_types', 'loan_types', NULL),
	('messages', 'messages', NULL),
	('my_wallets', 'my_wallets', NULL),
	('overdues', 'overdues', NULL),
	('payments', 'payments', NULL),
	('roles', 'roles', NULL);
/*!40000 ALTER TABLE `c19_permissions` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_plugins
DROP TABLE IF EXISTS `c19_plugins`;
CREATE TABLE IF NOT EXISTS `c19_plugins` (
  `plugin_id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(200) NOT NULL,
  `module_desc` varchar(200) NOT NULL,
  `module_settings` text NOT NULL,
  `status_flag` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  PRIMARY KEY (`plugin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;



-- Dumping structure for table kloans.c19_roles
DROP TABLE IF EXISTS `c19_roles`;
CREATE TABLE IF NOT EXISTS `c19_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `low_level` varchar(200) NOT NULL,
  `rights` text NOT NULL,
  `write_access` text NOT NULL,
  `added_by` int(11) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_roles: ~2 rows (approximately)
/*!40000 ALTER TABLE `c19_roles` DISABLE KEYS */;
INSERT INTO `c19_roles` (`role_id`, `name`, `low_level`, `rights`, `write_access`, `added_by`) VALUES
	(13, 'admin', '["15"]', '["home","leads","emails","customers","roles","loan_types","my_wallets","loans","messages","overdues","payments","employees","plugins","config"]', '["home","leads","emails","customers","roles","loan_types","my_wallets","loans","messages","overdues","payments","employees","plugins","config"]', 1),
	(15, 'Staff', 'false', '["customers","roles","loan_types"]', '', 1);
/*!40000 ALTER TABLE `c19_roles` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_sessions
DROP TABLE IF EXISTS `c19_sessions`;
CREATE TABLE IF NOT EXISTS `c19_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table kloans.c19_sessions: ~8 rows (approximately)


-- Dumping structure for table kloans.c19_smtp
DROP TABLE IF EXISTS `c19_smtp`;
CREATE TABLE IF NOT EXISTS `c19_smtp` (
  `smtp_id` int(11) NOT NULL AUTO_INCREMENT,
  `smtp_host` varchar(300) NOT NULL,
  `smtp_port` int(11) NOT NULL,
  `smtp_user` varchar(300) NOT NULL,
  `smtp_pass` varchar(300) NOT NULL,
  PRIMARY KEY (`smtp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_smtp: ~1 rows (approximately)
/*!40000 ALTER TABLE `c19_smtp` DISABLE KEYS */;
INSERT INTO `c19_smtp` (`smtp_id`, `smtp_host`, `smtp_port`, `smtp_user`, `smtp_pass`) VALUES
	(1, '', 0, '', '');
/*!40000 ALTER TABLE `c19_smtp` ENABLE KEYS */;


-- Dumping structure for table kloans.c19_wallets
DROP TABLE IF EXISTS `c19_wallets`;
CREATE TABLE IF NOT EXISTS `c19_wallets` (
  `wallet_id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) NOT NULL,
  `descriptions` varchar(200) NOT NULL,
  `wallet_type` enum('debit','credit','transfer') NOT NULL,
  `trans_date` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `transfer_to` int(11) NOT NULL,
  PRIMARY KEY (`wallet_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table kloans.c19_wallets: ~0 rows (approximately)
/*!40000 ALTER TABLE `c19_wallets` DISABLE KEYS */;
/*!40000 ALTER TABLE `c19_wallets` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
