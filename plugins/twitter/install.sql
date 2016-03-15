CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_twitter_account` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `title` varchar(100) NOT NULL,
 `consumer_token` varchar(100) NOT NULL,
 `consumer_secret_token` varchar(100) NOT NULL,
 `access_token` varchar(100) NOT NULL,
 `secret_token` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_twitter_hashtag` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `hashtag` varchar(50) NOT NULL,
 `twitter_next_id` bigint(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_twitter_timeline` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `title` varchar(100) NOT NULL,
 `user_id` varchar(100) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;