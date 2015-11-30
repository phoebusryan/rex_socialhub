<?php 
  $values = $this->getVar('values');

  $image = $values['image'];
  $message = urldecode($values['message']);
  $message = preg_replace('/((https*|www\.)[^\s]+)/is','<a href="$1" target="_blank" title="link to $1">$1</a>',$message);
  $message = preg_replace('/\@([^ :]*)/is','<a href="http://www.twitter.com/$1" target="_blank" title="link to $1">@$1</a>',$message);
  $message = preg_replace('/\#([0-9a-zA-Z_\-]+)/is','<a href="https://twitter.com/search?q=%23$1" target="_blank" title="Hashtag-Suche auf Facebook: https://www.facebook.com/hashtag/$1">#$1</a>',$message);

?><figure>
<img style="height: auto; max-width:200px;" src="<?php echo $image;?>" alt="" title="" /><br><br>
<figcaption><?php echo nl2br($message);?></figcaption>
</figure>