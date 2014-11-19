<?php namespace Demo;

use App;
use SleepingOwl\Apist\Apist;
use SleepingOwl\Apist\DomCrawler\Crawler;

class WikiApi extends Apist
{

	public function getBaseUrl()
	{
		return 'http://en.wikipedia.org';
	}

	public function index()
	{
		return $this->get('/wiki/Main_Page', [
			'welcome_message'  => Apist::filter('#mp-topbanner div:first')->text()->mb_substr(0, -1),
			'portals'          => Apist::filter('a[title^="Portal:"]')->each([
				'link'  => Apist::current()->attr('href')->call(function ($href)
				{
					return $this->getBaseUrl() . $href;
				}),
				'label' => Apist::current()->text()
			]),
			'languages'        => Apist::filter('#p-lang li a[title]')->each([
				'label' => Apist::current()->text(),
				'lang'  => Apist::current()->attr('title'),
				'link'  => Apist::current()->attr('href')->call(function ($href)
				{
					return 'http:' . $href;
				})
			]),
			'sister_projects'  => Apist::filter('#mp-sister b a')->each()->text(),
			'featured_article' => Apist::filter('#mp-tfa')->html()
		]);
	}

	public function current_events()
	{
		return $this->get('/wiki/Portal:Current_events', Apist::filter('#mw-content-text > table:last td:first table.vevent')->each([
			'date'   => Apist::filter('.bday')->text(),
			'events' => Apist::filter('dl')->each()->text()
		]));
	}

	public function get404()
	{
		return $this->get('/unknown-page', 'this-will-be-ignored');
	}

}