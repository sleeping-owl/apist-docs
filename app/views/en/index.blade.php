@extends('_layout')

@section('content')
	<div class="jumbotron">
		<p class="lead">
			<b>SleepingOwl Apist</b> is a small library which allows you to access any site in api-like style, based on html parsing.
		</p>
	</div>

	<div class="page-header">
		<div id="overview" class="fix-navbar-fixed"></div>
		<h2>Overview</h2>
	</div>
	<p>
		The library provides simple way to create api to access foreign site data, based on html parsing.
		All you need to do is to create class and write all api methods you want with html parse rules.
		To see it in action see <a href="#examples">examples</a>.
	</p>

	<div class="page-header">
		<div id="installation" class="fix-navbar-fixed"></div>
		<h2>Installation</h2>
	</div>
	<p>Require this package in your composer.json and run <code>composer update</code> (or run <code>composer require sleeping-owl/apist:1.x</code> directly):</p>
	<code>"sleeping-owl/apist": "1.*"</code>

	<div class="page-header">
		<div id="usage" class="fix-navbar-fixed"></div>
		<h2>Usage</h2>
	</div>
	<p>Extend <code>SleepingOwl\Apist\Apist</code> class and override <code>getBaseUrl()</code> method:</p>
	<pre><code class="language-php">use SleepingOwl\Apist\Apist;

class WikiApi extends Apist
{
  public function getBaseUrl()
  {
    return 'http://en.wikipedia.org';
  }
}</code></pre>
	<p>or override <code>$baseUrl</code> field:</p>
	<pre><code class="language-php">use SleepingOwl\Apist\Apist;

class WikiApi extends Apist
{
  protected $baseUrl = 'http://en.wikipedia.org';
}</code></pre>

	<p>Write any api method you want:</p>
	<pre><code class="language-php" data-source="index"></code></pre>
	<p>Now you can use this api method:</p>
	<pre><code class="language-php">$api = new WikiApi;
$result = $api->index();</code></pre>

	<p>Result will be:</p>
	<pre class="example"><code class="language-json" data-call="index"></code></pre>

	<div class="page-header">
		<div id="examples" class="fix-navbar-fixed"></div>
		<h2>Examples</h2>
	</div>

	@include('example', [
		'title' => 'Wikipedia Main Page',
		'method' => 'index'
	])
	@include('example', [
		'title' => 'Wikipedia Current Events',
		'method' => 'current_events'
	])
@stop