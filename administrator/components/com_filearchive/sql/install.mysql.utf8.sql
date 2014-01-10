CREATE TABLE IF NOT EXISTS `#__filearchive_files` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`name` VARCHAR(255)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`description` TEXT NOT NULL ,
`file` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

