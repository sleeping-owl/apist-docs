@extends('_layout')

@section('content')
	<div class="jumbotron">
		<p class="lead">
			<b>SleepingOwl Apist</b> &mdash; небольшая библиотека, позволяющая вам получить данные со сторонних сайтов в API-стиле, основанном на парсинге html.
		</p>
	</div>

	<div class="page-header">
		<div id="overview" class="fix-navbar-fixed"></div>
		<h2>Обзор</h2>
	</div>
	<p>
		Библиотека упрощает процесс создания api для доступа к данным на сторонних сайтах, основанном на парсинге html.
		Для использования вам необходимо создать класс (олицетворяющий апи стороннего сайта) и описать все необходимые api методы и правила получения контента.
		Чтобы увидеть библиотеку в действии смотрите <a href="#examples">примеры</a>.
	</p>

	<div class="page-header">
		<div id="installation" class="fix-navbar-fixed"></div>
		<h2>Установка</h2>
	</div>
	<p>Подключите этот пакет в вашем composer.json и выполните <code>composer update</code> (или выполните <code>composer require sleeping-owl/apist:1.x</code> в терминале):</p>
	<code>"sleeping-owl/apist": "1.*"</code>

	<div class="page-header">
		<div id="usage" class="fix-navbar-fixed"></div>
		<h2>Использование</h2>
	</div>
	<p>Создайте свой класс, расширяющий <code>SleepingOwl\Apist\Apist</code> и создайте метод <code>getBaseUrl()</code>:</p>
	<pre><code class="language-php">use SleepingOwl\Apist\Apist;

class WikiApi extends Apist
{
  public function getBaseUrl()
  {
    return 'http://en.wikipedia.org';
  }
}</code></pre>
	<p>или переопределите поле <code>$baseUrl</code>:</p>
	<pre><code class="language-php">use SleepingOwl\Apist\Apist;

class WikiApi extends Apist
{
  protected $baseUrl = 'http://en.wikipedia.org';
}</code></pre>

	<p>Опишите нужные вам api-методы:</p>
	<pre><code class="language-php" data-source="index"></code></pre>
	<p>Теперь вы можете их использовать:</p>
	<pre><code class="language-php">$api = new WikiApi;
$result = $api->index();</code></pre>

	<p>Полученный результат будет:</p>
	<pre class="example"><code class="language-json" data-call="index"></code></pre>

	<div class="page-header">
		<div id="examples" class="fix-navbar-fixed"></div>
		<h2>Примеры</h2>
	</div>

	@include('example', [
		'title' => 'Главная страница Википедии',
		'method' => 'index'
	])
	@include('example', [
		'title' => 'Текущие события Википедии',
		'method' => 'current_events'
	])
@stop