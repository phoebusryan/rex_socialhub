<?php

$RSHI = rex_socialhub_instagram::factory();

if(rex_get('page') == 'rex_socialhub/instagram/toggle_entry') {
  $RSHI->toggleVisibility();
  die();
}
if(rex_get('page') == 'rex_socialhub/instagram/toggle_highlight') {
  $RSHI->toggleHighlight();
  die();
}