<?php

$RSHF = socialhub_facebook::factory();

if(rex_get('page') == 'socialhub/facebook/toggle_entry') {
  $RSHF->toggleVisibility();
  die();
}
if(rex_get('page') == 'socialhub/facebook/toggle_highlight') {
  $RSHF->toggleHighlight();
  die();
}