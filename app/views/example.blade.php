<h3>{{{ $title }}}</h3>
<p><a href="#" class="toggle"><i class="fa fa-fw fa-code"></i> Show method code</a></p>
<pre class="hidden-common"><code class="language-php">{{ $source }}</code></pre>
<p><a href="#" class="toggle"><i class="fa fa-fw fa-sitemap"></i> Show result</a></p>
<pre class="example hidden-common"><code class="language-json">{{{ json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}}</code></pre>
