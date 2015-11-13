<?php

$message = '';

if(rex_post('btn_save', 'string') != '') {

  $pValues = rex_post('rex_socialhub', [
    ['facebook', 'array'],
  ]);

  $this->setConfig($pValues);
  $message = $this->i18n('config_saved_successfull');
}

$content = $sections = '';

$Values = $this->getConfig('facebook');
if(empty($Values['page']))
  $Values = ['page'=>['']];
else $Values['page'][] = '';

foreach($Values['page'] as $key => $value) {
  $fragment = new rex_fragment();
  $fragment->setVar('name', 'rex_socialhub[facebook][page][]', false);
  $fragment->setVar('value', $value, false);
  $fragment->setVar('label', rex_i18n::msg('rex_socialhub_facebook_page').' '.($key+1).'.)', false);
  $fragment->addDirectory($this->getAddon()->getPath());
  $content .= $fragment->parse('form/input.php');
}


$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('slice_ui_general'));
$fragment->setVar('body', $content, false);
$sections .= $fragment->parse('core/page/section.php');
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

?>
<form action="<?php echo rex_url::currentBackendPage();?>" method="post">
  <fieldset>
    <?php if($message) echo rex_view::success($message);?>
    <?php echo $sections;?>
  </fieldset>
</form>