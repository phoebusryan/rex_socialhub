<?php
	if(rex_post('save', 'string') != '') {
		foreach (['twitter_consumer_token', 'twitter_consumer_secret_token', 'twitter_access_token', 'twitter_secret_token'] as $field) {
			$this->setConfig($field, rex_post($field, 'string'));
		}
		
		echo rex_view::success($this->i18n('config_saved'));
	}
	
	echo rex_view::info($this->i18n('twitter_infotext'));
	
	$config = rex_config::get('rex_socialhub');
	
	//Start - configuration form
		$content = '';
		
		$content .= '<form action="' . rex_url::currentBackendPage() . '" method="post">';
		$content .= '	<fieldset>';
		
		$formElements = [];
		
		//Start - add consumerToken field
			$n = [];
			$n['label'] = '<label for="rex-form-consumer_token">' . 'Consumer Token' . '</label>';
			$n['field'] = '<input class="form-control" id="rex-form-consumer_token" type="text" name="twitter_consumer_token" value="' . $config['twitter_consumer_token'] . '" />';
			$formElements[] = $n;
		//End - add consumerToken field
		
		//Start - add consumerSecretToken field
			$n = [];
			$n['label'] = '<label for="rex-form-consumer_secret_token">' . 'Consumer Secret Token' . '</label>';
			$n['field'] = '<input class="form-control" id="rex-form-consumer_secret_token" type="text" name="twitter_consumer_secret_token" value="' . $config['twitter_consumer_secret_token'] . '" />';
			$formElements[] = $n;
		//End - add consumerSecretToken field
		
		//Start - add accessToken field
			$n = [];
			$n['label'] = '<label for="rex-form-access_token">' . 'Access Token' . '</label>';
			$n['field'] = '<input class="form-control" id="rex-form-access_token" type="text" name="twitter_access_token" value="' . $config['twitter_access_token'] . '" />';
			$formElements[] = $n;
		//End - add accessToken field
		
		//Start - add secretToken field
			$n = [];
			$n['label'] = '<label for="rex-form-secret_token">' . 'Secret Token' . '</label>';
			$n['field'] = '<input class="form-control" id="rex-form-secret_token" type="text" name="twitter_secret_token" value="' . $config['twitter_secret_token'] . '" />';
			$formElements[] = $n;
		//End - add secretToken field
		
		$fragment = new rex_fragment();
		$fragment->setVar('elements', $formElements, false);
		$content .= $fragment->parse('core/form/form.php');
		
		$content .= '</fieldset>';
	
		$formElements = [];
		$n = [];
		$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $this->i18n('button_save') . '">' . $this->i18n('button_save'). '</button>';
		$formElements[] = $n;
		
		$fragment = new rex_fragment();
		$fragment->setVar('elements', $formElements, false);
		$buttons = $fragment->parse('core/form/submit.php');
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $this->i18n('twitter_caption'), false);
		$fragment->setVar('body', $content, false);
		$fragment->setVar('buttons', $buttons, false);
		$content = $fragment->parse('core/page/section.php');
		
		$content .= '</form>';
		
		echo $content;
	//End - configuration form
?>