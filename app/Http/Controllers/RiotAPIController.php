<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Thinesjs\ValorAuth\Authentication;
use Thinesjs\ValorAuth\Utils;
use Thinesjs\ValorAuth\ValorHelper;

class RiotAPIController extends Controller
{
    public ValorHelper $valorClient;
    public Utils $utils;

    public function __construct(Request $request){
        $access_token = $request->header('Authorization');
        $riot_entitlement = $request->header('X-Riot-Entitlements-JWT');
        $this->valorClient = new ValorHelper($access_token, $request->region, $riot_entitlement, $request->puuid);
        $this->utils = new Utils();
    }

    public function handleLogin(Request $request): JsonResponse
    {
        $request->validate([
            "username" => "required",
            "password" => "required"
        ]);

        $valorAuth = new Authentication(["username"=>$request->username, "password"=>$request->password, "shard"=>"ap", "remember"=>true]);


        $authTokens = $valorAuth->authenticate();

        if(isset($authTokens['type']) && $authTokens['type'] == "2FA"){
            return response()->json([
                'status' => '2fa',
                'request' => $authTokens
            ], 401);
        }elseif (is_array($authTokens)) {
            if($authTokens['access_token'] != null){
                return response()->json([
                    'status' => 'success',
                    'data' => $authTokens
                ], 200);
            }elseif ($authTokens['error'] != null) {
                return response()->json([
                    'status' => 'error',
                    'err_msg' => $authTokens['error']
                ], 404);
            }
        }elseif ($authTokens == '{"error":"Invalid username or password"}') {
            return response()->json([
                'status' => 'error',
                'err_msg' => 'invalid username or password'
            ], 401);
        }else{
            return response()->json([
                'status' => 'error',
                'err_msg' => 'riot servers are unreachable'
            ], 404);
        }
        return response()->json([
            'status' => 'error',
            'err_msg' => 'server error'
        ], 500);
    }

    public function handle2fa(Request $request): JsonResponse
    {
        $request->validate([
            "multifactorcode" => "required",
            "asid" => "required",
        ]);

        $valorAuth = new Authentication();
        $authTokens = $valorAuth->authenticate(true, $request->multifactorcode, $request->asid);

        if($authTokens == "2FA"){
            return response()->json([
                'status' => '2fa',
                'err_msg' => $authTokens
            ], 200);
        }elseif (is_array($authTokens)) {
            if($authTokens['access_token'] != null){
                return response()->json([
                    'status' => 'success',
                    'data' => $authTokens
                ], 200);
            }elseif ($authTokens['error'] != null) {
                return response()->json([
                    'status' => 'error',
                    'err_msg' => $authTokens['error']
                ], 404);
            }
        }elseif ($authTokens == '{"error":"Invalid username or password"}') {
            return response()->json([
                'status' => 'error',
                'err_msg' => 'invalid username or password'
            ], 401);
        }elseif ($authTokens == '{"error":"multifactor_attempt_failed"}'){
            return response()->json([
                'status' => 'error',
                'err_msg' => 'invalid multifactorcode'
            ], 401);
        }else{
            return response()->json([
                'status' => 'error',
                'err_msg' => 'riot servers are unreachable'
            ], 404);
        }
        return response()->json([
            'status' => 'error',
            'err_msg' => 'server error'
        ], 500);
    }

    public function handleRecookie(Request $request): JsonResponse
    {
        $request->validate([
            "ssid" => "required",
        ]);

        $valorAuth = new Authentication();
        $authTokens = $valorAuth->reAuth($request->ssid);

        if($authTokens == "2FA"){
            return response()->json([
                'status' => '2fa',
                'err_msg' => $authTokens
            ], 200);
        }elseif (is_array($authTokens)) {
            if($authTokens['access_token'] != null){
                return response()->json([
                    'status' => 'success',
                    'data' => $authTokens
                ], 200);
            }elseif ($authTokens['error'] != null) {
                return response()->json([
                    'status' => 'error',
                    'err_msg' => $authTokens['error']
                ], 404);
            }
        }elseif ($authTokens == '{"error":"Invalid username or password"}') {
            return response()->json([
                'status' => 'error',
                'err_msg' => 'invalid username or password'
            ], 401);
        }elseif ($authTokens == '{"error":"multifactor_attempt_failed"}'){
            return response()->json([
                'status' => 'error',
                'err_msg' => 'invalid multifactorcode'
            ], 401);
        }else{
            return response()->json([
                'status' => 'error',
                'err_msg' => 'riot servers are unreachable'
            ], 404);
        }
        return response()->json([
            'status' => 'error',
            'err_msg' => 'server error'
        ], 500);
    }

    public function getUserInfo(Request $request): JsonResponse
    {
        $userInfo = $this->valorClient->userInfo();
        if(!empty($userInfo) && !isset($userInfo->error)) return response()->json(['status' => 'success', 'data' => $userInfo], 200); else return response()->json(['status' => 'error', 'data' => 'invalid access token'], 401);
    }

    public function getStorefront(Request $request): JsonResponse
    {
        $storeFront = $this->valorClient->storefront();

        $weaponDisplayNames = array();
        foreach($storeFront->SkinsPanelLayout->SingleItemOffers as $i) {
            $weaponDisplayNames[] = $this->utils->getWeaponName($i);
        }

        $nightmarketDisplayNames = array();
        if(isset($storeFront->BonusStore)){
            foreach($storeFront->BonusStore->BonusStoreOffers as $i) {
                $nightmarketDisplayNames[] = $this->utils->getWeaponName($i->Offer->Rewards[0]->ItemID);
            }
        }

        $merged_array = array("market"=>$weaponDisplayNames, "nightmarket"=>$nightmarketDisplayNames);

        if(!empty($storeFront) && !isset($storeFront->error)) return response()->json(['status' => 'success', 'data' => $merged_array], 200); else return response()->json(['status' => 'error', 'data' => 'invalid access token'], 401);
    }

    public function getWallet(Request $request): JsonResponse
    {
        $walletBalance = $this->valorClient->wallet();
        if(!empty($walletBalance) && !isset($walletBalance->error)) return response()->json(['status' => 'success', 'data' => $walletBalance], 200); else return response()->json(['status' => 'error', 'data' => 'invalid access token'], 401);
    }

    public function getPenalties(Request $request): JsonResponse
    {
        $penalties = $this->valorClient->penalties();
        if(!empty($penalties) && !isset($penalties->error)) return response()->json(['status' => 'success', 'data' => $penalties], 200); else return response()->json(['status' => 'error', 'data' => 'invalid access token'], 401);
    }
}
