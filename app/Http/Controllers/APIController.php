<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Version;
use App\Http\Resources\VersionAndroidResource;
use App\Http\Resources\VersionIOSResource;
use App\Http\Resources\VersionResource;

class APIController extends Controller
{
    public function getVersion(Request $request){
        $version = Version::all();
        $maintenance = Version::find(1, ['maintenanceMode'])->value('maintenanceMode');

        return response()->json([
            'ios' => VersionAndroidResource::collection($version)[0], 
            'android' => VersionIOSResource::collection($version)[0],
            'maintenanceMode' => $maintenance,
        ], 200);
    }
}
