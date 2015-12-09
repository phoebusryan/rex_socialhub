<?php

// if(($short = rex_request('share','string')) !== '') {
//   $Plattform = rex_sql::factory()
//     ->setTable(rex::getTablePrefix().'socialhub_share')
//     ->setWhere(['short'=>$short])
//     ->select()
//     ->getArray();

//   echo '<pre>'.print_r($Plattform,1).'</pre>';
//   die();
// }

if(!rex::isBackend())
  rex_extension::register('SLICE_OUTPUT','socialhub_share::add_buttons');