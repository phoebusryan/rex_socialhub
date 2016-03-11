<?php
	$RSHI = rex_socialhub_twitter::factory();
	
	if (rex_post('btn_save', 'string') != '') {
		$pValues = rex_post('rex_socialhub', [
			['twitter', 'array'],
		]);
		
		foreach($pValues['twitter']['accounts'] as $key => $values) {
			$pValues['twitter']['accounts'][$key] = array_filter($values);
		}
		
		$pValues['twitter']['accounts'] = array_filter($pValues['twitter']['accounts']);
		
		$this->setConfig($pValues);
		$message = $this->i18n('config_saved_successfull');
	}
	
	$content = '';
	$sections = '';
	
	$config = $this->getConfig('twitter');
	
	if (empty($config['accounts'])) {
		$config = ['accounts'=>['']];
	} else {
		$config['accounts'][] = '';
	}
	
	foreach($config['accounts'] as $key => $value) {
		$fragment = new rex_fragment();
		$fragment->setVar('name', 'rex_socialhub[twitter][accounts]['.$key.'][consumer_token]', false);
		$fragment->setVar('value', (isset($value['consumer_token']) ? $value['consumer_token'] : ''), false);
		$fragment->setVar('label', $this->i18n('consumer_token').' '.($key+1).':', false);
		$fragment->addDirectory($this->getAddon()->getPath());
		$content .= $fragment->parse('form/input.php');
		
		$fragment = new rex_fragment();
		$fragment->setVar('name', 'rex_socialhub[twitter][accounts]['.$key.'][consumer_secret_token]', false);
		$fragment->setVar('value', (isset($value['consumer_secret_token']) ? $value['consumer_secret_token'] : ''), false);
		$fragment->setVar('label', $this->i18n('secret_token').' '.($key+1).':', false);
		$fragment->addDirectory($this->getAddon()->getPath());
		$content .= $fragment->parse('form/input.php');
		
		$fragment = new rex_fragment();
		$fragment->setVar('name', 'rex_socialhub[twitter][accounts]['.$key.'][access_token]', false);
		$fragment->setVar('value', (isset($value['access_token']) ? $value['access_token'] : ''), false);
		$fragment->setVar('label', $this->i18n('access_token').' '.($key+1).':', false);
		$fragment->addDirectory($this->getAddon()->getPath());
		$content .= $fragment->parse('form/input.php');
		
		$fragment = new rex_fragment();
		$fragment->setVar('name', 'rex_socialhub[twitter][accounts]['.$key.'][secret_token]', false);
		$fragment->setVar('value', (isset($value['secret_token']) ? $value['secret_token'] : ''), false);
		$fragment->setVar('label', $this->i18n('secret_token').' '.($key+1).':', false);
		$fragment->addDirectory($this->getAddon()->getPath());
		$content .= $fragment->parse('form/input.php');
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $this->i18n('accounts'));
		$fragment->setVar('body', $content, false);
		$sections .= $fragment->parse('core/page/section.php');
		$content = '';
	}
	
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
		<?php
			if(isset($message)) {
				echo rex_view::success($message);
			}
			
			echo $sections;
		?>
	</fieldset>
</form>