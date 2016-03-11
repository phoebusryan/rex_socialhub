CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_facebook` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `post_id` varchar(100) NOT NULL,
 `message` text NULL,
 `name` varchar(100) NOT NULL,
 `visible` char(1) NOT NULL DEFAULT '1',
 `highlight` char(1) NOT NULL DEFAULT '0',
 `query` text NULL,
 PRIMARY KEY (`id`),
 KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;