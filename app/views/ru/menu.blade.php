<li><a href="{{ route('index', [$lang, $syntax]) }}#overview">Обзор</a></li>
<li><a href="{{ route('index', [$lang, $syntax]) }}#installation">Установка</a></li>
<li><a href="{{ route('index', [$lang, $syntax]) }}#usage">Использование</a></li>
<li><a href="{{ route('index', [$lang, $syntax]) }}#examples">Примеры</a></li>
<li><a href="{{ route('documentation', [$lang, $syntax]) }}">Документация</a></li>
@if ($syntax === 'php')
	<li><a href="https://github.com/sleeping-owl/apist"><i class="fa fa-github"></i> GitHub</a></li>
@else
	<li><a href="https://github.com/sleeping-owl/apist-ruby"><i class="fa fa-github"></i> GitHub</a></li>
@endif
