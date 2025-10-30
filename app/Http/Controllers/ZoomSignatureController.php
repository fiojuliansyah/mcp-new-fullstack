<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ZoomSignatureController extends Controller
{
    public function generateSignature(Request $request)
    {
        $request->validate([
            'meetingNumber' => 'required',
            'role' => 'required',
        ]);

        $sdkKey = env('ZOOM_SDK_KEY');
        $sdkSecret = env('ZOOM_SDK_SECRET');
        
        $iat = time() - 30;
        $exp = $iat + 60 * 60 * 2;

        $payload = [
            'sdkKey' => $sdkKey,
            'mn' => $request->meetingNumber,
            'role' => $request->role,
            'iat' => $iat,
            'exp' => $exp,
            'appKey' => $sdkKey,
            'tokenExp' => $exp,
        ];
        
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        
        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));
        
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $sdkSecret, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);
        
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return response()->json(['signature' => $jwt]);
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}