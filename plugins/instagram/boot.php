<?php

$RSHI = socialhub_instagram::factory();

if(rex_get('page') == 'socialhub/instagram/toggle_entry') {
  $RSHI->toggleVisibility();
  die();
}
if(rex_get('page') == 'socialhub/instagram/toggle_highlight') {
  $RSHI->toggleHighlight();
  die();
}