<?php

$sh = rex_socialhub_facebook::factory();
foreach($sh->entries() as $key => $value)
  echo '<pre>'.print_r($value,1).'</pre><br><br>';