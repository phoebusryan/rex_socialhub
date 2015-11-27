<?php

$RSHF = rex_socialhub_instagram::factory();

if(rex_get('page') == 'rex_socialhub/instagram/toggle_entry') {
  $RSHF->toggleVisibility();
  die();
}
if(rex_get('page') == 'rex_socialhub/instagram/toggle_highlight') {
  $RSHF->toggleHighlight();
  die();
}