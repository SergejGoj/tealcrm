-- phpMyAdmin SQL Dump
-- version 4.2.0
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2015 at 06:55 PM
-- Server version: 5.6.17
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `blank_teal`
--

-- --------------------------------------------------------

--
-- Table structure for table `sc_companies`
--

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
  `deleted` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_countries`
--

CREATE TABLE IF NOT EXISTS `sc_countries` (
`idCountry` int(5) NOT NULL,
  `countryCode` char(2) NOT NULL DEFAULT '',
  `countryName` varchar(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=251 ;

--
-- Dumping data for table `sc_countries`
--

INSERT INTO `sc_countries` (`idCountry`, `countryCode`, `countryName`) VALUES
(1, 'AD', 'Andorra'),
(2, 'AE', 'United Arab Emirates'),
(3, 'AF', 'Afghanistan'),
(4, 'AG', 'Antigua and Barbuda'),
(5, 'AI', 'Anguilla'),
(6, 'AL', 'Albania'),
(7, 'AM', 'Armenia'),
(8, 'AO', 'Angola'),
(9, 'AQ', 'Antarctica'),
(10, 'AR', 'Argentina'),
(11, 'AS', 'American Samoa'),
(12, 'AT', 'Austria'),
(13, 'AU', 'Australia'),
(14, 'AW', 'Aruba'),
(15, 'AX', 'Åland'),
(16, 'AZ', 'Azerbaijan'),
(17, 'BA', 'Bosnia and Herzegovina'),
(18, 'BB', 'Barbados'),
(19, 'BD', 'Bangladesh'),
(20, 'BE', 'Belgium'),
(21, 'BF', 'Burkina Faso'),
(22, 'BG', 'Bulgaria'),
(23, 'BH', 'Bahrain'),
(24, 'BI', 'Burundi'),
(25, 'BJ', 'Benin'),
(26, 'BL', 'Saint Barthélemy'),
(27, 'BM', 'Bermuda'),
(28, 'BN', 'Brunei'),
(29, 'BO', 'Bolivia'),
(30, 'BQ', 'Bonaire'),
(31, 'BR', 'Brazil'),
(32, 'BS', 'Bahamas'),
(33, 'BT', 'Bhutan'),
(34, 'BV', 'Bouvet Island'),
(35, 'BW', 'Botswana'),
(36, 'BY', 'Belarus'),
(37, 'BZ', 'Belize'),
(38, 'CA', 'Canada'),
(39, 'CC', 'Cocos [Keeling] Islands'),
(40, 'CD', 'Democratic Republic of the Congo'),
(41, 'CF', 'Central African Republic'),
(42, 'CG', 'Republic of the Congo'),
(43, 'CH', 'Switzerland'),
(44, 'CI', 'Ivory Coast'),
(45, 'CK', 'Cook Islands'),
(46, 'CL', 'Chile'),
(47, 'CM', 'Cameroon'),
(48, 'CN', 'China'),
(49, 'CO', 'Colombia'),
(50, 'CR', 'Costa Rica'),
(51, 'CU', 'Cuba'),
(52, 'CV', 'Cape Verde'),
(53, 'CW', 'Curacao'),
(54, 'CX', 'Christmas Island'),
(55, 'CY', 'Cyprus'),
(56, 'CZ', 'Czechia'),
(57, 'DE', 'Germany'),
(58, 'DJ', 'Djibouti'),
(59, 'DK', 'Denmark'),
(60, 'DM', 'Dominica'),
(61, 'DO', 'Dominican Republic'),
(62, 'DZ', 'Algeria'),
(63, 'EC', 'Ecuador'),
(64, 'EE', 'Estonia'),
(65, 'EG', 'Egypt'),
(66, 'EH', 'Western Sahara'),
(67, 'ER', 'Eritrea'),
(68, 'ES', 'Spain'),
(69, 'ET', 'Ethiopia'),
(70, 'FI', 'Finland'),
(71, 'FJ', 'Fiji'),
(72, 'FK', 'Falkland Islands'),
(73, 'FM', 'Micronesia'),
(74, 'FO', 'Faroe Islands'),
(75, 'FR', 'France'),
(76, 'GA', 'Gabon'),
(77, 'GB', 'United Kingdom'),
(78, 'GD', 'Grenada'),
(79, 'GE', 'Georgia'),
(80, 'GF', 'French Guiana'),
(81, 'GG', 'Guernsey'),
(82, 'GH', 'Ghana'),
(83, 'GI', 'Gibraltar'),
(84, 'GL', 'Greenland'),
(85, 'GM', 'Gambia'),
(86, 'GN', 'Guinea'),
(87, 'GP', 'Guadeloupe'),
(88, 'GQ', 'Equatorial Guinea'),
(89, 'GR', 'Greece'),
(90, 'GS', 'South Georgia and the South Sandwich Islands'),
(91, 'GT', 'Guatemala'),
(92, 'GU', 'Guam'),
(93, 'GW', 'Guinea-Bissau'),
(94, 'GY', 'Guyana'),
(95, 'HK', 'Hong Kong'),
(96, 'HM', 'Heard Island and McDonald Islands'),
(97, 'HN', 'Honduras'),
(98, 'HR', 'Croatia'),
(99, 'HT', 'Haiti'),
(100, 'HU', 'Hungary'),
(101, 'ID', 'Indonesia'),
(102, 'IE', 'Ireland'),
(103, 'IL', 'Israel'),
(104, 'IM', 'Isle of Man'),
(105, 'IN', 'India'),
(106, 'IO', 'British Indian Ocean Territory'),
(107, 'IQ', 'Iraq'),
(108, 'IR', 'Iran'),
(109, 'IS', 'Iceland'),
(110, 'IT', 'Italy'),
(111, 'JE', 'Jersey'),
(112, 'JM', 'Jamaica'),
(113, 'JO', 'Jordan'),
(114, 'JP', 'Japan'),
(115, 'KE', 'Kenya'),
(116, 'KG', 'Kyrgyzstan'),
(117, 'KH', 'Cambodia'),
(118, 'KI', 'Kiribati'),
(119, 'KM', 'Comoros'),
(120, 'KN', 'Saint Kitts and Nevis'),
(121, 'KP', 'North Korea'),
(122, 'KR', 'South Korea'),
(123, 'KW', 'Kuwait'),
(124, 'KY', 'Cayman Islands'),
(125, 'KZ', 'Kazakhstan'),
(126, 'LA', 'Laos'),
(127, 'LB', 'Lebanon'),
(128, 'LC', 'Saint Lucia'),
(129, 'LI', 'Liechtenstein'),
(130, 'LK', 'Sri Lanka'),
(131, 'LR', 'Liberia'),
(132, 'LS', 'Lesotho'),
(133, 'LT', 'Lithuania'),
(134, 'LU', 'Luxembourg'),
(135, 'LV', 'Latvia'),
(136, 'LY', 'Libya'),
(137, 'MA', 'Morocco'),
(138, 'MC', 'Monaco'),
(139, 'MD', 'Moldova'),
(140, 'ME', 'Montenegro'),
(141, 'MF', 'Saint Martin'),
(142, 'MG', 'Madagascar'),
(143, 'MH', 'Marshall Islands'),
(144, 'MK', 'Macedonia'),
(145, 'ML', 'Mali'),
(146, 'MM', 'Myanmar [Burma]'),
(147, 'MN', 'Mongolia'),
(148, 'MO', 'Macao'),
(149, 'MP', 'Northern Mariana Islands'),
(150, 'MQ', 'Martinique'),
(151, 'MR', 'Mauritania'),
(152, 'MS', 'Montserrat'),
(153, 'MT', 'Malta'),
(154, 'MU', 'Mauritius'),
(155, 'MV', 'Maldives'),
(156, 'MW', 'Malawi'),
(157, 'MX', 'Mexico'),
(158, 'MY', 'Malaysia'),
(159, 'MZ', 'Mozambique'),
(160, 'NA', 'Namibia'),
(161, 'NC', 'New Caledonia'),
(162, 'NE', 'Niger'),
(163, 'NF', 'Norfolk Island'),
(164, 'NG', 'Nigeria'),
(165, 'NI', 'Nicaragua'),
(166, 'NL', 'Netherlands'),
(167, 'NO', 'Norway'),
(168, 'NP', 'Nepal'),
(169, 'NR', 'Nauru'),
(170, 'NU', 'Niue'),
(171, 'NZ', 'New Zealand'),
(172, 'OM', 'Oman'),
(173, 'PA', 'Panama'),
(174, 'PE', 'Peru'),
(175, 'PF', 'French Polynesia'),
(176, 'PG', 'Papua New Guinea'),
(177, 'PH', 'Philippines'),
(178, 'PK', 'Pakistan'),
(179, 'PL', 'Poland'),
(180, 'PM', 'Saint Pierre and Miquelon'),
(181, 'PN', 'Pitcairn Islands'),
(182, 'PR', 'Puerto Rico'),
(183, 'PS', 'Palestine'),
(184, 'PT', 'Portugal'),
(185, 'PW', 'Palau'),
(186, 'PY', 'Paraguay'),
(187, 'QA', 'Qatar'),
(188, 'RE', 'Réunion'),
(189, 'RO', 'Romania'),
(190, 'RS', 'Serbia'),
(191, 'RU', 'Russia'),
(192, 'RW', 'Rwanda'),
(193, 'SA', 'Saudi Arabia'),
(194, 'SB', 'Solomon Islands'),
(195, 'SC', 'Seychelles'),
(196, 'SD', 'Sudan'),
(197, 'SE', 'Sweden'),
(198, 'SG', 'Singapore'),
(199, 'SH', 'Saint Helena'),
(200, 'SI', 'Slovenia'),
(201, 'SJ', 'Svalbard and Jan Mayen'),
(202, 'SK', 'Slovakia'),
(203, 'SL', 'Sierra Leone'),
(204, 'SM', 'San Marino'),
(205, 'SN', 'Senegal'),
(206, 'SO', 'Somalia'),
(207, 'SR', 'Suriname'),
(208, 'SS', 'South Sudan'),
(209, 'ST', 'São Tomé and Príncipe'),
(210, 'SV', 'El Salvador'),
(211, 'SX', 'Sint Maarten'),
(212, 'SY', 'Syria'),
(213, 'SZ', 'Swaziland'),
(214, 'TC', 'Turks and Caicos Islands'),
(215, 'TD', 'Chad'),
(216, 'TF', 'French Southern Territories'),
(217, 'TG', 'Togo'),
(218, 'TH', 'Thailand'),
(219, 'TJ', 'Tajikistan'),
(220, 'TK', 'Tokelau'),
(221, 'TL', 'East Timor'),
(222, 'TM', 'Turkmenistan'),
(223, 'TN', 'Tunisia'),
(224, 'TO', 'Tonga'),
(225, 'TR', 'Turkey'),
(226, 'TT', 'Trinidad and Tobago'),
(227, 'TV', 'Tuvalu'),
(228, 'TW', 'Taiwan'),
(229, 'TZ', 'Tanzania'),
(230, 'UA', 'Ukraine'),
(231, 'UG', 'Uganda'),
(232, 'UM', 'U.S. Minor Outlying Islands'),
(233, 'US', 'United States'),
(234, 'UY', 'Uruguay'),
(235, 'UZ', 'Uzbekistan'),
(236, 'VA', 'Vatican City'),
(237, 'VC', 'Saint Vincent and the Grenadines'),
(238, 'VE', 'Venezuela'),
(239, 'VG', 'British Virgin Islands'),
(240, 'VI', 'U.S. Virgin Islands'),
(241, 'VN', 'Vietnam'),
(242, 'VU', 'Vanuatu'),
(243, 'WF', 'Wallis and Futuna'),
(244, 'WS', 'Samoa'),
(245, 'XK', 'Kosovo'),
(246, 'YE', 'Yemen'),
(247, 'YT', 'Mayotte'),
(248, 'ZA', 'South Africa'),
(249, 'ZM', 'Zambia'),
(250, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `sc_custom_fields`
--

CREATE TABLE IF NOT EXISTS `sc_custom_fields` (
  `cf_id` varchar(36) NOT NULL,
  `cf_module` varchar(45) NOT NULL,
  `cf_name` varchar(255) NOT NULL,
  `cf_label` varchar(255) NOT NULL,
  `cf_type` varchar(255) NOT NULL,
  `cf_data` text NOT NULL,
  `delete_status` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sc_custom_fields_data`
