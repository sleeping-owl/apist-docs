@extends('_layout')

@section('content')
	<div class="page-header"><h2>Содержимое</h2></div>
	<p>
		<ul>
			<li><a href="#http-requests">Используя http-запросы</a></li>
			<li><a href="#without-http-requests">Не используя http-запросы</a></li>
			<li><a href="#php-blueprint">PHP схема</a></li>
			<li><a href="#yaml-configuration">Yaml схема</a></li>
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
		<li><strong>$url</strong> &mdash; адрес запроса (относительный базового адреса или абсолютный)</li>
		<li><strong>$blueprint</strong> &mdash; схема для парсинга результата</li>
		<li><strong>$options</strong> <small>(optional)</small> &mdash; дополнительные параметры для использования в запросе.</li>
	</ol>
	<h3>Использование Get и Post параметров запроса</h3>
	<p>Вы можете добавить Get параметры в запрос в опциональном третьем параметре <code>$options</code>:</p>
	<pre><code class="language-php">$this->get('/', ..., [
  'query' => [
    'parameter1' => 'value1',
    'parameter2' => 'value2',
  ]
])</code></pre>
	<p>Также вы можете добавить Post параметры в запрос:</p>
	<pre><code class="language-php">$this->post('/', ..., [
  'body' => [
    'parameter1' => 'value1',
    'parameter2' => 'value2',
  ]
])</code></pre>
	<p>Дополнительно вы можете указать заголовки запроса и другие настройки. Для полного списка возможностей посетите <a href="http://guzzle.readthedocs.org/en/latest/clients.html#request-options">документацию Guzzle</a>.</p>
	<h3>Обработка ошибок</h3>
	<p>Если в ходе запроса возникла ошибка результат будет выглядеть как:</p>
	<pre><code class="language-json" data-call="get404"></code></pre>

	<div class="page-header">
		<div id="without-http-requests" class="fix-navbar-fixed"></div>
		<h2>Не используя http-запросы</h2>
	</div>
	<p>Вы можете использовать метод <code>parse($content, $blueprint)</code> чтобы распарить $content по предоставленной схеме без использования http-запросов.</p>

	<div class="page-header">
		<div id="php-blueprint" class="fix-navbar-fixed"></div>
		<h2>PHP схема</h2>
	</div>
	<p><strong>Пример:</strong> исходный код работающего примера можно посмотреть в файле <a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/HabrApi.php">HabrApi.php</a>.</p>
	<p>Схема представляет собой структуру, которую вы хотите получить в результате вызова api-метода.
		Она может быть массивом фильтров или одиночным фильтром <code>Apist::filter()</code>.
		Чтобы вставить значение из результата запроса используйте метод <code>Apist::filter($cssSelector)</code>.
		Он найдет нужный элемент в html и заменит себя в схеме им.</p>
	<p>Если вам нужно получить текущий элемент используйте <code>Apist::current()</code>.</p>
	<p>Вы можете указать дополнительные данные, которые необходимо получить от элемента
		(полный список вы можете посмотреть в файле <a href="https://github.com/sleeping-owl/apist/blob/master/src/SleepingOwl/Apist/Selectors/ApistFilter.php">ApistFilter.php</a>):</p>
	<ul>
		<li><code>->text()</code> &mdash; получить текстовое содержимое элемента</li>
		<li><code>->html()</code> &mdash; получить html содержимое элемента</li>
		<li><code>->attr($attributeName)</code> &mdash; получить значение аттрибута html элемента</li>
		<li><code>->eq($position)</code> &mdash; получить элемент по его номеру позиции (если селектором было выбрано несколько элементов)</li>
		<li><code>->first()</code> &mdash; получить первый элемент из выборки</li>
		<li><code>->last()</code> &mdash; получить последний элемент из выборки</li>
		<li><code>->element()</code> &mdash; получить в результате объект класса <code>Symfony\Component\DomCrawler\Crawler</code>, отвечающий за найденный элемент</li>
		<li><code>->call($callback)</code> &mdash; использовать $callback для модификации элемента</li>
		<li><code>->each($blueprint)</code> &mdash; заменяет себя на результат парсинга схемы $blueprint,
			$blueprint будет парситься относительно текущего элемента<br/>
			<i>Примечание: ваши css-селекторы внутри схемы $blueprint будут применены к текущему элементу, ваш не нужно писать полный путь в селекторах</i></li>
		<li><code>->each($callback)</code> &mdash; заменяет себя на массив, каждый элемент которого - результат выполнения $callback,
			$callback будет вызван с параметрами ($node, $index) для каждого элемента, выбранного селектором.</li>
		<li>Также вы можете использовать любой метод из вашего api-класса</li>
		<li>Вы можете использовать стандартные PHP-функции:
			<code>trim</code>, <code>strtoupper</code>, <code>strtolower</code>,
			<code>mb_strtoupper</code>, <code>mb_strtolower</code>,
			<code>intval</code>, <code>floatval</code> или ваши собственные функции в корневом namespace</li>
		<li>Вы можете применять фильтры последовательно: <code>Apist::filter('.title')->first()->text()->mb_strtoupper()->trim()</code></li>
	</ul>
	<h3>Режим подавления ошибок</h3>
	<p>По умолчанию режим подавления ошибок включен. Любой фильтр схемы, вызвавший ошибку, будет заменен на <code>null</code>.</p>
	<p>Вы можете отключить режим подавления ошибок (если вы хотите сами обработать их):</p>
	<pre><code class="language-php">$api->setSuppressExceptions(false)</code></pre>
	<h3>Условные выражения в схеме</h3>
	<p>Вы можете использовать условные выражения в ваших схемах:</p>
	<pre><code class="language-php">Apist::filter('.page-header')->exists()->then(
  Apist::filter('.page-header .title')->text() // Это значение будет использовано если элемент .page-header был найден
)->else(
  null // Это значение будет использовано если элемент .page-header не был найден
)</code></pre>
	<p>или используйте метод <code>check($callback)</code> с произволным замыканием:</p>
	<pre><code class="language-php">Apist::filter('.page-header')->check(function ($node)
{
  return $node->text() === 'My Title';
})->then(...)->else(...)</code></pre>
	<p>Методы <code>then()</code> и <code>else()</code> принимают схему в качестве аргумента. Вы можете использовать массив или одиночный объект.</p>
	<h3>Примеры фильтров схемы</h3>

	<h5>Получение адреса ссылки</h5>
	<pre><code class="language-php">Apist::filter('.title_block .title a')->attr('href')</code></pre>

	<h5>Получение текста заголовка и конвертация в верхний регистр</h5>
	<pre><code class="language-php">Apist::filter('.title')->text()->trim()->mb_strtoupper()</code></pre>

	<h5>Получение текст заголовка и использование метода из api-класса для обработки значения</h5>
	<pre><code class="language-php"># метод, объявленный в api-классе
