<?php
	if ( ! isset($type))
	{
		$type = 'php';
	}
	if ( ! isset($source))
	{
		$source = $method;
	}
?>
<h3>{{{ $title }}}</h3>
<p><a href="#" class="toggle"><i class="fa fa-fw fa-code"></i> Show method code</a></p>
<pre class="hidden-common"><code class="language-{{ $type }}" data-source="{{{ $source }}}"></code></pre>
<p><a href="#" class="toggle"><i class="fa fa-fw fa-sitemap"></i> Show result</a></p>
<pre class="example hidden-common"><code class="language-json" data-call="{{{ $method }}}"></code></pre>
