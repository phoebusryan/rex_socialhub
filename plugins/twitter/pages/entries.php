<?php

$sections = '';
$sh = rex_socialhub_twitter::factory();
foreach($sh->entries() as $key => $value) {
  $fragment = new rex_fragment();
  $fragment->setVar('values',$value);
  $entry = $fragment->parse('entry.php');

  $buttons = new rex_fragment();
  $buttons->setVar('visible',$value['visible']);
  $buttons->setVar('highlight',$value['highlight']);
  $buttons->setVar('entry',$value['id'],false);
  $buttons->setVar('plugin','twitter',false);
  $buttons = $buttons->parse('buttons.php');

  $fragment = new rex_fragment();
  $fragment->setVar('class', 'info', false);
  $fragment->setVar('title', 'Profil: <a href="https://www.twitter.com/'.$value['query']['user']['screen_name'].'" target="_blank" title="Twitter '.$value['query']['user']['screen_name'].' aufrufen">@'.ucfirst($value['query']['user']['screen_name']).'</a>',false);
  $fragment->setVar('heading',$buttons,false);
  $fragment->setVar('body', $entry, false);

  $sections .= $fragment->parse('core/page/section.php');
}

echo $sections;