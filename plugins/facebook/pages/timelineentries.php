<?php
	$func = rex_request('func', 'string');
	
	if ($func == '') {
		$list = rex_list::factory("SELECT `id`, `message` FROM `".rex::getTablePrefix()."socialhub_entry_timeline` WHERE `source` = 'facebook' ORDER BY `id` DESC");
		$list->addTableAttribute('class', 'table-striped');
		$list->setNoRowsMessage($this->i18n('entries_norowsmessage'));
		
		// icon column
		$list->addColumn('&nbsp;', '<i class="rex-icon fa-facebook"></i>', 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
		
		$list->setColumnLabel('message', $this->i18n('entries_column_caption'));
		$list->setColumnFormat('message', 'custom', function ($params) {
			return urldecode($params['subject']);
		});
		
		$funcs = $this->i18n('entries_column_status');
		
		$list->addColumn($funcs, '<i class="rex-icon rex-icon-online"></i> online', -1, ['<th class="rex-table-action" colspan="2">###VALUE###</th>', '<td class="rex-table-action">###VALUE###</td>']);
		
		$list->setColumnParams($funcs, ['id' => '###id###', 'func' => 'toggleVisibility']);
		
		$list->removeColumn('id');
		
		$content = $list->get();
		
		$fragment = new rex_fragment();
		$fragment->setVar('content', $content, false);
		$content = $fragment->parse('core/page/section.php');
		
		echo $content;
	}
?>