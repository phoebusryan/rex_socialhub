CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%socialhub_share` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL,
    `short` varchar(50) NOT NULL,
    `icon` varchar(50) NOT NULL,
    `link` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `%TABLE_PREFIX%socialhub_share` (`id`, `title`, `short`, `icon`, `link`) VALUES
(1, 'Facebook', 'fb', 'fa-facebook', 'https://www.facebook.com'),
(2, 'Twitter', 't', 'fa-twitter', 'https://www.twitter.com'),
(3, 'Google +', 'g+', 'fa-google', 'https://plus.google.com');