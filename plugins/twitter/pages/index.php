<?php

$message = '';

$RSHI = rex_socialhub_twitter::factory();

$Values = $this->getConfig('twitter');

if(rex_post('btn_save', 'string') != '') {

  $pValues = rex_post('rex_socialhub', [
    ['twitter', 'array'],
  ]);

  $pValues['twitter']['access_tokens'] = array_filter($pValues['twitter']['access_tokens']);
  $pValues['twitter']['access_tokens'] = array_unique($pValues['twitter']['access_tokens']);
  if(!empty($pValues['twitter']['access_tokens'])) {
    $pValues['twitter']['accounts'] = $RSHI->getAccountData($pValues['twitter']['access_tokens']);
    $pValues['twitter']['access_token'] = $pValues['twitter']['access_tokens'][0];
  } else {
    $pValues['twitter']['accounts'] = [];
    $pValues['twitter']['access_token'] = 0;
  }
    

  $this->setConfig($pValues);
  $message = $this->i18n('config_saved_successfull');
}

$content = $sections = '';

$Values = $this->getConfig('twitter');
// print_r($Values);

if(empty($Values['access_tokens']))
  $Values = ['access_tokens'=>['']];
else $Values['access_tokens'][] = '';

foreach($Values['access_tokens'] as $key => $value) {
  $fragment = new rex_fragment();
  $fragment->setVar('name', 'rex_socialhub[twitter][access_tokens][]', false);
  $fragment->setVar('value', $value, false);
  $fragment->setVar('label', rex_i18n::msg('rex_socialhub_twitter_access_token').' '.($key+1).':', false);
  $fragment->addDirectory($this->getAddon()->getPath());
  $content .= $fragment->parse('form/input.php');
}

$content .= '<p class="rex-form-aligned">Hier den persönlichen Access Token generieren: <a href="http://twitter.pixelunion.net/" target="_blank" title="Access Token Generator">http://twitter.pixelunion.net/</a></p>';

$fragment = new rex_fragment();
$fragment->setVar('class', 'edit', false);
$fragment->setVar('title', rex_i18n::msg('rex_socialhub_accounts'));
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