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
				<a id="top" class="navbar-brand navbar-brand-active" href="{{ route('index', $lang) }}">SleepingOwl
					Apist</a>
			</div>
			<ul class="nav navbar-nav">
				@include($lang . '.menu')
			</ul>
			@include('_partials.lang_control')
		</div>
	</div>

	<div class="container">
		@yield('content')
	</div>

	<div class="well container footer text-right">
		&copy; 2014{{ (date('Y') != 2014) ? '&mdash;' . date('Y') : '' }} <a href="mailto:owl.sleeping@yahoo.com">Sleeping Owl</a>
	</div>

	{{ HTML::script('js/jquery-1.11.0.js') }}
	{{ HTML::script('js/bootstrap.min.js') }}
	{{ HTML::script('js/highlight.min.js') }}
	{{ HTML::script('js/main.js') }}
	<script>hljs.initHighlightingOnLoad();</script>
</body>
</html>
