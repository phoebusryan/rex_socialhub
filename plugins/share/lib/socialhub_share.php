<?php

class socialhub_share {
  public static function add_buttons(rex_extension_point $ep) {
    $Slice = $ep->getParam('slice_data');
    $Subject = $ep->getSubject();
    $Plugin = rex_addon::get('socialhub');
    $Config = $Plugin->getConfig('share');

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

    $fragment = new rex_fragment();
    $fragment->setVar('buttons',$shareButtons);
    $fragment->setVar('fontawesome',!empty($Config['general']['fontawesome'])?$Config['general']['fontawesome']:false);
    $fragment->setVar('short',!empty($Config['general']['short'])?$Config['general']['short']:false);
    $fragment->setVar('delimitter',!empty($Config['general']['delimitter'])?str_replace(' ',"&nbsp;",$Config['general']['delimitter']):false,false);
    $Buttons = $fragment->parse('share.php');

    if(empty($Config['general']['position']) || $Config['general']['position'] == '0')
      $Subject .= "\n".$Buttons;
    else $Subject = $Buttons."\n".$Subject;

    return $Subject;
  }
}