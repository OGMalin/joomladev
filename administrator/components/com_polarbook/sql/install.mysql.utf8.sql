DROP TABLE IF EXISTS `#__polarbook_book`;
DROP TABLE IF EXISTS `#__polarbook_data`;

CREATE TABLE `#__polarbook_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `user` int(11) NOT NULL COMMENT 'Userid in Joomla',
  `trashed` int(11) NOT NULL DEFAULT '0',
  `public` int(11) NOT NULL COMMENT '0=none, 1=read, 2=write',
  `member` int(11) NOT NULL COMMENT '0=none, 1=read, 2=write',
  `readusers` text NOT NULL,
  `writeusers` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `#__polarbook_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL COMMENT 'Pointer to #__polarbook_book id',
  `fen` varchar(256) NOT NULL,
  `computer` varchar(256) NOT NULL,
  `moves` tinytext NOT NULL COMMENT 'List of moves with movecomment, repertoire move, statistics',
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `fen` (`fen`(255)),
  KEY `computer` (`computer`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

