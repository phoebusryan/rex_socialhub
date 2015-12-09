<?php

class socialhub_share {

  public static function setConfig($key,$value) {
    $Plugin = rex_plugin::get('socialhub','share');
    $Plugin->setProperty($key,$value);
  }

  public static function add_buttons(rex_extension_point $ep) {
    $Slice = $ep->getParam('slice_data');
    $Subject = $ep->getSubject();

    $Addon = rex_addon::get('socialhub');
    $Plugin = rex_plugin::get('socialhub','share');
    $Config = $Addon->getConfig('share');

    ob_start();
    rex_file::put($Plugin->getPath().'lib/cache.php',$Subject);
    include 'cache.php';
    ob_end_clean();


    rex_file::delete($Plugin->getPath().'lib/cache.php');

    if(!empty($Config['modules']) && !in_array('all',$Config['modules']) && !in_array($Slice->getValue('module_id'),$Config['modules']))
      return $Subject;
    
    $article = rex_sql::factory();
    // $article->setDebug();

    $articleTable = rex::getTablePrefix().'article';
    $article->setTable($articleTable);

    $article->setQuery('
      SELECT article.*, template.attributes as template_attributes
      FROM '.rex::getTablePrefix().'article as article
      LEFT JOIN '.rex::getTablePrefix().'template as template ON template.id=article.template_id
      WHERE article.id = ? AND clang_id = ?',array($ep->getParam('article_id'),$ep->getParam('clang')));

    $template_attributes = json_decode($article->getValue('template_attributes'),1);
    if($template_attributes === null)
      $template_attributes = array();

    if(!empty($Config['ctypes']))
      $Config['ctypes'] = $Config['ctypes'][$article->getValue('template_id')];

    if(!empty($Config['ctypes']) && !in_array('all',$Config['ctypes']) && !in_array($Slice->getValue('ctype_id'),$Config['ctypes']))
      return $Subject;
    
    $shareButtons = rex_sql::factory()
      ->setTable(rex::getTablePrefix().'socialhub_share')
      ->select()->getArray();

    if(!empty($shareButtons)) {

      foreach($shareButtons as $key => $button) {
        $shareButtons[$key]['properties'] = $Plugin->getProperty('share_url');
        $shareButtons[$key]['query'] = preg_replace('|[\r\n]+|is','&amp;',$button['url_parameter']);

        preg_match_all('|{{([^}]+)}}|is',$shareButtons[$key]['query'],$matched);
        $match = array_map(function($n) {return strtolower($n);},$matched[1]);
        $Terms = [
          '{{SHARE_URL}}' => 'http://' . $_SERVER['HTTP_HOST'].explode('?', $_SERVER['REQUEST_URI'], 2)[0],
          '{{SHARE_TITLE}}' => rex::getServerName().' - '.$article->getValue('name'),
        ];
        foreach($match as $mKey => $term) {
          if($Plugin->hasProperty($term))
            $Terms['{{'.strtoupper($term).'}}'] = $Plugin->getProperty($term);
        }

        $shareButtons[$key]['query'] = str_replace(array_keys($Terms),array_values($Terms),$shareButtons[$key]['query']);
      }

      $fragment = new rex_fragment();
      $fragment->setVar('buttons',$shareButtons);
      $fragment->setVar('fontawesome',!empty($Config['general']['fontawesome'])?$Config['general']['fontawesome']:false);
      $fragment->setVar('short',!empty($Config['general']['short'])?$Config['general']['short']:false);
      $fragment->setVar('delimitter',!empty($Config['general']['delimitter'])?str_replace(' ',"&nbsp;",$Config['general']['delimitter']):false,false);
      $Buttons = $fragment->parse('share.php');

      if(empty($Config['general']['position']) || $Config['general']['position'] == '0')
        $Subject .= "\n".$Buttons;
      else $Subject = $Buttons."\n".$Subject;
    }

    return $Subject;
  }
}