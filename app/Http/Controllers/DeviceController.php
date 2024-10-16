<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function generateDeviceId(Request $request): Response
    {

        if (!$request->hasCookie('device_id')) {

            $deviceId = Str::uuid();

            Cookie::queue('device_id', $deviceId, 60 * 24 * 30);
        }

        return response()->view('welcome')->cookie('device_id', $request->cookie('device_id'), 60 * 24 * 30);
    }
}
