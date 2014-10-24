<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SleepingOwl Apist</title>
	{{ HTML::style('css/bootstrap.min.css') }}
	{{ HTML::style('css/main.css') }}
	{{ HTML::style('css/daux-blue.css') }}
	{{ HTML::style('css/font-awesome.min.css') }}
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<a id="top" class="navbar-brand navbar-brand-active" href="/">SleepingOwl
					Apist</a>
			</div>
			<div class="collapse navbar-collapse navbar-bb-collapse">
				<ul class="nav navbar-nav">
					<li><a href="#overview">Overview</a></li>
					<li><a href="#installation">Installation</a></li>
					<li><a href="#usage">Usage</a></li>
					<li><a href="#documentation">Documentation</a></li>
					<li><a href="#examples">Examples</a></li>
					<li><a href="https://github.com/sleeping-owl/apist"><i class="fa fa-github"></i> GitHub</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
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

class HabrApi extends Apist
{
  public function getBaseUrl()
  {
    return 'http://habrahabr.ru';
  }
}</code></pre>
		<p>or override <code>$baseUrl</code> field:</p>
		<pre><code class="language-php">use SleepingOwl\Apist\Apist;

class HabrApi extends Apist
{
  protected $baseUrl = 'http://habrahabr.ru';
}</code></pre>

		<p>Write any api method you want:</p>
		<pre><code class="language-php" data-source="index"></code></pre>
		<p>Now you can use this api method:</p>
		<pre><code class="language-php">$api = new HabrApi;
$result = $api->index();</code></pre>

		<p>Result will be:</p>
		<pre class="example"><code class="language-json" data-call="index"></code></pre>


		<div class="page-header">
			<div id="documentation" class="fix-navbar-fixed"></div>
			<h2>Documentation</h2>
		</div>
		<h3>Http-requests</h3>
		<p>You can use following methods in your api functions to get data via http-request:</p>
		<ul>
			<li><code>get</code></li>
			<li><code>head</code></li>
			<li><code>post</code></li>
			<li><code>patch</code></li>
			<li><code>put</code></li>
			<li><code>delete</code></li>
		</ul>
		<p><small>(note: each method represents http method type that will by used in query)</small></p>
		<p>Accepts 3 parameters:</p>
		<ol>
			<li><strong>$url</strong> &mdash; url to use in query (relative to base url or absolute)</li>
			<li><strong>$blueprint</strong> &mdash; array or single object parsing rules</li>
			<li><strong>$options</strong> <small>(optional)</small> &mdash; any additional options to use in query.</li>
		</ol>
		<h4>Providing Get and Post parameters</h4>
		<p>You can provide get parameters to the query in optional third <code>$options</code> parameter:</p>
		<pre><code class="language-php">$this->get('/', ..., [
  'query' => [
    'parameter1' => 'value1',
    'parameter2' => 'value2',
  ]
])</code></pre>
		<p>Also you can provide post parameters to the query:</p>
		<pre><code class="language-php">$this->post('/', ..., [
  'body' => [
    'parameter1' => 'value1',
    'parameter2' => 'value2',
  ]
])</code></pre>
		<p>Also you can specify headers and request configuration. For full documentation visit <a href="http://guzzle.readthedocs.org/en/latest/clients.html#request-options">Guzzle docs</a>.</p>
		<h4>Error Handling</h4>
		<p>If there was an error during request your response data will look like:</p>
		<pre><code class="language-json" data-call="get404"></code></pre>
		<h3>Using without http-requests</h3>
		<p>You can use <code>parse($content, $blueprint)</code> method to parse content by blueprint without any http-requests.</p>

		<h3>Blueprint</h3>
		<p>Blueprint represents structure you want to get from api call.
			It can be array or single <code>Apist::filter()</code> object.
			To insert value from query result use <code>Apist::filter($cssSelector)</code> method.
			It will search and replace itself with required element from html.</p>
		<p>You can specify additional data you want to get from element:</p>
		<ul>
			<li><code>->text()</code> &mdash; get text content from element</li>
			<li><code>->html()</code> &mdash; get html content from element</li>
			<li><code>->attr($attributeName)</code> &mdash; get attribute value from element</li>
			<li><code>->eq($position)</code> &mdash; get element with $position from all elements matches css selector</li>
			<li><code>->first()</code> &mdash; get first element from all elements matches css selector</li>
			<li><code>->last()</code> &mdash; get last element from all elements matches css selector</li>
			<li><code>->element()</code> &mdash; get element as <code>Symfony\Component\DomCrawler\Crawler</code> object</li>
			<li><code>->call($callback)</code> &mdash; use custom callback to modify element</li>
			<li><code>->each($blueprint)</code> &mdash; replaces itself with array,
				$blueprint will be parsed within current element<br/>
				<i>Note: your css selectors within $blueprint will be applied to current element, you don't have to write full selectors</i></li>
			<li><code>->each($callback)</code> &mdash; replaces itself with array,
				$callback will be called with ($node, $index) for every node in list.
				Callback result will be used as array value.</li>
			<li>You can use any method from your api class</li>
			<li>You can use simple string methods:
				<code>trim</code>, <code>strtoupper</code>, <code>strtolower</code>,
				<code>mb_strtoupper</code>, <code>mb_strtolower</code>,
				<code>intval</code>, <code>floatval</code> or your own functions in root namespace</li>
			<li>You can chain methods: <code>Apist::filter('.title')->first()->text()->mb_strtoupper()->trim()</code></li>
		</ul>
		<h4>Blueprint Conditionals</h4>
		<p>You can use conditionals in your blueprints:</p>
		<pre><code class="language-php">Apist::filter('.page-header')->exists()->then(
  Apist::filter('.page-header .title')->text() // This value will be used if .page-header element was found
)->else(
  null // This value will be used if .page-header element doesn't exist in html response
)</code></pre>
		<p>or use <code>check($callback)</code> method with custom callback:</p>
		<pre><code class="language-php">Apist::filter('.page-header')->check(function ($node)
{
  return $node->text() === 'My Title';
})->then(...)->else(...)</code></pre>
		<p><code>then()</code> and <code>else()</code> methods accept blueprint as argument. You can provide array or single item as you want.</p>
		<p>Using conditionals you can make your api result fully customizable.</p>
		<h4>Blueprint Filter Examples</h4>

		<h5>Get href from link</h5>
		<pre><code class="language-php">Apist::filter('.title_block .title a')->attr('href')</code></pre>

		<h5>Get title text, trim and convert to uppercase</h5>
		<pre><code class="language-php">Apist::filter('.title')->text()->trim()->mb_strtoupper()</code></pre>

		<h5>Get title and use custom method from your api class</h5>
		<pre><code class="language-php"># declaration in api class
