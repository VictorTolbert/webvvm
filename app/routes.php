<?php

Route::get('/', ['as' => 'home', function () {
	return Redirect::to('messages');
}]);

Route::put('messages', 'MessagesController@updateMultiple');
Route::delete('messages', 'MessagesController@destroyMultiple');
Route::get('messages/{id}', 'MessagesController@show');
Route::resource('messages', 'MessagesController');

Route::get('login', 'SessionsController@create');
Route::get('logout', 'SessionsController@destroy');
Route::resource('sessions', 'SessionsController', ['only' => ['create', 'store', 'destroy']]);

Route::get('password_resets/reset/{token}', 'PasswordResetsController@reset');
Route::post('password_resets/reset/{token}', 'PasswordResetsController@postReset');
Route::resource('password_resets', 'PasswordResetsController');