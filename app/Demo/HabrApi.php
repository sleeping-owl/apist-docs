<?php namespace Demo;

use SleepingOwl\Apist\Apist;

class HabrApi extends Apist
{

	protected $baseUrl = 'http://habrahabr.ru';

	public function index()
	{
		return $this->get('/', [
			'title' => Apist::filter('.page_head')->exists()->then(Apist::filter('.page_head .title')->text()->trim())->else('Title not found'),
			'posts' => Apist::filter('.posts .post')->each([
				'title'      => Apist::filter('h1.title a')->text(),
				'link'       => Apist::filter('h1.title a')->attr('href'),
				'hubs'       => Apist::filter('.hubs a')->each(Apist::filter('*')->text()),
				'views'      => Apist::filter('.pageviews')->intval(),
				'favs_count' => Apist::filter('.favs_count')->intval(),
				'content'    => Apist::filter('.content')->html(),
				'author'     => [
					'username'     => Apist::filter('.author a'),
					'profile_link' => Apist::filter('.author a')->attr('href'),
					'rating'       => Apist::filter('.author .rating')->text()
				]
			]),
		]);
	}

	public function get404()
	{
		return $this->get('/unknown-page', 'this-will-be-ignored');
	}

	public function live_broadcasts()
	{
		return $this->get('/', [
			'title' => Apist::filter('.live_broadcast .title')->text(),
			'items' => Apist::filter('.live_broadcast .post_item')->each([
				'title' => Apist::filter('a'),
				'count' => Apist::filter('.count'),
				'link'  => Apist::filter('a')->attr('href')
			])
		]);
	}

	public function first_live_broadcast()
	{
		$broadcasts = $this->live_broadcasts();
		if (isset($broadcasts['error']))
		{
			return $broadcasts;
		}
		$url = $broadcasts['items'][0]['link'];
		return $this->getPost($url);
	}

	public function getPost($url)
	{
		return $this->get($url, [
			'title'   => Apist::filter('.post .post_title'),
			'hubs'    => Apist::filter('.post .hubs a')->each([
				'title' => Apist::filter('*')->text(),
				'link'  => Apist::filter('*')->attr('href')
			]),
			'tags'    => Apist::filter('.post .tags li')->each(Apist::filter('a')->text()),
			'content' => Apist::filter('.post .content')->html(),
			'info'    => [
				'views'     => Apist::filter('.pageviews'),
				'fav_count' => Apist::filter('.favs_count'),
				'author'    => [
					'username' => Apist::filter('.post .author a')->text(),
					'profile'  => Apist::filter('.post .author a')->attr('href'),
					'rating'   => Apist::filter('.post .author .rating')
				]
			],
			'similar' => Apist::filter('.similar_posts .post_item')->each([
				'title'     => Apist::filter('a'),
				'link'      => Apist::filter('a')->attr('href'),
				'posted_at' => Apist::filter('.when')
			])
		]);
	}

	public function users()
	{
		return $this->get('/users', [
			'title' => 'Пользователи',
			'users' => Apist::filter('.users .user')->each([
				'avatar'   => Apist::filter('.avatar img')->attr('src'),
				'username' => Apist::filter('.info .username'),
				'lifetime' => Apist::filter('.lifetime'),
				'rating'   => Apist::filter('.rating')->text()->trim(),
				'karma'    => Apist::filter('.karma')->text()->trim()
			])
		]);
	}

	public function search($query)
	{
		return $this->get('/search', [
			'query'              => $query,
			'publications_count' => Apist::filter('.menu .item:nth-child(1) span')->text()->intval(),
			'hubs_count'         => Apist::filter('.menu .item:nth-child(2) span')->text()->intval(),
			'users_count'        => Apist::filter('.menu .item:nth-child(3) span')->text()->intval(),
			'comments_count'     => Apist::filter('.menu .item:nth-child(4) span')->text()->intval(),
			'posts'              => Apist::filter('.post')->each([
				'title'        => Apist::filter('.title a')->text(),
				'link'         => Apist::filter('.title a')->attr('href'),
				'published_at' => Apist::filter('.published')->text(),
				'content'      => Apist::filter('.content')->html()
			])
		], [
			'query' => ['q' => $query]
		]);
	}

}