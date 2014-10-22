<?php namespace Demo;

use SleepingOwl\Apist\Apist;

class AdminApi extends Apist
{

	protected $baseUrl = 'http://sleeping-owl-admin.my';

	protected $username = 'admin';
	protected $password = 'SleepingOwl';

	public function getLoginToken()
	{
		return $this->get('/admin/login', Apist::filter('input[name="_token"]')->attr('value'));
	}

	public function login()
	{
		$token = $this->getLoginToken();
		return $this->post('/admin/login', Apist::filter('.page-header')->html(), [
			'body' => [
				'_token'   => $token,
				'username' => $this->username,
				'password' => $this->password
			]
		]);
	}

	public function contacts()
	{
		$this->login();
		return $this->get('/admin/contacts', [
			'entries' => Apist::filter('.table tbody tr')->each([
				'photo'     => Apist::filter('td:first-child img')->attr('src'),
				'name'      => Apist::filter('td')->eq(1)->text(),
				'birthday'  => Apist::filter('.column-date')->attr('data-order'),
				'country'   => Apist::filter('td')->eq(3)->text(),
				'companies' => Apist::filter('td:nth-child(5) li')->each(
					Apist::filter('*')->text()
				)
			])
		]);
	}

} 