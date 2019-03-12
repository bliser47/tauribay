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

Route::get('oauth', 'OAuthController@auth');
Route::get('oauthd', 'OAuthController@debug');


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
Route::post('/raidupdate', 'ProgressController@updateRaids');
Route::get('/raidupdate', 'ProgressController@updateRaids');
Route::get('/raidmemberupdate', 'ProgressController@updateRaidMembers');

Route::get('/progressdebug', 'ProgressController@debug');



Route::get('/guildprogress', 'ProgressController@updateGuildProgressAll');
Route::get('/guildprogressn', 'ProgressController@updateGuildProgressForNewGuilds');
Route::post('/guildprogress', 'ProgressController@updateGuildProgress');

Route::group(['middleware' => 'language'], function () {

    Route::get('/', 'IndexController@Start');
    Route::get('/home', 'HomeController@index');
    Route::get('armory', 'ArmoryController@Request');

    Route::get('/trade', 'TradesController@ShowAll');
    Route::get('/trade/char', 'TradesController@ShowCharacters');
    Route::get('/trade/raid', 'TradesController@ShowGdkps');
    Route::get('/trade/credit', 'TradesController@ShowCredits');

    Route::get('/changelog', 'ChangelogController@ShowChanges');

    Route::get('/bg', 'BattlegroundController@index');
    Route::get('/top', 'TopItemLevelsController@index');
    Route::get('/progress/damage', 'ProgressController@damage');


    Route::get('/progress', 'ProgressController@index');
    Route::get('/guild/{guild_id}', 'GuildController@index');

    Route::get('/encounter/fix', 'EncounterController@fixTopNotProcessed');
    Route::get('/encounter/fix2', 'EncounterController@fix2');
    Route::get('/encounter/{encounter_name_url}', 'EncounterController@index');
    Route::get('/encounter/{encounter_name_url}/{log_id}', 'EncounterController@log');

    Route::get('/ladder/pve/', 'PveLadderController@index');
    Route::get('/ladder/pve/{expansion_name_short}/', 'PveLadderController@expansion');
    Route::get('/ladder/pve/{expansion_name_short}/{map_name_short}', 'PveLadderController@map');
    Route::get('/ladder/pve/{expansion_name_short}/{map_name_short}/{difficulty_name_short}', 'PveLadderController@map_with_difficulty');
    Route::get('/ladder/pve/{expansion_name_short}/{map_name_short}/{encounter_name_short}/{difficulty_name_short}', 'PveLadderController@encounter');
    Route::get('/ladder/pve/encounter/{encounter_name_short}', 'PveLadderController@encounter');
    Route::post('/ladder/pve/', 'PveLadderController@ajax');

    Route::get('/role/{role_id}', 'RaidController@getRoleClasses');
    Route::get('/classAndRole/{role_id}/{class_id}', 'RaidController@getRoleClassSpecs');
    Route::get('/class/{class_id}', 'RaidController@getClassSpecs');
    Route::get('/raid/{expansion_id}', 'RaidController@getExpansionMaps');
    Route::get('/raid/{expansion_id}/{map_id}', 'RaidController@getMapEncounters');


    Route::get('/player/', 'PlayerController@index');
    Route::get('/player/{realm_short}/{name}', 'PlayerController@search');
    Route::get('/player/{realm_short}/{name}/{mode}', 'PlayerController@mode');


    Route::get('/ilvl', 'TopItemLevelsController@index'); // For ppl who bookmarked old website

    Route::get('/stats', 'StatsController@index');


    Route::auth();
});
