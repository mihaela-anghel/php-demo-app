SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `qwertys` (
  `qwerty_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `add_date` datetime NOT NULL,
  `url_key` varchar(255) NOT NULL,
  PRIMARY KEY (`qwerty_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qwertys_files` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT,
  `qwerty_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`file_id`),
  KEY `qwerty_id` (`qwerty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `qwertys_images` (
  `image_id` int(11) NOT NULL AUTO_INCREMENT,
  `qwerty_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`image_id`),
  KEY `qwerty_id` (`qwerty_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `qwertys_files`
  ADD CONSTRAINT `qwertys_files_ibfk_1` FOREIGN KEY (`qwerty_id`) REFERENCES `qwertys` (`qwerty_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `qwertys_images`
  ADD CONSTRAINT `qwertys_images_ibfk_1` FOREIGN KEY (`qwerty_id`) REFERENCES `qwertys` (`qwerty_id`) ON DELETE CASCADE ON UPDATE NO ACTION;