public function myFunc($subject, $from, $to)
{
  return str_replace($from, $to, $subject);
}

# использование метода в схеме
Apist::filter('.title')->text()->myFunc('find', 'replace')</code></pre>

	<h5>Вы можете использовать полный css-селектор или использовать последовательность фильтров</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->eq(3)->filter('a')->first()->text()</code></pre>

	<h5>То же, что и предыдущий пример <small>(помните, что eq() начинает отсчет с нуля, а :nth-child() начинает отсчет с единицы)</small></h5>
	<pre><code class="language-php">Apist::filter('.navbar li:nth-child(4) a:first')->text()</code></pre>

	<h5>Создание массива по схеме для каждого элемента</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->each([
  'title' => Apist::filter('a')->text(),
  'link' => Apist::filter('a')->attr('href')
])</code></pre>

	<h5>Создание плоского массива без ключей</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->each(Apist::filter('a')->text())</code></pre>

	<h5>Применение строковых функций для нескольких элементов</h5>
	<pre><code class="language-php">Apist::filter('.navbar li')->each()->strtoupper() # результатом будет массив, каждый элемент которого - переведенный в верхний регистр текст элемента</code></pre>

	<h5>Вы можете использовать любую структуру схемы</h5>
	<pre><code class="language-php">[
  'first'  => 'static field', // использовать статичные поля
  'second' => Apist::filter('.title')->text(), // комбинироавть их с фильтрами
  'third'  => [
	'my',
	'custom',
	'array',
	Apist::filter('.title')->text()
  ]
]</code></pre>

	<div class="page-header">
		<div id="yaml-configuration" class="fix-navbar-fixed"></div>
		<h2>Yaml схема</h2>
	</div>
	<p><strong>Пример:</strong> исходный код работающего примера можно посмотреть в файле
		<a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/HabrYmlApi.php">HabrYmlApi.php</a>
		и
		<a href="https://github.com/sleeping-owl/apist-docs/blob/master/app/Demo/Api/habr.yml">habr.yml</a>.</p>
	<p><strong>Важно:</strong> для использования yaml схемы вам необходимо расширять класс <code>SleepingOwl\Apist\Yaml\YamlApist</code>.</p>
	<p>Для загрузки схемы из yaml-файла вам необходимо описать конструктор в вашем классе:</p>
	<pre><code class="language-php">function __construct($options = [])
{
  parent::__construct('path/to/your/file.yml', $options);
}</code></pre>
	<p>Либо если вы не хотите создавать новый класс, используйте автоматический конструктор:</p>
	<pre><code class="language-php">$api = \SleepingOwl\Apist\Apist::fromYaml('path/to/your/file.yml');</code></pre>
	<h3>Структура yaml-файла</h3>
	<pre><code class="language-php">baseUrl: http://host.com

