<?php

class IndexController extends \Controller
{

	protected function getFromApi($method, $parameter = null)
	{
		$lifetime = 10;
		$habr = new \Demo\HabrApi;

		$cacheKey = 'habr.' . $method . $parameter;

		$result = Cache::get($cacheKey);
		if (is_null($result))
		{
			if (is_null($parameter))
			{
				$result = $habr->$method();
			} else
			{
				$result = $habr->$method($parameter);
			}
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

	public function getIndex()
	{
		return View::make('index');
	}

	public function getApiCall($method)
	{
		if ( ! method_exists('\Demo\HabrApi', $method)) $method = 'index';
		$parameter = ($method === 'search') ? 'php' : null;
		$data = $this->getFromApi($method, $parameter);
		return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function getSource($method)
	{
		if ( ! method_exists('\Demo\HabrApi', $method)) $method = 'index';
		return $this->getMethodSource($method);
	}

}
