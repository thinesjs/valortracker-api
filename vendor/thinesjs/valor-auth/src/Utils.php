<?php
namespace Thinesjs\ValorAuth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;

class Utils {
    private $client;
    private $baseUrl;
    private $headers;

    public function __construct(){
        $this->client = new Client(array('curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_3],'cookies' => true,'http_errors' => false, 'verify'=>false));
        $this->headers = [
            'Content-Type' => 'application/json',
        ];
        $this->baseUrl = "https://valorant-api.com/v1";
        //$response = $this->client->request("GET","https://$addr/api/v1/authorization", ["json"=>$bodyParams, "headers"=>$this->headers]);
    }


    public function getBetween($start, $end, $str){
        return explode($end,explode($start,$str)[1])[0];
    }

    public function getWeaponName($weaponId){
        $response = $this->client->request("GET","$this->baseUrl/weapons/skinlevels/$weaponId");
        return json_decode((string)$response->getBody());
    }

    public function getMap($mapUrl){
        $tempData = array();
        $mapDisplayName = null;
        $mapUUID = null;

        $json = File::get('maps.json');
        foreach(json_decode($json)->data as $map){
            if($map->mapUrl == $mapUrl){
                $mapUUID = $map->uuid;
                $mapDisplayName = $map->displayName;
            }
        }
        $tempData['mapDisplayName'] = $mapDisplayName;
        $tempData['mapUUID'] = $mapUUID;
        return $tempData;
    }

    // public function getRankImage($tierId){
    //     $tempData = array();
    //     $mapDisplayName = null;
    //     $mapUUID = null;

    //     $json = File::get('ranks.json');
    //     $ranks = json_decode($json)->data;
    //     foreach($ranks[array_key_last($ranks)]->tiers as $tier){
    //         if($tier->tier == $tierId){
    //             $mapUUID = $map->uuid;
    //             $mapDisplayName = $map->displayName;
    //         }
    //     }
    //     $tempData['mapDisplayName'] = $mapDisplayName;
    //     $tempData['mapUUID'] = $mapUUID;
    //     return $tempData;
    // }
}
?>
