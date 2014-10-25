<?php

Route::get('/', 'IndexController@getIndex');
Route::get('/documentation', 'DocsController@getIndex');
Route::get('/call/{method}', 'IndexController@getApiCall');
Route::get('/source/{method}', 'IndexController@getSource');
