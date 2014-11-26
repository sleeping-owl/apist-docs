<?php

Route::get('/', function ()
{
	return Redirect::route('index', ['lang' => 'en']);
});

Route::get('documentation', function ()
{
	return Redirect::route('index', ['lang' => 'en']);
});


Route::get('{lang}', [
	'as'   => 'choose',
	'uses' => 'IndexController@getChoose'
])->where('lang', 'en|ru');

Route::group([
	'prefix' => '{lang}/{syntax}',
	'where'  => [
		'lang' => 'en|ru'
	],
], function ()
{
	Route::get('/', [
		'as'   => 'index',
		'uses' => 'IndexController@getIndex'
	]);
	Route::get('documentation', [
		'as'   => 'documentation',
		'uses' => 'IndexController@getDocumentation'
	]);
});

Route::get('/call/{method}', 'IndexController@getApiCall');
Route::get('/source/{method}', 'IndexController@getSource');
