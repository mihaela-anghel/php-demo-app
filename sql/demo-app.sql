-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2022 at 12:28 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `demo-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_role_id` int(11) NOT NULL,
  `admin_username` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_phone` varchar(255) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins_rights`
--

CREATE TABLE `admins_rights` (
  `admin_right_id` int(11) NOT NULL,
  `admin_section_id` int(11) NOT NULL,
  `admin_right_url` varchar(255) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins_rights_details`
--

CREATE TABLE `admins_rights_details` (
  `id` int(11) NOT NULL,
  `admin_right_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `admin_right_name` varchar(255) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins_roles`
--

CREATE TABLE `admins_roles` (
  `admin_role_id` int(11) NOT NULL,
  `admin_role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins_roles_sections_rights`
--

CREATE TABLE `admins_roles_sections_rights` (
  `admin_role_id` int(11) NOT NULL,
  `admin_section_id` int(11) NOT NULL,
  `admin_right_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins_sections`
--

CREATE TABLE `admins_sections` (
  `admin_section_id` int(11) NOT NULL,
  `admin_section_url` varchar(255) NOT NULL,
  `menu` enum('0','1') NOT NULL DEFAULT '1',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `admins_sections_details`
--

CREATE TABLE `admins_sections_details` (
  `id` int(11) NOT NULL,
  `admin_section_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `admin_section_name` varchar(255) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `age_categories`
--

CREATE TABLE `age_categories` (
  `age_category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `min_age` int(11) NOT NULL,
  `max_age` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `age_categories_details`
--

CREATE TABLE `age_categories_details` (
  `id` int(11) NOT NULL,
  `age_category_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `age_category_name` varchar(255) NOT NULL,
  `age_category_description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `arbiters`
--

CREATE TABLE `arbiters` (
  `arbiter_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `function` varchar(50) NOT NULL,
  `company` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `school` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `article_id` int(11) NOT NULL,
  `published_date` date NOT NULL,
  `url` varchar(255) NOT NULL,
  `map` mediumtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `on_slider` enum('0','1') NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_details`
--

CREATE TABLE `articles_details` (
  `id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `description` longtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_files`
--

CREATE TABLE `articles_files` (
  `file_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_images`
--

CREATE TABLE `articles_images` (
  `image_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `articles_videos`
--

CREATE TABLE `articles_videos` (
  `video_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `banner_id` int(11) NOT NULL,
  `name_ro` varchar(255) NOT NULL,
  `subtitle_ro` varchar(255) NOT NULL,
  `description_ro` tinytext NOT NULL,
  `url` tinytext NOT NULL,
  `filename` varchar(255) NOT NULL,
  `position` varchar(30) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `subtitle_en` varchar(255) NOT NULL,
  `description_en` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `categories_details`
--

CREATE TABLE `categories_details` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE `competitions` (
  `competition_id` int(11) NOT NULL,
  `type` enum('national','international') NOT NULL DEFAULT 'national',
  `start_registration_date` date NOT NULL,
  `end_registration_date` date NOT NULL,
  `end_submit_project_date` date NOT NULL,
  `show_results_date` date NOT NULL,
  `default_count_participants` int(11) NOT NULL,
  `default_count_schools` int(11) NOT NULL,
  `default_count_countries` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `code_language_image` varchar(255) NOT NULL,
  `popup_info_active` enum('0','1') NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  `on_home` enum('0','1') NOT NULL DEFAULT '0',
  `on_comming_soon` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('open','close') NOT NULL DEFAULT 'open',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL,
  `save_notes_date` datetime NOT NULL,
  `generate_diplomas_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions2age_categories`
--

CREATE TABLE `competitions2age_categories` (
  `competition_id` int(11) NOT NULL,
  `age_category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions2categories`
--

CREATE TABLE `competitions2categories` (
  `competition_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_details`
--

CREATE TABLE `competitions_details` (
  `id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `theme_name` varchar(255) NOT NULL,
  `theme_description` mediumtext NOT NULL,
  `rules` text NOT NULL,
  `code_language` text NOT NULL,
  `email_content` text NOT NULL,
  `email_content_for_certificate` text NOT NULL,
  `popup_info` mediumtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_files`
--

CREATE TABLE `competitions_files` (
  `file_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_images`
--

CREATE TABLE `competitions_images` (
  `image_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_participants`
--

CREATE TABLE `competitions_participants` (
  `competitions_participant_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `age_category_id` int(11) NOT NULL,
  `registration_date` datetime NOT NULL,
  `project_number` varchar(50) NOT NULL,
  `project_filename` varchar(255) NOT NULL,
  `project_link_extern` varchar(255) NOT NULL,
  `project_verified` enum('0','1') NOT NULL DEFAULT '0',
  `note` decimal(10,2) UNSIGNED NOT NULL,
  `prize_id` int(11) NOT NULL,
  `project_add_date` datetime NOT NULL,
  `diploma` varchar(255) NOT NULL,
  `on_home` enum('0','1') NOT NULL DEFAULT '1',
  `comment` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_prizes`
--

CREATE TABLE `competitions_prizes` (
  `prize_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `type` enum('prize','special_diplama') NOT NULL DEFAULT 'prize',
  `image` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_prizes_details`
--

CREATE TABLE `competitions_prizes_details` (
  `id` int(11) NOT NULL,
  `prize_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `certificate` varchar(255) NOT NULL,
  `prize_name` varchar(255) NOT NULL,
  `prize_description` text NOT NULL,
  `email_content` text NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `competitions_videos`
--

CREATE TABLE `competitions_videos` (
  `video_id` int(11) NOT NULL,
  `competition_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(255) DEFAULT NULL,
  `country_iso_code_2` varchar(255) DEFAULT NULL,
  `country_iso_code_3` varchar(255) DEFAULT NULL,
  `address_format_id` varchar(255) DEFAULT NULL,
  `ue` enum('0','1') DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `email_template_id` int(11) NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates_details`
--

CREATE TABLE `email_templates_details` (
  `id` int(11) NOT NULL,
  `email_template_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `description` longtext NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE `galleries` (
  `gallery_id` int(11) NOT NULL,
  `galleries_category_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_categories`
--

CREATE TABLE `galleries_categories` (
  `galleries_category_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_categories_details`
--

CREATE TABLE `galleries_categories_details` (
  `id` int(11) NOT NULL,
  `galleries_category_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `galleries_category_name` varchar(255) NOT NULL,
  `galleries_category_description` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_details`
--

CREATE TABLE `galleries_details` (
  `id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `description` longtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_images`
--

CREATE TABLE `galleries_images` (
  `image_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `galleries_videos`
--

CREATE TABLE `galleries_videos` (
  `video_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `judete`
--

CREATE TABLE `judete` (
  `id` int(11) NOT NULL,
  `judet` varchar(255) NOT NULL,
  `cod` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `municipiu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `international_name` varchar(50) NOT NULL,
  `code` varchar(255) NOT NULL,
  `google_translate` enum('0','1') NOT NULL DEFAULT '1',
  `active_site` enum('0','1') NOT NULL DEFAULT '0',
  `active_admin` enum('0','1') NOT NULL DEFAULT '0',
  `default_site` enum('0','1') NOT NULL DEFAULT '0',
  `default_admin` enum('0','1') NOT NULL DEFAULT '0',
  `flag` varchar(255) NOT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` enum('individual','juridical') NOT NULL DEFAULT 'individual',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `cnp` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_vat_number` varchar(255) NOT NULL,
  `company_reg_com` varchar(255) NOT NULL,
  `company_bank` varchar(255) NOT NULL,
  `company_bank_account` varchar(255) NOT NULL,
  `company_phone` varchar(255) NOT NULL,
  `company_fax` varchar(255) NOT NULL,
  `company_web` varchar(255) NOT NULL,
  `company_position` varchar(255) NOT NULL,
  `company_description` text NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `delivery_address` varchar(255) NOT NULL,
  `delivery_city` varchar(255) NOT NULL,
  `delivery_region` varchar(255) NOT NULL,
  `delivery_country_id` int(255) NOT NULL,
  `delivery_postal_code` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `enable_submit_project_file` enum('0','1') NOT NULL DEFAULT '0',
  `registration_date` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `last_access` datetime NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `activation_token` varchar(255) NOT NULL,
  `removed` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `class` varchar(30) NOT NULL,
  `on_menu` enum('0','1') NOT NULL DEFAULT '0',
  `on_footer` enum('0','1') NOT NULL DEFAULT '0',
  `on_home` enum('0','1') NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL,
  `active_ro` enum('0','1') NOT NULL DEFAULT '1',
  `active_en` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_details`
--

CREATE TABLE `pages_details` (
  `id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `description` longtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_files`
--

CREATE TABLE `pages_files` (
  `file_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_images`
--

CREATE TABLE `pages_images` (
  `image_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pages_videos`
--

CREATE TABLE `pages_videos` (
  `video_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `partner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `on_header` enum('0','1') NOT NULL DEFAULT '0',
  `on_footer` enum('0','1') NOT NULL DEFAULT '0',
  `on_diploma` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `partner_links`
--

CREATE TABLE `partner_links` (
  `partner_link_id` int(11) NOT NULL,
  `type` enum('file','script') NOT NULL DEFAULT 'script',
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `script` mediumtext NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proiecte`
--

CREATE TABLE `proiecte` (
  `proiect_id` int(11) NOT NULL,
  `client` varchar(255) NOT NULL,
  `year` int(11) NOT NULL,
  `link` varchar(255) NOT NULL,
  `published_date` date NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `on_home` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proiecte_details`
--

CREATE TABLE `proiecte_details` (
  `id` int(11) NOT NULL,
  `proiect_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `description` longtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proiecte_files`
--

CREATE TABLE `proiecte_files` (
  `file_id` int(11) NOT NULL,
  `proiect_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proiecte_images`
--

CREATE TABLE `proiecte_images` (
  `image_id` int(11) NOT NULL,
  `proiect_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proiecte_videos`
--

CREATE TABLE `proiecte_videos` (
  `video_id` int(11) NOT NULL,
  `proiect_id` int(11) NOT NULL,
  `video` text NOT NULL,
  `filename` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `is_multilanguage` enum('0','1') NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'input',
  `html_textarea` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `settings_details`
--

CREATE TABLE `settings_details` (
  `id` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `function` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `on_home` enum('0','1') NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials_details`
--

CREATE TABLE `testimonials_details` (
  `id` int(11) NOT NULL,
  `testimonial_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `abstract` text NOT NULL,
  `description` longtext NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `lang_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `region` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `school` varchar(255) NOT NULL,
  `guide` varchar(255) NOT NULL,
  `birthday` date NOT NULL,
  `school_certificate` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `enable_submit_project_file` enum('0','1') NOT NULL DEFAULT '0',
  `inactive_reason` mediumtext NOT NULL,
  `admin_message` mediumtext NOT NULL,
  `activation_token` varchar(255) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `add_date` datetime NOT NULL,
  `last_login_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `admin_role_id` (`admin_role_id`);

--
-- Indexes for table `admins_rights`
--
ALTER TABLE `admins_rights`
  ADD PRIMARY KEY (`admin_right_id`),
  ADD KEY `admin_section_id` (`admin_section_id`);

--
-- Indexes for table `admins_rights_details`
--
ALTER TABLE `admins_rights_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_right_id` (`admin_right_id`),
  ADD KEY `lang_id` (`lang_id`);

--
-- Indexes for table `admins_roles`
--
ALTER TABLE `admins_roles`
  ADD PRIMARY KEY (`admin_role_id`);

--
-- Indexes for table `admins_roles_sections_rights`
--
ALTER TABLE `admins_roles_sections_rights`
  ADD KEY `admin_role_id` (`admin_role_id`),
  ADD KEY `admin_section_id` (`admin_section_id`),
  ADD KEY `admin_right_id` (`admin_right_id`);

--
-- Indexes for table `admins_sections`
--
ALTER TABLE `admins_sections`
  ADD PRIMARY KEY (`admin_section_id`);

--
-- Indexes for table `admins_sections_details`
--
ALTER TABLE `admins_sections_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_section_id` (`admin_section_id`),
  ADD KEY `lang_id` (`lang_id`);

--
-- Indexes for table `age_categories`
--
ALTER TABLE `age_categories`
  ADD PRIMARY KEY (`age_category_id`);

--
-- Indexes for table `age_categories_details`
--
ALTER TABLE `age_categories_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `age_category_id` (`age_category_id`);

--
-- Indexes for table `arbiters`
--
ALTER TABLE `arbiters`
  ADD PRIMARY KEY (`arbiter_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `articles_details`
--
ALTER TABLE `articles_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `article_id` (`article_id`) USING BTREE;

--
-- Indexes for table `articles_files`
--
ALTER TABLE `articles_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `article_id` (`article_id`) USING BTREE;

--
-- Indexes for table `articles_images`
--
ALTER TABLE `articles_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `article_id` (`article_id`) USING BTREE;

--
-- Indexes for table `articles_videos`
--
ALTER TABLE `articles_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `page_id` (`article_id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `categories_details`
--
ALTER TABLE `categories_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`competition_id`);

--
-- Indexes for table `competitions2age_categories`
--
ALTER TABLE `competitions2age_categories`
  ADD KEY `competition_id` (`competition_id`),
  ADD KEY `age_category_id` (`age_category_id`);

--
-- Indexes for table `competitions2categories`
--
ALTER TABLE `competitions2categories`
  ADD KEY `competition_id` (`competition_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `competitions_details`
--
ALTER TABLE `competitions_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `competitions_files`
--
ALTER TABLE `competitions_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `competitions_images`
--
ALTER TABLE `competitions_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `competitions_participants`
--
ALTER TABLE `competitions_participants`
  ADD PRIMARY KEY (`competitions_participant_id`),
  ADD KEY `competition_id` (`competition_id`,`user_id`,`category_id`,`age_category_id`) USING BTREE,
  ADD KEY `prize_id` (`prize_id`);

--
-- Indexes for table `competitions_prizes`
--
ALTER TABLE `competitions_prizes`
  ADD PRIMARY KEY (`prize_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `competitions_prizes_details`
--
ALTER TABLE `competitions_prizes_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `prize_id` (`prize_id`);

--
-- Indexes for table `competitions_videos`
--
ALTER TABLE `competitions_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `competition_id` (`competition_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`email_template_id`);

--
-- Indexes for table `email_templates_details`
--
ALTER TABLE `email_templates_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `email_template_id` (`email_template_id`);

--
-- Indexes for table `galleries`
--
ALTER TABLE `galleries`
  ADD PRIMARY KEY (`gallery_id`),
  ADD KEY `galleries_category_id` (`galleries_category_id`);

--
-- Indexes for table `galleries_categories`
--
ALTER TABLE `galleries_categories`
  ADD PRIMARY KEY (`galleries_category_id`);

--
-- Indexes for table `galleries_categories_details`
--
ALTER TABLE `galleries_categories_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `galleries_category_id` (`galleries_category_id`);

--
-- Indexes for table `galleries_details`
--
ALTER TABLE `galleries_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `gallery_id` (`gallery_id`);

--
-- Indexes for table `galleries_images`
--
ALTER TABLE `galleries_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `gallery_id` (`gallery_id`);

--
-- Indexes for table `galleries_videos`
--
ALTER TABLE `galleries_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `gallery_id` (`gallery_id`);

--
-- Indexes for table `judete`
--
ALTER TABLE `judete`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`);

--
-- Indexes for table `pages_details`
--
ALTER TABLE `pages_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `pages_files`
--
ALTER TABLE `pages_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `pages_images`
--
ALTER TABLE `pages_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `pages_videos`
--
ALTER TABLE `pages_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`partner_id`);

--
-- Indexes for table `partner_links`
--
ALTER TABLE `partner_links`
  ADD PRIMARY KEY (`partner_link_id`);

--
-- Indexes for table `proiecte`
--
ALTER TABLE `proiecte`
  ADD PRIMARY KEY (`proiect_id`);

--
-- Indexes for table `proiecte_details`
--
ALTER TABLE `proiecte_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `noutate_id` (`proiect_id`);

--
-- Indexes for table `proiecte_files`
--
ALTER TABLE `proiecte_files`
  ADD PRIMARY KEY (`file_id`),
  ADD KEY `proiect_id` (`proiect_id`) USING BTREE;

--
-- Indexes for table `proiecte_images`
--
ALTER TABLE `proiecte_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `noutate_id` (`proiect_id`);

--
-- Indexes for table `proiecte_videos`
--
ALTER TABLE `proiecte_videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `page_id` (`proiect_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `settings_details`
--
ALTER TABLE `settings_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `setting_id` (`setting_id`),
  ADD KEY `lang_id` (`lang_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`);

--
-- Indexes for table `testimonials_details`
--
ALTER TABLE `testimonials_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lang_id` (`lang_id`),
  ADD KEY `testimonial_id` (`testimonial_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `admins_rights`
--
ALTER TABLE `admins_rights`
  MODIFY `admin_right_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `admins_rights_details`
--
ALTER TABLE `admins_rights_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `admins_roles`
--
ALTER TABLE `admins_roles`
  MODIFY `admin_role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admins_sections`
--
ALTER TABLE `admins_sections`
  MODIFY `admin_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `admins_sections_details`
--
ALTER TABLE `admins_sections_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `age_categories`
--
ALTER TABLE `age_categories`
  MODIFY `age_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `age_categories_details`
--
ALTER TABLE `age_categories_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `arbiters`
--
ALTER TABLE `arbiters`
  MODIFY `arbiter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `article_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `articles_details`
--
ALTER TABLE `articles_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `articles_files`
--
ALTER TABLE `articles_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articles_images`
--
ALTER TABLE `articles_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `articles_videos`
--
ALTER TABLE `articles_videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories_details`
--
ALTER TABLE `categories_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `competitions`
--
ALTER TABLE `competitions`
  MODIFY `competition_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `competitions_details`
--
ALTER TABLE `competitions_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `competitions_files`
--
ALTER TABLE `competitions_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competitions_images`
--
ALTER TABLE `competitions_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `competitions_participants`
--
ALTER TABLE `competitions_participants`
  MODIFY `competitions_participant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5225;

--
-- AUTO_INCREMENT for table `competitions_prizes`
--
ALTER TABLE `competitions_prizes`
  MODIFY `prize_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `competitions_prizes_details`
--
ALTER TABLE `competitions_prizes_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT for table `competitions_videos`
--
ALTER TABLE `competitions_videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `email_template_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `email_templates_details`
--
ALTER TABLE `email_templates_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `galleries`
--
ALTER TABLE `galleries`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `galleries_categories`
--
ALTER TABLE `galleries_categories`
  MODIFY `galleries_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `galleries_categories_details`
--
ALTER TABLE `galleries_categories_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `galleries_details`
--
ALTER TABLE `galleries_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `galleries_images`
--
ALTER TABLE `galleries_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `galleries_videos`
--
ALTER TABLE `galleries_videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `judete`
--
ALTER TABLE `judete`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `pages_details`
--
ALTER TABLE `pages_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `pages_files`
--
ALTER TABLE `pages_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages_images`
--
ALTER TABLE `pages_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `pages_videos`
--
ALTER TABLE `pages_videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `partner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `partner_links`
--
ALTER TABLE `partner_links`
  MODIFY `partner_link_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `proiecte`
--
ALTER TABLE `proiecte`
  MODIFY `proiect_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `proiecte_details`
--
ALTER TABLE `proiecte_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `proiecte_files`
--
ALTER TABLE `proiecte_files`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proiecte_images`
--
ALTER TABLE `proiecte_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proiecte_videos`
--
ALTER TABLE `proiecte_videos`
  MODIFY `video_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `settings_details`
--
ALTER TABLE `settings_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `testimonials_details`
--
ALTER TABLE `testimonials_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6919;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
