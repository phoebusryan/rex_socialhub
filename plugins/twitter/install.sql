CREATE TABLE IF NOT EXISTS `rex_socialhub_twitter` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `post_id` varchar(100) NOT NULL,
 `message` text NULL,
 `image` text NULL,
 `name` varchar(100) NOT NULL,
 `author` text NULL,
 `visible` char(1) NOT NULL DEFAULT '1',
 `highlight` char(1) NOT NULL DEFAULT '0',
 `query` text Null,
 PRIMARY KEY (`id`),
 KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;