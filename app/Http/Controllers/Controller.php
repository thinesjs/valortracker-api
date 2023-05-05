<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function addressApi($path, Request $request)
    {
        $url = $path;
        $postData = $request->all();
        $access_token = $request->header('Authorization');
        $riot_entitlement = $request->header('X-Riot-Entitlements-JWT');
        $headers = [
            "Accept-Encoding" => "gzip, deflate, br",
            'Content-Type' => 'application/json',
            'User-Agent' => 'RiotClient/62.0.1.4852117.4789131 rso-auth (Windows;10;;Professional, x64)',
            'Host' => 'auth.riotgames.com',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Authorization' => $access_token,
            'X-Riot-Entitlements-JWT' => $riot_entitlement,
        ];
        $client = new Client(array('curl' => [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_3],'cookies' => true,'http_errors' => false, 'verify'=>false));

        $response = $client->request('GET', $url, ['json' => $postData, "headers"=>$headers]);

        return response($response->getBody())->withHeaders($response->getHeaders());
    }

}
