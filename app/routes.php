<?php

Route::get('/', ['as' => 'home', function () {
	return Redirect::to('messages');
}]);

//Route::put('messages', 'MessagesController@updateMultiple');
//Route::delete('messages', 'MessagesController@destroyMultiple');
Route::resource('messages', 'MessagesController', ['only' => ['index', 'store', 'update', 'destroy']]);
Route::get('messages/{id}', 'AudioController@show');

Route::get('login', 'SessionsController@create');
Route::get('login/oauth', 'SessionsController@oauth');
Route::get('login/oauth2', 'SessionsController@oauth2');
Route::get('logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController', ['only' => ['create', 'store', 'destroy']]);

Route::get('password_resets/reset/{token}', 'PasswordResetsController@reset');
Route::post('password_resets/reset/{token}', 'PasswordResetsController@postReset');
Route::resource('password_resets', 'PasswordResetsController');