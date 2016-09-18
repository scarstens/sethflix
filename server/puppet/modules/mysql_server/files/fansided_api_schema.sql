# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 52.7.228.61 (MySQL 5.5.49-0ubuntu0.14.04.1-log)
# Database: fansided_api
# Generation Time: 2016-06-02 19:46:39 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table api_keys
# ------------------------------------------------------------

CREATE TABLE `api_keys` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `api_key` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `api_token` varchar(255) CHARACTER SET latin1 NOT NULL,
  `application` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'All',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `rate_limit` int(11) unsigned NOT NULL DEFAULT '0',
  `rate_count` int(11) unsigned NOT NULL DEFAULT '0',
  `rate_start_time` date DEFAULT NULL,
  `rate_lifetime` bigint(22) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `setting_name` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table app_settings
# ------------------------------------------------------------

CREATE TABLE `app_settings` (
  `app_id` tinyint(6) unsigned NOT NULL,
  `sort_key` int(11) unsigned NOT NULL DEFAULT '1',
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `public` binary(1) NOT NULL DEFAULT '1',
  `setting_group` varchar(255) CHARACTER SET utf8 NOT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `setting_value` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `setting_icon` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `setting_icon_alt` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `autoload` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `nav_group_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `setting_name` (`setting_name`),
  KEY `app_id` (`app_id`),
  KEY `public` (`public`),
  KEY `setting_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table invoice_transactions
# ------------------------------------------------------------

CREATE TABLE `invoice_transactions` (
  `invoice_id` varchar(255) CHARACTER SET latin1 NOT NULL,
  `transaction_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `invoice_type` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT 'Expert',
  `statement_month` decimal(2,0) unsigned zerofill NOT NULL DEFAULT '01',
  `statement_year` smallint(5) unsigned NOT NULL DEFAULT '2015',
  `vendor_id` decimal(10,0) unsigned zerofill NOT NULL,
  `site_id` int(11) unsigned NOT NULL DEFAULT '229',
  `user_id` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `user_full_name` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `transaction_type` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'payperview',
  `user_publish_count` int(11) NOT NULL DEFAULT '0',
  `site_publish_count` int(11) NOT NULL DEFAULT '0',
  `gross_views` int(11) DEFAULT '0',
  `prepaid_views` int(11) unsigned NOT NULL DEFAULT '0',
  `page_views` int(11) unsigned NOT NULL DEFAULT '0',
  `eligible` binary(1) NOT NULL DEFAULT '1',
  `pay_rate` decimal(4,2) unsigned NOT NULL DEFAULT '1.50',
  `pay_split` int(2) unsigned NOT NULL DEFAULT '1',
  `pay_prorate` decimal(15,2) NOT NULL DEFAULT '0.00',
  `pay_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET latin1 NOT NULL,
  `invoice_group` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `status` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `confirmation_number` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `confirmation_date` datetime DEFAULT NULL,
  `invoice_subcategory` varchar(5) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'used to re-issue invoices that can''t have duplicate invoice IDs',
  PRIMARY KEY (`transaction_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `author_id` (`user_id`),
  KEY `vender_id` (`vendor_id`),
  KEY `site_id` (`site_id`),
  KEY `eligible` (`eligible`),
  KEY `user_name` (`user_full_name`),
  KEY `statement_month` (`statement_month`,`statement_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table newsletters
# ------------------------------------------------------------

CREATE TABLE `newsletters` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `from_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `domain` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `default_tag` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `site_url` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `from` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `confirm_subject` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `logo_src` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `logo_alt` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_param` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`(191)),
  KEY `domain` (`domain`(191)),
  KEY `site_url` (`site_url`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table partner_feeds
# ------------------------------------------------------------

CREATE TABLE `partner_feeds` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sites_id` int(10) unsigned NOT NULL,
  `topic_id` int(11) unsigned DEFAULT NULL,
  `partner` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `feed_url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'http://',
  `tags` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `topic_image_dir` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `team_logo` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `feed_type` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'rss',
  `last_sync` datetime DEFAULT NULL,
  `notes` tinytext CHARACTER SET utf8,
  `active` binary(1) NOT NULL DEFAULT '0',
  `groups` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `feed_frequency` int(11) NOT NULL COMMENT 'in minutes check for new content',
  `feed_item_expiry` int(11) NOT NULL COMMENT 'when the videos will stop working',
  PRIMARY KEY (`id`),
  KEY `tags` (`tags`(255)),
  KEY `partner` (`partner`),
  KEY `active` (`active`),
  KEY `sites_id` (`sites_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table partner_posts
# ------------------------------------------------------------

CREATE TABLE `partner_posts` (
  `post_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `post_status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `post_tags` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `network_id` bigint(20) unsigned NOT NULL,
  `sites_id` bigint(20) unsigned NOT NULL COMMENT 'site->id',
  `feed_id` bigint(20) unsigned NOT NULL COMMENT 'parnter_feeds->id',
  `app_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL COMMENT 'sites->site_id',
  `post_id` bigint(20) unsigned NOT NULL,
  `permalink` varchar(2048) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL,
  `post_date_utc` datetime NOT NULL,
  `post_content` longtext CHARACTER SET utf8 NOT NULL,
  `post_content_mobile` longtext CHARACTER SET utf8 NOT NULL,
  `post_excerpt` mediumtext CHARACTER SET utf8 NOT NULL,
  `post_author` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_author_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image_mid` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_med` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_large` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `share_count` int(11) unsigned NOT NULL DEFAULT '0',
  `read_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_key`),
  KEY `permalink` (`permalink`(255)),
  KEY `network_id` (`network_id`),
  KEY `blog_id` (`blog_id`),
  KEY `post_id` (`post_id`),
  KEY `post_tags` (`post_tags`(255)),
  KEY `post_status` (`post_status`),
  KEY `guid` (`guid`),
  KEY `post_date_utc` (`post_date_utc`),
  KEY `partner_id` (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table partner_posts_recent
# ------------------------------------------------------------

CREATE TABLE `partner_posts_recent` (
  `post_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `post_status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `post_tags` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `network_id` bigint(20) unsigned NOT NULL,
  `sites_id` bigint(20) unsigned NOT NULL,
  `feed_id` bigint(20) unsigned NOT NULL,
  `app_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `permalink` varchar(2048) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL,
  `post_date_utc` datetime NOT NULL,
  `post_excerpt` mediumtext CHARACTER SET utf8 NOT NULL,
  `post_author` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_author_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image_mid` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_med` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_large` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `share_count` int(11) unsigned NOT NULL DEFAULT '0',
  `read_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_key`),
  KEY `permalink` (`permalink`(255)),
  KEY `network_id` (`network_id`),
  KEY `blog_id` (`blog_id`),
  KEY `post_id` (`post_id`),
  KEY `post_tags` (`post_tags`(255)),
  KEY `post_status` (`post_status`),
  KEY `guid` (`guid`),
  KEY `post_date_utc` (`post_date_utc`),
  KEY `partner_id` (`feed_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts
# ------------------------------------------------------------

CREATE TABLE `posts` (
  `post_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `post_tags` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `sites_id` bigint(20) unsigned NOT NULL,
  `network_id` bigint(20) unsigned NOT NULL,
  `app_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL COMMENT 'sites->site_id',
  `post_id` bigint(20) unsigned NOT NULL,
  `permalink` varchar(2048) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_title` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL,
  `post_date_utc` datetime NOT NULL,
  `post_content` longtext CHARACTER SET utf8mb4 NOT NULL,
  `post_content_mobile` longtext CHARACTER SET utf8 NOT NULL,
  `post_excerpt` mediumtext CHARACTER SET utf8 NOT NULL,
  `post_author` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_author_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image_mid` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_med` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_large` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `share_count` int(11) unsigned NOT NULL DEFAULT '0',
  `read_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_key`),
  KEY `permalink` (`permalink`(191)),
  KEY `network_id` (`network_id`),
  KEY `blog_id` (`blog_id`),
  KEY `post_id` (`post_id`),
  KEY `post_tags` (`post_tags`(255)),
  KEY `post_status` (`post_status`),
  KEY `guid` (`guid`),
  KEY `post_date_utc` (`post_date_utc`),
  KEY `sites_id` (`sites_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_recent
# ------------------------------------------------------------

CREATE TABLE `posts_recent` (
  `post_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_status` varchar(255) CHARACTER SET utf8 NOT NULL,
  `post_tags` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `sites_id` bigint(20) unsigned NOT NULL,
  `network_id` bigint(20) unsigned NOT NULL,
  `app_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `permalink` varchar(2048) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_title` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL,
  `post_date_utc` datetime NOT NULL,
  `post_excerpt` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_author` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_author_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image` varchar(1024) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `post_image_mid` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_med` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `post_image_large` varchar(1024) CHARACTER SET utf8 NOT NULL,
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `share_count` int(11) unsigned NOT NULL DEFAULT '0',
  `read_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_key`),
  KEY `permalink` (`permalink`(255)),
  KEY `network_id` (`network_id`),
  KEY `blog_id` (`blog_id`),
  KEY `post_id` (`post_id`),
  KEY `post_tags` (`post_tags`(255)),
  KEY `post_status` (`post_status`),
  KEY `guid` (`guid`),
  KEY `post_date_utc` (`post_date_utc`),
  KEY `sites_id` (`sites_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table posts_yesterday
# ------------------------------------------------------------

CREATE TABLE `posts_yesterday` (
  `post_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_status` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_tags` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `sites_id` bigint(20) unsigned NOT NULL,
  `network_id` bigint(20) unsigned NOT NULL,
  `app_id` bigint(20) unsigned NOT NULL,
  `blog_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `permalink` varchar(2048) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_title` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL,
  `post_date_utc` datetime NOT NULL,
  `post_excerpt` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_author` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_author_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_image` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_image_mid` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_image_med` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_image_large` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `share_count` int(11) unsigned NOT NULL DEFAULT '0',
  `read_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_key`),
  KEY `permalink` (`permalink`(191)),
  KEY `network_id` (`network_id`),
  KEY `blog_id` (`blog_id`),
  KEY `post_id` (`post_id`),
  KEY `post_tags` (`post_tags`(191)),
  KEY `post_status` (`post_status`(191)),
  KEY `guid` (`guid`(191)),
  KEY `post_date_utc` (`post_date_utc`),
  KEY `sites_id` (`sites_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sites
# ------------------------------------------------------------

CREATE TABLE `sites` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'site GUID',
  `type` enum('FS','PARTNER','TI','EW','SI','OFFICIAL') CHARACTER SET utf8mb4 DEFAULT 'FS',
  `network_id` int(11) unsigned NOT NULL DEFAULT '1',
  `app_id` int(11) unsigned NOT NULL DEFAULT '1',
  `topic_id` int(11) unsigned NOT NULL,
  `site_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'posts->blog_id',
  `nav_group_id` int(11) unsigned NOT NULL DEFAULT '1',
  `site_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `site_url` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `site_image_dir` varchar(30) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `primary_logo` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL,
  `uix_color_swap` tinyint(1) NOT NULL DEFAULT '0',
  `lighter_color` varchar(7) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `darker_color` varchar(7) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `module_strong` varchar(7) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `link_color` varchar(7) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `twitter_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `facebook_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `gplus_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `google_analytics_viewid` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `site_payout` varchar(255) CHARACTER SET utf8mb4 DEFAULT 'site_pageviews',
  `api_endpoint` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `social_data` varchar(1055) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `site_name` (`site_url`(191)),
  KEY `app_id` (`app_id`),
  KEY `network_id` (`network_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sites_networks
# ------------------------------------------------------------

CREATE TABLE `sites_networks` (
  `id` int(11) unsigned NOT NULL,
  `wp_id` int(11) unsigned NOT NULL COMMENT 'used to describe wordpress multisite network ID',
  `app_id` int(11) NOT NULL DEFAULT '0',
  `network_slug` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `network_domain` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'used to assess CDN domain for network sites',
  `descriptions` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `newsletter` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `privacy` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `tos` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'only used as a unique id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sites_options
# ------------------------------------------------------------

CREATE TABLE `sites_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sites_id` smallint(6) NOT NULL,
  `blog_id` int(11) unsigned DEFAULT NULL,
  `network_id` int(11) unsigned DEFAULT NULL,
  `setting_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `setting_value` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `autoload` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `setting_name` (`setting_name`(191)),
  KEY `app_id` (`sites_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sites_topics
# ------------------------------------------------------------

CREATE TABLE `sites_topics` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `vertical` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `division` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `location` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `topic` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `tags` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `local_market` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `geo_lat` float(10,6) NOT NULL,
  `geo_lon` float(10,6) NOT NULL,
  `topic_image_dir` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `team_logo` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (`topic_id`),
  KEY `vertical` (`vertical`(191)),
  KEY `division` (`division`(191)),
  KEY `tags` (`tags`(191)),
  KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sites_topics_options
# ------------------------------------------------------------

CREATE TABLE `sites_topics_options` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `app_id` int(11) NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `order` int(11) NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table sites_trending_topics
# ------------------------------------------------------------

CREATE TABLE `sites_trending_topics` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `logo` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `background_image` varchar(1055) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `url` varchar(1055) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `start` date NOT NULL,
  `end` date NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'trending-tag',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table topics_relationships
# ------------------------------------------------------------

CREATE TABLE `topics_relationships` (
  `site_id` int(11) unsigned NOT NULL COMMENT 'sites->id',
  `topic_id` int(11) unsigned NOT NULL COMMENT 'sites_topics->topic_id',
  KEY `topic_id` (`topic_id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users_newsletters
# ------------------------------------------------------------

CREATE TABLE `users_newsletters` (
  `user_number` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newsletter` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `username` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `email_address` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `tags` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `confirm_ip` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '0',
  `confirm_time` timestamp NULL DEFAULT NULL,
  `optin_ip` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `optin_time` timestamp NULL DEFAULT NULL,
  `gmt_off` int(3) NOT NULL DEFAULT '-5',
  `lat` float NOT NULL DEFAULT '0',
  `lon` float NOT NULL DEFAULT '0',
  `cc` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `region` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `zipcode` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `source` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`user_number`),
  KEY `newsletter` (`newsletter`(191)),
  KEY `username` (`username`(191)),
  KEY `email_address` (`email_address`(191)),
  KEY `region` (`region`(191)),
  KEY `confirmed` (`confirmed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table videos
# ------------------------------------------------------------

CREATE TABLE `videos` (
  `post_key` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `guid` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'guid from partner _vfl_video->title',
  `post_status` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT 'draft',
  `post_tags` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'keywords',
  `network_id` int(11) unsigned NOT NULL COMMENT 'partner_feeds->network_id',
  `topic_id` int(11) unsigned NOT NULL COMMENT 'maybe_category?',
  `feed_id` int(11) unsigned NOT NULL DEFAULT '0',
  `app_id` int(11) unsigned NOT NULL COMMENT 'partner_feeds->app_id',
  `blog_id` int(11) unsigned NOT NULL COMMENT 'partner_feeds->sites_id',
  `groups` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'partner_feeds->tags',
  `video_title` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_description` varchar(2048) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `post_date` datetime NOT NULL,
  `post_date_utc` datetime NOT NULL,
  `video_embed` varchar(2048) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_embed_mobile` varchar(2048) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_author` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_author_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL DEFAULT '' COMMENT 'author or partner_feed->label',
  `video_thumbnail` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_thumbnail_mid` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_thumbnail_med` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `video_thumbnail_large` varchar(1024) CHARACTER SET utf8mb4 NOT NULL DEFAULT '',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `share_count` int(11) unsigned NOT NULL DEFAULT '0',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `media_key` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `publisher_key` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  PRIMARY KEY (`post_key`),
  KEY `network_id` (`network_id`),
  KEY `blog_id` (`blog_id`),
  KEY `post_tags` (`post_tags`(191)),
  KEY `post_status` (`post_status`(191)),
  KEY `post_date_utc` (`post_date_utc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
