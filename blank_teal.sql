-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.23-0ubuntu0.16.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table tealcrm.sc_companies
CREATE TABLE IF NOT EXISTS `sc_companies` (
  `company_id` varchar(36) NOT NULL DEFAULT '',
  `date_entered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `lead_source_id` int(11) DEFAULT NULL,
  `lead_status_id` int(11) DEFAULT NULL,
  `company_name` varchar(150) NOT NULL,
  `company_type` int(11) NOT NULL DEFAULT '75',
  `industry` int(11) NOT NULL,
  `phone_work` varchar(50) DEFAULT NULL,
  `phone_fax` varchar(50) DEFAULT NULL,
  `email1` varchar(120) NOT NULL,
  `email2` varchar(120) DEFAULT NULL,
  `do_not_call` enum('N','Y') NOT NULL DEFAULT 'N',
  `email_opt_out` enum('N','Y') NOT NULL DEFAULT 'N',
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `webpage` varchar(150) DEFAULT NULL,
  `description` longtext,
  `import_datetime` datetime DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_countries
CREATE TABLE IF NOT EXISTS `sc_countries` (
  `idCountry` int(5) NOT NULL AUTO_INCREMENT,
  `countryCode` char(2) NOT NULL DEFAULT '',
  `countryName` varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (`idCountry`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_custom_fields
CREATE TABLE IF NOT EXISTS `sc_custom_fields` (
  `cf_id` varchar(36) NOT NULL,
  `cf_module` varchar(45) NOT NULL,
  `cf_name` varchar(255) NOT NULL,
  `cf_label` varchar(255) NOT NULL,
  `cf_type` varchar(255) NOT NULL,
  `cf_data` text NOT NULL,
  `delete_status` int(10) NOT NULL,
  PRIMARY KEY (`cf_id`),
  UNIQUE KEY `cf_id` (`cf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_custom_fields_data
CREATE TABLE IF NOT EXISTS `sc_custom_fields_data` (
  `custom_fields_id` varchar(36) NOT NULL,
  `companies_id` varchar(36) NOT NULL,
  `data_value` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_custom_listview
CREATE TABLE IF NOT EXISTS `sc_custom_listview` (
  `id` varchar(56) NOT NULL,
  `module_type` varchar(56) NOT NULL,
  `field_name` varchar(56) NOT NULL,
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_deals
CREATE TABLE IF NOT EXISTS `sc_deals` (
  `deal_id` varchar(36) NOT NULL DEFAULT '',
  `date_entered` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `company_id` varchar(36) DEFAULT NULL,
  `people_id` varchar(36) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `expected_close_date` date NOT NULL,
  `sales_stage_id` int(11) NOT NULL DEFAULT '0',
  `probability` int(10) NOT NULL DEFAULT '0',
  `next_step` varchar(255) DEFAULT NULL,
  `description` longtext,
  `import_datetime` datetime DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`deal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_drop_down_options
CREATE TABLE IF NOT EXISTS `sc_drop_down_options` (
  `drop_down_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `related_module_id` int(10) NOT NULL,
  `related_field_name` varchar(100) NOT NULL,
  `editable` int(1) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `order_by` int(11) NOT NULL,
  PRIMARY KEY (`drop_down_id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_feeds
CREATE TABLE IF NOT EXISTS `sc_feeds` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(36) NOT NULL,
  `date_entered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `by_uacc_id` varchar(36) NOT NULL,
  `description` longtext NOT NULL,
  `category` tinyint(3) NOT NULL COMMENT '1=companies, 2=contacts, 3=deals, 4=notes, 5=tasks, 6=meetings',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_field_dictionary
CREATE TABLE IF NOT EXISTS `sc_field_dictionary` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `system_field` bit(1) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `field_type` varchar(30) DEFAULT NULL,
  `name_value` bit(1) DEFAULT b'0',
  `calculation` blob,
  `validation_rules` varchar(255) DEFAULT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `deleted` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_google_mail
CREATE TABLE IF NOT EXISTS `sc_google_mail` (
  `mail_id` varchar(56) NOT NULL,
  `subject` text,
  `message_body` text,
  `from_email` varchar(56) DEFAULT NULL,
  `from_name` varchar(56) DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `category` varchar(56) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_google_task
CREATE TABLE IF NOT EXISTS `sc_google_task` (
  `google_task_id` varchar(136) NOT NULL,
  `date_entered` datetime NOT NULL,
  `created_by` varchar(136) NOT NULL,
  `deleted` int(1) NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` varchar(52) DEFAULT NULL,
  `subject` varchar(136) NOT NULL,
  `description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_integrations
CREATE TABLE IF NOT EXISTS `sc_integrations` (
  `application_id` int(11) NOT NULL AUTO_INCREMENT,
  `application_name` varchar(100) NOT NULL,
  `api_key` varchar(500) NOT NULL,
  `api_secret` varchar(500) NOT NULL,
  `api_token` varchar(500) NOT NULL,
  `last_sync` datetime NOT NULL,
  `data` varchar(255) NOT NULL,
  PRIMARY KEY (`application_id`),
  KEY `application_id` (`application_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_language
CREATE TABLE IF NOT EXISTS `sc_language` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_entered` datetime DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `module_name` varchar(50) DEFAULT NULL,
  `field_name` varchar(150) DEFAULT NULL,
  `value` longtext,
  `deleted` bit(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_log
CREATE TABLE IF NOT EXISTS `sc_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `message` varchar(255) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_meetings
CREATE TABLE IF NOT EXISTS `sc_meetings` (
  `meeting_id` varchar(36) NOT NULL DEFAULT '',
  `date_entered` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `company_id` varchar(36) DEFAULT NULL,
  `people_id` varchar(36) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `event_type` int(11) NOT NULL DEFAULT '0',
  `description` longtext,
  `import_datetime` datetime DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `event_google_id` varchar(36) DEFAULT NULL,
  PRIMARY KEY (`meeting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_messages
CREATE TABLE IF NOT EXISTS `sc_messages` (
  `mail_id` varchar(36) DEFAULT NULL,
  `message_id` varchar(36) NOT NULL,
  `created_by` varchar(56) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `from_email` varchar(56) DEFAULT NULL,
  `from_name` varchar(56) DEFAULT NULL,
  `category` varchar(56) DEFAULT NULL,
  `message_type` int(5) NOT NULL,
  `recipients` int(255) NOT NULL,
  `message` blob NOT NULL,
  `timestamp` datetime NOT NULL,
  `assigned_user_id` varchar(36) NOT NULL,
  `status` varchar(36) NOT NULL DEFAULT 'Not Archived',
  `relationship_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_modules
CREATE TABLE IF NOT EXISTS `sc_modules` (
  `module_id` int(10) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `db_table` varchar(50) NOT NULL,
  `db_key` varchar(150) NOT NULL,
  `view_layout` blob,
  `search_options` blob,
  `list_layout` blob,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_module_relationships
CREATE TABLE IF NOT EXISTS `sc_module_relationships` (
  `id` int(11) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  `system_module` bit(1) DEFAULT b'1',
  `created_by` varchar(36) DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `module_id` varchar(100) DEFAULT NULL,
  `related_module` varchar(50) DEFAULT NULL,
  `related_module_id` varchar(100) DEFAULT NULL,
  `deleted` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_notes
CREATE TABLE IF NOT EXISTS `sc_notes` (
  `note_id` varchar(36) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `company_id` varchar(36) DEFAULT NULL,
  `people_id` varchar(36) DEFAULT NULL,
  `subject` varchar(150) NOT NULL,
  `description` longtext,
  `filename_original` varchar(150) DEFAULT NULL,
  `filename_mimetype` varchar(100) DEFAULT NULL,
  `import_datetime` datetime DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `project_id` varchar(36) NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_people
CREATE TABLE IF NOT EXISTS `sc_people` (
  `people_id` varchar(36) NOT NULL DEFAULT '',
  `date_entered` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `lead_source_id` int(11) NOT NULL,
  `lead_status_id` int(11) NOT NULL,
  `job_title` varchar(50) DEFAULT NULL,
  `company_id` varchar(36) NOT NULL,
  `contact_type` int(11) NOT NULL DEFAULT '0',
  `birthdate` date DEFAULT NULL,
  `phone_home` varchar(50) DEFAULT NULL,
  `phone_work` varchar(50) DEFAULT NULL,
  `phone_mobile` varchar(50) DEFAULT NULL,
  `email1` varchar(120) DEFAULT NULL,
  `email2` varchar(120) DEFAULT NULL,
  `do_not_call` enum('N','Y') NOT NULL DEFAULT 'N',
  `email_opt_out` enum('N','Y') NOT NULL DEFAULT 'N',
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `description` longtext,
  `import_datetime` datetime DEFAULT NULL,
  `csv_file_name` varchar(100) DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `google_access_token` tinytext,
  `mailchimp_id` varchar(100) DEFAULT '0',
  `last_viewed` datetime DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `google_contact_id` varchar(136) DEFAULT NULL,
  PRIMARY KEY (`people_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_products
CREATE TABLE IF NOT EXISTS `sc_products` (
  `product_id` varchar(36) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `modified_user_id` varchar(36) NOT NULL,
  `created_by` varchar(36) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `manufacturer_part_number` int(11) NOT NULL,
  `vendor_part_number` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `tax_percentage` decimal(10,2) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `description` text NOT NULL,
  `active` int(11) NOT NULL,
  `deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_product_lineitems
CREATE TABLE IF NOT EXISTS `sc_product_lineitems` (
  `product_lineitem_id` varchar(36) NOT NULL,
  `proposal_id` varchar(36) NOT NULL,
  `product_id` varchar(36) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `modified_user_id` varchar(36) NOT NULL,
  `created_by` varchar(36) NOT NULL,
  `product_type` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `manufacturer_part_number` int(11) NOT NULL,
  `vendor_part_number` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `list_price` decimal(10,2) NOT NULL,
  `tax_percentage` decimal(10,2) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_projects
CREATE TABLE IF NOT EXISTS `sc_projects` (
  `project_id` varchar(36) NOT NULL,
  `parent_id` varchar(36) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `company_id` varchar(36) DEFAULT NULL,
  `project_name` varchar(150) NOT NULL,
  `description` longtext,
  `time_estimate` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  `archived` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_project_tasks
CREATE TABLE IF NOT EXISTS `sc_project_tasks` (
  `sc_project_task_id` varchar(36) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `task_id` varchar(36) NOT NULL,
  PRIMARY KEY (`sc_project_task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_provinces
CREATE TABLE IF NOT EXISTS `sc_provinces` (
  `idProvince` int(2) NOT NULL AUTO_INCREMENT,
  `provinceName` varchar(32) NOT NULL COMMENT 'State name with first letter capital',
  `provinceCode` varchar(8) DEFAULT NULL COMMENT 'Optional state abbreviation (US is 2 capital letters)',
  `countryCode` varchar(2) NOT NULL,
  PRIMARY KEY (`idProvince`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_record_views
CREATE TABLE IF NOT EXISTS `sc_record_views` (
  `record_view_id` varchar(36) NOT NULL,
  `module_id` int(11) NOT NULL,
  `record_id` varchar(36) NOT NULL,
  `view_time_stamp` datetime NOT NULL,
  `description` varchar(200) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  PRIMARY KEY (`record_view_id`),
  UNIQUE KEY `record_view_id` (`record_view_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_saved_search
CREATE TABLE IF NOT EXISTS `sc_saved_search` (
  `search_id` int(11) NOT NULL AUTO_INCREMENT,
  `date_entered` datetime NOT NULL,
  `created_by` varchar(36) NOT NULL,
  `title` varchar(36) NOT NULL,
  `module` varchar(36) NOT NULL,
  `search_string` blob NOT NULL,
  PRIMARY KEY (`search_id`),
  UNIQUE KEY `search_id` (`search_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_sessions
CREATE TABLE IF NOT EXISTS `sc_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_settings
CREATE TABLE IF NOT EXISTS `sc_settings` (
  `id` int(1) NOT NULL DEFAULT '1',
  `site_id` varchar(100) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `company_type` varchar(50) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `country` varchar(255) NOT NULL,
  `date_modified` datetime NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `billing_email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_tasks
CREATE TABLE IF NOT EXISTS `sc_tasks` (
  `task_id` varchar(36) NOT NULL DEFAULT '',
  `parent_id` varchar(36) NOT NULL DEFAULT '0',
  `date_entered` datetime NOT NULL,
  `date_modified` datetime DEFAULT NULL,
  `modified_user_id` varchar(36) DEFAULT NULL,
  `assigned_user_id` varchar(36) DEFAULT NULL,
  `created_by` varchar(36) DEFAULT NULL,
  `company_id` varchar(36) DEFAULT NULL,
  `people_id` varchar(36) DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status_id` int(11) NOT NULL,
  `priority_id` int(11) NOT NULL,
  `description` longtext,
  `completed_date` datetime DEFAULT NULL,
  `import_datetime` datetime DEFAULT NULL,
  `last_viewed` datetime DEFAULT NULL,
  `time_used` int(11) NOT NULL,
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_templates
CREATE TABLE IF NOT EXISTS `sc_templates` (
  `template_id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `modified_user_id` varchar(36) NOT NULL,
  `created_by` varchar(36) NOT NULL,
  `html_body` text NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_accounts
CREATE TABLE IF NOT EXISTS `sc_user_accounts` (
  `uacc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uacc_uid` varchar(36) NOT NULL,
  `uacc_group_fk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `uacc_email` varchar(100) NOT NULL DEFAULT '',
  `uacc_username` varchar(15) NOT NULL DEFAULT '',
  `uacc_password` varchar(60) NOT NULL DEFAULT '',
  `uacc_ip_address` varchar(40) NOT NULL DEFAULT '',
  `uacc_salt` varchar(40) NOT NULL DEFAULT '',
  `uacc_activation_token` varchar(40) NOT NULL DEFAULT '',
  `uacc_forgotten_password_token` varchar(40) NOT NULL DEFAULT '',
  `uacc_forgotten_password_expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uacc_update_email_token` varchar(40) NOT NULL DEFAULT '',
  `uacc_update_email` varchar(100) NOT NULL DEFAULT '',
  `uacc_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uacc_suspend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `uacc_fail_login_attempts` smallint(5) NOT NULL DEFAULT '0',
  `uacc_fail_login_ip_address` varchar(40) NOT NULL DEFAULT '',
  `uacc_date_fail_login_ban` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Time user is banned until due to repeated failed logins',
  `uacc_date_last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uacc_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uacc_id`),
  UNIQUE KEY `uacc_id` (`uacc_id`),
  KEY `uacc_group_fk` (`uacc_group_fk`),
  KEY `uacc_email` (`uacc_email`),
  KEY `uacc_username` (`uacc_username`),
  KEY `uacc_fail_login_ip_address` (`uacc_fail_login_ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_address
CREATE TABLE IF NOT EXISTS `sc_user_address` (
  `uadd_id` int(11) NOT NULL AUTO_INCREMENT,
  `uadd_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `uadd_alias` varchar(50) NOT NULL DEFAULT '',
  `uadd_recipient` varchar(100) NOT NULL DEFAULT '',
  `uadd_phone` varchar(25) NOT NULL DEFAULT '',
  `uadd_company` varchar(75) NOT NULL DEFAULT '',
  `uadd_address_01` varchar(100) NOT NULL DEFAULT '',
  `uadd_address_02` varchar(100) NOT NULL DEFAULT '',
  `uadd_city` varchar(50) NOT NULL DEFAULT '',
  `uadd_county` varchar(50) NOT NULL DEFAULT '',
  `uadd_post_code` varchar(25) NOT NULL DEFAULT '',
  `uadd_country` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`uadd_id`),
  UNIQUE KEY `uadd_id` (`uadd_id`),
  KEY `uadd_uacc_fk` (`uadd_uacc_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_groups
CREATE TABLE IF NOT EXISTS `sc_user_groups` (
  `ugrp_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `ugrp_name` varchar(20) NOT NULL DEFAULT '',
  `ugrp_desc` varchar(100) NOT NULL DEFAULT '',
  `ugrp_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ugrp_id`),
  UNIQUE KEY `ugrp_id` (`ugrp_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_login_sessions
CREATE TABLE IF NOT EXISTS `sc_user_login_sessions` (
  `usess_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `usess_series` varchar(40) NOT NULL DEFAULT '',
  `usess_token` varchar(40) NOT NULL DEFAULT '',
  `usess_login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`usess_token`),
  UNIQUE KEY `usess_token` (`usess_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_privileges
CREATE TABLE IF NOT EXISTS `sc_user_privileges` (
  `upriv_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `upriv_name` varchar(20) NOT NULL DEFAULT '',
  `upriv_desc` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`upriv_id`),
  UNIQUE KEY `upriv_id` (`upriv_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_privilege_groups
CREATE TABLE IF NOT EXISTS `sc_user_privilege_groups` (
  `upriv_groups_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `upriv_groups_ugrp_fk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `upriv_groups_upriv_fk` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`upriv_groups_id`),
  UNIQUE KEY `upriv_groups_id` (`upriv_groups_id`) USING BTREE,
  KEY `upriv_groups_ugrp_fk` (`upriv_groups_ugrp_fk`),
  KEY `upriv_groups_upriv_fk` (`upriv_groups_upriv_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_privilege_users
CREATE TABLE IF NOT EXISTS `sc_user_privilege_users` (
  `upriv_users_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `upriv_users_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `upriv_users_upriv_fk` smallint(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`upriv_users_id`),
  UNIQUE KEY `upriv_users_id` (`upriv_users_id`) USING BTREE,
  KEY `upriv_users_uacc_fk` (`upriv_users_uacc_fk`),
  KEY `upriv_users_upriv_fk` (`upriv_users_upriv_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Data exporting was unselected.
-- Dumping structure for table tealcrm.sc_user_profiles
CREATE TABLE IF NOT EXISTS `sc_user_profiles` (
  `upro_id` int(11) NOT NULL AUTO_INCREMENT,
  `upro_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `upro_company` varchar(50) NOT NULL DEFAULT '',
  `upro_first_name` varchar(50) NOT NULL DEFAULT '',
  `upro_last_name` varchar(50) NOT NULL DEFAULT '',
  `language` varchar(50) NOT NULL DEFAULT 'english_ca',
  `upro_phone` varchar(25) NOT NULL DEFAULT '',
  `upro_newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `upro_filename_mimetype` varchar(110) DEFAULT NULL,
  `upro_filename_original` varchar(110) DEFAULT NULL,
  `upro_google_email` varchar(100) DEFAULT NULL,
  `upro_google_name` varchar(100) DEFAULT NULL,
  `upro_google_access_token` tinytext NOT NULL,
  `upro_google_calendar_nextSyncToken` varchar(150) DEFAULT NULL,
  `email_sending_option` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 = using teal crm, 1 = using external email.',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `imap_address` varchar(500) NOT NULL,
  `ssl_value` varchar(10) NOT NULL,
  `mail_server_port` int(11) NOT NULL,
  `imap_active` int(1) NOT NULL,
  PRIMARY KEY (`upro_id`),
  UNIQUE KEY `upro_id` (`upro_id`),
  KEY `upro_uacc_fk` (`upro_uacc_fk`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Data exporting was unselected.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
