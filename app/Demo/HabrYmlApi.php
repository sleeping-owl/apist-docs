<?php namespace Demo;

use SleepingOwl\Apist\Yaml\YamlApist;

class HabrYmlApi extends YamlApist
{

	function __construct($options = [])
	{
		parent::__construct(app_path('Demo/Api/habr.yml'), $options);
	}

	public function modifyTitle($title)
	{
		return 'Modified Title: ' . $title;
	}

	public function getPostTitle($node)
	{
		return 'My: ' . $node->filter('.title a')->text();
	}

}