<?php

  $sections = '';
  $fragment = new rex_fragment();
  $fragment->setVar('class', 'info', false);
  $fragment->setVar('title', rex_i18n::msg('socialhub_help'));
  $fragment->setVar('body', 'Lorem ipsum dolor...', false);
  $sections .= $fragment->parse('core/page/section.php');
 
  echo $sections;
?>