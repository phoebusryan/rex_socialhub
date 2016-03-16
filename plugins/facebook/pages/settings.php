<?php
	$func = rex_request('func', 'string');
	
	if ($func == '') {
		//Start - generate list for accounts
			$list = rex_list::factory("SELECT `id`, `title` FROM `".rex::getTablePrefix()."socialhub_facebook_account` ORDER BY `title` ASC");
			$list->addTableAttribute('class', 'table-striped');
			$list->setNoRowsMessage($this->i18n('facebook_settings_accounts_norowsmessage'));
			
			// icon column
			$thIcon = '<a href="'.$list->getUrl(['func' => 'addAccount']).'"><i class="rex-icon rex-icon-add-action"></i></a>';
			$tdIcon = '<i class="rex-icon fa-user"></i>';
			$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
			$list->setColumnParams($thIcon, ['func' => 'editAccount', 'id' => '###id###']);
			
			$list->setColumnLabel('title', $this->i18n('facebook_settings_accounts_column_title'));
			
			// functions column spans 2 data-columns
			$funcs = $this->i18n('facebook_settings_accounts_column_functions');
			
			$list->addColumn($funcs, '<i class="rex-icon rex-icon-edit"></i> '.rex_i18n::msg('edit'), -1, ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
			$list->setColumnParams($funcs, ['id' => '###id###', 'func' => 'editAccount']);
			
			$delete = 'deleteCol';
			$list->addColumn($delete, '<i class="rex-icon rex-icon-delete"></i> '.rex_i18n::msg('delete'), -1, ['', '<td class="rex-table-action">###VALUE###</td>']);
			$list->setColumnParams($delete, ['id' => '###id###', 'func' => 'deleteAccount']);
			$list->addLinkAttribute($delete, 'data-confirm', rex_i18n::msg('delete').' ?');
			
			$list->removeColumn('id');
			
			$content = $list->get();
			
			//Start - output list via fragment
				$fragment = new rex_fragment();
				$fragment->setVar('class', 'info', false);
				$fragment->setVar('title',  $this->i18n('facebook_settings_accounts'), false);
				$fragment->setVar('content', $content, false);
				echo $fragment->parse('core/page/section.php');
			//End - output list via fragment
		//End - generate list for accounts
		
		//Start - generate list for timelines
			$list = rex_list::factory("SELECT `id`, `title` FROM `".rex::getTablePrefix()."socialhub_facebook_timeline` ORDER BY `title` ASC");
			$list->addTableAttribute('class', 'table-striped');
			$list->setNoRowsMessage($this->i18n('facebook_settings_timelines_norowsmessage'));
			
			// icon column
			$thIcon = '<a href="'.$list->getUrl(['func' => 'addTimeline']).'"><i class="rex-icon rex-icon-add-action"></i></a>';
			$tdIcon = '<i class="rex-icon fa-clock-o"></i>';
			$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
			$list->setColumnParams($thIcon, ['func' => 'editTimeline', 'id' => '###id###']);
			
			$list->setColumnLabel('title', $this->i18n('facebook_settings_timelines_column_title'));
			
			// functions column spans 2 data-columns
			$funcs = $this->i18n('facebook_settings_timelines_column_functions');
			
			$list->addColumn($funcs, '<i class="rex-icon rex-icon-edit"></i> '.rex_i18n::msg('edit'), -1, ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
			$list->setColumnParams($funcs, ['id' => '###id###', 'func' => 'editTimeline']);
			
			$delete = 'deleteCol';
			$list->addColumn($delete, '<i class="rex-icon rex-icon-delete"></i> '.rex_i18n::msg('delete'), -1, ['', '<td class="rex-table-action">###VALUE###</td>']);
			$list->setColumnParams($delete, ['id' => '###id###', 'func' => 'deleteTimeline']);
			$list->addLinkAttribute($delete, 'data-confirm', rex_i18n::msg('delete').' ?');
			
			$list->removeColumn('id');
			
			$content = $list->get();
			
			//Start - output list via fragment
				$fragment = new rex_fragment();
				$fragment->setVar('class', 'info', false);
				$fragment->setVar('title',  $this->i18n('facebook_settings_timelines'), false);
				$fragment->setVar('content', $content, false);
				echo $fragment->parse('core/page/section.php');
			//End - output list via fragment
		//End - generate list for timelines
	} else if ($func == 'addAccount' || $func == 'editAccount') {
		$id = rex_request('id', 'int');
		
		if ($func == 'editAccount') {
			$formLabel = $this->i18n('facebook_settings_accounts_formcaption_edit');
		} elseif ($func == 'addAccount') {
			$formLabel = $this->i18n('facebook_settings_accounts_formcaption_add');
		}
		
		$form = rex_form::factory(rex::getTablePrefix().'socialhub_facebook_account', '', 'id='.$id);
		
		//Start - add title-field
			$field = $form->addTextField('title');
			$field->setLabel($this->i18n('facebook_settings_accounts_label_title'));
		//End - add title-field
		
		//Start - add app_id-field
			$field = $form->addTextField('app_id');
			$field->setLabel($this->i18n('facebook_settings_accounts_label_app_id'));
		//End - add app_id-field
		
		//Start - add app_secret-field
			$field = $form->addTextField('consumer_secret_token');
			$field->setLabel($this->i18n('facebook_settings_accounts_label_app_secret'));
		//End - add app_secret-field
		
		if ($func == 'editAccount') {
			$form->addParam('id', $id);
		}
		
		$content = $form->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $formLabel, false);
		$fragment->setVar('body', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	} else if ($func == 'deleteAccount') {
		$id = rex_request('id', 'int');
		
		$del = rex_sql::factory();
		$del->setQuery('DELETE FROM ' . rex::getTablePrefix() . 'socialhub_facebook_account WHERE `id` = "'.$id.'"');
		echo 'Account wurde gelöscht'; //todo: translate
	} else if ($func == 'addTimeline' || $func == 'editTimeline') {
		$id = rex_request('id', 'int');
		
		if ($func == 'editTimeline') {
			$formLabel = $this->i18n('facebook_settings_timelines_formcaption_edit');
		} elseif ($func == 'addTimeline') {
			$formLabel = $this->i18n('facebook_settings_timelines_formcaption_add');
		}
		
		$form = rex_form::factory(rex::getTablePrefix().'socialhub_facebook_timeline', '', 'id='.$id);
		
		//Start - add title-field
			$field = $form->addTextField('title');
			$field->setLabel($this->i18n('facebook_settings_timelines_label_title'));
		//End - add title-field
		
		//Start - add user_id-field
			$field = $form->addTextField('user_id');
			$field->setLabel($this->i18n('facebook_settings_timelines_label_user_id'));
		//End - add user_id-field
		
		if ($func == 'editTimeline') {
			$form->addParam('id', $id);
		}
		
		$content = $form->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $formLabel, false);
		$fragment->setVar('body', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	} else if ($func == 'deleteTimeline') {
		$id = rex_request('id', 'int');
		
		$del = rex_sql::factory();
		$del->setQuery('DELETE FROM ' . rex::getTablePrefix() . 'socialhub_facebook_timeline WHERE `id` = "'.$id.'"');
		echo 'Timeline wurde gelöscht'; //todo: translate
	}
?>