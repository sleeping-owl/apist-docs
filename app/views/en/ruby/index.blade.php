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
	<p>Add this line to your application's Gemfile:</p>
	<pre><code class="language-ruby">gem 'apist'</code></pre>
	<p>And then execute:</p>
	<pre><code>$ bundle</code></pre>
	<p>Or install it yourself as:</p>
	<pre><code>$ gem install apist</code></pre>

	<div class="page-header">
		<div id="usage" class="fix-navbar-fixed"></div>
		<h2>Usage</h2>
	</div>
	<p>Require <code>'apist'</code>, extend <code>Apist</code> class and provide base url:</p>
	<pre><code class="language-ruby">require 'apist'

class WikiApi < Apist
  base_url 'http://en.wikipedia.org'
end</code></pre>

	<p>Write any api method you want:</p>
	<pre><code class="language-ruby" data-source="ruby.index"></code></pre>
	<p>Now you can use this api method:</p>
	<pre><code class="language-php">api = WikiApi.new
result = api.index</code></pre>

	<p>Result will be (<i>json format used only for visualization, actual result type is <code>Hash</code></i>):</p>
	<pre class="example"><code class="language-json" data-call="index"></code></pre>

	<div class="page-header">
		<div id="examples" class="fix-navbar-fixed"></div>
		<h2>Examples</h2>
	</div>

	@include('example', [
		'title' => 'Wikipedia Main Page',
		'method' => 'index',
		'source' => 'ruby.index',
		'type' => 'ruby'
	])
	@include('example', [
		'title' => 'Wikipedia Current Events',
		'method' => 'current_events',
		'source' => 'ruby.index',
		'type' => 'ruby'
	])
@stop