<ul class="nav navbar-nav navbar-right">
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fa fa-fw fa-laptop"></i> {{{ $syntaxLabel }}} <i class="fa fa-caret-down"></i>
		</a>
		<ul class="dropdown-menu">
			@include('_partials.syntax', [
				'_syntax' => 'php',
				'label' => 'PHP'
			])
			@include('_partials.syntax', [
				'_syntax' => 'ruby',
				'label' => 'Ruby'
			])
		</ul>
	</li>
</ul>
