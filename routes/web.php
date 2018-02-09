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

Route::get('/', 'IndexController@Start');

Route::post('api/receiveData', 'ApiController@ReceiveData');




Route::get('/admin/kiskarik', 'AdminController@ShowLowLevels');
Route::post('/admin/kiskarik', 'AdminController@UpdateLowLevel');

Route::get('/debug/parse/{_parsed_data_id}', 'ApiController@SmartParseDebug');
Route::get('/debug/smart/parserange/{_parsed_from_id}/{_parse_till_id}', 'ApiController@SmartParseRangeDebug');
Route::get('/debug/trade/parserange/{_parsed_from_id}/{_parse_till_id}', 'ApiController@TradeParseRangeDebug');

Route::get('/changelog', 'ChangelogController@ShowChanges');


Route::get('/hirdetesek', 'TradesController@ShowAll');
Route::get('/hirdetesek/karakter', 'TradesController@ShowCharacters');
Route::get('/hirdetesek/gdkp', 'TradesController@ShowGdkps');
Route::get('/hirdetesek/kredit', 'TradesController@ShowCredits');

Route::auth();
Route::get('/home', 'HomeController@index');
