<?php
echo $_GET['share'];
if(($short = rex_request('share','string')) !== '') {
  $Plattform = rex_sql::factory()
    ->setTable(rex::getTablePrefix().'socialhub_share')
    ->setWhere(['short'=>$short])
    ->select()
    ->getArray();

  if(!empty($Plattform)) {
    header("Location: ".$Plattform[0]['link'].'?'.str_replace('&amp;','&',preg_replace('|share=[^&]+&rex_modal=1&|is','',$_SERVER['QUERY_STRING'])));
    exit();
  }
}

if(!rex::isBackend())
  rex_extension::register('SLICE_OUTPUT','socialhub_share::add_buttons');