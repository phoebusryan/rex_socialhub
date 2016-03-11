<?php
	$sections = '';
	$sh = rex_socialhub_facebook::factory();
	
	foreach ($sh->entries() as $key => $value) {
		$fragment = new rex_fragment();
		$fragment->setVar('values',$value);
		$entry = $fragment->parse('entry.php');
	
		$buttons = new rex_fragment();
	  $buttons->setVar('visible',$value['visible']);
	  $buttons->setVar('highlight',$value['highlight']);
	  $buttons->setVar('entry',$value['id'],false);
	  $buttons->setVar('plugin','facebook',false);
	  $buttons = $buttons->parse('buttons.php');
	
	  $fragment = new rex_fragment();
	  $fragment->setVar('class', 'info', false);
	  $fragment->setVar('title', 'Seite: <a href="https://www.facebook.com/'.$value['author']['id'].'" target="_blank" title="Facebook-Profil '.$value['name'].' aufrufen">'.$value['name'].'</a>',false);
	  $fragment->setVar('heading',$buttons,false);
	  $fragment->setVar('body', $entry, false);
	
	  $fragment->setVar('footer','<p>Likes: '.$value['count_likes'].($value['count_likes'] > 0?' ('.implode(', ',$likers).')':'').'</p>', false);
	
	  $sections .= $fragment->parse('core/page/section.php');
	}
	
	echo $sections;
?>