# описание всех ваших структур (начинаются с подчеркивания)
_post:
  title: .title | text
  link: .title | attr('href')

# описание методов api
index:
  url: /
  method: get # (post, put, ...), по умолчанию "get"
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

	<h3>Схема метода</h3>
	<p>Схема метода используя yaml-конфигурацию очень схожа со схемой метода используя PHP описание.
		Вы должны описать css-селектор для выбора элемента и указать необходимые фильтры для обработки значения.</p>
	<h4>Примеры фильтров</h4>
	<h5>Получение текста элемента</h5>
	<pre><code class="language-php">title: .title | text</code></pre>
	<h5>Получение аттрибута элемента</h5>
	<pre><code class="language-php">title: .title | attr('href')</code></pre>
	<h5>Использование структуры <small>(подробнее можно посмотреть в разделе <a href="#structures">структуры</a>)</small></h5>
	<pre><code class="language-php">post_body: :post</code></pre>
	<h5>Условные выражения</h5>
	<pre><code class="language-php">title: .header | exists | then(:post_title) | else('Заголовок не найден')</code></pre>
	<h5>Применение методов api-класса</h5>
	<pre><code class="language-php">title: .title | modifyTitle # public function modifyTitle($node) должен быть описан в вашем api-классе</code></pre>
	<h5>Использование структуры для описания элемента в each</h5>
	<pre><code class="language-php">posts: .post | each(:post)</code></pre>

	<div class="page-header">
		<div id="structures" class="fix-navbar-fixed"></div>
		<h3>Структуры</h3>
	</div>
	<p>Структуры могут быть использованы в нескольких элементах схемы.
	Название структуры должно начинаться с подчеркивания при определении и с двоеточния при использовании в схеме.</p>
	<pre><code class="language-php">_post:
  title: .title | text
  link: .title | attr('href')

postsList:
  posts: .post | each(:post)

getPost:
  post: :post</code></pre>
  	<p>Существует одна структура по умолчанию <code>:current</code>. Вы можете использовать ее для получения текущего элемента. Например:</p>
	<pre><code class="language-php">_menu_item: :current | text
	
postsList:
  menu: .nav li | each(:menu_item)</code></pre>

	<h3>Параметры метода</h3>
	<p>Вы можете использовать значения <code>$1</code>, <code>$2</code>, ... в ваших yaml-файлах для вставки параметров метода.
		Эти значения будут заменены в полях url, blueprint и options. Параметры начинаются с <code>$1</code>. Например:</p>
	<pre><code class="language-php">user:
  url: /user/$1
  ...</code></pre>
	<p>Когда вы вызовите этот метод api:</p>
	<pre><code class="language-php">$api->user('john');</code></pre>
	<p>Будет произведен http-запрос по адресу <code>"/user/john"</code>.</p>

	<div class="page-header">
		<div id="examples" class="fix-navbar-fixed"></div>
		<h2>Примеры</h2>
	</div>
	@include('example', [
		'title' => 'Главная страница хабрахабра',
		'method' => 'yaml.index'
	])
	@include('example', [
		'title' => 'Поиск по хабрахабру: "php"',
		'method' => 'yaml.search'
	])

@stop