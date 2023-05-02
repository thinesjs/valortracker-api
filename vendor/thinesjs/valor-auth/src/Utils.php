<?php
namespace Thinesjs\ValorAuth;
use GuzzleHttp\Client;

class Utils {
    public $baseUrl;

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
}
?>
