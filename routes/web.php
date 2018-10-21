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

Route::post('armory', 'ArmoryController@Request');
Route::get('tooltip', 'TooltipController@Request');

Route::get('api/receiveData', 'IndexController@Start');
Route::post('api/receiveBattlegrounds', 'IndexController@Start');


Route::post('api/receiveData', 'ApiController@ReceiveData');
Route::post('api/receiveBattlegrounds', 'ApiController@ReceiveBattlegroundData');

Route::post('profile/avatar', 'HomeController@ChangeAvatar');
Route::post('profile/password', 'HomeController@ChangePassword');

Route::post('/ilvl', 'TopItemLevelsController@store');
Route::get('/ilvlupdate', 'TopItemLevelsController@update');
Route::post('/ilvlupdate', 'TopItemLevelsController@update');

/*
Route::get('/admin/kiskarik', 'AdminController@ShowLowLevels');
Route::post('/admin/kiskarik', 'AdminController@UpdateLowLevel');

Route::get('/debug/parse/{_parsed_data_id}', 'ApiController@SmartParseDebug');
Route::get('/debug/smart/parserange/{_parsed_from_id}/{_parse_till_id}', 'ApiController@SmartParseRangeDebug');
Route::get('/debug/trade/parserange/{_parsed_from_id}/{_parse_till_id}', 'ApiController@TradeParseRangeDebug');
*/



Route::group(['middleware' => 'language'], function () {

    Route::get('/', 'IndexController@Start');
    Route::get('/home', 'HomeController@index');

    // Here your routes
    Route::get('/trade', 'TradesController@ShowAll');
    Route::get('/trade/char', 'TradesController@ShowCharacters');
    Route::get('/trade/raid', 'TradesController@ShowGdkps');

    Route::get('/changelog', 'ChangelogController@ShowChanges');

    Route::get('/bg', 'BattlegroundController@index');
    Route::get('/top', 'TopItemLevelsController@index');

    Route::auth();
});

/*
Route::get('/trade/gdkp', 'TradesController@ShowGdkps');
Route::get('/trade/kredit', 'TradesController@ShowCredits');
*/


