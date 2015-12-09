<?php

$message = '';

if(rex_post('btn_save', 'string') != '') {

  $pValues = rex_post('share', [
    ['general', 'array'],
    ['modules', 'array'],
    ['ctypes', 'array'],
  ]);

  foreach($pValues['ctypes'] as $cKey => $ctypes) {
    if(in_array('all',$ctypes))
      $pValues['ctypes'][$cKey] = array();
  }

  $this->setConfig(['share'=>$pValues]);

  $gc = rex_sql::factory();
  $gc->setQuery('SELECT DISTINCT(' . rex::getTablePrefix() . 'article.id) FROM ' . rex::getTablePrefix() . 'article
      LEFT JOIN ' . rex::getTablePrefix() . 'article_slice ON ' . rex::getTablePrefix() . 'article.id=' . rex::getTablePrefix() . 'article_slice.article_id');
  for ($i = 0; $i < $gc->getRows(); ++$i) {
    rex_article_cache::delete($gc->getValue(rex::getTablePrefix() . 'article.id'));
    $gc->next();
  }

  $message = $this->i18n('config_saved_successfull');
}


$sql = rex_sql::factory();
$modules = $sql->getArray("SELECT id,name FROM ".rex::getTablePrefix()."module");

$sections = '';

$Config = $this->getConfig('share');

$Values = $Config['general'];

/* GENERAL */
$content = '';
$fragment = new rex_fragment();
$fragment->setVar('name', 'share[general][position]', false);
$fragment->setVar('checked', (!empty($Values['position']) && $Values['position'] == '1'?true:false), false);
$fragment->setVar('label', rex_i18n::msg('socialhub_share_position'), false);
$content .= $fragment->parse('form/checkbox.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('share_general'));
$fragment->setVar('body', $content, false);
$sections .= $fragment->parse('core/page/section.php');
$content = '';

/* MODULES */
$Values = $Config['modules'];

$content = '';
$fragment = new rex_fragment();
$fragment->setVar('name', 'share[modules][]', false);
$fragment->setVar('checked', (!empty($Values) && $Values[0] == 'all'?true:false), false);
$fragment->setVar('value', 'all', false);
$fragment->setVar('label', $this->i18n('modules_available_all'), false);
$fragment->setVar('toggleFields','.allmodules',false);
$content .= $fragment->parse('form/checkbox.php');

$fragment = new rex_fragment();
$fragment->setVar('group', 'allmodules', false);
$fragment->setVar('name', 'share[modules][]', false);
$fragment->setVar('min', count($modules), false);
$fragment->setVar('size', 5, false);
$fragment->setVar('multiple', true, false);
$fragment->setVar('selected',$Values,false);
$fragment->setVar('label', rex_i18n::msg('modules_available'), false);
$fragment->setVar('options',$modules,false);
$fragment->setVar('info',rex_i18n::msg('ctrl'),false);
$content .= $fragment->parse('form/select.php');

$sql = rex_sql::factory();
$Attributes = $sql->getArray("SELECT id,name,attributes FROM ".rex::getTablePrefix()."template");
$arrAttributes = array();
foreach($Attributes as $key => $attributes) {
  $_attributes = json_decode($attributes['attributes'],1);
  if(!empty($_attributes['ctype']))
    $arrAttributes[$attributes['id']] = array('name'=>$attributes['name'],'data'=>$_attributes['ctype']);
}

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('share_config_modules'));
$fragment->setVar('body', $content, false);
$sections .= $fragment->parse('core/page/section.php');
$content = '';

/* CTYPES */
if(!empty($arrAttributes)) {

  $Values = $Config['ctypes'];
  foreach($arrAttributes as $tid => $template) {
    $content = '';

    $fragment = new rex_fragment();
    $fragment->setVar('name', 'share[ctypes]['.$tid.'][]', false);
    $fragment->setVar('checked', ($Values[$tid] == 'all' || empty($Values[$tid])?true:false), false);
    $fragment->setVar('value', 'all', false);
    $fragment->setVar('label', $this->i18n('ctypes_available_all'), false);
    $fragment->setVar('toggleFields','.allctypes_'.$tid,false);
    $content .= $fragment->parse('form/checkbox.php');

    $fragment = new rex_fragment();
    $fragment->setVar('group', 'allctypes_'.$tid, false);
    $fragment->setVar('name', 'share[ctypes]['.$tid.'][]', false);
    $fragment->setVar('min', count($template['data']), false);
    $fragment->setVar('size', 5, false);
    $fragment->setVar('multiple', true, false);
    $fragment->setVar('selected',$Values[$tid],false);
    $fragment->setVar('label', $this->i18n('ctypes_available'), false);
    $fragment->setVar('options',$template['data'],false);
    $fragment->setVar('info',rex_i18n::msg('ctrl'),false);
    $content .= $fragment->parse('form/select.php');

    $fragment = new rex_fragment();
    $fragment->setVar('class', 'edit', false);
    $fragment->setVar('title', $this->i18n('template_headline').' '.$template['name']);
    $fragment->setVar('body', $content, false);
    $sections .= $fragment->parse('core/page/section.php');
  }
}
$content = '';

$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="btn_save" value="' . $this->i18n('save') . '">' . $this->i18n('save') . '</button>';
$formElements[] = $n;
$n = [];
$n['field'] = '<button class="btn btn-reset" type="reset" name="btn_reset" value="' . $this->i18n('reset') . '" data-confirm="' . $this->i18n('reset_info') . '">' . $this->i18n('reset') . '</button>';
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('flush', true);
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$sections .= $fragment->parse('core/page/section.php');

?><form action="<?php echo rex_url::currentBackendPage();?>" method="post">
  <?php if($message) echo rex_view::success($message);?>
  <?php echo $sections;?>
</form>