<?php
  $func = rex_request('func', 'string');

  if($func !== '') {
    $gc = rex_sql::factory();
    $gc->setQuery('SELECT DISTINCT(' . rex::getTablePrefix() . 'article.id) FROM ' . rex::getTablePrefix() . 'article
        LEFT JOIN ' . rex::getTablePrefix() . 'article_slice ON ' . rex::getTablePrefix() . 'article.id=' . rex::getTablePrefix() . 'article_slice.article_id');
    for ($i = 0; $i < $gc->getRows(); ++$i) {
      rex_article_cache::delete($gc->getValue(rex::getTablePrefix() . 'article.id'));
      $gc->next();
    }
  }
  
  if ($func == '') {
    $list = rex_list::factory("SELECT `id`, `title`, `short`, `icon`, `link`, `url_parameter` FROM `".rex::getTablePrefix()."socialhub_share` ORDER BY `title` ASC");
    $list->addTableAttribute('class', 'table-striped');
    $list->setNoRowsMessage($this->i18n('share_norowsmessage'));
    
    // icon column
    $thIcon = '<a href="'.$list->getUrl(['func' => 'add']).'" title="'.$this->i18n('share_title').' '.rex_i18n::msg('add').'"><i class="rex-icon rex-icon-add-action"></i></a>';
    $tdIcon = '<i class="rex-icon fa-file-text-o"></i>';
    $list->addColumn($thIcon, $tdIcon, 0, ['<th class="rex-table-icon">###VALUE###</th>', '<td class="rex-table-icon">###VALUE###</td>']);
    $list->setColumnParams($thIcon, ['func' => 'edit', 'id' => '###id###']);
      
    $list->setColumnFormat($thIcon,
      'custom',
      function($params) use ($list) {
        $Icon = $params['list']->getValue('icon');
        if(substr($Icon,0,3) === 'fa-')
          $params['subject'] = str_replace('fa-file-text-o',$Icon,$params['subject']);

        $params['subject'] = '<a href="'.$list->getUrl(['func' => 'edit','id'=>$params['list']->getValue('id')]).'" title="'.$this->i18n('share_title').' '.rex_i18n::msg('edit').'">'.$params['subject'];
        $params['subject'] .= '</a>';
        return $params['subject'];
      }
    );

    $list->setColumnFormat('title',
      'custom',
      function($params) use ($list) {
        $params['subject'] = '<a href="'.$list->getUrl(['func' => 'edit','id'=>$params['list']->getValue('id')]).'" title="'.$this->i18n('share_title').' '.rex_i18n::msg('edit').'">'.$params['subject'];
        $params['subject'] .= '</a>';
        return $params['subject'];
      }
    );

    $list->setColumnFormat('link',
      'custom',
      function($params) use ($list) {
        $params['subject'] = '<a href="'.$params['list']->getValue('link').'" target="_blank" title="'.rex_i18n::msg('share_open_plattform').'">'.$params['subject'].'</a>';
        return $params['subject'];
      }
    );

    $list->setColumnLabel('title', $this->i18n('share_column_title'));
    $list->removeColumn('icon');
    $list->removeColumn('url_parameter');
    $list->setColumnLabel('link', $this->i18n('share_column_link'));
    
    // functions column spans 2 data-columns
    $funcs = $this->i18n('share_column_functions');
    
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
      $formLabel = $this->i18n('share_formcaption_edit');
    } elseif ($func == 'add') {
      $formLabel = $this->i18n('share_formcaption_add');
    }
    
    $form = rex_form::factory(rex::getTablePrefix().'socialhub_share', '', 'id='.$id);
    
    //Start - add title-field
      $field = $form->addTextField('title');
      $field->setLabel($this->i18n('share_label_title'));
    //End - add title-field

    //Start - add short-field
      $field = $form->addTextField('short');
      $field->setLabel($this->i18n('share_label_short'));
    //End - add short-field
    
    //Start - add icon-field
      $field = $form->addTextField('icon');
      $Icon = $form->getSql()->getValue('icon');
      if(substr($Icon,0,3) === 'fa-' && $func == 'edit')
        $Icon = '[<i class="fa '.$Icon.'"></i>] ';
      else $Icon = '';
      $field->setLabel($Icon.rex_i18n::rawMsg('share_label_icon',false));
    //End - add icon-field
    
    //Start - add link-field
      $field = $form->addTextField('link');
      $field->setLabel($this->i18n('share_label_link'));
    //End - add link-field
    
    //Start - add link-field
      $field = $form->addTextAreaField('url_parameter');
      $field->setLabel($this->i18n('share_label_parameter'));
    //End - add link-field
    
    
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
    $del->setQuery('DELETE FROM ' . rex::getTablePrefix() . 'socialhub_share WHERE `id` = "'.$id.'"');
    echo rex_i18n::msg('share_deleted');
  }
?>