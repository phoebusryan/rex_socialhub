CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_twitter_entries` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_twitter_account` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `consumer_token` varchar(100) NOT NULL,
 `consumer_secret_token` varchar(100) NOT NULL,
 `access_token` varchar(100) NOT NULL,
 `secret_token` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;