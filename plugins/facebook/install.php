<?php


$newCron = rex_sql::factory();
$newCron->setTable(REX_CRONJOB_TABLE);
$newCron->setWhere(array('name'=>'Update Facebook'));
$newCron->select();

if($newCron->getRows() === 0) {
  $newCron = rex_sql::factory();
  $newCron->setTable(REX_CRONJOB_TABLE);
  $newCron->setValue('name','Update Facebook');
  $newCron->setValue('description','Prüft ob neue Facebookeinträge auf den angegebenen Seiten vorhanden sind.');
  $newCron->setValue('type','rex_cronjob_phpcallback');
  $newCron->setValue('parameters','{"rex_cronjob_phpcallback_callback":"rex_socialhub_facebook::cron()"}');
  $newCron->setValue('interval','|1|h|');
  $newCron->setValue('nexttime','0000-00-00 00:00:00');
  $newCron->setValue('environment','|0|1|');
  $newCron->setValue('execution_moment',0);
  $newCron->setValue('status',1);
  $newCron->setValue('execution_start',date('Y-m-d').' 01:00:00');

  $newCron->addGlobalUpdateFields();
  $newCron->addGlobalCreateFields();
  try {
    $newCron->insert();
  } catch (rex_sql_exception $e) {
    echo rex_view::warning($e->getMessage());
  }
}