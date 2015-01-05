DROP TABLE IF EXISTS `#__polartour_tournament`;
DROP TABLE IF EXISTS `#__polartour_player`;
DROP TABLE IF EXISTS `#__polartour_playerbase`;
DROP TABLE IF EXISTS `#__polartour_result`;

CREATE TABLE `#__polartour_tournament` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user` int(11) NOT NULL COMMENT 'Userid in Joomla',
  `elocat` int(11) NOT NULL COMMENT '0=classical, 1=rapid, 2=blitz',
  `startdate` date NOT NULL DEFAULT '0000-00-00',
  `enddate` date NOT NULL DEFAULT '0000-00-00',
  `trashed` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `#__polartour_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eventid` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `club` varchar(255) NOT NULL,
  `elo` int(11) NOT NULL DEFAULT '0',
  `trashed` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `eventid` (`eventid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `#__polartour_playerbase` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `club` varchar(255) NOT NULL,
  `classical` int(11) NOT NULL DEFAULT '0',
  `rapid` int(11) NOT NULL DEFAULT '0',
  `blitz` int(11) NOT NULL DEFAULT '0',
  `trashed` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `firstname` (`firstname`).
  KEY `lastname` (`lastname`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
