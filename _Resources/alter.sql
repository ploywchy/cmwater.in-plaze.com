ALTER TABLE `blog` ADD `Tags` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `Images`;

CREATE TABLE IF NOT EXISTS `tag` (
  `Tag_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`Tag_ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

RENAME TABLE `content` TO `contents`;

ALTER TABLE `category` ADD `Description` TEXT NULL AFTER `Name`;