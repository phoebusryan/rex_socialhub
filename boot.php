<?php
	$pageConfig = [
		'title' => $this->i18n('title'),
		'perm' => 'rex_socialhub[]',
		'icon' => 'rex-icon fa-share-alt',
		'subpages'=> []
	];
	
	if(rex_addon::get('assets')->isAvailable()) {
	  rex_extension::register('BE_ASSETS',function($ep) {
	    $Subject = $ep->getSubject()?$ep->getSubject():[];
	    $Subject[$this->getPackageId()] = [
	      'files' => [
	        $this->getPath('assets/socialhub.css'),
	        $this->getPath('assets/socialhub.js'),
	      ],
	      'addon' => $this->getPackageId(),
	    ];
	    return $Subject;
	  });
	} elseif(rex::isBackend()) {
	  rex_view::addCssFile($this->getAssetsUrl('socialhub.cssmin.min.css'));
	  rex_view::addJsFile($this->getAssetsUrl('socialhub.jsmin.min.js'));
	}

	$pageConfig['subpages']['main'] = ['title' => $this->i18n('main')];
	$pageConfig['subpages']['hashtags'] = ['title' => $this->i18n('hashtags')];
	
	$plugins = rex_plugin::getRegisteredPlugins('rex_socialhub');
	foreach ($plugins as $pluginName => $plugin) {
		$pageConfig['subpages'][$pluginName] = ['title' => $this->i18n($pluginName)];
	}

	$pageConfig['subpages']['entries'] = ['title' => $this->i18n('hastag_entries')];
	
	$this->setProperty('page', $pageConfig);
?>