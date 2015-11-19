<?php
	$pageConfig = [
		'title' => $this->i18n('title'),
		'perm' => 'rex_socialhub[]',
		'icon' => 'rex-icon fa-share-alt',
		'subpages'=> []
	];
	
	if(rex::isBackend()) {
	  rex_view::addCssFile($this->getAssetsUrl('socialhub.css'));
	  rex_view::addJsFile($this->getAssetsUrl('socialhub.js'));
	}

	$pageConfig['subpages']['main'] = ['title' => $this->i18n('main')];
	$pageConfig['subpages']['hashtags'] = ['title' => $this->i18n('hashtags')];
	
	$plugins = rex_plugin::getRegisteredPlugins('rex_socialhub');
	foreach ($plugins as $pluginName => $plugin) {
		$pageConfig['subpages'][$pluginName] = ['title' => $this->i18n($pluginName)];
	}

	$pageConfig['subpages']['entries'] = ['title' => $this->i18n('entries')];
	
	$this->setProperty('page', $pageConfig);
?>