--

CREATE TABLE IF NOT EXISTS `sc_custom_fields_data` (
  `custom_fields_id` varchar(36) NOT NULL,
  `companies_id` varchar(36) NOT NULL,
  `data_value` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sc_custom_listview`
--

CREATE TABLE IF NOT EXISTS `sc_custom_listview` (
  `id` varchar(56) NOT NULL,
  `module_type` varchar(56) NOT NULL,
  `field_name` varchar(56) NOT NULL,
  `order_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sc_custom_listview`
--

INSERT INTO `sc_custom_listview` (`id`, `module_type`, `field_name`, `order_by`) VALUES
('0438c13e-4838-4ab8-ad00-cabc9c0af4c1', 'people', 'job_title', 4),
('0918a8e1-bd1b-4313-8331-531af9c043f8', 'proposal', 'deal_id', 4),
('0aa8da1e-a440-4914-8cfc-fa094101fc39', 'meeting', 'people_id', 4),
('0c4d548e-19aa-427a-8429-4806bd35102f', 'proposal', 'company_id', 5),
('15c7851f-02aa-44a9-a307-35d1bd919928', 'meeting', 'company_id', 3),
('1b878cbb-27e9-4d92-967d-2f0d70e7c38f', 'people', 'last_name', 2),
('2546ace7-e7a3-4484-9b60-a9191454518a', 'task', 'people_id', 3),
('3f533161-0f61-4efd-81ee-a4bcefab381e', 'deal', 'value', 2),
('5255b8d5-02e9-4861-867c-afaa62ad63e7', 'deal', 'sales_stage_id', 3),
('553367d4-98f0-4ef1-9fb9-5b8e3625f2a5', 'proposal', 'subject', 1),
('588d2340-8712-4b9f-a392-2ce731d30309', 'company', 'company_name', 1),
('60b851b8-ee49-4cb3-9472-467dbd85cf75', 'company', 'industry', 2),
('74d51976-d6b2-4abb-8ca7-9dc787d4705b', 'meeting', 'subject', 1),
('7cc53d22-ae67-4794-bd49-dd0b113e7b33', 'proposal', 'quote_date', 3),
('81551abb-a533-4c1a-bf02-2a105f97c0b6', 'task', 'company_id', 2),
('83bfcc79-c143-47c3-9645-5d531e9b8032', 'proposal', 'proposal_stage_id', 2),
('8421b169-3b11-4a16-898c-f202d2ed4a68', 'people', 'birthdate', 5),
('89342c05-413f-48ba-a86b-4665a95d2d7b', 'task', 'due_date', 5),
('90698f58-772e-477f-a2a5-3acaa98d4f8b', 'deal', 'company_id', 5),
('9176b194-0d21-4719-a236-4b99a74ebde6', 'task', 'subject', 1),
('b40774f5-6afa-4b87-a530-a5a5ff636674', 'people', 'lead_source_id', 3),
('b775a456-5aa3-4dc3-8936-64bd30923a57', 'company', 'phone_work', 5),
('b81f12e2-9183-4a48-bd7d-b535ca31108b', 'people', 'first_name', 1),
('bb9d048b-fc4a-429a-a18f-330ceb547b83', 'deal', 'name', 1),
('c53b7c0f-ac13-4cd0-88fb-0ce7b9e39652', 'note', 'date_entered', 2),
('c5c41134-a48a-4c82-8376-92c99de4e7a2', 'company', 'company_type', 3),
('caef22d2-5a10-49dd-a0ed-4da89144c889', 'company', 'created_by', 4),
('cb212e71-f8ae-47ae-9601-6f6cfe518e67', 'note', 'subject', 1),
('d0a24cdc-9001-4b1e-acb1-3a8e3f465e46', 'task', 'status_id', 4),
('fdbe9efc-3a76-4472-93a4-872194a4dd35', 'deal', 'expected_close_date', 4),
('fe5d04f2-764b-47ef-afbb-dab3458addc0', 'meeting', 'location', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sc_deals`
--

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
  `deleted` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_drop_down_options`
--

CREATE TABLE IF NOT EXISTS `sc_drop_down_options` (
`drop_down_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `related_module_id` int(10) NOT NULL,
  `related_field_name` varchar(100) NOT NULL,
  `editable` int(1) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT '0',
  `order_by` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=130 ;

--
-- Dumping data for table `sc_drop_down_options`
--

INSERT INTO `sc_drop_down_options` (`drop_down_id`, `name`, `related_module_id`, `related_field_name`, `editable`, `deleted`, `order_by`) VALUES
(0, 'Not Selected', 0, '', 0, 0, 0),
(1, 'Advertisement', 3, 'lead_source', 1, 0, 0),
(2, 'Affiliate / Partner Programs', 3, 'lead_source', 1, 0, 0),
(3, 'Cold Calling', 3, 'lead_source', 1, 0, 0),
(4, 'Customer Referral', 3, 'lead_source', 1, 0, 0),
(5, 'Direct Mail', 3, 'lead_source', 1, 0, 0),
(6, 'E-Mail Marketing', 3, 'lead_source', 1, 0, 0),
(7, 'Employee Referral', 3, 'lead_source', 1, 0, 0),
(8, 'Events / Shows', 3, 'lead_source', 1, 0, 0),
(9, 'Family', 3, 'lead_source', 1, 0, 0),
(10, 'Friend', 3, 'lead_source', 1, 0, 0),
(11, 'Inbound Call', 3, 'lead_source', 1, 0, 0),
(12, 'Online Marketing', 3, 'lead_source', 1, 0, 0),
(13, 'Other', 3, 'lead_source', 1, 0, 0),
(14, 'Partner', 3, 'lead_source', 1, 0, 0),
(15, 'Radio', 3, 'lead_source', 1, 0, 0),
(16, 'Search Engine', 3, 'lead_source', 1, 0, 0),
(17, 'Social Media', 3, 'lead_source', 1, 0, 0),
(18, 'Trade Show', 3, 'lead_source', 1, 0, 0),
(19, 'Web Site', 3, 'lead_source', 1, 0, 0),
(20, 'Word of Mouth', 3, 'lead_source', 1, 0, 0),
(21, 'New', 3, 'lead_status', 1, 0, 0),
(22, 'Attempted to Contact', 3, 'lead_status', 1, 0, 0),
(23, 'Cold', 3, 'lead_status', 1, 0, 0),
(24, 'Contact in Future', 3, 'lead_status', 1, 0, 0),
(25, 'Contacted', 3, 'lead_status', 1, 0, 0),
(26, 'Hot', 3, 'lead_status', 1, 0, 0),
(27, 'Junk Lead', 3, 'lead_status', 1, 0, 0),
(28, 'Lost Lead', 3, 'lead_status', 1, 0, 0),
(29, 'Not Contacted', 3, 'lead_status', 1, 0, 0),
(30, 'Pre Qualified', 3, 'lead_status', 1, 0, 0),
(31, 'Qualified', 3, 'lead_status', 1, 0, 0),
(32, 'Warm', 3, 'lead_status', 1, 0, 0),
(33, 'Hot', 3, 'rating', 1, 0, 0),
(34, 'Warm', 3, 'rating', 1, 0, 0),
(35, 'Cold', 3, 'rating', 1, 0, 0),
(36, 'Advertising/Public Relations', 1, 'industry', 1, 0, 0),
(37, 'Agricultural Services & Products', 1, 'industry', 1, 0, 0),
(38, 'Apparel & Accessories', 1, 'industry', 1, 1, 0),
(39, 'Architectural Services', 1, 'industry', 1, 1, 0),
(40, 'Automotive', 1, 'industry', 1, 0, 0),
(41, 'Biotechnology', 1, 'industry', 1, 0, 0),
(42, 'Broadcasting', 1, 'industry', 1, 0, 0),
(43, 'Building Materials & Equipment', 1, 'industry', 1, 0, 0),
(44, 'Call Centers', 1, 'industry', 1, 0, 0),
(45, 'Chemical & Related Manufacturing', 1, 'industry', 1, 0, 0),
(46, 'Construction', 1, 'industry', 1, 0, 0),
(47, 'Consulting', 1, 'industry', 1, 0, 0),
(48, 'Digital Media', 1, 'industry', 1, 0, 0),
(49, 'Education', 1, 'industry', 1, 0, 0),
(50, 'Electronics', 1, 'industry', 1, 0, 0),
(51, 'Energy & Natural Resources', 1, 'industry', 1, 0, 0),
(52, 'Engineering', 1, 'industry', 1, 0, 0),
(53, 'Entertainment', 1, 'industry', 1, 0, 0),
(54, 'Farming', 1, 'industry', 1, 0, 0),
(55, 'Financial Services', 1, 'industry', 1, 0, 0),
(56, 'Food & Beverage', 1, 'industry', 1, 0, 0),
(57, 'Gas & Oil', 1, 'industry', 1, 0, 0),
(58, 'General Contractors', 1, 'industry', 1, 0, 0),
(59, 'Government', 1, 'industry', 1, 0, 0),
(60, 'Health Care', 1, 'industry', 1, 0, 0),
(61, 'Home Builders', 1, 'industry', 1, 0, 0),
(62, 'Hospitality', 1, 'industry', 1, 0, 0),
(63, 'Information Technology', 1, 'industry', 1, 0, 0),
(64, 'Insurance', 1, 'industry', 1, 0, 0),
(65, 'Lawyers / Law Firms', 1, 'industry', 1, 0, 0),
(66, 'Machinery and Equipment', 1, 'industry', 1, 0, 0),
(67, 'Manufacturing', 1, 'industry', 1, 0, 0),
(68, 'Marketing', 1, 'industry', 1, 0, 0),
(69, 'Non-Profit Organizations', 1, 'industry', 1, 0, 0),
(70, 'Oil & Gas', 1, 'industry', 1, 0, 0),
(71, 'Other', 1, 'industry', 1, 0, 0),
(72, 'Publishing', 1, 'industry', 1, 0, 0),
(73, 'Real Estate', 1, 'industry', 1, 0, 0),
(74, 'Renewable Energy', 1, 'industry', 1, 0, 0),
(75, 'Retail Sales', 1, 'industry', 1, 0, 0),
(76, 'Software', 1, 'industry', 1, 0, 0),
(77, 'Sports', 1, 'industry', 1, 0, 0),
(78, 'Technology', 1, 'industry', 1, 0, 0),
(79, 'Telecommunications', 1, 'industry', 1, 0, 0),
(80, 'Transportation', 1, 'industry', 1, 0, 0),
(81, 'Prospecting', 3, 'sales_stage', 1, 0, 1),
(82, 'Needs Analysis', 3, 'sales_stage', 1, 0, 2),
(83, 'Qualification', 3, 'sales_stage', 1, 0, 3),
(84, 'Value Proposition', 3, 'sales_stage', 1, 0, 4),
(85, 'Proposal/Price Quote', 3, 'sales_stage', 1, 0, 5),
(86, 'Negotiation/Review', 3, 'sales_stage', 1, 0, 6),
(87, 'Deal Won', 3, 'sales_stage', 0, 0, 7),
(88, 'Deal Lost', 3, 'sales_stage', 0, 0, 8),
(89, 'Existing Customer', 3, 'type_of_business', 1, 0, 0),
(90, 'New Business', 3, 'type_of_business', 1, 0, 0),
(91, 'Customer', 1, 'account_type', 0, 0, 0),
(92, 'Prospect', 1, 'account_type', 0, 0, 0),
(93, 'Meeting', 4, 'event_type', 0, 0, 0),
(94, 'Webinar', 4, 'event_type', 1, 0, 0),
(95, 'Phone Call', 4, 'event_type', 0, 0, 0),
(96, 'Low', 6, 'priority_id', 1, 0, 0),
(97, 'Normal', 6, 'priority_id', 1, 0, 0),
(98, 'High', 6, 'priority_id', 1, 0, 0),
(99, 'Urgent', 6, 'priority_id', 1, 0, 0),
(100, 'Not Started', 4, 'status_id', 0, 0, 0),
(101, 'In Progress', 4, 'status_id', 0, 0, 0),
(102, 'Delayed', 4, 'status_id', 0, 0, 0),
(103, 'Completed', 4, 'status_id', 0, 0, 0),
(104, 'Yes', 2, 'do_not_call', 0, 0, 0),
(105, 'Yes', 2, 'email_opt_out', 0, 0, 0),
(106, 'No', 2, 'do_not_call', 0, 0, 0),
(107, 'No', 2, 'email_opt_out', 0, 0, 0),
(108, 'Product', 7, 'product_type', 1, 0, 1),
(109, 'Service', 7, 'product_type', 1, 0, 2),
(110, 'Draft', 8, 'proposal_stage', 1, 0, 1),
(111, 'Send', 8, 'proposal_stage', 1, 0, 2),
(112, 'Approved', 8, 'proposal_stage', 1, 0, 3),
(113, 'InActive', 9, 'user_status', 0, 0, 1),
(114, 'Active', 9, 'user_status', 0, 0, 2),
(115, 'Send Email Using External Email', 9, 'user_email_option', 0, 0, 1),
(116, 'Send Email Using TealCRM Email', 9, 'user_email_option', 0, 0, 2),
(117, 'Networking', 1, 'industry', 1, 0, 0),
(118, 'Companies', 9, 'module', 0, 0, 0),
(119, 'People', 9, 'module', 0, 0, 1),
(120, 'Deals', 9, 'module', 0, 0, 2),
(121, 'Notes', 9, 'module', 0, 0, 3),
(122, 'Proposals', 9, 'module', 0, 0, 4),
(123, 'Tasks', 9, 'module', 0, 0, 5),
(124, 'Meetings', 9, 'module', 0, 0, 6);

-- --------------------------------------------------------

--
-- Table structure for table `sc_feeds`
--

CREATE TABLE IF NOT EXISTS `sc_feeds` (
`id` bigint(20) NOT NULL,
  `company_id` varchar(36) NOT NULL,
  `date_entered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `by_uacc_id` varchar(36) NOT NULL,
  `description` longtext NOT NULL,
  `category` tinyint(3) NOT NULL COMMENT '1=companies, 2=contacts, 3=deals, 4=notes, 5=tasks, 6=meetings'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sc_google_mail`
--

CREATE TABLE IF NOT EXISTS `sc_google_mail` (
  `mail_id` varchar(56) NOT NULL,
  `subject` text,
  `message_body` text,
  `from_email` varchar(56) DEFAULT NULL,
  `from_name` varchar(56) DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `category` varchar(56) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sc_google_task`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `sc_integrations`
--

CREATE TABLE IF NOT EXISTS `sc_integrations` (
`application_id` int(11) NOT NULL,
  `application_name` varchar(100) NOT NULL,
  `api_key` varchar(500) NOT NULL,
  `api_secret` varchar(500) NOT NULL,
  `api_token` varchar(500) NOT NULL,
  `last_sync` datetime NOT NULL,
  `data` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sc_integrations`
--

INSERT INTO `sc_integrations` (`application_id`, `application_name`, `api_key`, `api_secret`, `api_token`, `last_sync`, `data`) VALUES
(1, 'mailchimp', '0', '', '', '0000-00-00 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `sc_log`
--

CREATE TABLE IF NOT EXISTS `sc_log` (
`log_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sc_meetings`
--

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
  `event_google_id` varchar(36) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_messages`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `sc_modules`
--

CREATE TABLE IF NOT EXISTS `sc_modules` (
`module_id` int(10) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `directory` varchar(100) NOT NULL,
  `db_table` varchar(50) NOT NULL,
  `db_key` varchar(150) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `sc_modules`
--

INSERT INTO `sc_modules` (`module_id`, `module_name`, `directory`, `db_table`, `db_key`) VALUES
(1, 'Companies', 'companies', 'sc_companies', 'company_id'),
(2, 'People', 'people', 'sc_people', 'people_id'),
(3, 'Deals', 'deals', 'sc_deals', 'deal_id'),
(4, 'Meetings', 'meetings', 'sc_meetings', 'meeting_id'),
(5, 'Notes', 'notes', 'sc_notes', 'note_id'),
(6, 'Tasks', 'tasks', 'sc_tasks', 'task_id'),
(7, 'Products', 'products', 'sc_products', 'product_id'),
(8, 'Proposals', 'proposals', 'sc_proposals', 'proposal_id');

-- --------------------------------------------------------

--
-- Table structure for table `sc_notes`
--

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
  `project_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_people`
--

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
  `google_contact_id` varchar(136) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_products`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `sc_product_lineitems`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `sc_projects`
--

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
  `archived` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_project_tasks`
--

CREATE TABLE IF NOT EXISTS `sc_project_tasks` (
  `sc_project_task_id` varchar(36) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `task_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


--
-- Table structure for table `sc_provinces`
--

CREATE TABLE IF NOT EXISTS `sc_provinces` (
`idProvince` int(2) NOT NULL,
  `provinceName` varchar(32) NOT NULL COMMENT 'State name with first letter capital',
  `provinceCode` varchar(8) DEFAULT NULL COMMENT 'Optional state abbreviation (US is 2 capital letters)',
  `countryCode` varchar(2) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=247 ;

--
-- Dumping data for table `sc_provinces`
--

INSERT INTO `sc_provinces` (`idProvince`, `provinceName`, `provinceCode`, `countryCode`) VALUES
(183, 'Alaska', 'AK', 'US'),
(184, 'Alabama', 'AL', 'US'),
(185, 'Arkansas', 'AR', 'US'),
(186, 'Arizona', 'AZ', 'US'),
(187, 'California', 'CA', 'US'),
(188, 'Colorado', 'CO', 'US'),
(189, 'Connecticut', 'CT', 'US'),
(190, 'Delaware', 'DE', 'US'),
(191, 'District of Columbia', 'DC', 'US'),
(192, 'Florida', 'FL', 'US'),
(193, 'Georgia', 'GA', 'US'),
(194, 'Hawaii', 'HI', 'US'),
(195, 'Iowa', 'IA', 'US'),
(196, 'Idaho', 'ID', 'US'),
(197, 'Illinois', 'IL', 'US'),
(198, 'Indiana', 'IN', 'US'),
(199, 'Kansas', 'KS', 'US'),
(200, 'Kentucky', 'KY', 'US'),
(201, 'Louisiana', 'LA', 'US'),
(202, 'Massachusetts', 'MA', 'US'),
(203, 'Maryland', 'MD', 'US'),
(204, 'Maine', 'ME', 'US'),
(205, 'Michigan', 'MI', 'US'),
(206, 'Minnesota', 'MN', 'US'),
(207, 'Missouri', 'MO', 'US'),
(208, 'Mississippi', 'MS', 'US'),
(209, 'Montana', 'MT', 'US'),
(210, 'North Carolina', 'NC', 'US'),
(211, 'North Dakota', 'ND', 'US'),
(212, 'Nebraska', 'NE', 'US'),
(213, 'New Hampshire', 'NH', 'US'),
(214, 'New Jersey', 'NJ', 'US'),
(215, 'New Mexico', 'NM', 'US'),
(216, 'Nevada', 'NV', 'US'),
(217, 'New York', 'NY', 'US'),
(218, 'Ohio', 'OH', 'US'),
(219, 'Oklahoma', 'OK', 'US'),
(220, 'Oregon', 'OR', 'US'),
(221, 'Pennsylvania', 'PA', 'US'),
(222, 'Rhode Island', 'RI', 'US'),
(223, 'South Carolina', 'SC', 'US'),
(224, 'South Dakota', 'SD', 'US'),
(225, 'Tennessee', 'TN', 'US'),
(226, 'Texas', 'TX', 'US'),
(227, 'Utah', 'UT', 'US'),
(228, 'Virginia', 'VA', 'US'),
(229, 'Vermont', 'VT', 'US'),
(230, 'Washington', 'WA', 'US'),
(231, 'Wisconsin', 'WI', 'US'),
(232, 'West Virginia', 'WV', 'US'),
(233, 'Wyoming', 'WY', 'US'),
(234, 'Alberta', 'AB', 'CA'),
(235, 'British Columbia', 'BC', 'CA'),
(236, 'Manitoba', 'MB', 'CA'),
(237, 'New Brunswick', 'NB', 'CA'),
(238, 'Newfoundland & Labrador', 'NL', 'CA'),
(239, 'Nova Scotia', 'NS', 'CA'),
(240, 'Northwest Territories', 'NT', 'CA'),
(241, 'Nunavut', 'NU', 'CA'),
(242, 'Ontario', 'ON', 'CA'),
(243, 'Prince Edward Island', 'PE', 'CA'),
(244, 'Quebec', 'QC', 'CA'),
(245, 'Saskatchewan', 'SK', 'CA'),
(246, 'Yukon', 'YT', 'CA');

-- --------------------------------------------------------

--
-- Table structure for table `sc_record_views`
--

CREATE TABLE IF NOT EXISTS `sc_record_views` (
  `record_view_id` varchar(36) NOT NULL,
  `module_id` int(11) NOT NULL,
  `record_id` varchar(36) NOT NULL,
  `view_time_stamp` datetime NOT NULL,
  `description` varchar(200) NOT NULL,
  `user_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sc_saved_search`
--

CREATE TABLE IF NOT EXISTS `sc_saved_search` (
`search_id` int(11) NOT NULL,
  `date_entered` datetime NOT NULL,
  `created_by` varchar(36) NOT NULL,
  `title` varchar(36) NOT NULL,
  `module` varchar(36) NOT NULL,
  `search_string` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sc_sessions`
--

CREATE TABLE IF NOT EXISTS `sc_sessions` (
        `id` varchar(128) NOT NULL,
        `ip_address` varchar(45) NOT NULL,
        `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
        `data` blob NOT NULL,
        KEY `ci_sessions_timestamp` (`timestamp`)
);

-- --------------------------------------------------------

--
-- Table structure for table `sc_settings`
--

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
  `billing_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sc_settings`
--

INSERT INTO `sc_settings` (`id`, `site_id`, `business_name`, `company_type`, `address1`, `address2`, `city`, `province`, `postal_code`, `country`, `date_modified`, `timezone`) VALUES
(1, 'tealcrm', '', 'corporation', '123 Main Street', '', '', '', '', '', '', 'America/Vancouver');

-- --------------------------------------------------------

--
-- Table structure for table `sc_tasks`
--

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
  `deleted` tinyint(3) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_templates`
--

CREATE TABLE IF NOT EXISTS `sc_templates` (
  `template_id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_entered` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `modified_user_id` varchar(36) NOT NULL,
  `created_by` varchar(36) NOT NULL,
  `html_body` text NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_accounts`
--

CREATE TABLE IF NOT EXISTS `sc_user_accounts` (
`uacc_id` int(11) unsigned NOT NULL,
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
  `uacc_date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `sc_user_accounts`
--

INSERT INTO `sc_user_accounts` (`uacc_id`, `uacc_uid`, `uacc_group_fk`, `uacc_email`, `uacc_username`, `uacc_password`, `uacc_ip_address`, `uacc_salt`, `uacc_activation_token`, `uacc_forgotten_password_token`, `uacc_forgotten_password_expire`, `uacc_update_email_token`, `uacc_update_email`, `uacc_active`, `uacc_suspend`, `uacc_fail_login_attempts`, `uacc_fail_login_ip_address`, `uacc_date_fail_login_ban`, `uacc_date_last_login`, `uacc_date_added`)
VALUES
	(1,'8bae741f-c5c9-4851-bc5b-c2e074d4a184',3,'yourname@yourname.com','admin','$2a$08$hpMvXy8on8yQxwQf6ihHA.bWGQYq./Ms0wZvuzeD/iDqhLlYRc8v6','::1','XKVT29q2Jr','','2e6c74b2c3c9455763eb246be39af4ef71a4b83a','2015-05-19 11:06:21','','',1,0,0,'','0000-00-00 00:00:00','2018-07-25 05:01:33','2011-01-01 00:00:00');


-- --------------------------------------------------------

--
-- Table structure for table `sc_user_address`
--

CREATE TABLE IF NOT EXISTS `sc_user_address` (
`uadd_id` int(11) NOT NULL,
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
  `uadd_country` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sc_user_address`
--

INSERT INTO `sc_user_address` (`uadd_id`, `uadd_uacc_fk`, `uadd_alias`, `uadd_recipient`, `uadd_phone`, `uadd_company`, `uadd_address_01`, `uadd_address_02`, `uadd_city`, `uadd_county`, `uadd_post_code`, `uadd_country`) VALUES
(1, 4, 'Home', 'Joe Public', '0123456789', '', '123', '', 'My City', 'My County', 'My Post Code', 'My Country'),
(2, 4, 'Work', 'Joe Public', '0123456789', 'Flexi', '321', '', 'My Work City', 'My Work County', 'My Work Post Code', 'My Work Country');

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_groups`
--

CREATE TABLE IF NOT EXISTS `sc_user_groups` (
`ugrp_id` smallint(5) NOT NULL,
  `ugrp_name` varchar(20) NOT NULL DEFAULT '',
  `ugrp_desc` varchar(100) NOT NULL DEFAULT '',
  `ugrp_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sc_user_groups`
--

INSERT INTO `sc_user_groups` (`ugrp_id`, `ugrp_name`, `ugrp_desc`, `ugrp_admin`) VALUES
(1, 'Regular User', '', 0),
(3, 'Master Admin', 'Master Admin : has full admin access rights.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_login_sessions`
--

CREATE TABLE IF NOT EXISTS `sc_user_login_sessions` (
  `usess_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `usess_series` varchar(40) NOT NULL DEFAULT '',
  `usess_token` varchar(40) NOT NULL DEFAULT '',
  `usess_login_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_privileges`
--

CREATE TABLE IF NOT EXISTS `sc_user_privileges` (
`upriv_id` smallint(5) NOT NULL,
  `upriv_name` varchar(20) NOT NULL DEFAULT '',
  `upriv_desc` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `sc_user_privileges`
--

INSERT INTO `sc_user_privileges` (`upriv_id`, `upriv_name`, `upriv_desc`) VALUES
(1, 'View Users', 'User can view user company details.'),
(2, 'View User Groups', 'User can view user groups.'),
(3, 'View Privileges', 'User can view privileges.'),
(4, 'Insert User Groups', 'User can insert new user groups.'),
(5, 'Insert Privileges', 'User can insert privileges.'),
(6, 'Update Users', 'User can update user company details.'),
(7, 'Update User Groups', 'User can update user groups.'),
(8, 'Update Privileges', 'User can update user privileges.'),
(9, 'Delete Users', 'User can delete user companies.'),
(10, 'Delete User Groups', 'User can delete user groups.'),
(11, 'Delete Privileges', 'User can delete user privileges.');

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_privilege_groups`
--

CREATE TABLE IF NOT EXISTS `sc_user_privilege_groups` (
`upriv_groups_id` smallint(5) unsigned NOT NULL,
  `upriv_groups_ugrp_fk` smallint(5) unsigned NOT NULL DEFAULT '0',
  `upriv_groups_upriv_fk` smallint(5) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `sc_user_privilege_groups`
--

INSERT INTO `sc_user_privilege_groups` (`upriv_groups_id`, `upriv_groups_ugrp_fk`, `upriv_groups_upriv_fk`) VALUES
(1, 3, 1),
(3, 3, 3),
(4, 3, 4),
(5, 3, 5),
(6, 3, 6),
(7, 3, 7),
(8, 3, 8),
(9, 3, 9),
(10, 3, 10),
(11, 3, 11),
(12, 2, 2),
(13, 2, 4),
(14, 2, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_privilege_users`
--

CREATE TABLE IF NOT EXISTS `sc_user_privilege_users` (
`upriv_users_id` smallint(5) NOT NULL,
  `upriv_users_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `upriv_users_upriv_fk` smallint(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `sc_user_privilege_users`
--

INSERT INTO `sc_user_privilege_users` (`upriv_users_id`, `upriv_users_uacc_fk`, `upriv_users_upriv_fk`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 2, 1),
(13, 2, 2),
(14, 2, 3),
(15, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `sc_user_profiles`
--

CREATE TABLE IF NOT EXISTS `sc_user_profiles` (
`upro_id` int(11) NOT NULL,
  `upro_uacc_fk` int(11) NOT NULL DEFAULT '0',
  `upro_company` varchar(50) NOT NULL DEFAULT '',
  `upro_first_name` varchar(50) NOT NULL DEFAULT '',
  `upro_last_name` varchar(50) NOT NULL DEFAULT '',
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
  `imap_active` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `sc_user_profiles`
--

INSERT INTO `sc_user_profiles` (`upro_id`, `upro_uacc_fk`, `upro_company`, `upro_first_name`, `upro_last_name`, `upro_phone`, `upro_newsletter`, `upro_filename_mimetype`, `upro_filename_original`, `upro_google_email`, `upro_google_name`, `upro_google_access_token`, `upro_google_calendar_nextSyncToken`, `email_sending_option`, `username`, `password`, `imap_address`, `ssl_value`, `mail_server_port`, `imap_active`) VALUES
(1, 1, '', '', '', '', 0, 'image/png', 'default.png', '', '', '', '', 1, '', '', '', '', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sc_companies`
--
ALTER TABLE `sc_companies`
 ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `sc_countries`
--
ALTER TABLE `sc_countries`
 ADD PRIMARY KEY (`idCountry`);

--
-- Indexes for table `sc_custom_fields`
--
ALTER TABLE `sc_custom_fields`
 ADD PRIMARY KEY (`cf_id`), ADD UNIQUE KEY `cf_id` (`cf_id`);

--
-- Indexes for table `sc_custom_listview`
--
ALTER TABLE `sc_custom_listview`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sc_deals`
--
ALTER TABLE `sc_deals`
 ADD PRIMARY KEY (`deal_id`);

--
-- Indexes for table `sc_drop_down_options`
--
ALTER TABLE `sc_drop_down_options`
 ADD PRIMARY KEY (`drop_down_id`);

--
-- Indexes for table `sc_feeds`
--
ALTER TABLE `sc_feeds`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sc_integrations`
--
ALTER TABLE `sc_integrations`
 ADD PRIMARY KEY (`application_id`), ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `sc_log`
--
ALTER TABLE `sc_log`
 ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `sc_meetings`
--
ALTER TABLE `sc_meetings`
 ADD PRIMARY KEY (`meeting_id`);

--
-- Indexes for table `sc_modules`
--
ALTER TABLE `sc_modules`
 ADD PRIMARY KEY (`module_id`);

--
-- Indexes for table `sc_notes`
--
ALTER TABLE `sc_notes`
 ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `sc_people`
--
ALTER TABLE `sc_people`
 ADD PRIMARY KEY (`people_id`);

--
-- Indexes for table `sc_projects`
--
ALTER TABLE `sc_projects`
 ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `sc_project_tasks`
--
ALTER TABLE `sc_project_tasks`
 ADD PRIMARY KEY (`sc_project_task_id`);



--
-- Indexes for table `sc_provinces`
--
ALTER TABLE `sc_provinces`
 ADD PRIMARY KEY (`idProvince`);

--
-- Indexes for table `sc_record_views`
--
ALTER TABLE `sc_record_views`
 ADD PRIMARY KEY (`record_view_id`), ADD UNIQUE KEY `record_view_id` (`record_view_id`);

--
-- Indexes for table `sc_saved_search`
--
ALTER TABLE `sc_saved_search`
 ADD PRIMARY KEY (`search_id`), ADD UNIQUE KEY `search_id` (`search_id`);

--
-- Indexes for table `sc_sessions`
--
ALTER TABLE `sc_sessions`
 ADD PRIMARY KEY (`session_id`), ADD KEY `last_activity` (`last_activity`);

--
-- Indexes for table `sc_settings`
--
ALTER TABLE `sc_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sc_tasks`
--
ALTER TABLE `sc_tasks`
 ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `sc_templates`
--
ALTER TABLE `sc_templates`
 ADD PRIMARY KEY (`template_id`);

--
-- Indexes for table `sc_user_accounts`
--
ALTER TABLE `sc_user_accounts`
 ADD PRIMARY KEY (`uacc_id`), ADD UNIQUE KEY `uacc_id` (`uacc_id`), ADD KEY `uacc_group_fk` (`uacc_group_fk`), ADD KEY `uacc_email` (`uacc_email`), ADD KEY `uacc_username` (`uacc_username`), ADD KEY `uacc_fail_login_ip_address` (`uacc_fail_login_ip_address`);

--
-- Indexes for table `sc_user_address`
--
ALTER TABLE `sc_user_address`
 ADD PRIMARY KEY (`uadd_id`), ADD UNIQUE KEY `uadd_id` (`uadd_id`), ADD KEY `uadd_uacc_fk` (`uadd_uacc_fk`);

--
-- Indexes for table `sc_user_groups`
--
ALTER TABLE `sc_user_groups`
 ADD PRIMARY KEY (`ugrp_id`), ADD UNIQUE KEY `ugrp_id` (`ugrp_id`) USING BTREE;

--
-- Indexes for table `sc_user_login_sessions`
--
ALTER TABLE `sc_user_login_sessions`
 ADD PRIMARY KEY (`usess_token`), ADD UNIQUE KEY `usess_token` (`usess_token`);

--
-- Indexes for table `sc_user_privileges`
--
ALTER TABLE `sc_user_privileges`
 ADD PRIMARY KEY (`upriv_id`), ADD UNIQUE KEY `upriv_id` (`upriv_id`) USING BTREE;

--
-- Indexes for table `sc_user_privilege_groups`
--
ALTER TABLE `sc_user_privilege_groups`
 ADD PRIMARY KEY (`upriv_groups_id`), ADD UNIQUE KEY `upriv_groups_id` (`upriv_groups_id`) USING BTREE, ADD KEY `upriv_groups_ugrp_fk` (`upriv_groups_ugrp_fk`), ADD KEY `upriv_groups_upriv_fk` (`upriv_groups_upriv_fk`);

--
-- Indexes for table `sc_user_privilege_users`
--
ALTER TABLE `sc_user_privilege_users`
 ADD PRIMARY KEY (`upriv_users_id`), ADD UNIQUE KEY `upriv_users_id` (`upriv_users_id`) USING BTREE, ADD KEY `upriv_users_uacc_fk` (`upriv_users_uacc_fk`), ADD KEY `upriv_users_upriv_fk` (`upriv_users_upriv_fk`);

--
-- Indexes for table `sc_user_profiles`
--
ALTER TABLE `sc_user_profiles`
 ADD PRIMARY KEY (`upro_id`), ADD UNIQUE KEY `upro_id` (`upro_id`), ADD KEY `upro_uacc_fk` (`upro_uacc_fk`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sc_countries`
--
ALTER TABLE `sc_countries`
MODIFY `idCountry` int(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=251;
--
-- AUTO_INCREMENT for table `sc_drop_down_options`
--
ALTER TABLE `sc_drop_down_options`
MODIFY `drop_down_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=130;
--
-- AUTO_INCREMENT for table `sc_feeds`
--
ALTER TABLE `sc_feeds`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sc_integrations`
--
ALTER TABLE `sc_integrations`
MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `sc_log`
--
ALTER TABLE `sc_log`
MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sc_modules`
--
ALTER TABLE `sc_modules`
MODIFY `module_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `sc_provinces`
--
ALTER TABLE `sc_provinces`
MODIFY `idProvince` int(2) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=247;
--
-- AUTO_INCREMENT for table `sc_saved_search`
--
ALTER TABLE `sc_saved_search`
MODIFY `search_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sc_user_accounts`
--
ALTER TABLE `sc_user_accounts`
MODIFY `uacc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `sc_user_address`
--
ALTER TABLE `sc_user_address`
MODIFY `uadd_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sc_user_groups`
--
ALTER TABLE `sc_user_groups`
MODIFY `ugrp_id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sc_user_privileges`
--
ALTER TABLE `sc_user_privileges`
MODIFY `upriv_id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sc_user_privilege_groups`
--
ALTER TABLE `sc_user_privilege_groups`
MODIFY `upriv_groups_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `sc_user_privilege_users`
--
ALTER TABLE `sc_user_privilege_users`
MODIFY `upriv_users_id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `sc_user_profiles`
--
ALTER TABLE `sc_user_profiles`
MODIFY `upro_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
