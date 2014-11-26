@extends('_layout')

@section('content')
	<div class="page-header"><h2>Содержимое</h2></div>
	<p>
		<ul>
			<li><a href="#http-requests">Используя http-запросы</a></li>
			<li><a href="#without-http-requests">Не используя http-запросы</a></li>
			<li><a href="#blueprint-config">Конфигурация шаблона</a></li>
		</ul>
	</p>

	<div class="page-header">
		<div id="http-requests" class="fix-navbar-fixed"></div>
		<h2>Используя http-запросы</h2>
	</div>
	<p>Вы можете использовать эти методы в ваших api-методах чтобы получить данные посредством http-запроса:</p>
	<ul>
		<li><code>get</code></li>
		<li><code>head</code></li>
		<li><code>post</code></li>
		<li><code>patch</code></li>
		<li><code>put</code></li>
		<li><code>delete</code></li>
	</ul>
	<p><small>(примечание: каждый метод представляет собой http метод запроса, который будет использован)</small></p>
	<p>Каждый метод принимает 3 параметра:</p>
	<ol>
		<li><strong>url</strong> &mdash; адрес запроса (относительный базового адреса или абсолютный)</li>
		<li><strong>blueprint</strong> &mdash; схема для парсинга результата</li>
		<li><strong>options</strong> <small>(optional)</small> &mdash; дополнительные параметры для использования в запросе.</li>
	</ol>
	<h3>Использование Get и Post параметров запроса</h3>
	<p>Вы можете добавить Get параметры в запрос в опциональном третьем параметре <code>options</code>:</p>
	<pre><code class="language-ruby">get '/', ..., {
  query: {
    parameter1: 'value1',
    parameter2: 'value2'
  }
}</code></pre>
	<p>Также вы можете добавить Post параметры в запрос:</p>
	<pre><code class="language-ruby">post '/', ..., {
  body: {
    parameter1: 'value1',
    parameter2: 'value2'
  }
}</code></pre>
	<p>Дополнительно вы можете указать заголовки запроса и другие настройки. Для полного списка возможностей обратитесь к документации <code>HTTParty</code>.</p>
	<h3>Обработка ошибок</h3>
	<p>Если в ходе запроса возникла ошибка результат будет выглядеть как:</p>
	<pre><code class="language-json" data-call="get404"></code></pre>

	<div class="page-header">
		<div id="without-http-requests" class="fix-navbar-fixed"></div>
		<h2>Не используя http-запросы</h2>
	</div>
	<p>Вы можете использовать метод <code>parse(content, blueprint)</code> чтобы распарить <code>content</code> по предоставленной схеме без использования http-запросов.</p>

	<div class="page-header">
		<div id="blueprint-config" class="fix-navbar-fixed"></div>
		<h2>Конфигурация шаблона</h2>
	</div>
	<p><strong>Пример:</strong> исходный код работающего примера можно посмотреть в файле <a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/ruby/basic.rb">basic.rb</a>.</p>
	<p>Схема представляет собой структуру, которую вы хотите получить в результате вызова api-метода.
		Она может быть массивом фильтров или одиночным фильтром <code>filter()</code>.
		Чтобы вставить значение из результата запроса используйте метод <code>filter(cssSelector)</code>.
		Он найдет нужный элемент в html и заменит себя в схеме им.</p>
	<p>Если вам нужно получить текущий элемент используйте <code>current</code>.</p>
	<p>Вы можете указать дополнительные данные, которые необходимо получить от элемента
       		(полный список вы можете посмотреть в файле <a href="https://github.com/sleeping-owl/apist-ruby/blob/master/lib/apist/filter.rb">filter.rb</a>):</p>
	<ul>
		<li><code>.text</code> &mdash; получить текстовое содержимое элемента</li>
		<li><code>.html</code> &mdash; получить html содержимое элемента</li>
		<li><code>.attr(attributeName)</code> &mdash; получить значение аттрибута html элемента</li>
		<li><code>.eq(position)</code> &mdash; получить элемент по его номеру позиции (если селектором было выбрано несколько элементов)</li>
		<li><code>.first</code> &mdash; получить первый элемент из выборки</li>
		<li><code>.last</code> &mdash; получить последний элемент из выборки</li>
		<li><code>.element</code> &mdash; получить в результате объект, отвечающий за найденный элемент</li>
		<li><code>.call(lambda { |element| ...})</code> &mdash; использовать callback для модификации элемента</li>
		<li><code>.each(blueprint)</code> &mdash; заменяет себя на результат парсинга схемы blueprint,
			blueprint будет парситься относительно текущего элемента<br/>
			<i>Примечание: ваши css-селекторы внутри схемы blueprint будут применены к текущему элементу, вам не нужно писать полный путь в селекторах</i></li>
		<li><code>.each(lambda { |element, i| ... })</code> &mdash; заменяет себя на массив, каждый элемент которого - результат выполнения callback,
			callback будет вызван с параметрами (node, index) для каждого элемента, выбранного селектором.</li>
		<li>Также вы можете использовать любой метод из вашего api-класса</li>
		<li>Вы можете использовать методы текущего элемента:
			<code>strip</code>, <code>upcase</code>, <code>downcase</code>,
			<code>to_i</code>, <code>to_f</code> или ваши собственные функции</li>
		<li>Вы можете применять фильтры последовательно: <code>filter('.title').first.text.mb_chars.upcase.strip.to_s</code></li>
	</ul>
	<h3>Режим подавления ошибок</h3>
	<p>По умолчанию режим подавления ошибок включен. Любой фильтр схемы, вызвавший ошибку, будет заменен на <code>nil</code>.</p>
	<p>Вы можете отключить режим подавления ошибок (если вы хотите сами обработать их):</p>
	<pre><code class="language-ruby">api.suppress_exceptions = false</code></pre>
	<h3>Условные выражения в схеме</h3>
	<p>Вы можете использовать условные выражения в ваших схемах:</p>
	<pre><code class="language-ruby">filter('.page-header').exists.then(
  filter('.page-header .title').text # Это значение будет использовано если элемент .page-header был найден
).else(
  nil # Это значение будет использовано если элемент .page-header был найден
)</code></pre>
	<p>или используйте метод <code>check(lambda { |node| ... })</code> с произволным замыканием:</p>
	<pre><code class="language-ruby">filter('.page-header').check(lambda { |node|
  node.text === 'My Title'
}).then(...).else(...)</code></pre>
	<p>Методы <code>then()</code> и <code>else()</code> принимают схему в качестве аргумента. Вы можете использовать Hash или одиночный объект.</p>
	<h3>Примеры фильтров схемы</h3>

	<h5>Получение адреса ссылки</h5>
	<pre><code class="language-ruby">filter('.title_block .title a').attr('href')</code></pre>

	<h5>Получение текста заголовка и конвертация в верхний регистр</h5>
	<pre><code class="language-ruby">filter('.title').text.strip.upcase</code></pre>

	<h5>Получение текст заголовка и использование метода из api-класса для обработки значения</h5>
	<pre><code class="language-ruby"># declaration in api class
def my_func(subject, from, to)
  subject.gsub from, to
end

# usage in your blueprint
filter('.title').text.my_func(/find/, 'replace')</code></pre>

	<h5>Вы можете использовать полный css-селектор или использовать последовательность фильтров</h5>
	<pre><code class="language-ruby">filter('.navbar li').eq(3).filter('a').first.text</code></pre>

	<h5>То же, что и предыдущий пример <small>(помните, что eq() начинает отсчет с нуля, а :nth-child() начинает отсчет с единицы)</small></h5>
	<pre><code class="language-ruby">filter('.navbar li:nth-child(4) a:first').text</code></pre>

	<h5>Создание массива по схеме для каждого элемента</h5>
	<pre><code class="language-ruby">filter('.navbar li').each({
  title: filter('a').text,
  link: filter('a').attr('href')
})</code></pre>

	<h5>Создание плоского массива</h5>
	<pre><code class="language-ruby">filter('.navbar li').each(filter('a').text)</code></pre>

	<h5>Применение строковых функций для нескольких элементов</h5>
	<pre><code class="language-ruby">filter('.navbar li').each.upcase # this will grap text from each node and convert to uppercase, result will be an array</code></pre>

	<h5>Вы можете использовать любую структуру схемы</h5>
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