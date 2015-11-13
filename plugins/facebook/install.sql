CREATE TABLE IF NOT EXISTS `rex_socialhub_facebook` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `fid` varchar(100) NOT NULL,
 `message` text NULL,
 `name` varchar(100) NOT NULL,
 `author` text NULL,
 `post_type` varchar(50) NOT NULL,
 `privacy` text NULL,
 `likes` text NULL,
 `count_likes` int(11) unsigned NOT NULL,
 PRIMARY KEY (`id`),
 KEY `fid` (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;