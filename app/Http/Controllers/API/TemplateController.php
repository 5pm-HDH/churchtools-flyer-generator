<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PreInstalledTemplateDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function getTemplates(): JsonResponse
    {
        return response()->json(["data" =>  PreInstalledTemplateDTO::getAll()]);
    }
}
