<?php
	$func = rex_request('func', 'string');
	
	if ($func == '') {
		$list = rex_list::factory("SELECT `id`, `title` FROM `".rex::getTablePrefix()."socialhub_twitter_account` ORDER BY `title` ASC");
		$list->addTableAttribute('class', 'table-striped');
		$list->setNoRowsMessage($this->i18n('account_norowsmessage'));
		
		// icon column
		$thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'" title="'.$this->i18n('column_hashtag').' '.rex_i18n::msg('add').'"><i class="rex-icon rex-icon-add-action"></i></a>';
		$tdIcon = '<i class="rex-icon fa-twitter"></i>';
		$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
		$list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);
		
		$list->setColumnLabel('title', $this->i18n('twitter_account_column_title'));
		
		// functions column spans 2 data-columns
		$funcs = $this->i18n('twitter_account_column_functions');
		
		$list->addColumn($funcs, '<i class="rex-icon rex-icon-edit"></i> '.rex_i18n::msg('edit'), -1, ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams($funcs, ['id' => '###id###', 'func' => 'edit']);
		
		$delete = 'deleteCol';
		$list->addColumn($delete, '<i class="rex-icon rex-icon-delete"></i> '.rex_i18n::msg('delete'), -1, ['', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams($delete, ['id' => '###id###', 'func' => 'delete']);
		$list->addLinkAttribute($delete, 'data-confirm', rex_i18n::msg('delete').' ?');
		
		$list->removeColumn('id');
		
		$content = $list->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('content', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	} else if ($func == 'add' || $func == 'edit') {
		$id = rex_request('id', 'int');
		
		if ($func == 'edit') {
			$formLabel = $this->i18n('twitter_account_formcaption_edit');
		} elseif ($func == 'add') {
			$formLabel = $this->i18n('twitter_account_formcaption_add');
		}
		
		$form = rex_form::factory(rex::getTablePrefix().'socialhub_twitter_account', '', 'id='.$id);
		
		//Start - add title-field
			$field = $form->addTextField('title');
			$field->setLabel($this->i18n('twitter_account_label_title'));
		//End - add title-field
		
		//Start - add consumer_token-field
			$field = $form->addTextField('consumer_token');
			$field->setLabel($this->i18n('twitter_account_label_consumer_token'));
		//End - add consumer_token-field
		
		//Start - add consumer_secret_token-field
			$field = $form->addTextField('consumer_secret_token');
			$field->setLabel($this->i18n('twitter_account_label_consumer_secret_token'));
		//End - add consumer_secret_token-field
		
		//Start - add access_token-field
			$field = $form->addTextField('access_token');
			$field->setLabel($this->i18n('twitter_account_label_access_token'));
		//End - add access_token-field
		
		//Start - add secret_token-field
			$field = $form->addTextField('secret_token');
			$field->setLabel($this->i18n('twitter_account_label_secret_token'));
		//End - add secret_token-field
		
		if ($func == 'edit') {
			$form->addParam('id', $id);
		}
		
		$content = $form->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('class', 'edit', false);
		$fragment->setVar('title', $formLabel, false);
		$fragment->setVar('body', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	} else if ($func == 'delete') {
		$id = rex_request('id', 'int');
		
		$del = rex_sql::factory();
		$del->setQuery('DELETE FROM ' . rex::getTablePrefix() . 'socialhub_twitter_account WHERE `id` = "'.$id.'"');
		echo 'Account wurde gelöscht'; //todo: translate
	}
?>