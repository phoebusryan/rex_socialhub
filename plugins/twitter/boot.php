<?php
	$RSHT = rex_socialhub_twitter::factory();
	
	if(rex_get('page') == 'rex_socialhub/twitter/toggle_entry') {
		$RSHT->toggleVisibility();
		die();
	}
	
	if(rex_get('page') == 'rex_socialhub/twitter/toggle_highlight') {
		$RSHT->toggleHighlight();
		die();
	}
?>