<?php

class rex_var_social extends rex_var {
  
  protected function getOutput() {

    $Type = $this->getArg('type', 0, true);
    $From = $this->getArg('from', 0, true);
    $Template = $this->getArg('template', 0, true);

    $Limit = (int)$this->getArg('limit', 0, true);

    $sql = rex_sql::factory();

    $entry = '';
    switch($Type) {
      case 'hashtags':
        $sql->setTable(rex::getTablePrefix().'socialhub_entries');
        if(!empty($From)) {
          $From = str_replace(' ','',$From);
          $sql->setWhere("source IN ('".str_replace(',',"','",$From)."')");
        }
        // if($Limit)
        //   $sql->setLimit($Limit);
        $sql->select();
        if($sql->getRows())
          while($sql->hasNext() && ($Limit == 0 || $sql->key() !== $Limit)) {

            $Plugin = 'socialhub_'.$sql->getValue('source');

            $caption = urldecode($sql->getValue('caption'));
            $caption = preg_replace('/((https*|www\.)[^\s]+)/is','<a href="$1" target="_blank" title="link to $1">$1</a>',$caption);
            $caption = preg_replace('/\@([^ :]*)/is','<a href="'.$Plugin::$url.'/$1" target="_blank" title="link to $1">@$1</a>',$caption);
            $caption = preg_replace('/\#([0-9a-zA-Z_\-]+)/is','<a href="'.$Plugin::$search_url.'$1" target="_blank" title="Hashtag-Suche auf '.ucfirst($sql->getValue('source')).': '.$Plugin::$search_url.'$1">#$1</a>',$caption);

            $fragment = new rex_fragment();
            $fragment->setVar('id',$sql->getValue('id')); 
            $fragment->setVar('visible',$sql->getValue('visible'));
            $fragment->setVar('source',$sql->getValue('source')); 
            $fragment->setVar('source_id',$sql->getValue('source_id'));
            $fragment->setVar('post_id',$sql->getValue('post_id'));
            $fragment->setVar('caption',$caption,false);
            $fragment->setVar('image',$sql->getValue('image'));
            $fragment->setVar('video',$sql->getValue('video'));
            $fragment->setVar('query',$sql->getValue('query'));

            $entry .= $fragment->parse('frontend/hastags.php');
            
            $sql->next();
          }
      break;
      default:
        if(!empty($From)) {
          $From = explode(',',$From);
          foreach($From as $classPart) {
            $Class = 'Socialhub'.ucfirst($classPart);
            $Class = new $Class();
            $entry .= $Class->timeline();
          }
        }
        $sql->setTable(rex::getTablePrefix().'socialhub_hashtag');
      break;
    }

    if(!empty($Template)) {
      $fragment = new rex_fragment();
      $fragment->setVar('body',$entry,false);
      $entry = $fragment->parse('frontend/grid.php');
    }

    return self::quote($entry);
  }
}