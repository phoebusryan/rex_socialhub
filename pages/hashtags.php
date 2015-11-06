<?php
	$func = rex_request('func', 'string');
	$id = rex_request('id', 'int');
	
	if ($func == '') {
		$list = rex_list::factory("SELECT `id`, `hashtag` FROM `".rex::getTablePrefix()."socialhub_hashtags` ORDER BY `hashtag` ASC");
		$list->addTableAttribute('class', 'table-striped');
		
		$list->setColumnLabel('hashtag', $this->i18n('column_hashtag'));
		
		// icon column
		$thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'" title="'.$this->i18n('column_hashtag').' '.rex_i18n::msg('add').'"><i class="rex-icon rex-icon-add-action"></i></a>';
		$tdIcon = '<i class="rex-icon fa-share-alt"></i>';
		$list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
		$list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);
		
		// functions column spans 2 data-columns
		$funcs = rex_i18n::msg('rex_socialhub_functions');
		
		$list->addColumn($funcs, '<i class="rex-icon rex-icon-edit"></i> '.rex_i18n::msg('edit'), -1, ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams($funcs, ['id' => '###id###', 'func' => 'edit']);
		
		$delete = 'deleteCol';
		$list->addColumn($delete, '<i class="rex-icon rex-icon-delete"></i> '.rex_i18n::msg('delete'), -1, ['', '<td class="rex-table-action">###VALUE###</td>']);
		$list->setColumnParams($delete, ['id' => '###id###', 'func' => 'delete']);
		$list->addLinkAttribute($delete, 'data-confirm', rex_i18n::msg('delete').' ?');
		
		$list->removeColumn('id');
		
		$content = $list->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('title', $this->i18n('hashtags_caption'), false);
		$fragment->setVar('content', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	} else if ($func == 'add' || $func == 'edit') {
		if ($func == 'edit') {
			$formLabel = $this->i18n('column_hashtag').' '.rex_i18n::msg('edit');
		} elseif ($func == 'add') {
			$formLabel = $this->i18n('column_hashtag').' '.rex_i18n::msg('add');
		}
		
		$form = rex_form::factory(rex::getTablePrefix().'socialhub_hashtags', '', 'id='.$id);
		
		$field = $form->addTextField('hashtag');
		$field->setLabel($this->i18n('label_name'));
		
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
	}
?>