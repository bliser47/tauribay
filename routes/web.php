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
Route::get('oauth/character', 'OAuthController@char');
Route::get('oauthd', 'OAuthController@debug');



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
Route::get('/removeInvalids', 'ProgressController@deleteInvalids');

Route::get('/guildprogress', 'ProgressController@updateGuildProgressAll');
Route::get('/guildprogressn', 'ProgressController@updateGuildProgressForNewGuilds');
Route::post('/guildprogress', 'ProgressController@updateGuildProgress');

Route::group(['middleware' => 'language'], function () {

    Route::get('gdkp/{raid_id}', 'BliserGdkpController@index');
    Route::get('gdkpChar/{character_id}', 'BliserGdkpController@character');
    Route::post('gdkp/{raid_id}', 'BliserGdkpController@apply');

    Route::get('/', 'IndexController@Start');
    Route::get('/interview', function(){
        return view("interview");
    });

    Route::get('/test', function(){
        $api = new \TauriBay\Tauri\ApiClient();
        return $api->getCharacterSheet(\TauriBay\Realm::REALMS[\TauriBay\Realm::TAURI], "Blizer");
    });

    Route::get('/home', 'HomeController@index');
    Route::get('armory', 'ArmoryController@Request');

    Route::get('/trade', 'TradesController@ShowAll');
    Route::get('/trade/char', 'TradesController@ShowCharacters');
    Route::get('/trade/raid', 'TradesController@ShowGdkps');
    Route::get('/trade/credit', 'TradesController@ShowCredits');

    Route::get('/changelog', 'ChangelogController@ShowChanges');

    Route::get('/bg', 'BattlegroundController@index');
    Route::get('/top', 'TopItemLevelsController@index');
    Route::get('/top/fame', 'TopItemLevelsController@hallOfFame');
    Route::get('/progress/damage', 'ProgressController@damage');

    Route::get('bmah', 'BMAHController@index');
    Route::get('bmahd', 'BMAHController@debug');


    Route::get('/progress', 'ProgressController@index');
    Route::get('/guild/{guild_id}', 'GuildController@index');

    Route::get('/encounter/fixCharacters', 'EncounterController@fixCharacters');
    Route::get('/encounter/fixMissingEncounters', 'EncounterController@fixMissingEncounters');
    Route::get('/encounter/fixMissingEncounterMembers', 'EncounterController@fixMissingEncounterMembers');
    Route::get('/encounter/{encounter_name_url}', 'EncounterController@index');
    Route::get('/encounter/{encounter_name_url}/{log_id}', 'EncounterController@log');
    Route::get('/encounter/{encounter_name_url}/{log_id}/{mode}', 'EncounterController@mode');
    Route::get('/encounter/{encounter_name_url}/{log_id}/{mode}/{encounter_id}/{spec_id}', 'EncounterController@spec');

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


    Route::get('/player', 'PlayerController@index');
    Route::post('/player/{realm_short}/{name}/{character_guid}', 'PlayerController@ajax');
    Route::get('/player/{realm_short}/{name}/{character_guid}', 'PlayerController@player');
    Route::get('/player/{realm_short}/{name}/{character_guid}/score', 'PlayerController@playLiveScore');
    Route::get('/player/{realm_short}/{name}/{character_guid}/{mode}/{expansion_id}/{map_id}', 'PlayerController@mode');
    Route::get('/player/{realm_short}/{name}/{character_guid}/{mode}/{expansion_id}/{map_id}/{difficulty_id}', 'PlayerController@difficulty');
    Route::get('/player/{realm_short}/{name}/{character_guid}/{mode}/{expansion_id}/{map_id}/{difficulty_id}/{encounter_id}/{spec_id}', 'PlayerController@spec');


    Route::get('/ilvl', 'TopItemLevelsController@index'); // For ppl who bookmarked old website

    Route::get('/stats', 'StatsController@index');


    Route::auth();
});
