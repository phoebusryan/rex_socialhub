<?php
	
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

	$page = $this->getProperty('page');

	$Hashtags = false;
	$Plugins = $this->getAvailablePlugins();
	foreach($Plugins as $pluginName => $data) {
		if($data->hasProperty('hashtags'))
			$Hashtags = true;
		$page['subpages'][$pluginName] = ['title' => $this->i18n($pluginName)];
	}
	if(!$Hashtags)
		unset($page['subpages']['hashtags']);

	$this->setProperty('page', $page);
?>