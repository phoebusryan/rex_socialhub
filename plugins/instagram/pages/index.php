<?php
	$RSHI = rex_socialhub_instagram::factory();
	
	if (rex_post('btn_save', 'string') != '') {
		$pValues = rex_post('rex_socialhub', [
			['instagram', 'array'],
		]);

		$pValues['instagram']['access_tokens'] = array_filter($pValues['instagram']['access_tokens']);
		$pValues['instagram']['access_tokens'] = array_unique($pValues['instagram']['access_tokens']);
		if(!empty($pValues['instagram']['access_tokens'])) {
			$pValues['instagram']['accounts'] = $RSHI->getAccountData($pValues['instagram']['access_tokens']);
			$pValues['instagram']['access_token'] = $pValues['instagram']['access_tokens'][0];
		} else {
			$pValues['instagram']['accounts'] = [];
			$pValues['instagram']['access_token'] = 0;
		}
	
		$this->setConfig($pValues);
		$message = $this->i18n('config_saved_successfull');
	}
	
	$content = '';
	$sections = '';
	
	$config = $this->getConfig('instagram');
	
	$fragment = new rex_fragment();
	$fragment->setVar('name', 'socialhub[instagram][client_id]', false);
	$fragment->setVar('value', (isset($config['client_id']) ? $config['client_id'] : ''), false);
	$fragment->setVar('label', $this->i18n('client_id').':', false);
	$fragment->addDirectory($this->getAddon()->getPath());
	$content .= $fragment->parse('form/input.php');
	
	$content .= '<br><br><h3 class="rex-form-aligned">Access-Tokens</h3><p class="rex-form-aligned">Access-Tokens benötigt es nur um Einträge aus bestimmten Instagram-Accounts auszugeben.</p><br>'; //todo: translate
	
	if(empty($Values['access_tokens'])) {
		$config = ['access_tokens'=>['']];
	} else {
		$config['access_tokens'][] = '';
	}
	
	foreach ($config['access_tokens'] as $key => $value) {
		$fragment = new rex_fragment();
		$fragment->setVar('name', 'rex_socialhub[instagram][access_tokens][]', false);
		$fragment->setVar('value', $value, false);
		$fragment->setVar('label', $this->i18n('access_token').' '.($key+1).':', false);
		$fragment->addDirectory($this->getAddon()->getPath());
		$content .= $fragment->parse('form/input.php');
		
		if (!empty($Values['accounts'][$value])) {
			$content .= '<div class="rex-form-aligned"><ul style="list-style-type: none; padding:0;"><li>Name: <a href="http://www.instagram.com/'.$Values['accounts'][$value]['username'].'" target="_blank" title="Instagramprofil von '.ucfirst($Values['accounts'][$value]['username']).'">'.ucfirst($Values['accounts'][$value]['username']).'</a></li><li>ID: '.$Values['accounts'][$value]['id'].'</li></div><br>';
		}
	}
	
	$content .= '<p class="rex-form-aligned">Hier den persönlichen Access Token generieren: <a href="http://instagram.pixelunion.net/" target="_blank" title="Access Token Generator">http://instagram.pixelunion.net/</a></p>';
	
	$fragment = new rex_fragment();
	$fragment->setVar('class', 'edit', false);
	$fragment->setVar('title', $this->i18n('accounts'));
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
		<?php
			if(isset($message)) {
				echo rex_view::success($message);
			}
			
			echo $sections;
		?>
	</fieldset>
</form>