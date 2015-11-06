<?php
	$func = rex_request('func', 'string');
	
	if ($func != '') {
		$id = rex_request('id', 'int');
		
		switch ($func) {
			case 'set_online':
				$sql = rex_sql::factory();
				$sql->setQuery("UPDATE `".rex::getTablePrefix()."socialhub_entries` SET `visible` = 1 WHERE id = ?", [$id]);
			break;
			case 'set_offline':
				$sql = rex_sql::factory();
				$sql->setQuery("UPDATE `".rex::getTablePrefix()."socialhub_entries` SET `visible` = 0  WHERE id = ?", [$id]);
			break;
		}
		
		echo rex_view::success('Status wurde aktualisiert!'); //todo
	}
	
	//Start - filter form
		//Start - get postvars
			$visible = rex_post('visible', 'string');
			$source = rex_post('source', 'string');
			$hashtag = rex_post('hashtag', 'string');
			$keyword = rex_post('keyword', 'string');
		//End - get postvars
		
		$content = '';
		
		$content .= '<form action="' . rex_url::currentBackendPage() . '" method="post">';
		$content .= '	<fieldset>';
		
		$formElements = [];
		
		//Start - add source field
			$n = [];
			$n['label'] = '<label for="rex-form-source">' . $this->i18n('label_source') . '</label>';
			
			$select = new rex_select();
			$select->setId('rex-form-source');
			$select->setName('source');
			$select->setSelected($source);
			$select->setSize(1);
			$select->setAttribute('class', 'form-control');
			$select->addOption($this->i18n('all'), 'all');
			
			$plugins = rex_plugin::getRegisteredPlugins('rex_socialhub');
			foreach ($plugins as $pluginName => $plugin) {
				$select->addOption($this->i18n($pluginName), $pluginName);
			}
			
			$n['field'] = $select->get();
			$formElements[] = $n;
		//End - add source field
		
		//Start - add visible field
			$n = [];
			$n['label'] = '<label for="rex-form-visible">' . $this->i18n('label_visible') . '</label>';
			
			$select = new rex_select();
			$select->setId('rex-form-visible');
			$select->setName('visible');
			$select->setSelected($visible);
			$select->setSize(1);
			$select->setAttribute('class', 'form-control');
			$select->addOption($this->i18n('all'), 'all');
			$select->addOption(rex_i18n::msg('no'), '0');
			$select->addOption(rex_i18n::msg('yes'), '1');
			
			
			$n['field'] = $select->get();
			$formElements[] = $n;
		//End - add visible field
		
		//Start - add hashtag field
			$n = [];
			$n['label'] = '<label for="rex-form-hashtag">' . $this->i18n('label_hashtag') . '</label>';
			
			$select = new rex_select();
			$select->setId('rex-form-hashtag');
			$select->setName('hashtag');
			$select->setSelected($hashtag);
			$select->setSize(1);
			$select->setAttribute('class', 'form-control');
			$select->addOption($this->i18n('all'), 'all');
			
			//Start - get hashtags from the database
				$sql = rex_sql::factory();
				$entries = $sql->setQuery("SELECT `hashtag` FROM `".rex::getTablePrefix()."socialhub_hashtags` ORDER BY `hashtag` ASC")->getArray();
			//End - get hashtags from the database
			
			foreach ($entries as $entry) {
				$select->addOption($entry['hashtag'], $entry['hashtag']);
			}
			
			$n['field'] = $select->get();
			$formElements[] = $n;
		//End - add hashtag field
		
		//Start - add keyword field
			$n = [];
			$n['label'] = '<label for="rex-form-keyword">' . $this->i18n('label_keyword') . '</label>';
			$n['field'] = '<input class="form-control" id="rex-form-keyword" type="text" name="keyword" value="' . $keyword . '" />';
			$formElements[] = $n;
		//End - add keyword field
		
		$fragment = new rex_fragment();
		$fragment->setVar('elements', $formElements, false);
		$content .= $fragment->parse('core/form/form.php');
		
		$content .= '</fieldset>';
		
		$formElements = [];
		$n = [];
		$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="filter" value="' . $this->i18n('button_filter') . '">' . $this->i18n('button_filter') . '</button>';
		$formElements[] = $n;
		
		$fragment = new rex_fragment();
		$fragment->setVar('elements', $formElements, false);
		$buttons = $fragment->parse('core/form/submit.php');
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', 'Liste einschränken', false); //todo
		$fragment->setVar('body', $content, false);
		$fragment->setVar('buttons', $buttons, false);
		$content = $fragment->parse('core/page/section.php');
		
		$content .= '</form>';
		
		echo $content;
	//End - filter form
	
	//////////////////
	
	//Start - generate where-condition
		$whereCondition = '';
		
		if ($source != '' && $source != 'all') {
			$whereCondition .= '`source` = "'.addslashes($source).'" AND ';
		}
		
		if ($visible != '' && $visible != 'all') {
			$whereCondition .= '`visible` = "'.addslashes($visible).'" AND ';
		}
		
		if ($hashtag != '' && $hashtag != 'all') {
			$whereCondition .= '`query` = "#'.addslashes($hashtag).'" AND ';
		}
		
		if ($keyword != '' && $keyword != '#') {
			$whereCondition .= '`caption` LIKE \'%'.addslashes($keyword).'%\' AND ';
		}
		
		$whereCondition = substr($whereCondition, 0, -4);
	//End - generate where-condition
	
	$list = rex_list::factory("SELECT `id`, `source`, `caption`, `query`,`visible` FROM `".rex::getTablePrefix()."socialhub_entries` ".(($whereCondition != '') ? 'WHERE '.$whereCondition : '')." ORDER BY `created_time` DESC");
	$list->addTableAttribute('class', 'table-striped');
	
	$list->setColumnLabel('source', '');
	$list->setColumnFormat('source', 'custom', function ($params) {
		return '<i class="fa fa-'.$params['value'].'"></i>';
	});
	
	$list->setColumnLabel('caption', $this->i18n('column_caption'));
	$list->setColumnFormat('caption', 'custom', function ($params) {
		$params['value'] = nl2br(urldecode($params['value']));
		$params['value'] = str_replace('#', ' #',$params['value']);
		$params['value'] = preg_replace('/ +/', ' ', $params['value']);
		$params['value'] = stripslashes($params['value']);
		
		return $params['value'];
	});
	
	$list->setColumnLabel('query', $this->i18n('column_query'));
	
	$list->setColumnLabel('visible', $this->i18n('label_status'));
	$list->setColumnFormat('visible', 'custom', function ($params) {
		if ($params['subject'] == '0') {
			return '<a class="rex-offline" href="'.rex_url::currentBackendPage().'&func=set_online&id=###id###" style="white-space:nowrap"><i class="rex-icon rex-icon-offline"></i> offline</a>';
		} else if ($params['subject'] == '1') {
			return '<a class="rex-online" href="'.rex_url::currentBackendPage().'&func=set_offline&id=###id###" style="white-space:nowrap"><i class="rex-icon rex-icon-online"></i> online</a>';
		}
	});
	
	$list->removeColumn('id');
	
	$content = $list->get();
	
	$fragment = new rex_fragment();
	$fragment->setVar('title', $this->i18n('rex_socialhub_entries_caption'), false);
	$fragment->setVar('content', $content, false);
	$content = $fragment->parse('core/page/section.php');
	
	echo $content;
?>