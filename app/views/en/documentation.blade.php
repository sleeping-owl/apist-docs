@extends('_layout')

@section('content')
	<div class="page-header"><h2>Table of Contents</h2></div>
	<p>
		<ul>
			<li><a href="#http-requests">With http-requests</a></li>
			<li><a href="#without-http-requests">Without http-requests</a></li>
			<li><a href="#php-blueprint">PHP Blueprint</a></li>
			<li><a href="#yaml-configuration">Yaml Configuration</a></li>
		</ul>
	</p>

	<div class="page-header">
		<div id="http-requests" class="fix-navbar-fixed"></div>
		<h2>Http-requests</h2>
	</div>
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
	<h3>Providing Get and Post parameters</h3>
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
	<h3>Error Handling</h3>
	<p>If there was an error during request your response data will look like:</p>
	<pre><code class="language-json" data-call="get404"></code></pre>

	<div class="page-header">
		<div id="without-http-requests" class="fix-navbar-fixed"></div>
		<h2>Without http-requests</h2>
	</div>
	<p>You can use <code>parse($content, $blueprint)</code> method to parse content by blueprint without any http-requests.</p>

	<div class="page-header">
		<div id="php-blueprint" class="fix-navbar-fixed"></div>
		<h2>PHP Blueprint</h2>
	</div>
	<p><strong>Example:</strong> for full-feature demo api class source see <a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/WikiApi.php">WikiApi.php</a>.</p>
	<p>Blueprint represents structure you want to get from api call.
		It can be array or single <code>Apist::filter()</code> object.
		To insert value from query result use <code>Apist::filter($cssSelector)</code> method.
		It will search and replace itself with required element from html.</p>
	<p>If you need to grab current node element you can use <code>Apist::current()</code>.</p>
	<p>You can specify additional data you want to get from element
		(full list you can see in <a href="https://github.com/sleeping-owl/apist/blob/master/src/SleepingOwl/Apist/Selectors/ApistFilter.php">ApistFilter.php</a>):</p>
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
	<h3>Exception Suppression</h3>
	<p>By default exception suppression is turned on. Every blueprint filter with exception will be replaced by <code>null</code>.</p>
	<p>You can disable suppression by (if you want manually catch them):</p>
	<pre><code class="language-php">$api->setSuppressExceptions(false)</code></pre>
	<h3>Blueprint Conditionals</h3>
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
	<h3>Blueprint Filter Examples</h3>

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
	<pre><code class="language-php">Apist::filter('.navbar li:nth-child(4) a:first')->text()</code></pre>

	<h5>Create array with blueprint for every item</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->each([
  'title' => Apist::filter('a')->text(),
  'link' => Apist::filter('a')->attr('href')
])</code></pre>

	<h5>Create flat array without keys</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->each(Apist::filter('a')->text())</code></pre>

	<h5>Perform string function on set of nodes</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->each()->strtoupper() # this will grap text from each node and convert to uppercase, result will be an array</code></pre>

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
		<div id="yaml-configuration" class="fix-navbar-fixed"></div>
		<h2>Yaml Configuration</h2>
	</div>
	<p><strong>Example:</strong> for full-feature demo api class source using yaml see
		<a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/WikiYmlApi.php">WikiYmlApi.php</a>
		and
		<a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/Api/wiki.yml">wiki.yml</a>.</p>
	<p><strong>Important:</strong> to use yaml config you must extends <code>SleepingOwl\Apist\Yaml\YamlApist</code> class.</p>
	<p>To load api configuration from Yaml file you must override constructor in your class:</p>
	<pre><code class="language-php">function __construct($options = [])
{
  parent::__construct('path/to/your/file.yml', $options);
}</code></pre>
	<p>Or if you dont want to implement your own class you can use factory instead:</p>
	<pre><code class="language-php">$api = \SleepingOwl\Apist\Apist::fromYaml('path/to/your/file.yml');</code></pre>
	<h3>Yaml Structure</h3>
	<pre><code class="language-php">baseUrl: http://host.com

# all your structures (with leading underscore)
_post:
  title: .title | text
  link: .title | attr('href')

# all your methods
index:
  url: /
  method: get # (post, put, ...), default is "get"
  blueprint:
    title: .page_head .title | text
    posts: .posts .post | each(:post)
  options:
    query:
      field: value

search:
  url: /search
  blueprint:
    query: $1
    publications_count: .menu .item:nth-child(1) span | text | intval
    posts: .post | each (:post)
  options:
    query:
      q: $1</code></pre>

	<h3>Method Blueprint</h3>
	<p>Yaml method blueprint structure is very similar to PHP blueprint structure.
		You must provide css-selector to select node and pipe it through necessary methods.</p>
	<h4>Piping Examples</h4>
	<h5>Grap text from node</h5>
	<pre><code class="language-php">title: .title | text</code></pre>
	<h5>Grap attribute from node</h5>
	<pre><code class="language-php">title: .title | attr('href')</code></pre>
	<h5>Use custom structure <small>(for details see <a href="#structures">structures</a>)</small></h5>
	<pre><code class="language-php">post_body: :post</code></pre>
	<h5>Conditionals</h5>
	<pre><code class="language-php">title: .header | exists | then(:post_title) | else('Title not found')</code></pre>
	<h5>Custom api class methods</h5>
	<pre><code class="language-php">title: .title | modifyTitle # public function modifyTitle($node) must be implemented in your class api</code></pre>
	<h5>Each node structure</h5>
	<pre><code class="language-php">posts: .post | each(:post)</code></pre>

	<div class="page-header">
		<div id="structures" class="fix-navbar-fixed"></div>
		<h3>Structures</h3>
	</div>
	<p>Structures can be used in multiple methods blueprint.
	Structure name must start with underscore in definition and with colon in usage.</p>
	<pre><code class="language-php">_post:
  title: .title | text
  link: .title | attr('href')

postsList:
  posts: .post | each(:post)

getPost:
  post: :post</code></pre>
  	<p>There is one predefined structure <code>:current</code>. You can use it to get current node. For example:</p>
	<pre><code class="language-php">_menu_item: :current | text
	
postsList:
  menu: .nav li | each(:menu_item)</code></pre>

	<h3>Method Parameters</h3>
	<p>You can use <code>$1</code>, <code>$2</code>, ... values in your Yaml file to insert method parameter.
		It will be replaced in url, blueprint or options. Parameters starts from <code>$1</code>. For example:</p>
	<pre><code class="language-php">user:
  url: /user/$1
  ...</code></pre>
	<p>When you call your api:</p>
	<pre><code class="language-php">$api->user('john');</code></pre>
	<p>This will make http-request to the <code>"/user/john"</code> url.</p>

	<div class="page-header">
		<div id="examples" class="fix-navbar-fixed"></div>
		<h2>Examples</h2>
	</div>
	@include('example', [
		'title' => 'Wikipedia Main Page',
		'method' => 'yaml.index'
	])
	@include('example', [
		'title' => 'Wikipedia Current Events',
		'method' => 'yaml.current_events'
	])

@stop