<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use CTApi\CTConfig;
use CTApi\Exceptions\CTRequestException;
use CTApi\Models\Common\Info\InfoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerifyChurchToolsUrlController extends Controller
{

    public function verifyUrl(Request $request): JsonResponse
    {
        $url = $request->get('churchToolsUrl');
        if($url === null){
            return response()->json(["error" => "ChurchTools URL darf nicht leer sein."], 422);
        }

        CTConfig::setApiUrl($url);
        try{
            $info = InfoRequest::getInfo();
            if(!array_key_exists('version', $info)){
                return response()->json(["error" => "Url ist keine ChurchTools Instanz."], 422);
            }
            return response()->json(["data" => [
                "siteName" => $info["siteName"] ?? null,
                "shortName" => $info["shortName"] ?? null,
            ]]);
        }catch (CTRequestException $exception){
            return response()->json(["error" => $exception->getMessage()], 422);
        }
    }

}
