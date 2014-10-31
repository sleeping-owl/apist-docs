<?php

Route::group([
	'prefix' => '{lang?}',
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

Route::get('documentation', 'IndexController@getDocumentation');

Route::get('/call/{method}', 'IndexController@getApiCall');
Route::get('/source/{method}', 'IndexController@getSource');
