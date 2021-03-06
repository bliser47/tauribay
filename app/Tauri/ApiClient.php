<?php

namespace TauriBay\Tauri;

class ApiClient {


    private $baseurl = 'http://chapi.tauri.hu/apiIndex.php';
    private $apikey  = '0ff33944145eb785';
    private $secret  = '1b01e00e88492048b1d6833c6ac175141cd41d6d';

    private $request = array(
        'url'    => '',
        'params' => array()
    );

    public function getBMAuctionsInfo($realm) {
        $this->request['url'] = 'bmauctions-info';
        $this->request['params'] = array(
            'r'  => $realm
        );
        return $this->communicate();
    }

    public function getBMAuctionsData($realm) {
        $this->request['url'] = 'bmauctions-data';
        $this->request['params'] = array(
            'r'  => $realm
        );
        return $this->communicate();
    }

    /**
     * Returns Achievement Firsts from the specified realm
     * @param string $realm - Full name of the realm
     * @return array
     */
    public function getAchievementFirsts ($realm) {
        $this->request['url'] = 'achievement-firsts';
        $this->request['params'] = array(
            'r' => $realm
        );
        return $this->communicate();
    }

    /**
     * Returns Achievements of the specified character
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getCharacterAchievements ($realm, $character) {
        $this->request['url'] = 'character-achievements';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        return $this->communicate();
    }

    /**
     * Returns Achievements of the specified character from the specified category
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @param int $category - Achievement category id
     * @return array
     */
    public function getCharacterAchievementsByCategory ($realm, $character, $category) {
        $this->request['url'] = 'achievements-loader';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character,
            'c' => $category
        );
        return $this->communicate();
    }

    /**
     * Returns Arena ladder from the specified realm with the specified team size
     * @param string $realm- Full name of the realm
     * @param int $teamSize - Team Size (2, 3, 5)
     * @return array
     */
    public function getArenaLadderByTeamSize ($realm, $teamSize) {
        $this->request['url'] = 'arena-ladder';
        $this->request['params'] = array(
            'r'  => $realm,
            'ts' => $teamSize
        );
        return $this->communicate();
    }

    /**
     * Returns Information about the specified Arena Team
     * @param string $realm - Full name of the realm
     * @param int $teamSize - Team size (2, 3, 5)
     * @param string $team - Name of the team
     * @return array
     */
    public function getArenaTeamInfo ($realm, $teamSize, $team) {
        $this->request['url'] = 'team-info';
        $this->request['params'] = array(
            'r'  => $realm,
            'ts' => $teamSize,
            't'  => $team
        );
        return $this->communicate();
    }

    /**
     * Returns Arena Team data for graphical analysis
     * @param string $realm - Full name of the realm
     * @param int $teamSize - Team size (2, 3, 5)
     * @param strng $team - Name of the team
     * @return array
     */
    public function getArenaTeamGameChartData ($realm, $teamSize, $team) {
        $this->request['url'] = 'arena-team-game-chart';
        $this->request['params'] = array(
            'r'  => $realm,
            'ts' => $teamSize,
            't'  => $team
        );
        return $this->communicate();
    }

    /**
     * Returns data about the opposing teams of the specified Arena Team
     * @param string $realm - Full name of the realm
     * @param int $teamSize - Team size (2, 3, 5)
     * @param strng $team - Name of the team
     * @return array
     */
    public function getArenaTeamOpposingTeamData ($realm, $teamSize, $team) {
        $this->request['url'] = 'arena-team-report-opposing-teams';
        $this->request['params'] = array(
            'r'  => $realm,
            'ts' => $teamSize,
            't'  => $team
        );
        return $this->communicate();
    }

    /**
     * Ruturns information about a specified arena game
     * @param string $realm - Full name of the realm
     * @param int $gameId - Unique identifier of the game
     * @return array
     */
    public function getArenaGame ($realm, $gameId) {
        $this->request['url'] = 'arena-game';
        $this->request['params'] = array(
            'r'   => $realm,
            'gid' => $gameId
        );
        return $this->communicate();
    }

    /**
     * Returns information about a character
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @param integer|null $guild - Character guid
     * @return array
     */
    public function getCharacterSheet ($realm, $character, $guild = null) {
        $this->request['url'] = 'character-sheet';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        if ( $guild !== null ) {
            $this->request['params']['guid'] = $guild;
        }
        return $this->communicate();
    }

    /**
     * Returns data for the character model object
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getCharacterModelData ($realm, $character) {
        $this->request['url'] = 'character-model';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        return $this->communicate();
    }

    /**
     * Returns character talents and glyphs
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getCharacterTalents ($realm, $character) {
        $this->request['url'] = 'character-talents';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        return $this->communicate();
    }

    /**
     * Returns reputation standings of the specified character
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getCharacterReputation ($realm, $character) {
        $this->request['url'] = 'character-reputation';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        return $this->communicate();
    }

    /**
     * Returns the character feed
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getCharacterFeed ($realm, $character) {
        $this->request['url'] = 'character-feed';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        return $this->communicate();
    }

    /**
     * Returns arena teams of the specified character
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getArenaTeamsByCharacter ($realm, $character) {
        $this->request['url'] = 'character-arenateams';
        $this->request['params'] = array(
            'r' => $realm,
            'n' => $character
        );
        return $this->communicate();
    }

    /**
     * Returns information about the specified guild
     * @param string $realm - Full name of the realm
     * @param string $guild - Guild name
     * @return array
     */
    public function getGuildRoster ($realm, $guild) {
        $this->request['url'] = 'guild-info';
        $this->request['params'] = array(
            'r'  => $realm,
            'gn' => $guild
        );
        return $this->communicate();
    }

    /**
     * Returns statistics about the specified guild for graphical analysis
     * @param string $realm - Full name of the realm
     * @param string $guild - Guild name
     * @return array
     */
    public function getGuildStatistics ($realm, $guild) {
        $this->request['url'] = 'guild-stats';
        $this->request['params'] = array(
            'r'  => $realm,
            'gn' => $guild
        );
        return $this->communicate();
    }

    /**
     * Returns the contents of the specified guild bank if the account associated with the API key has access to it.
     * @param string $realm - Full name of the realm
     * @param string $guild - Guild name
     * @return array
     */
    public function getGuildBankContents ($realm, $guild) {
        $this->request['url'] = 'guild-bank-contents';
        $this->request['params'] = array(
            'r'  => $realm,
            'gn' => $guild
        );
        return $this->communicate();
    }

    /**
     * Returns the guild bank log if the account associated with the API key has access to it.
     * @param string $realm - Full name of the realm
     * @param string $guild - Guild name
     * @return array
     */
    public function getGuildBankLog ($realm, $guild) {
        $this->request['url'] = 'guild-bank-log';
        $this->request['params'] = array(
            'r'  => $realm,
            'gn' => $guild
        );
        return $this->communicate();
    }

    /**
     * Returns information about the item(s) associated with the specified entry or entries
     * @param int $realm - Full name of the realm
     * @param mixed $entry - Item entry (int) or entries (array)
     * @return array
     */
    public function getItemTooltipDataByEntry ($realm, $entry) {
        $this->request['url'] = 'item-tooltip';
        $this->request['params'] = array(
            'r' => $realm,
            'e' => $entry
        );
        return $this->communicate();
    }

    /**
     * Returns information about the item(s) associated with the specified guid or guids
     * @param int $realm - Full name of the realm
     * @param mixed $entry - Item guid (int) or guids (array)
     * @return array
     */
    public function getItemTooltipDataByGuid ($realm, $guid) {
        $this->request['url'] = 'item-tooltip';
        $this->request['params'] = array(
            'r' => $realm,
            'i' => $guid
        );
        return $this->communicate();
    }

    /**
     * Get a minimal amount of information about the specified character
     * @param string $realm - Full name of the realm
     * @param string $character - Character name
     * @return array
     */
    public function getCharacterTooltipData ($realm, $character) {
        $this->request['url'] = 'item-tooltip';
        $this->request['params'] = array(
            'r'  => $realm,
            'cn' => $character
        );
        return $this->communicate();
    }

    /**
     * Basic CURL-based communication with the Armory Server.
     * The request is json and urlencoded.
     * The request is url and json decoded.
     * Stops execution on error.
     * @param bool $noParse
     * @throws \Exception
     * @return mixed
     */
    private function communicate ($noParse = false) {
        $this->request['secret'] = $this->secret;

        $ch = curl_init($this->baseurl . '?apikey=' . $this->apikey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_USERAGENT, 'Armory Public API client');
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 600);
        curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode(json_encode($this->request)));
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        if ($err) {
            throw new \Exception($err);
        } else {
            if ( $noParse )
            {
                return urldecode($response);
            }
            else {
                $ret = json_decode(urldecode($response), true);
                if (json_last_error() != JSON_ERROR_NONE) {
                    throw new \Exception(json_last_error());
                } else {
                    return $ret;
                }
            }
        }
    }

    public function getRaidMaps($realm)
    {
        $this->request['url'] = 'raid-maps';
        $this->request['params'] = array(
            'r'  => $realm
        );
        return $this->communicate();
    }

    public function getRaidLog($realm, $id)
    {
        $this->request['url'] = 'raid-log';
        $this->request['params'] = array(
            'r'  => $realm,
            'id' => $id
        );
        return $this->communicate();
    }

    public function getRaidLast($realm, $from = 0, $limit = null)
    {
        $this->request['url'] = 'raid-last';
        $this->request['params'] = array(
            'r'  => $realm,
            'from' => $from
        );
        if ( $limit !== null )
        {
            $this->request['params']['limit'] = $limit;
        }
        return $this->communicate();
    }

    public function getRaidPlayer($realm, $character)
    {
        $this->request['url'] = 'raid-player';
        $this->request['params'] = array(
            'r'  => $realm,
            'cn' => $character
        );
        return $this->communicate();
    }

    public function getRaidGuild($realm, $guild)
    {
        $this->request['url'] = 'raid-guild';
        $this->request['params'] = array(
            'r'  => $realm,
            'gn' => $guild
        );
        return $this->communicate();
    }

    public function getRaidRank($realm, $encounter, $difficulty)
    {
        $this->request['url'] = 'raid-rank-encounter';
        $this->request['params'] = array(
            'r'  => $realm,
            'encounter' => $encounter,
            'difficulty' => $difficulty,
        );
        return $this->communicate();
    }

    public function getRaidGuildRank($realm, $encounter, $difficulty)
    {
        $this->request['url'] = 'raid-guildrank-encounter';
        $this->request['params'] = array(
            'r'  => $realm,
            'encounter' => $encounter,
            'difficulty' => $difficulty,
        );
        return $this->communicate();
    }
}


?>