CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_entry_hashtag` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `visible` tinyint(1) NOT NULL,
 `source` varchar(20) NOT NULL,
 `source_id` varchar(255) NOT NULL,
 `post_id` varchar(50) NOT NULL,
 `caption` mediumtext NOT NULL,
 `image` varchar(250) NOT NULL,
 `video` varchar(250) NOT NULL,
 `created_time` timestamp NULL DEFAULT NULL,
 `author_id` text,
 `author_name` text NOT NULL,
 `query` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_entry_timeline` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `visible` tinyint(1) NOT NULL,
 `source` varchar(20) NOT NULL,
 `post_id` varchar(100) NOT NULL,
 `message` text,
 `image` text,
 `author_id` text,
 `author_name` text NOT NULL,
 `created_time` timestamp NULL DEFAULT NULL,
 `query` text,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

