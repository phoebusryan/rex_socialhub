CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_entries` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `visible` tinyint(1) NOT NULL,
 `source` varchar(20) NOT NULL,
 `source_id` varchar(255) NOT NULL,
 `post_id` varchar(50) NOT NULL,
 `caption` mediumtext NOT NULL,
 `image` varchar(250) NOT NULL,
 `video` varchar(250) NOT NULL,
 `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 `user_id` int(11) unsigned NOT NULL,
 `query` varchar(100) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `visible+source` (`visible`,`source`),
 KEY `created_time` (`created_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_hashtags` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `hashtag` varchar(50) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;