public function myFunc($subject, $from, $to)
{
  return str_replace($from, $to, $subject);
}

# usage in your blueprint
Apist::filter('.title')->text()->myFunc('find', 'replace')</code></pre>

		<h5>You can write one css selector or use chain methods</h5>
		<pre><code class="language-php">Apist::filter('.navbar li')->eq(3)->filter('a')->first()->text()</code></pre>

		<h5>Same as previous filter <small>(remember that eq() starts from zero, but :nth-child() starts from one)</small></h5>
		<pre><code class="language-php">Apist::filter('.navbar li:nth-child(4) a:first-child')->text()</code></pre>

		<h5>Create array with blueprint for every item</h5>
		<pre><code class="language-php">Apist::filter('.navbar li')->each([
  'title' => Apist::filter('a')->text(),
  'link' => Apist::filter('a')->attr('href')
])</code></pre>

		<h5>Create flat array without keys</h5>
		<pre><code class="language-php">Apist::filter('.navbar li')->each(Apist::filter('a')->text())</code></pre>

		<h5>You can use any array structure you want</h5>
		<pre><code class="language-php">[
  'first'  => 'static field', // and use static field values
  'second' => Apist::filter('.title')->text(), // combine them with computed as you want
  'third'  => [
	'my',
	'custom',
	'array',
	Apist::filter('.title')->text()
  ]
]</code></pre>

		<div class="page-header">
			<div id="examples" class="fix-navbar-fixed"></div>
			<h2>Examples</h2>
		</div>

		@include('example', [
			'title' => 'Habrahabr index',
			'method' => 'index'
		])
		@include('example', [
			'title' => 'Habrahabr live broadcasts',
			'method' => 'live_broadcasts'
		])
		@include('example', [
			'title' => 'Habrahabr first live broadcast post',
			'method' => 'first_live_broadcast'
		])
		@include('example', [
			'title' => 'Habrahabr users',
			'method' => 'users'
		])
		@include('example', [
			'title' => 'Habrahabr search: "php"',
			'method' => 'search'
		])
		@include('example', [
			'title' => 'Parse local file without http-requests',
			'method' => 'parse_local_file'
		])
	</div>

	<div class="well container footer text-right">
		&copy; 2014{{ (date('Y') != 2014) ? '&mdash;' . date('Y') : '' }} <a href="mailto:owl.sleeping@yahoo.com">Sleeping Owl</a>
	</div>

	{{ HTML::script('js/jquery-1.11.0.js') }}
	{{ HTML::script('js/highlight.min.js') }}
	{{ HTML::script('js/main.js') }}
	<script>hljs.initHighlightingOnLoad();</script>
</body>
</html>
