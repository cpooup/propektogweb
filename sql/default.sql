-- phpMyAdmin SQL Dump
-- version 4.1.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2015 at 11:13 AM
-- Server version: 5.0.51b-community-nt-log
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `propektogweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `email` varchar(256) NOT NULL,
  `priority` enum('0','1') NOT NULL,
  `comment` text NOT NULL,
  `approveby` varchar(150) NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `updateby` int(11) NOT NULL,
  `site_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers_columns`
--

CREATE TABLE IF NOT EXISTS `customers_columns` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) NOT NULL,
  `status` enum('0','1') NOT NULL default '0',
  `site_id` int(11) NOT NULL,
  `ordering` tinyint(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15;

--
-- Dumping data for table `customers_columns`
--

INSERT INTO `customers_columns` (`id`, `name`, `status`, `site_id`, `ordering`) VALUES
(1, 'id', '0', 1, 1),
(2, 'sitename', '1', 1, 2),
(3, 'name', '1', 1, 3),
(4, 'email', '1', 1, 4),
(5, 'data_entry', '1', 1, 5),
(6, 'posting', '1', 1, 6),
(7, 'choice', '0', 1, 7),
(8, 'priority', '0', 1, 8),
(9, 'comment', '1', 1, 9),
(10, 'approveby', '0', 1, 10),
(11, 'deleted', '1', 1, 11),
(13, 'updated', '1', 1, 12),
(14, 'updateby', '1', 1, 13);

-- --------------------------------------------------------

--
-- Table structure for table `data_entry`
--

CREATE TABLE IF NOT EXISTS `data_entry` (
  `data_entry_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `data_entry_monday` enum('0','1') NOT NULL default '0',
  `data_entry_tuesday` enum('0','1') NOT NULL default '0',
  `data_entry_wednesday` enum('0','1') NOT NULL default '0',
  `data_entry_thursday` enum('0','1') NOT NULL default '0',
  `data_entry_friday` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`data_entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE IF NOT EXISTS `menus` (
  `menu_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY  (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `name`) VALUES
(1, 'Admin Main Menu'),
(2, 'Main Menu');

-- --------------------------------------------------------

--
-- Table structure for table `navs`
--

CREATE TABLE IF NOT EXISTS `navs` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `menu_id` int(11) unsigned NOT NULL,
  `parent_id` int(11) unsigned NOT NULL default '0',
  `title` varchar(64) NOT NULL,
  `url` varchar(128) NOT NULL,
  `type` enum('public','private','admin') NOT NULL default 'public',
  `sort_order` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`),
  KEY `parent_id` (`parent_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `navs`
--

INSERT INTO `navs` (`id`, `menu_id`, `parent_id`, `title`, `url`, `type`, `sort_order`) VALUES
(3, 1, 0, 'Home', '/admin/customers', 'admin', 1),
(4, 1, 0, 'Users', '/admin/users', 'admin', 100),
(5, 1, 0, 'Companies', '/admin/company', 'admin', 101);

-- --------------------------------------------------------

--
-- Table structure for table `posting`
--

CREATE TABLE IF NOT EXISTS `posting` (
  `posting_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `posting_monday` enum('0','1') NOT NULL default '0',
  `posting_tuesday` enum('0','1') NOT NULL default '0',
  `posting_wednesday` enum('0','1') NOT NULL default '0',
  `posting_thursday` enum('0','1') NOT NULL default '0',
  `posting_friday` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`posting_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL default '0',
  `ip_address` varchar(45) NOT NULL default '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL default '0',
  `user_data` text NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `name` varchar(128) NOT NULL,
  `input_type` enum('input','textarea','radio','dropdown','timezones') NOT NULL,
  `options` text COMMENT 'Use for radio and dropdown: key|value on each line',
  `is_numeric` enum('0','1') NOT NULL default '0' COMMENT 'forces numeric keypad on mobile devices',
  `show_editor` enum('0','1') NOT NULL default '0',
  `input_size` enum('large','medium','small') default NULL,
  `help_text` varchar(256) default NULL,
  `validation` varchar(128) NOT NULL,
  `sort_order` tinyint(3) unsigned NOT NULL,
  `label` varchar(128) NOT NULL,
  `value` text,
  `last_update` datetime default NULL,
  `updated_by` int(11) unsigned default NULL,
  KEY `name` (`name`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `input_type`, `options`, `is_numeric`, `show_editor`, `input_size`, `help_text`, `validation`, `sort_order`, `label`, `value`, `last_update`, `updated_by`) VALUES
('site_name', 'input', NULL, '0', '0', 'large', NULL, 'trim|xss_clean|required|min_length[3]|max_length[128]', 10, 'Site Name', 'PWC', '2015-01-30 06:18:59', 1),
('per_page_limit', 'dropdown', '10|10\r\n25|25\r\n50|50\r\n75|75\r\n100|100', '1', '0', 'small', NULL, 'trim|xss_clean|required|numeric', 50, 'Items Per Page', '10', '2015-01-30 06:18:59', 1),
('meta_keywords', 'input', NULL, '0', '0', 'large', 'Comma-seperated list of site keywords', 'trim|xss_clean', 20, 'Meta Keywords', 'these, are, keywords', '2015-01-30 06:18:59', 1),
('meta_description', 'textarea', NULL, '0', '0', 'large', 'Short description describing your site.', 'trim|xss_clean', 30, 'Meta Description', 'This is the site description.', '2015-01-30 06:18:59', 1),
('site_email', 'input', NULL, '0', '0', 'medium', 'Email address all emails will be sent from.', 'trim|xss_clean|required|valid_email', 40, 'Site Email', 'youremail@yourdomain.com', '2015-01-30 06:18:59', 1),
('timezones', 'timezones', NULL, '0', '0', 'medium', NULL, 'trim|xss_clean|required', 60, 'Timezone', 'UTC', '2015-01-30 06:18:59', 1),
('welcome_message', 'textarea', NULL, '0', '1', 'large', 'Message to display on home page.', 'trim|xss_clean', 70, 'Welcome Message', '<p>The page you are looking at is being generated <em>dynamically</em>. <strong>This text is editable in the admin settings.</strong></p>', '2015-01-30 06:18:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sitename`
--

CREATE TABLE IF NOT EXISTS `sitename` (
  `id` int(11) NOT NULL auto_increment,
  `sitename` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

--
-- Dumping data for table `sitename`
--

INSERT INTO `sitename` (`id`, `sitename`) VALUES
(1, 'propektogweb'),

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL,
  `password` char(128) NOT NULL,
  `salt` char(128) NOT NULL,
  `viewpass` varchar(128) NOT NULL,
  `is_admin` enum('0','1') NOT NULL default '0',
  `deleted` enum('0','1') NOT NULL default '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `site_id` int(11) NOT NULL,
  `updateby` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `viewpass`, `is_admin`, `deleted`, `created`, `updated`, `site_id`, `updateby`) VALUES
(1, 'admin', 'ce516f215aa468c376736c9013e8b663f7b3c06226a87739bc6b32882f9278349a721ea725a156eecf9e3c1868904a77e4d42c783e0287a0909a8bbb97e1525f', '66cb0ab1d9efe250b46e28ecb45eb33b3609f1efda37547409a113a2b84c3f94b6a0e738acc391e2dfa718676aa55adead05fcb12d2e32aee379e190511a3252', 'admin', '1', '0', '2013-01-01 00:00:00', '2013-01-01 00:00:00', 1, 0),

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
