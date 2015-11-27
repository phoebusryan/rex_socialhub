<?php

$sections = '';
$sh = rex_socialhub_instagram::factory();
foreach($sh->entries(true,array('highlight'=>1)) as $key => $value) {
  $fragment = new rex_fragment();
  $fragment->setVar('values',$value);
  $entry = $fragment->parse('entry.php');

  $buttons = new rex_fragment();
  $buttons->setVar('visible',$value['visible']);
  $buttons->setVar('highlight',$value['highlight']);
  $buttons->setVar('entry',$value['id'],false);
  $buttons->setVar('plugin','instagram',false);
  $buttons = $buttons->parse('buttons.php');
  
  $fragment = new rex_fragment();
  $fragment->setVar('class', 'info', false);
  $fragment->setVar('title', 'Seite: <a href="https://www.instagram.com/'.$value['query']['user']['username'].'" target="_blank" title="Instagram '.$value['query']['user']['username'].' aufrufen">'.ucfirst($value['query']['user']['username']).'</a>',false);
  $fragment->setVar('heading',$buttons,false);
  $fragment->setVar('body', $entry, false);

  $sections .= $fragment->parse('core/page/section.php');
}

echo $sections;