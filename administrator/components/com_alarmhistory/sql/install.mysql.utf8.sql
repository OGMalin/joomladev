CREATE TABLE `#__alarmhistory_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `FIELD` int(11) NOT NULL DEFAULT '0',
  `REGION` int(11) NOT NULL DEFAULT '0',
  `DISTRICT` int(11) NOT NULL DEFAULT '0',
  `LOCATION` int(11) NOT NULL DEFAULT '0',
  `section` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `#__alarmhistory_section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `SEC1` varchar(21) NOT NULL DEFAULT '',
  `SEC2` varchar(21) NOT NULL DEFAULT '',
  `SEC3` varchar(21) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
