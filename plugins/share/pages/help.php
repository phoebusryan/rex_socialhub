<?php 

$fragment = new rex_fragment();
$content = $fragment->parse('help.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'info', false);
$fragment->setVar('title', $this->i18n('help').' '.$template['name']);
$fragment->setVar('body', $content, false);
echo $fragment->parse('core/page/section.php');