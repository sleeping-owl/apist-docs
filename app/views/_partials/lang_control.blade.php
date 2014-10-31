<ul class="nav navbar-nav navbar-right">
	<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<i class="fa fa-fw fa-globe"></i> {{{ $langLabel }}} <i class="fa fa-caret-down"></i>
		</a>
		<ul class="dropdown-menu">
			@include('_partials.lang', [
				'locale' => 'en',
				'label' => 'English'
			])
			@include('_partials.lang', [
				'locale' => 'ru',
				'label' => 'Russian'
			])
		</ul>
	</li>
</ul>
