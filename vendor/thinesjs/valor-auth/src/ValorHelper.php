<?php

namespace Thinesjs\ValorAuth;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class ValorHelper
{
    private $client;
    public $accessToken;
    public $headers;
    public $clientPlatform;
    public $idToken;
    public $authUrl;
    public $playerDataUrl;
    public $gameDataUrl;
    public $playerId;

    public function __construct($access_token = null, $shard = null, $entitlements = null, $puuid = null){
        $this->client = new Client(array('curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_3],'cookies' => true,'http_errors' => false, 'verify'=>false));
        $this->headers = [
            "Accept-Encoding" => "gzip, deflate, br",
            'Content-Type' => 'application/json',
            'User-Agent' => 'RiotClient/62.0.1.4852117.4789131 rso-auth (Windows;10;;Professional, x64)',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Authorization' => $access_token,
            'X-Riot-Entitlements-JWT' => $entitlements,
            'X-Riot-ClientPlatform' => 'ew0KCSJwbGF0Zm9ybVR5cGUiOiAiUEMiLA0KCSJwbGF0Zm9ybU9TIjogIldpbmRvd3MiLA0KCSJwbGF0Zm9ybU9TVmVyc2lvbiI6ICIxMC4wLjE5MDQyLjEuMjU2LjY0Yml0IiwNCgkicGxhdGZvcm1DaGlwc2V0IjogIlVua25vd24iDQp9',
            'X-Riot-ClientVersion' => 'release-06.08-shipping-14-872043'
        ];
        $this->clientPlatform = "ew0KCSJwbGF0Zm9ybVR5cGUiOiAiUEMiLA0KCSJwbGF0Zm9ybU9TIjogIldpbmRvd3MiLA0KCSJwbGF0Zm9ybU9TVmVyc2lvbiI6ICIxMC4wLjE5MDQyLjEuMjU2LjY0Yml0IiwNCgkicGxhdGZvcm1DaGlwc2V0IjogIlVua25vd24iDQp9";
        $this->address = "auth.riotgames.com";
        $this->shard = $shard;
        $this->authUrl = "https://auth.riotgames.com";
        $this->playerDataUrl = "https://pd.$shard.a.pvp.net";
        $this->gameDataUrl = "https://glz-$shard-1.$shard.a.pvp.net/pregame/v1/players";
        $this->playerId = $puuid;
        //$response = $this->client->request("GET","https://$addr/api/v1/authorization", ["json"=>$bodyParams, "headers"=>$this->headers]);
    }

    public function userInfo()
    {
        $response = $this->client->request("GET","$this->authUrl/userinfo", ["headers"=>$this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function storefront()
    {
        $response = $this->client->request("GET","$this->playerDataUrl/store/v2/storefront/$this->playerId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function wallet()
    {
        $response = $this->client->request("GET","$this->playerDataUrl/store/v1/wallet/$this->playerId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function penalties()
    {
        $response = $this->client->request("GET","$this->playerDataUrl/restrictions/v3/penalties", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function mmr()
    {
        $response = $this->client->request("GET","$this->playerDataUrl/mmr/v1/players/$this->playerId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    //MATCHES

    public function matchHistory($startIndex = 0, $endIndex = 20, $queue = null)
    {
        $response = $this->client->request("GET","$this->playerDataUrl/match-history/v1/history/$this->playerId?startIndex=$startIndex&endIndex=$endIndex", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function matchDetails($matchId)
    {
        $response = $this->client->request("GET","$this->playerDataUrl/match-details/v1/matches/$matchId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    //END MATCHES

    //PRE GAME

    public function preGamePlayer()
    {
        $response = $this->client->request("GET","$this->gameDataUrl/pregame/v1/players/$this->playerId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function preGameMatch($matchId)
    {
        $response = $this->client->request("GET","$this->gameDataUrl/pregame/v1/matches/$matchId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function preGameSelectAgent($matchId, $agentId)
    {
        $response = $this->client->request("POST","$this->gameDataUrl/pregame/v1/matches/$matchId/select/$agentId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    public function preGameLockAgent($matchId, $agentId)
    {
        $response = $this->client->request("POST","$this->gameDataUrl/pregame/v1/matches/$matchId/lock/$agentId", ["headers" => $this->headers]);
        return json_decode((string)$response->getBody());
    }

    //END PRE GAME
}
