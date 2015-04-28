<?php
Route::get('player/deleteAudioAJAX', 'PlayerController@deleteAudioAJAX')
    ->before('auth');
Route::get('player/jsonGetAudio', 'PlayerController@jsonGetAudio')
    ->before('auth');
Route::post('player/jsonUpdateAudio', 'PlayerController@jsonUpdateAudio')
    ->before('auth');
Route::get('player/getPopupsJSON', 'PlayerController@getPopupsJSON')
    ->before('auth');
Route::get('player/deletePlayerAJAX', 'PlayerController@deletePlayerAJAX')
    ->before('auth');
Route::get('player/getPlayerSettingsJSON', 'PlayerController@getPlayerSettingsJSON')
    ->before('auth');
Route::get('player/getPlayersJSON', 'PlayerController@getPlayersJSON')
    ->before('auth');
Route::post('player/updatePlayerJSON', 'PlayerController@updatePlayerJSON')
    ->before('auth');
Route::post('player/saveSettings', ['as' => 'player.saveSettings', 'uses' => 'PlayerController@saveSettings'])
    ->before('auth');
Route::get('player/settings', 'PlayerController@settings')
    ->before('auth');
Route::get('player/cabinet', 'PlayerController@cabinet')
    ->before('auth');
Route::post('player/saveProfile', 'PlayerController@updateProfile')
    ->before('auth');
Route::post('player/createProfile', 'PlayerController@createProfile')
    ->before('auth');
Route::get('player/createProfileView', 'PlayerController@createProfileView')
    ->before('auth');
Route::post('player/editStatusProfiles', 'PlayerController@editStatusProfiles')
    ->before('auth');
Route::post('player/upload', 'PlayerController@upload')
    ->before('auth');

Route::get('snippet', 'SnippetController@create')
    ->before('auth');
Route::post('player/ajaxUploadAudio', 'SnippetController@ajaxUploadAudio')
    ->before('auth');
Route::post('player/generate', 'SnippetController@generate')
    ->before('auth');
Route::get('player/embed', 'SnippetController@embed');
Route::post('player/uploadBySlug', 'SnippetController@uploadBySlug')
    ->before('auth');

Route::get('/', 'SessionController@create');
Route::get('login', 'SessionController@create');
Route::get('logout', 'SessionController@destroy');
Route::resource('session', 'SessionController');

//Route::get('/', function()
//{
//	return View::make('hello');
//});