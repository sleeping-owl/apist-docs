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
		<p>Extend <code>SleepingOwl\Apist\Apist</code> class and provide base url:</p>
		<pre><code class="language-php">use SleepingOwl\Apist\Apist;

class HabrApi extends Apist
{
  protected $baseUrl = 'http://habrahabr.ru';
}</code></pre>

		<p>Write any api method you want:</p>
		<pre><code class="language-php">public function index()
{
  return $this->get('/', [
    'title' => Apist::filter('.page_head')->exists()->then(
      Apist::filter('.page_head .title')->text()->trim()
    )->else(
      'Title not found'
    ),
    'posts' => Apist::filter('.posts .post')->each([
      'title'      => Apist::filter('h1.title a')->text(),
      'link'       => Apist::filter('h1.title a')->attr('href'),
      'hubs'       => Apist::filter('.hubs a')->each(Apist::filter('*')->text()),
      'views'      => Apist::filter('.pageviews')->intval(),
      'favs_count' => Apist::filter('.favs_count')->intval(),
      'content'    => Apist::filter('.content')->html(),
      'author'     => [
        'username'     => Apist::filter('.author a'),
        'profile_link' => Apist::filter('.author a')->attr('href'),
        'rating'       => Apist::filter('.author .rating')->text()
      ]
    ]),
  ]);
}</code></pre>
		<p>Now you can use this api method:</p>
		<pre><code class="language-php">$api = new HabrApi;
$result = $api->index();</code></pre>

		<p>Result will be:</p>
		<pre class="example"><code class="language-json" data-call="index"></code></pre>


		<div class="page-header">
			<div id="documentation" class="fix-navbar-fixed"></div>
			<h2>Documentation</h2>
		</div>
		<p>You can use following methods in your api functions to get data:</p>
		<ul>
			<li>get</li>
			<li>head</li>
			<li>post</li>
			<li>patch</li>
			<li>put</li>
			<li>delete</li>
		</ul>
		<p>Each method represents http method type that will by used in query.</p>
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
		<h4>Blueprint</h4>
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
			<li><code>->each($blueprint)</code> &mdash; replaces itself with array,
				$blueprint will be parsed within current element<br/>
				<i>Note: your css selectors within $blueprint will be applied to current element, you don't have to write full selectors</i></li>
			<li>You can use simple string methods:
				<code>trim</code>, <code>strtoupper</code>, <code>strtolower</code>,
				<code>mb_strtoupper</code>, <code>mb_strtolower</code>,
				<code>intval</code>, <code>floatval</code> or your own functions in root namespace</li>
			<li>You can chain methods: <code>Apist::filter('.title')->first()->text()->mb_strtoupper()->trim()</code></li>
		</ul>
		<h5>Blueprint Conditionals</h5>
		<p>You can use conditionals in your blueprints:</p>
		<pre><code class="language-php">Apist::filter('.page-header')->exists()->then(
  Apist::filter('.page-header .title')->text() // This value will be used if .page-header element was found
)->else(
  null // This value will be used if .page-header element doesn't exist in html response
)</code></pre>
		<p><code>then()</code> and <code>else()</code> methods accept blueprint as argument. You can provide array or single item as you want.</p>
		<p>Using conditionals you can make your api result fully customizable.</p>
		<h5>Blueprint Examples</h5>
		<pre><code class="language-php">public function index()
{
  return $this->get('/', [
    'link'           => Apist::filter('.title_block .title a')->attr('href'), // Get href from link
    'title'          => Apist::filter('.title')->text()->trim()->mb_strtoupper(), // Get title text, trim and convert to uppercase
    'second_element' => Apist::filter('.navbar li')->eq(3)->filter('a')->first()->text(), // You can write this with one css selector or use chain methods
    'custom_css'     => Apist::filter('.navbar li:nth-child(4) a:first-child')->text(), // Same as previous value, remember that eq() starts from zero, but :nth-child() starts from one
    'items'          => Apist::filter('.navbar li')->each([ // Create array with blueprint for every item
      'title' => Apist::filter('a')->text(),
      'link' => Apist::filter('a')->attr('href')
    ]),
    'items_flat'     => Apist::filter('.navbar li')->each(Apist::filter('a')->text()), // Create flat array without keys
    'custom_fields'  => [ // You can use any array structure you want
      'first'  => 'static field', // and use static field values
      'second' => Apist::filter('.title')->text(), // combine them with computed as you want
      'third'  => [
        'my',
        'custom',
        'array',
        Apist::filter('.title')->text()
      ],
      'fourth' => $this->customMethod() // You can use computed values as well
    ]
  ]);
}</code></pre>

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
