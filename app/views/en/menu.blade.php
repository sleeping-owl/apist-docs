<li><a href="{{ route('index', [$lang, $syntax]) }}#overview">Overview</a></li>
<li><a href="{{ route('index', [$lang, $syntax]) }}#installation">Installation</a></li>
<li><a href="{{ route('index', [$lang, $syntax]) }}#usage">Usage</a></li>
<li><a href="{{ route('index', [$lang, $syntax]) }}#examples">Examples</a></li>
<li><a href="{{ route('documentation', [$lang, $syntax]) }}">Documentation</a></li>
@if ($syntax === 'php')
	<li><a href="https://github.com/sleeping-owl/apist"><i class="fa fa-github"></i> GitHub</a></li>
@else
	<li><a href="https://github.com/sleeping-owl/apist-ruby"><i class="fa fa-github"></i> GitHub</a></li>
@endif
