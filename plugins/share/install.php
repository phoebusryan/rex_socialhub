<?php

$Entries = rex_sql::factory()
  ->setTable(rex::getTablePrefix().'socialhub_share')
  ->select();

if(!$Entries->getRows()) {
  rex_sql::factory()->setQuery("INSERT INTO `".rex::getTablePrefix()."socialhub_share` (`id`, `title`, `short`, `icon`, `link`, `url_parameter`) VALUES
  (1, 'Facebook', 'fb', 'fa-facebook', 'https://www.facebook.com/sharer/sharer.php', 'u={{SHARE_URL}}\r\nt={{SHARE_TITLE}}\r\nredirect_uri=http%3A%2F%2Fwww.facebook.com'),
  (2, 'Twitter', 't', 'fa-twitter', 'http://twitter.com/share', 'url={{SHARE_URL}}\r\ntext={{SHARE_SLICE}}'),
  (3, 'Google +', 'g+', 'fa-google', 'https://plus.google.com/share', 'url={{SHARE_URL}}');");
}