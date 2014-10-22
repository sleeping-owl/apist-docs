<?php

class HomeController extends BaseController
{

	protected function getFromApi($method)
	{
		$lifetime = 10;
		$habr = new \Demo\HabrApi;

		$cacheKey = 'habr.' . $method;

		$result = Cache::get($cacheKey);
		if (is_null($result))
		{
			$result = $habr->$method();
			Cache::put($cacheKey, $result, $lifetime);
		}
		return $result;
	}

	protected function getMethodSource($method)
	{
		$reflection = new ReflectionMethod('\Demo\HabrApi', $method);
		$filename = $reflection->getFileName();
		$start = $reflection->getStartLine();
		$end = $reflection->getEndLine();
		$lines = file($filename);
		$lines = array_splice($lines, $start - 1, $end - $start + 1);
		$lines = array_map(function ($line)
		{
			$line = preg_replace('/^\t/', '', $line);
			$line = str_replace("\t", '  ', $line);
			$line = str_replace("\n", '', $line);
			$line = str_replace("\r", '', $line);
			return $line;
		}, $lines);
		return implode("\n", $lines);
	}

	public function showWelcome()
	{
		$index = $this->getFromApi('index');
		$indexSource = $this->getMethodSource('index');

		$live_broadcasts = $this->getFromApi('live_broadcasts');
		$live_broadcastsSource = $this->getMethodSource('live_broadcasts');

		$first_live_broadcast = $this->getFromApi('first_live_broadcast');
		$first_live_broadcastSource = $this->getMethodSource('first_live_broadcast') . "\n\n" . $this->getMethodSource('getPost');

		$users = $this->getFromApi('users');
		$usersSource = $this->getMethodSource('users');

		return View::make('index', compact('index', 'indexSource', 'live_broadcasts', 'live_broadcastsSource', 'first_live_broadcast', 'first_live_broadcastSource', 'users', 'usersSource'));
	}

}
