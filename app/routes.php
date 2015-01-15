<?php
Route::get('player/embed', 'PlayerController@embed');
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

Route::get('player/snippet', 'SnippetController@create')
    ->before('auth');

Route::get('login', 'SessionController@create');
Route::get('logout', 'SessionController@destroy');
Route::resource('session', 'SessionController');

Route::get('/', function()
{
	return View::make('hello');
});
