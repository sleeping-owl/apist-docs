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
	<p>Добавьте эту строчку в Gemfile вашего приложения:</p>
	<pre><code class="language-ruby">gem 'apist'</code></pre>
	<p>И выполните:</p>
	<pre><code>$ bundle</code></pre>
	<p>Или установите самостоятельно:</p>
	<pre><code>$ gem install apist</code></pre>

	<div class="page-header">
		<div id="usage" class="fix-navbar-fixed"></div>
		<h2>Использование</h2>
	</div>
	<p>Добавьте <code>require 'apist'</code>, создайте свой класс, расширяющий <code>Apist</code> и укажите базовый адрес:</p>
	<pre><code class="language-ruby">require 'apist'

class WikiApi < Apist
  base_url 'http://en.wikipedia.org'
end</code></pre>

	<p>Опишите нужные вам api-методы:</p>
	<pre><code class="language-ruby" data-source="ruby.index"></code></pre>
	<p>Теперь вы можете их использовать:</p>
	<pre><code class="language-php">api = WikiApi.new
result = api.index</code></pre>

	<p>Полученный результат будет (<i>json формат здесь использован для удобства, результат будет типа <code>Hash</code></i>):</p>
	<pre class="example"><code class="language-json" data-call="index"></code></pre>

	<div class="page-header">
		<div id="examples" class="fix-navbar-fixed"></div>
		<h2>Примеры</h2>
	</div>

	@include('example', [
		'title' => 'Главная страница Википедии',
		'method' => 'index',
		'source' => 'ruby.index',
		'type' => 'ruby'
	])
	@include('example', [
		'title' => 'Текущие события Википедии',
		'method' => 'current_events',
		'source' => 'ruby.index',
		'type' => 'ruby'
	])
@stop