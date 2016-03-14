<?php
	$newCron = rex_sql::factory();
	$newCron->setTable(REX_CRONJOB_TABLE);
	$newCron->setWhere(array('name'=>'Update Twitter-Timelines'));
	
	try {
		$newCron->delete();
	} catch (rex_sql_exception $e) {
		echo rex_view::warning($e->getMessage());
	}
	
	$newCron = rex_sql::factory();
	$newCron->setTable(REX_CRONJOB_TABLE);
	$newCron->setWhere(array('name'=>'Update Twitter-Hashtags'));
	
	try {
		$newCron->delete();
	} catch (rex_sql_exception $e) {
		echo rex_view::warning($e->getMessage());
	}
?>