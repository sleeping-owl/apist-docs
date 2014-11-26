@extends('_layout')

@section('content')
	<div class="page-header"><h2>Table of Contents</h2></div>
	<p>
		<ul>
			<li><a href="#http-requests">With http-requests</a></li>
			<li><a href="#without-http-requests">Without http-requests</a></li>
			<li><a href="#blueprint-config">Blueprint Configuration</a></li>
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
		<li><strong>url</strong> &mdash; url to use in query (relative to base url or absolute)</li>
		<li><strong>blueprint</strong> &mdash; hash or single filter of parsing rules</li>
		<li><strong>options</strong> <small>(optional)</small> &mdash; any additional options to use in query</li>
	</ol>
	<h3>Providing Get and Post parameters</h3>
	<p>You can provide get parameters to the query in optional third <code>options</code> parameter:</p>
	<pre><code class="language-ruby">get '/', ..., {
  query: {
    parameter1: 'value1',
    parameter2: 'value2'
  }
}</code></pre>
	<p>Also you can provide post parameters to the query:</p>
	<pre><code class="language-ruby">post '/', ..., {
  body: {
    parameter1: 'value1',
    parameter2: 'value2'
  }
}</code></pre>
	<p>Also you can specify headers and request configuration. For full documentation see <code>HTTParty</code> documentation.</p>
	<h3>Error Handling</h3>
	<p>If there was an error during request your response data will look like:</p>
	<pre><code class="language-json" data-call="get404"></code></pre>

	<div class="page-header">
		<div id="without-http-requests" class="fix-navbar-fixed"></div>
		<h2>Without http-requests</h2>
	</div>
	<p>You can use <code>parse(content, blueprint)</code> method to parse content by blueprint without any http-requests.</p>

	<div class="page-header">
		<div id="blueprint-config" class="fix-navbar-fixed"></div>
		<h2>Blueprint Configuration</h2>
	</div>
	<p><strong>Example:</strong> for full-feature demo api class source see <a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/ruby/basic.rb">basic.rb</a>.</p>
	<p>Blueprint represents structure you want to get from api call.
		It can be hash or single <code>filter()</code> object.
		To insert value from query result use <code>filter(cssSelector)</code> method.
		It will search and replace itself with required element from html.</p>
	<p>If you need to grab current node element you can use <code>current</code>.</p>
	<p>You can specify additional data you want to get from element
		(full list you can see in <a href="https://github.com/sleeping-owl/apist-ruby/blob/master/lib/apist/filter.rb">filter.rb</a>):</p>
	<ul>
		<li><code>.text</code> &mdash; get text content from element</li>
		<li><code>.html</code> &mdash; get html content from element</li>
		<li><code>.attr(attributeName)</code> &mdash; get attribute value from element</li>
		<li><code>.eq(position)</code> &mdash; get element with <code>position</code> from all elements matches css selector</li>
		<li><code>.first</code> &mdash; get first element from all elements matches css selector</li>
		<li><code>.last</code> &mdash; get last element from all elements matches css selector</li>
		<li><code>.element</code> &mdash; get element node as is</li>
		<li><code>.call(lambda { |element| ...})</code> &mdash; use custom callback to modify element</li>
		<li><code>.each(blueprint)</code> &mdash; replaces itself with array,
			blueprint will be parsed within current element<br/>
			<i>Note: your css selectors within blueprint will be applied to current element, you don't have to write full selectors</i></li>
		<li><code>.each(lambda { |element, i| ... })</code> &mdash; replaces itself with array,
			callback will be called with (node, index) for every node in list.
			Callback result will be used as array value.</li>
		<li>You can use any method from your api class</li>
		<li>You can use simple string methods:
			<code>strip</code>, <code>upcase</code>, <code>downcase</code>,
			<code>to_i</code>, <code>to_f</code> or your own functions</li>
		<li>You can chain methods: <code>filter('.title').first.text.mb_chars.upcase.strip.to_s</code></li>
	</ul>
	<h3>Exception Suppression</h3>
	<p>By default exception suppression is turned on. Every blueprint filter with exception will be replaced by <code>nil</code>.</p>
	<p>You can disable suppression by (if you want manually catch them):</p>
	<pre><code class="language-ruby">api.suppress_exceptions = false</code></pre>
	<h3>Blueprint Conditionals</h3>
	<p>You can use conditionals in your blueprints:</p>
	<pre><code class="language-ruby">filter('.page-header').exists.then(
  filter('.page-header .title').text # This value will be used if .page-header element was found
).else(
  nil # This value will be used if .page-header element doesn't exist in html response
)</code></pre>
	<p>or use <code>check(lambda { |node| ... })</code> method with custom callback:</p>
	<pre><code class="language-ruby">filter('.page-header').check(lambda { |node|
  node.text === 'My Title'
}).then(...).else(...)</code></pre>
	<p><code>then()</code> and <code>else()</code> methods accept blueprint as argument. You can provide hash or single item as you want.</p>
	<p>Using conditionals you can make your api result fully customizable.</p>
	<h3>Blueprint Filter Examples</h3>

	<h5>Get href from link</h5>
	<pre><code class="language-ruby">filter('.title_block .title a').attr('href')</code></pre>

	<h5>Get title text, trim and convert to uppercase</h5>
	<pre><code class="language-ruby">filter('.title').text.strip.upcase</code></pre>

	<h5>Get title and use custom method from your api class</h5>
	<pre><code class="language-ruby"># declaration in api class
def my_func(subject, from, to)
  subject.gsub from, to
end

# usage in your blueprint
filter('.title').text.my_func(/find/, 'replace')</code></pre>

	<h5>You can write one css selector or use chain methods</h5>
	<pre><code class="language-ruby">filter('.navbar li').eq(3).filter('a').first.text</code></pre>

	<h5>Same as previous filter <small>(remember that eq() starts from zero, but :nth-child() starts from one)</small></h5>
	<pre><code class="language-ruby">filter('.navbar li:nth-child(4) a:first').text</code></pre>

	<h5>Create array with blueprint for every item</h5>
	<pre><code class="language-ruby">filter('.navbar li').each({
  title: filter('a').text,
  link: filter('a').attr('href')
})</code></pre>

	<h5>Create flat array without keys</h5>
	<pre><code class="language-ruby">filter('.navbar li').each(filter('a').text)</code></pre>

	<h5>Perform string function on set of nodes</h5>
	<pre><code class="language-ruby">filter('.navbar li').each.upcase # this will grap text from each node and convert to uppercase, result will be an array</code></pre>

	<h5>You can use any array structure you want</h5>
	<pre><code class="language-ruby">{
  first: 'static field', # and use static field values
  second: filter('.title').text, # combine them with computed as you want
  third: {
	field1: 'my',
	field2: 'custom',
	field3: 'array',
	field4: filter('.title').text
  }
}</code></pre>

@stop