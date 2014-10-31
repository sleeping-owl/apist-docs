<?php

class IndexController extends \Controller
{

	protected $languages = [
		'en' => 'English',
		'ru' => 'Russian'
	];

	protected function getFromApi($api, $method, $parameter = null)
	{
		$lifetime = 10;

		$cacheKey = get_class($api) . $method . $parameter;

		$result = Cache::get($cacheKey);
		$result = null;
		if (is_null($result))
		{
			if (is_null($parameter))
			{
				$result = $api->$method();
			} else
			{
				$result = $api->$method($parameter);
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

	protected function make($lang, $view)
	{
		$langLabel = $this->languages[$lang];
		$route = $view;
		return View::make($lang . '.' . $view, compact('lang', 'langLabel', 'route'));
	}

	public function getIndex($lang = 'en')
	{
		return $this->make($lang, 'index');
	}

	public function getDocumentation($lang = 'en')
	{
		return $this->make($lang, 'documentation');
	}

	public function getApiCall($method)
	{
		if ($args = $this->isYamlMethod($method))
		{
			$method = $args['method'];
			$habr = new \Demo\HabrYmlApi;
		} else
		{
			if ( ! method_exists('\Demo\HabrApi', $method)) $method = 'index';
			$habr = new \Demo\HabrApi;
		}
		$parameter = ($method === 'search') ? 'php' : null;
		$data = $this->getFromApi($habr, $method, $parameter);
		return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function getSource($method)
	{
		if ($args = $this->isYamlMethod($method))
		{
			return file_get_contents(app_path('Demo/Api/habr.yml'));
		} else
		{
			if ( ! method_exists('\Demo\HabrApi', $method)) $method = 'index';
			return $this->getMethodSource($method);
		}
	}

	/**
	 * @param $method
	 * @return int
	 */
	protected function isYamlMethod($method)
	{
		if (preg_match('/^yaml\.(?<method>.+)$/', $method, $args))
		{
			return $args;
		}
		return null;
	}

}
