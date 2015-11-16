<?php 
  $values = $this->getVar('values');

  $message = $values['message'];
  $message = preg_replace('/((https*|www\.)[^\s]+)/is','<a href="$1" target="_blank" title="link to $1">$1</a>',$message);
  $message = preg_replace('/\#([0-9a-zA-Z]+)/is','<a href="https://www.facebook.com/hashtag/$1" target="_blank" title="Hashtag-Suche auf Facebook: https://www.facebook.com/hashtag/$1">#$1</a>',$message);

  $likers = [];
  foreach($values['likes']['data'] as $key => $liker)
    $likers[] = '<a href="https://www.facebook.com/'.$liker['id'].'" target="_blank">'.$liker['name'].'</a>';
?><p><?php echo nl2br($message);?></p>