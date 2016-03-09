<?php

  $sections = '';
  $fragment = new rex_fragment();
  $fragment->setVar('class', 'info', false);
  $fragment->setVar('title', $this->i18n('help'));
  $fragment->setVar('body', 'Lorem ipsum dolor...', false);
  $sections .= $fragment->parse('core/page/section.php');
 
  echo $sections;
?>