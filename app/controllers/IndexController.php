<?php

class IndexController extends \Controller
{

	protected $languages = [
		'en' => 'English',
		'ru' => 'Russian'
	];

	protected $syntaxes = [
		'ruby' => 'Ruby',
		'php' => 'PHP'
	];

	protected function getFromApi($api, $method, $parameter = null)
	{
		$lifetime = 10;

		$cacheKey = get_class($api) . $method . $parameter;

		$result = Cache::get($cacheKey);
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
		$reflection = new ReflectionMethod('\Demo\WikiApi', $method);
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

	protected function make($lang, $view, $syntax = null)
	{
		$langLabel = $this->languages[$lang];
		$syntaxLabel = is_null($syntax) ? null : $this->syntaxes[$syntax];
		$route = $view;
		if (in_array($view, ['index', 'documentation']))
		{
			$view = $syntax . '.' . $view;
		}
		return View::make($lang . '.' . $view, compact('lang', 'langLabel', 'route', 'syntax', 'syntaxLabel'));
	}

	public function getIndex($lang, $syntax)
	{
		return $this->make($lang, 'index', $syntax);
	}

	public function getDocumentation($lang, $syntax)
	{
		return $this->make($lang, 'documentation', $syntax);
	}

	public function getApiCall($method)
	{
		if ($args = $this->isYamlMethod($method))
		{
			$method = $args['method'];
			$wiki = new \Demo\WikiYmlApi;
		} else
		{
			if ( ! method_exists('\Demo\WikiApi', $method)) $method = 'index';
			$wiki = new \Demo\WikiApi;
		}
		$parameter = ($method === 'search') ? 'php' : null;
		$data = $this->getFromApi($wiki, $method, $parameter);
		return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	}

	public function getSource($method)
	{
		if ($args = $this->isYamlMethod($method))
		{
			return file_get_contents(app_path('Demo/Api/wiki.yml'));
		} elseif ($this->isRubyMethod($method))
		{
			return file_get_contents(app_path('Demo/ruby/basic.rb'));
		} else
		{
			if ( ! method_exists('\Demo\WikiApi', $method)) $method = 'index';
			return $this->getMethodSource($method);
		}
	}

	protected function isRubyMethod($method)
	{
		return strpos($method, 'ruby.') === 0;
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

	public function getChoose($lang = 'en')
	{
		return $this->make($lang, 'choose')->with('syntaxes', $this->syntaxes);
	}

}
