<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('gdkp', 'BliserGdkpController@index');
Route::get('tooltip', 'TooltipController@Request');
Route::get('tooltip2', 'TooltipController@ArmoryRequest');
Route::get('online', 'OnlineController@Request');

Route::get('api/receiveData', 'IndexController@Start');
Route::post('api/receiveBattlegrounds', 'IndexController@Start');


Route::post('api/receiveData', 'ApiController@ReceiveData');
Route::post('api/receiveBattlegrounds', 'ApiController@ReceiveBattlegroundData');

Route::post('profile/avatar', 'HomeController@ChangeAvatar');
Route::post('profile/password', 'HomeController@ChangePassword');

Route::post('/ilvl', 'TopItemLevelsController@store');
Route::get('/ilvlupdate', 'TopItemLevelsController@update');
Route::post('/ilvlupdate', 'TopItemLevelsController@update');


Route::group(['middleware' => 'language'], function () {

    Route::get('/', 'IndexController@Start');
    Route::get('/home', 'HomeController@index');
    Route::get('armory', 'ArmoryController@Request');


    // Here your routes
    Route::get('/trade', 'TradesController@ShowAll');
    Route::get('/trade/char', 'TradesController@ShowCharacters');
    Route::get('/trade/raid', 'TradesController@ShowGdkps');

    Route::get('/changelog', 'ChangelogController@ShowChanges');

    Route::get('/bg', 'BattlegroundController@index');
    Route::get('/top', 'TopItemLevelsController@index');
    Route::get('/ilvl', 'TopItemLevelsController@index'); // For ppl who bookmarked old website

    Route::auth();
});
