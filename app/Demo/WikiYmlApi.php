<?php namespace Demo;

use SleepingOwl\Apist\Yaml\YamlApist;

class WikiYmlApi extends YamlApist
{

	function __construct($options = [])
	{
		parent::__construct(app_path('Demo/Api/wiki.yml'), $options);
	}

	public function portalLink($href)
	{
		return $this->getBaseUrl() . $href;
	}

	public function prependHttp($href)
	{
		return 'http:' . $href;
	}

}