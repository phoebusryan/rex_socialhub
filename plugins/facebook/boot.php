<?php
	$RSHF = rex_socialhub_facebook::factory();
	
	if (rex_get('page') == 'rex_socialhub/facebook/toggle_entry') {
		$RSHF->toggleVisibility();
		die();
	}
	
	if (rex_get('page') == 'rex_socialhub/facebook/toggle_highlight') {
		$RSHF->toggleHighlight();
		die();
	}
?>